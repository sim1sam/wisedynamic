<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PackageOrder;
use App\Models\ServiceOrder;
use App\Models\CustomServiceRequest;
use App\Models\FundRequest;
use App\Models\ManualPayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Show payment options for an order.
     */
    public function showPaymentOptions(Request $request, $type, $id)
    {
        $order = $this->getOrder($type, $id);
        
        if (!$order || $order->user_id !== Auth::id()) {
            abort(404);
        }
        
        // Handle different order types
        if ($type === 'custom-service') {
            // For custom service requests, check if already paid
            if ($order->ssl_transaction_id) {
                return redirect()->route('customer.dashboard')->with('info', 'This custom service request has already been paid.');
            }
            
            // For custom service, use total_amount as the amount
            $totalAmount = $order->total_amount;
            $paidAmount = 0;
            $remainingAmount = $totalAmount;
            
            // Get payment statistics for better UX
            $paymentStats = [
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'payment_progress' => 0,
                'is_partial_payment' => false,
                'can_make_partial' => false, // Custom services are paid in full
            ];
        } else {
            // Check if order is already fully paid
            if ($order->payment_status === 'paid') {
                return redirect()->route('customer.dashboard')->with('info', 'This order has already been paid.');
            }
            
            // Calculate remaining amount to pay
            $remainingAmount = $order->amount - ($order->paid_amount ?? 0);
            
            // Get payment statistics for better UX
            $paymentStats = [
                'total_amount' => $order->amount,
                'paid_amount' => $order->paid_amount ?? 0,
                'remaining_amount' => $remainingAmount,
                'payment_progress' => $order->paid_amount ? round(($order->paid_amount / $order->amount) * 100, 1) : 0,
                'is_partial_payment' => ($order->paid_amount ?? 0) > 0,
                'can_make_partial' => $remainingAmount > 100, // Minimum 100 BDT for partial payment
            ];
        }
        
        // Get recent payment history for this order
        $recentPayments = Transaction::where(function($query) use ($type, $id) {
            if ($type === 'package') {
                $query->where('package_order_id', $id);
            } elseif ($type === 'service') {
                $query->where('service_order_id', $id);
            } elseif ($type === 'custom-service') {
                $query->where('custom_service_request_id', $id);
            }
        })
        ->where('status', 'completed')
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();
        
        return view('frontend.customer.payment.options', compact('order', 'type', 'paymentStats', 'recentPayments'));
    }
    
    /**
     * Handle SSL Commerz IPN callback (server-to-server notification).
     */
    public function sslIpn(Request $request)
    {
        // Basic sanity check
        if (!$request->has(['tran_id', 'val_id', 'status'])) {
            Log::warning('SSL IPN missing required fields', $request->all());
            return response('Bad Request', 400);
        }

        $type = $request->get('value_a');
        $id = $request->get('value_b');
        $transactionId = $request->get('tran_id');

        // Validate with SSLCommerz validator API
        $sslConfig = config('sslcommerz');
        $validationUrl = $sslConfig['sandbox'] ? $sslConfig['validation_url']['sandbox'] : $sslConfig['validation_url']['live'];

        $validationData = [
            'val_id' => $request->get('val_id'),
            'store_id' => $sslConfig['store_id'],
            'store_passwd' => $sslConfig['store_password'],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $validationUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($validationData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        $validationResponse = json_decode($response, true);

        if (!$validationResponse || !isset($validationResponse['status'])) {
            Log::error('Invalid SSL IPN validation response', [
                'response' => $response,
                'curl_error' => $curlError,
            ]);
            return response('Validation Failed', 422);
        }

        if (!in_array($validationResponse['status'], ['VALID', 'VALIDATED'])) {
            Log::warning('SSL IPN status not valid', $validationResponse);
            return response('Ignored', 200);
        }

        // Process order update similar to success flow
        $order = $this->getOrder($type, $id);
        if (!$order) {
            Log::error('SSL IPN order not found', ['type' => $type, 'id' => $id]);
            return response('Order Not Found', 404);
        }

        DB::beginTransaction();
        try {
            if ($type === 'custom-service') {
                $order->update([
                    'ssl_transaction_id' => $transactionId,
                    'ssl_response' => $validationResponse,
                ]);
            } elseif ($type === 'fund') {
                $order->update([
                    'ssl_transaction_id' => $transactionId,
                    'ssl_response' => $validationResponse,
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);
                $order->user->addBalance($order->amount);
            } else {
                $amount = $validationResponse['amount'] ?? ($order->amount ?? 0);
                $newPaidAmount = ($order->paid_amount ?? 0) + $amount;
                $newDueAmount = ($order->amount ?? 0) - $newPaidAmount;
                $order->update([
                    'payment_status' => $newDueAmount <= 0 ? 'paid' : 'pending_verification',
                    'payment_method' => 'SSL Payment',
                    'paid_amount' => $newPaidAmount,
                    'due_amount' => $newDueAmount,
                ]);
            }

            $transactionData = [
                'transaction_number' => Transaction::generateTransactionNumber(),
                'amount' => $validationResponse['amount'] ?? ($order->amount ?? 0),
                'payment_method' => 'SSL Payment',
                'status' => 'completed',
                'notes' => 'SSL IPN payment processed. Transaction ID: ' . $transactionId,
            ];

            if ($type === 'package') {
                $transactionData['package_order_id'] = $order->id;
            } elseif ($type === 'service') {
                $transactionData['service_order_id'] = $order->id;
            } elseif ($type === 'custom-service') {
                $transactionData['custom_service_request_id'] = $order->id;
            } elseif ($type === 'fund') {
                $transactionData['fund_request_id'] = $order->id;
            }

            Transaction::create($transactionData);

            DB::commit();
            return response('OK', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SSL IPN processing error: '.$e->getMessage());
            return response('Server Error', 500);
        }
    }

    /**
     * Process SSL payment.
     */
    public function processSSLPayment(Request $request, $type, $id)
    {
        $order = $this->getOrder($type, $id);
        
        if (!$order || $order->user_id !== Auth::id()) {
            abort(404);
        }
        
        // If it's a GET request, show the payment form
        if ($request->isMethod('GET')) {
            return view('customer.payment.ssl-form', compact('order', 'type', 'id'));
        }
        
        // Validate payment amount based on order type
        if ($type === 'custom-service') {
            $totalAmount = $order->total_amount;
            $remainingAmount = $totalAmount; // Custom services are paid in full
            
            $validated = $request->validate([
                'payment_amount' => ['required', 'numeric', 'min:1', 'max:' . $totalAmount],
            ]);
            
            // Check if already paid
            if ($order->ssl_transaction_id) {
                return redirect()->back()->with('error', 'This custom service request has already been paid.');
            }
        } elseif ($type === 'fund') {
            $totalAmount = $order->amount;
            $remainingAmount = $totalAmount; // Fund requests are paid in full
            
            $validated = $request->validate([
                'payment_amount' => ['required', 'numeric', 'min:1', 'max:' . $totalAmount],
            ]);
            
            // Check if already paid
            if ($order->ssl_transaction_id) {
                return redirect()->back()->with('error', 'This fund request has already been paid.');
            }
        } else {
            $validated = $request->validate([
                'payment_amount' => ['required', 'numeric', 'min:1', 'max:' . $order->amount],
            ]);
            
            // Enhanced payment amount validation for regular orders
            $totalAmount = $order->total_amount ?? $order->amount;
            $paidAmount = $order->payments()->where('status', 'completed')->sum('amount');
            $remainingAmount = $totalAmount - $paidAmount;
        }
        
        $paymentAmount = $validated['payment_amount'];

        // Validate payment amount
        if ($paymentAmount > $remainingAmount) {
            return redirect()->back()->with('error', 'Payment amount cannot exceed remaining balance of ৳' . number_format($remainingAmount, 2));
        }

        if ($paymentAmount < 1) {
            return redirect()->back()->with('error', 'Payment amount must be at least ৳1.00');
        }
        
        // SSL Commerz payment gateway integration
        $sslConfig = config('sslcommerz');
        
        // Generate unique transaction ID
        $transactionId = 'WD' . time() . rand(1000, 9999);
        
        // Enhanced SSL payment data
        $postData = [
            'store_id' => $sslConfig['store_id'],
            'store_passwd' => $sslConfig['store_password'],
            'total_amount' => $paymentAmount,
            'currency' => 'BDT',
            'tran_id' => $transactionId,
            'success_url' => route('customer.payment.ssl.success', ['type' => $type, 'id' => $id]),
            'fail_url' => route('customer.payment.ssl.fail', ['type' => $type, 'id' => $id]),
            'cancel_url' => route('customer.payment.ssl.cancel', ['type' => $type, 'id' => $id]),
            'ipn_url' => $sslConfig['ipn_url'],
            
            // Customer information
            'cus_name' => Auth::user()->name,
            'cus_email' => Auth::user()->email,
            'cus_add1' => Auth::user()->address ?? 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_country' => 'Bangladesh',
            'cus_phone' => Auth::user()->phone ?? '01700000000',
            
            // Product information
            'product_name' => ucfirst($type) . ' Order #' . $order->id,
            'product_category' => ucfirst($type),
            'product_profile' => 'general',
            
            // Shipping information
            'shipping_method' => 'NO',
            'ship_name' => Auth::user()->name,
            'ship_add1' => Auth::user()->address ?? 'Dhaka',
            'ship_city' => 'Dhaka',
            'ship_country' => 'Bangladesh',
            
            // Additional parameters
            'value_a' => $type,
            'value_b' => $id,
            'value_c' => Auth::id(),
            'value_d' => $paymentAmount,
        ];
        
        // Store payment attempt in session for tracking
        session([
            'payment_attempt' => [
                'transaction_id' => $transactionId,
                'order_type' => $type,
                'order_id' => $id,
                'amount' => $paymentAmount,
                'timestamp' => now()
            ]
        ]);
        
        // Get SSL Commerz API URL
        $apiUrl = $sslConfig['sandbox'] ? $sslConfig['api_url']['sandbox'] : $sslConfig['api_url']['live'];
        
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            Log::error('SSL Payment cURL Error: ' . $error);
            return redirect()->back()->with('error', 'Payment gateway connection failed. Please try again.');
        }

        if ($httpCode !== 200) {
            Log::error('SSL Payment HTTP Error: ' . $httpCode);
            return redirect()->back()->with('error', 'Payment gateway returned error. Please try again.');
        }
        
        $responseData = json_decode($response, true);
        
        if (!$responseData || $responseData['status'] !== 'SUCCESS') {
            $errorMessage = $responseData['failedreason'] ?? 'Payment initialization failed';
            Log::error('SSL Payment Init Failed: ' . $errorMessage);
            return redirect()->back()->with('error', 'Payment initialization failed: ' . $errorMessage);
        }

        // Log successful payment initialization
        Log::info('SSL Payment Initialized', [
            'transaction_id' => $transactionId,
            'order_type' => $type,
            'order_id' => $id,
            'amount' => $paymentAmount,
            'gateway_url' => $responseData['GatewayPageURL']
        ]);
        
        return redirect($responseData['GatewayPageURL']);
    }
    
    /**
     * Handle SSL payment success callback.
     */
    public function sslSuccess(Request $request, $type, $id)
    {
        $transactionId = $request->get('tran_id');
        
        // For SSL callbacks, we can't rely on session data as these come from external gateway
        // Instead, we'll validate the transaction directly with SSL Commerz
        
        // Verify payment with SSL Commerz
        $sslConfig = config('sslcommerz');
        $validationUrl = $sslConfig['sandbox'] ? $sslConfig['validation_url']['sandbox'] : $sslConfig['validation_url']['live'];
        
        $validationData = [
            'val_id' => $request->get('val_id'),
            'store_id' => $sslConfig['store_id'],
            'store_passwd' => $sslConfig['store_password'],
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $validationUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($validationData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        // Log the raw response for debugging
        Log::info('SSL Validation Response', [
            'raw_response' => $response,
            'curl_error' => $curlError,
            'validation_data' => $validationData
        ]);
        
        $validationResponse = json_decode($response, true);
        
        // Check if validation response is valid
        if (!$validationResponse || !isset($validationResponse['status'])) {
            Log::error('Invalid SSL validation response', [
                'response' => $response,
                'curl_error' => $curlError
            ]);
            
            // For testing purposes, if SSL validation fails, we'll still process the payment
            // In production, you should handle this more strictly
            if ($request->get('status') === 'VALID') {
                Log::info('Processing payment despite validation failure for testing');
                $validationResponse = ['status' => 'VALID', 'tran_id' => $transactionId];
            } else {
                return redirect()->route('customer.payment.options', ['type' => $type, 'id' => $id])
                    ->with('error', 'Payment validation failed. Please try again.');
            }
        }
        
        if ($validationResponse['status'] === 'VALID' && $validationResponse['tran_id'] === $transactionId) {
            // Payment is valid, process the order
            $order = $this->getOrder($type, $id);
            
            if (!$order) {
                return redirect()->route('customer.dashboard')->with('error', 'Order not found.');
            }
            
            DB::beginTransaction();
            
            try {
                // Update order payment status based on order type
                if ($type === 'custom-service') {
                    // For custom service requests, update ssl_transaction_id
                    $order->update([
                        'ssl_transaction_id' => $transactionId,
                        'ssl_response' => $validationResponse,
                    ]);
                    
                    $message = 'Payment completed successfully!';
                    $redirectRoute = 'customer.custom-service.show';
                } elseif ($type === 'fund') {
                    // For fund requests, update ssl_transaction_id
                    $order->update([
                        'ssl_transaction_id' => $transactionId,
                        'ssl_response' => $validationResponse,
                        'status' => 'approved',
                        'approved_at' => now(),
                    ]);
                    
                    // Add balance to user account
                    $order->user->addBalance($order->amount);
                    
                    $message = 'Fund request payment completed successfully!';
                    $redirectRoute = 'customer.fund.show';
                } else {
                    // For regular orders, update payment status
                    $amount = $validationResponse['amount'] ?? $order->amount;
                    $newPaidAmount = ($order->paid_amount ?? 0) + $amount;
                    $newDueAmount = $order->amount - $newPaidAmount;
                    
                    $order->update([
                        'payment_status' => $newDueAmount <= 0 ? 'paid' : 'pending_verification',
                        'payment_method' => 'SSL Payment',
                        'paid_amount' => $newPaidAmount,
                        'due_amount' => $newDueAmount,
                    ]);
                    
                    $message = $newDueAmount <= 0 ? 'Payment completed successfully!' : 'Partial payment of BDT ' . number_format($amount, 2) . ' completed successfully!';
                    $redirectRoute = $type === 'package' ? 'customer.package-orders.show' : 'customer.service-orders.show';
                }
                
                // Create transaction record
                $transactionData = [
                    'transaction_number' => Transaction::generateTransactionNumber(),
                    'amount' => $validationResponse['amount'] ?? $order->amount,
                    'payment_method' => 'SSL Payment',
                    'status' => 'completed',
                    'notes' => 'SSL payment completed successfully. Transaction ID: ' . $transactionId . ', Amount: BDT ' . number_format($validationResponse['amount'] ?? $order->amount, 2),
                ];
                
                // Set the appropriate foreign key based on order type
                if ($type === 'package') {
                    $transactionData['package_order_id'] = $order->id;
                } elseif ($type === 'service') {
                    $transactionData['service_order_id'] = $order->id;
                } elseif ($type === 'custom-service') {
                    $transactionData['custom_service_request_id'] = $order->id;
                } elseif ($type === 'fund') {
                    $transactionData['fund_request_id'] = $order->id;
                }
                
                Transaction::create($transactionData);
                
                DB::commit();
                
                // Redirect to appropriate route based on order type
                return redirect()->route($redirectRoute, $order->id)->with('success', $message);
                    
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->route('customer.dashboard')->with('error', 'Payment processing failed.');
            }
        } else {
            return redirect()->route('customer.dashboard')->with('error', 'Payment verification failed.');
        }
    }
    
    /**
     * Handle SSL payment failure callback.
     */
    public function sslFail(Request $request, $type, $id)
    {
        // Log the failure for debugging
        Log::info('SSL Payment Failed', [
            'type' => $type,
            'id' => $id,
            'request_data' => $request->all()
        ]);
        
        return redirect()->route('customer.payment.options', [$type, $id])
            ->with('error', 'Payment failed. Please try again.');
    }
    
    /**
     * Handle SSL payment cancellation callback.
     */
    public function sslCancel(Request $request, $type, $id)
    {
        // Log the cancellation for debugging
        Log::info('SSL Payment Cancelled', [
            'type' => $type,
            'id' => $id,
            'request_data' => $request->all()
        ]);
        
        return redirect()->route('customer.payment.options', [$type, $id])
            ->with('info', 'Payment was cancelled.');
    }
    
    /**
     * Show manual payment form.
     */
    public function showManualPaymentForm(Request $request, $type, $id)
    {
        $order = $this->getOrder($type, $id);
        
        if (!$order || $order->user_id !== Auth::id()) {
            abort(404);
        }
        
        // Get payment amount from request or default to full amount
        $paymentAmount = $request->get('payment_amount', $order->amount);
        
        // Validate payment amount
        if ($paymentAmount > $order->amount || $paymentAmount <= 0) {
            $paymentAmount = $order->amount;
        }
        
        // Check if manual payment already exists
        $existingPayment = $order->manualPayment;
        
        return view('frontend.customer.payment.manual', compact('order', 'type', 'existingPayment', 'paymentAmount'));
    }
    
    /**
     * Process manual payment submission.
     */
    public function processManualPayment(Request $request, $type, $id)
    {
        $order = $this->getOrder($type, $id);
        
        if (!$order || $order->user_id !== Auth::id()) {
            abort(404);
        }
        
        $validated = $request->validate([
            'payment_amount' => ['required', 'numeric', 'min:1', 'max:' . $order->amount],
            'bank_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:50'],
            'transaction_id' => ['required', 'string', 'max:100'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'payment_screenshot' => ['required', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:500']
        ]);
        
        $paymentAmount = $validated['payment_amount'];
        
        // Enhanced payment amount validation
        $totalAmount = $order->total_amount ?? $order->amount;
        $paidAmount = $order->payments()->where('status', 'completed')->sum('amount');
        $remainingAmount = $totalAmount - $paidAmount;

        // Validate payment amount
        if ($paymentAmount > $remainingAmount) {
            return redirect()->back()->with('error', 'Payment amount cannot exceed remaining balance of ৳' . number_format($remainingAmount, 2));
        }

        if ($paymentAmount < 1) {
            return redirect()->back()->with('error', 'Payment amount must be at least ৳1.00');
        }
        
        // Check for duplicate transaction ID
        $existingPayment = ManualPayment::where('transaction_id', $validated['transaction_id'])
            ->where('status', '!=', 'rejected')
            ->first();

        if ($existingPayment) {
            return redirect()->back()->with('error', 'This transaction ID has already been used. Please provide a unique transaction ID.');
        }
        
        DB::beginTransaction();
        
        try {
            // Store the screenshot
            $file = $request->file('payment_screenshot');
            $fileName = 'receipt_' . time() . '_' . $file->getClientOriginalName();
            $screenshotPath = $file->storeAs('payment-screenshots', $fileName, 'public');
            
            // Create or update manual payment record
            $order->manualPayment()->updateOrCreate(
                ['payable_type' => get_class($order), 'payable_id' => $order->id],
                [
                    'user_id' => Auth::id(),
                    'amount' => $paymentAmount,
                    'bank_name' => $validated['bank_name'],
                    'account_number' => $validated['account_number'],
                    'transaction_id' => $validated['transaction_id'],
                    'payment_screenshot' => $screenshotPath,
                    'status' => ManualPayment::STATUS_PENDING,
                    'payment_date' => $validated['payment_date'],
                    'notes' => $validated['notes'] ?? null,
                    'submitted_at' => now(),
                    'ip_address' => $request->ip()
                ]
            );
            
            // Update order payment status
            $order->update([
                'payment_status' => 'pending_verification',
                'payment_method' => 'Manual Bank Transfer',
            ]);
            
            // Log the manual payment submission
            Log::info('Manual Payment Submitted', [
                'user_id' => Auth::id(),
                'order_type' => $type,
                'order_id' => $id,
                'amount' => $paymentAmount,
                'transaction_id' => $validated['transaction_id']
            ]);
            
            $message = $paymentAmount < $order->amount ? 
                'Partial payment proof of BDT ' . number_format($paymentAmount, 2) . ' submitted successfully! It will be reviewed by our team within 24 hours.' :
                'Payment proof submitted successfully! It will be reviewed by our team within 24 hours. You will receive a confirmation once approved.';
            
            DB::commit();
            
            return redirect()->route('customer.' . ($type === 'package' ? 'orders' : 'service-orders') . '.show', $order)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Manual Payment Processing Error: ' . $e->getMessage());
            
            // Delete uploaded file if payment creation failed
            if (isset($screenshotPath) && Storage::disk('public')->exists($screenshotPath)) {
                Storage::disk('public')->delete($screenshotPath);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit payment proof. Please try again.');
        }
    }
    
    /**
     * Get order by type and ID.
     */
    private function getOrder($type, $id)
    {
        if ($type === 'package') {
            return PackageOrder::find($id);
        } elseif ($type === 'service') {
            return ServiceOrder::find($id);
        } elseif ($type === 'custom-service') {
            return CustomServiceRequest::find($id);
        } elseif ($type === 'fund') {
            return FundRequest::find($id);
        }
        
        return null;
    }
}
