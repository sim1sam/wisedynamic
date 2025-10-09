<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PackageOrder;
use App\Models\ServiceOrder;
use App\Models\CustomServiceRequest;
use App\Models\FundRequest;
use App\Models\ManualPayment;
use App\Models\Transaction;
use App\Http\Controllers\Api\TransactionController as ApiTransactionController;
use App\Services\FraudDetectionService;
use App\Services\PaymentAuditService;
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
        // Implement IP whitelisting for payment gateway callbacks
        // Note: Replace these with the actual IPs from SSL Commerz
        $allowedIPs = [
            '203.112.xxx.xxx',
            '203.112.xxx.xxx',
            // Add development IPs for testing
            '127.0.0.1',
            '::1'
        ];
        
        // Skip IP check in local/development environment
        if (app()->environment('production') && !in_array($request->ip(), $allowedIPs)) {
            Log::warning('Unauthorized IP attempting IPN callback', [
                'ip' => $request->ip(),
                'path' => $request->path()
            ]);
            
            return response('Unauthorized', 403);
        }
        
        // Basic sanity check
        if (!$request->has(['tran_id', 'val_id', 'status'])) {
            Log::warning('SSL IPN missing required fields', $request->all());
            return response('Bad Request', 400);
        }

        $type = $request->get('value_a');
        $id = $request->get('value_b');
        $transactionId = $request->get('tran_id');

        // Log IPN callback for debugging
        Log::info('SSL IPN Callback Received', [
            'transaction_id' => $transactionId,
            'type' => $type,
            'id' => $id,
            'status' => $request->get('status'),
            'amount' => $request->get('amount'),
        ]);

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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
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

        // Use API controller to update transaction status
        $apiController = new ApiTransactionController();
        
        if (in_array($validationResponse['status'], ['VALID', 'VALIDATED'])) {
            // Prepare data for successful transaction update
            $statusUpdateData = [
                'ssl_transaction_id' => $transactionId,
                'status' => 'success',
                'gateway_response' => $validationResponse,
                'bank_transaction_id' => $validationResponse['bank_tran_id'] ?? null,
                'card_type' => $validationResponse['card_type'] ?? null,
                'card_no' => $validationResponse['card_no'] ?? null,
                'card_issuer' => $validationResponse['card_issuer'] ?? null,
                'currency_type' => $validationResponse['currency_type'] ?? 'BDT',
                'currency_amount' => $validationResponse['amount'] ?? null,
            ];
            
            Log::info('SSL IPN: Updating transaction status to success', [
                'transaction_id' => $transactionId,
                'validation_response' => $validationResponse
            ]);
        } else {
            // Prepare data for failed transaction update
            $statusUpdateData = [
                'ssl_transaction_id' => $transactionId,
                'status' => 'failed',
                'fail_reason' => 'SSL validation failed: ' . ($validationResponse['status'] ?? 'Unknown error'),
                'gateway_response' => $validationResponse,
            ];
            
            Log::warning('SSL IPN: Updating transaction status to failed', [
                'transaction_id' => $transactionId,
                'validation_response' => $validationResponse
            ]);
        }

        // Create a mock request for the API controller
        $mockRequest = new \Illuminate\Http\Request();
        $mockRequest->merge($statusUpdateData);
        
        try {
            $apiResponse = $apiController->updateStatus($mockRequest);
            $responseData = json_decode($apiResponse->getContent(), true);
            
            if ($apiResponse->getStatusCode() === 200) {
                Log::info('SSL IPN: Transaction status updated successfully', [
                    'transaction_id' => $transactionId,
                    'api_response' => $responseData
                ]);
                return response('OK', 200);
            } else {
                Log::error('SSL IPN: Failed to update transaction status', [
                    'transaction_id' => $transactionId,
                    'api_response' => $responseData,
                    'status_code' => $apiResponse->getStatusCode()
                ]);
                return response('Update Failed', 422);
            }
        } catch (\Exception $e) {
            Log::error('SSL IPN: Exception during status update', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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
        
        // Generate unique transaction ID with cryptographically secure random bytes
        $transactionId = 'WD' . time() . bin2hex(random_bytes(6));
        
        // Get the current domain from the request
        $currentDomain = $request->getSchemeAndHttpHost();
        
        // Enhanced SSL payment data
        $postData = [
            'store_id' => $sslConfig['store_id'],
            'store_passwd' => $sslConfig['store_password'],
            'total_amount' => $paymentAmount,
            'currency' => 'BDT',
            'tran_id' => $transactionId,
            // Use generic callback URLs to ensure proper handling
            'success_url' => $currentDomain . "/customer/payment/ssl/success",
            'fail_url' => $currentDomain . "/customer/payment/ssl/fail",
            'cancel_url' => $currentDomain . "/customer/payment/ssl/cancel",
            'ipn_url' => $currentDomain . '/customer/payment/ssl/ipn',
            
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
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

        // Create a transaction record for tracking and fraud detection
        $transaction = Transaction::create([
            'transaction_number' => Transaction::generateTransactionNumber(),
            'ssl_transaction_id' => $transactionId,
            'amount' => $paymentAmount,
            'payment_method' => 'SSL Payment',
            'status' => 'pending',
            'customer_name' => Auth::user()->name,
            'customer_email' => Auth::user()->email,
            'customer_phone' => Auth::user()->phone,
            'customer_address' => Auth::user()->address,
            'order_type' => $type,
        ]);
        
        // Set the appropriate foreign key based on order type
        if ($type === 'package') {
            $transaction->package_order_id = $id;
        } elseif ($type === 'service') {
            $transaction->service_order_id = $id;
        } elseif ($type === 'custom-service') {
            $transaction->custom_service_request_id = $id;
        } elseif ($type === 'fund') {
            $transaction->fund_request_id = $id;
        }
        $transaction->save();
        
        // Run fraud detection
        $fraudDetection = app(FraudDetectionService::class)->checkTransaction($transaction, $request);
        
        // If transaction is suspicious, block it
        if ($fraudDetection['is_suspicious']) {
            Log::warning('Suspicious transaction blocked', [
                'transaction_id' => $transactionId,
                'fraud_score' => $fraudDetection['score'],
                'fraud_flags' => $fraudDetection['flags']
            ]);
            
            // Update transaction status
            $transaction->update([
                'status' => 'blocked',
                'notes' => 'Blocked by fraud detection system. Score: ' . $fraudDetection['score']
            ]);
            
            return redirect()->back()->with('error', 'Your payment could not be processed. Please contact customer support.');
        }
        
        // Log payment attempt in audit log
        app(PaymentAuditService::class)->logPaymentAttempt('ssl', $id, $type, $paymentAmount, $transactionId);
        
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
        // No need to manually disable CSRF here - we've added these routes to the except array in VerifyCsrfToken middleware
        
        // Implement IP whitelisting for payment gateway callbacks
        // Note: Replace these with the actual IPs from SSL Commerz
        $allowedIPs = [
            '203.112.xxx.xxx',
            '203.112.xxx.xxx',
            // Add development IPs for testing
            '127.0.0.1',
            '::1'
        ];
        
        // Skip IP check in local/development environment
        if (app()->environment('production') && !in_array($request->ip(), $allowedIPs)) {
            Log::warning('Unauthorized IP attempting payment callback', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'transaction_id' => $request->get('tran_id')
            ]);
            
            abort(403, 'Unauthorized access');
        }
        
        // If the user refreshes the page or hits the URL directly without
        // gateway payload, gracefully redirect to a safe page.
        if (!$request->has('tran_id')) {
            Log::warning('SSL Success callback missing tran_id', [
                'path' => $request->path(),
                'method' => $request->method(),
                'request_data' => $request->all(),
                'type' => $type,
                'id' => $id
            ]);
            
            return redirect()->route('customer.dashboard')
                ->with('info', 'Payment result already processed or no gateway data available.');
        }

        $transactionId = $request->get('tran_id');
        
        Log::info('SSL Success Callback Received', [
            'transaction_id' => $transactionId,
            'type' => $type,
            'id' => $id,
            'request_data' => $request->all()
        ]);
        
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        
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
        
        // Use API controller to update transaction status
        $apiController = new ApiTransactionController();
        
        // Check if validation response is valid
        if (!$validationResponse || !isset($validationResponse['status'])) {
            Log::error('Invalid SSL validation response', [
                'response' => $response,
                'curl_error' => $curlError
            ]);
            
            // Update transaction status to failed
            $statusUpdateData = [
                'ssl_transaction_id' => $transactionId,
                'status' => 'failed',
                'fail_reason' => 'SSL validation response invalid or empty',
                'gateway_response' => ['error' => 'Invalid validation response', 'curl_error' => $curlError],
            ];
            
            $mockRequest = new \Illuminate\Http\Request();
            $mockRequest->merge($statusUpdateData);
            
            try {
                $apiController->updateStatus($mockRequest);
            } catch (\Exception $e) {
                Log::error('Failed to update transaction status to failed: ' . $e->getMessage());
            }
            
            return redirect()->route('customer.payment.options', ['type' => $type, 'id' => $id])
                ->with('error', 'Payment validation failed. Please try again.');
        }
        
        if ($validationResponse['status'] === 'VALID' && $validationResponse['tran_id'] === $transactionId) {
            // Update transaction status to success
            $statusUpdateData = [
                'ssl_transaction_id' => $transactionId,
                'status' => 'success',
                'gateway_response' => $validationResponse,
                'bank_transaction_id' => $validationResponse['bank_tran_id'] ?? null,
                'card_type' => $validationResponse['card_type'] ?? null,
                'card_no' => $validationResponse['card_no'] ?? null,
                'card_issuer' => $validationResponse['card_issuer'] ?? null,
                'currency_type' => $validationResponse['currency_type'] ?? 'BDT',
                'currency_amount' => $validationResponse['amount'] ?? null,
            ];
            
            $mockRequest = new \Illuminate\Http\Request();
            $mockRequest->merge($statusUpdateData);
            
            try {
                $apiResponse = $apiController->updateStatus($mockRequest);
                $responseData = json_decode($apiResponse->getContent(), true);
                
                if ($apiResponse->getStatusCode() === 200) {
                    Log::info('SSL Success: Transaction status updated successfully', [
                        'transaction_id' => $transactionId,
                        'api_response' => $responseData
                    ]);
                } else {
                    Log::error('SSL Success: Failed to update transaction status', [
                        'transaction_id' => $transactionId,
                        'api_response' => $responseData
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('SSL Success: Exception during status update: ' . $e->getMessage());
            }
            
            // Payment is valid, process the order
            $order = $this->getOrder($type, $id);
            
            if (!$order) {
                Log::warning('SSL Success: Order not found', [
                    'transaction_id' => $transactionId,
                    'type' => $type,
                    'id' => $id
                ]);
                
                // Store transaction ID and success message in session
                $message = 'Your payment with transaction ID: ' . $transactionId . ' has been processed, but we could not find the associated order. Please contact support.';
                session([
                    'ssl_transaction_id' => $transactionId,
                    'payment_success' => $message
                ]);
                
                // Redirect to success page
                return redirect()->route('payment.success.page');
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
                
                // Create comprehensive transaction record with SSL data
                $transactionData = [
                    'transaction_number' => Transaction::generateTransactionNumber(),
                    'amount' => $validationResponse['amount'] ?? $order->amount,
                    'payment_method' => 'SSL Payment',
                    'status' => Transaction::STATUS_COMPLETED,
                    'notes' => 'SSL payment completed successfully. Transaction ID: ' . $transactionId . ', Amount: BDT ' . number_format($validationResponse['amount'] ?? $order->amount, 2),
                    
                    // SSL Commerz specific fields
                    'ssl_transaction_id' => $validationResponse['tran_id'] ?? $transactionId,
                    'ssl_session_id' => $validationResponse['sessionkey'] ?? null,
                    'ssl_bank_transaction_id' => $validationResponse['bank_tran_id'] ?? null,
                    'ssl_card_type' => $validationResponse['card_type'] ?? null,
                    'ssl_card_no' => $validationResponse['card_no'] ?? null,
                    'ssl_card_issuer' => $validationResponse['card_issuer'] ?? null,
                    'ssl_card_brand' => $validationResponse['card_brand'] ?? null,
                    'ssl_card_issuer_country' => $validationResponse['card_issuer_country'] ?? null,
                    'ssl_card_issuer_country_code' => $validationResponse['card_issuer_country_code'] ?? null,
                    'ssl_currency_type' => $validationResponse['currency_type'] ?? 'BDT',
                    'ssl_amount' => $validationResponse['amount'] ?? null,
                    'ssl_currency_amount' => $validationResponse['currency_amount'] ?? null,
                    'ssl_currency_rate' => $validationResponse['currency_rate'] ?? null,
                    'ssl_base_fair' => $validationResponse['base_fair'] ?? null,
                    'ssl_value_a' => $validationResponse['value_a'] ?? $type,
                    'ssl_value_b' => $validationResponse['value_b'] ?? $id,
                    'ssl_value_c' => $validationResponse['value_c'] ?? null,
                    'ssl_value_d' => $validationResponse['value_d'] ?? null,
                    'ssl_risk_level' => $validationResponse['risk_level'] ?? null,
                    'ssl_risk_title' => $validationResponse['risk_title'] ?? null,
                    
                    // New SSL status fields
                    'ssl_status' => 'VALID',
                    
                    // Customer information
                    'customer_name' => $validationResponse['cus_name'] ?? optional(Auth::user())->name,
                    'customer_email' => $validationResponse['cus_email'] ?? optional(Auth::user())->email,
                    'customer_phone' => $validationResponse['cus_phone'] ?? optional(Auth::user())->phone,
                    'customer_address' => $validationResponse['cus_add1'] ?? optional(Auth::user())->address,
                    'customer_city' => $validationResponse['cus_city'] ?? 'Dhaka',
                    'customer_state' => $validationResponse['cus_state'] ?? null,
                    'customer_postcode' => $validationResponse['cus_postcode'] ?? null,
                    'customer_country' => $validationResponse['cus_country'] ?? 'Bangladesh',
                    
                    // Order details
                    'order_type' => $type,
                    'order_details' => $this->getOrderDetails($order, $type),
                    'ssl_response_data' => $validationResponse,
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
                
                // Log successful payment in audit log
                app(PaymentAuditService::class)->logPaymentSuccess(
                    $transaction->id,
                    $transaction->amount,
                    [
                        'order_type' => $type,
                        'order_id' => $order->id,
                        'ssl_transaction_id' => $transactionId,
                        'payment_status' => $transaction->status
                    ]
                );
                
                // Store success message in session for display after login
                session()->flash('payment_success', $message);
                session()->flash('payment_order_type', $type);
                session()->flash('payment_order_id', $order->id);
                
                // Store success message in session for display on success page
                session(['payment_success' => $message]);
                
                // Redirect to dashboard if logged in, otherwise to public success page
                if (\Illuminate\Support\Facades\Auth::check()) {
                    return redirect()->route('customer.dashboard')->with('success', $message);
                }
                return redirect()->route('payment.success.page');
                    
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->route('customer.dashboard')->with('error', 'Payment processing failed.');
            }
        } else {
            // Update transaction status to failed
            $statusUpdateData = [
                'ssl_transaction_id' => $transactionId,
                'status' => 'failed',
                'fail_reason' => 'SSL validation failed or transaction ID mismatch',
                'gateway_response' => $validationResponse,
            ];
            
            $mockRequest = new \Illuminate\Http\Request();
            $mockRequest->merge($statusUpdateData);
            
            try {
                $apiController->updateStatus($mockRequest);
            } catch (\Exception $e) {
                Log::error('Failed to update transaction status to failed: ' . $e->getMessage());
            }
            
            return redirect()->route('customer.dashboard')->with('error', 'Payment verification failed.');
        }
    }
    
    /**
     * Handle SSL payment failure callback.
     */
    public function sslFail(Request $request, $type, $id)
    {
        // No need to manually disable CSRF here - we've added these routes to the except array in VerifyCsrfToken middleware
        
        // Handle requests without transaction ID
        if (!$request->has('tran_id')) {
            Log::warning('SSL Fail callback missing tran_id', [
                'path' => $request->path(),
                'method' => $request->method(),
                'request_data' => $request->all(),
                'type' => $type,
                'id' => $id
            ]);
            
            return redirect()->route('customer.payment.options', ['type' => $type, 'id' => $id])
                ->with('error', 'Payment failed. If you were attempting to make a payment, please try again or contact support.');
        }

        $transactionId = $request->get('tran_id');
        
        // Log the failure for debugging
        Log::info('SSL Payment Failed Callback', [
            'type' => $type,
            'id' => $id,
            'transaction_id' => $transactionId,
            'request_data' => $request->all()
        ]);
        
        // Get order to create failed transaction record
        $order = $this->getOrder($type, $id);
        
        if (!$order) {
            Log::warning('SSL Fail: Order not found', [
                'transaction_id' => $transactionId,
                'type' => $type,
                'id' => $id
            ]);
            
            // Store transaction ID and error message in session
            $errorMessage = 'Payment failed with transaction ID: ' . $transactionId . '. We could not find the associated order. Please contact support.';
            session([
                'ssl_transaction_id' => $transactionId,
                'payment_error' => $errorMessage
            ]);
            
            // Redirect to home with error message
            return redirect()->route('home')->with('error', $errorMessage);
        } else {
            DB::beginTransaction();
            try {
                // Create failed transaction record
                $transactionData = [
                    'transaction_number' => Transaction::generateTransactionNumber(),
                    'amount' => $request->get('amount') ?? $order->amount,
                    'payment_method' => 'SSL Payment',
                    'status' => Transaction::STATUS_FAILED,
                    'notes' => 'SSL payment failed. Transaction ID: ' . $transactionId . '. Reason: ' . ($request->get('failedreason') ?? 'Payment gateway failure'),
                    
                    // SSL specific fields for failed transaction
                    'ssl_transaction_id' => $transactionId,
                    'ssl_status' => 'FAILED',
                    'ssl_fail_reason' => $request->get('failedreason') ?? 'Payment gateway failure',
                    'ssl_response_data' => $request->all(),

                    // Customer information
                    'customer_name' => optional(Auth::user())->name,
                    'customer_email' => optional(Auth::user())->email,
                    'customer_phone' => optional(Auth::user())->phone,
                    'customer_address' => optional(Auth::user())->address,
                    'customer_city' => 'Dhaka',
                    'customer_country' => 'Bangladesh',
                    
                    // Order details
                    'order_type' => $type,
                    'order_details' => $this->getOrderDetails($order, $type),
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
                
                // Log failed payment in audit log
                app(PaymentAuditService::class)->logPaymentFailure(
                    $transactionId,
                    $request->get('failedreason') ?? 'Payment gateway failure',
                    [
                        'order_type' => $type,
                        'order_id' => $order->id
                    ]
                );
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Failed to create failed transaction record: ' . $e->getMessage());
            }
        }
        
        // Store error message in session
        $errorMessage = 'Payment failed. Please try again or contact support with your transaction ID: ' . $transactionId;
        session(['payment_error' => $errorMessage]);
        
        if (\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('customer.dashboard')->with('error', $errorMessage);
        }
        return redirect()->route('home')->with('error', $errorMessage);
    }
    
    /**
     * Handle SSL payment cancellation callback.
     */
    public function sslCancel(Request $request, $type, $id)
    {
        // No need to manually disable CSRF here - we've added these routes to the except array in VerifyCsrfToken middleware
        
        // Handle requests without transaction ID
        if (!$request->has('tran_id')) {
            Log::warning('SSL Cancel callback missing tran_id', [
                'path' => $request->path(),
                'method' => $request->method(),
                'request_data' => $request->all(),
                'type' => $type,
                'id' => $id
            ]);
            
            return redirect()->route('customer.payment.options', ['type' => $type, 'id' => $id])
                ->with('info', 'Payment was cancelled or the process was interrupted. You can try again when ready.');
        }

        $transactionId = $request->get('tran_id');
        
        // Log the cancellation for debugging
        Log::info('SSL Payment Cancelled', [
            'type' => $type,
            'id' => $id,
            'transaction_id' => $transactionId,
            'request_data' => $request->all()
        ]);
        
        // Get order to create cancelled transaction record
        $order = $this->getOrder($type, $id);
        
        if (!$order) {
            Log::warning('SSL Cancel: Order not found', [
                'transaction_id' => $transactionId,
                'type' => $type,
                'id' => $id
            ]);
            
            // Store transaction ID and cancel message in session
            $cancelMessage = 'Payment was cancelled with transaction ID: ' . $transactionId . '. We could not find the associated order. You can try again when ready.';
            session([
                'ssl_transaction_id' => $transactionId,
                'payment_cancel' => $cancelMessage
            ]);
            
            // Redirect to home with info message
            return redirect()->route('home')->with('info', $cancelMessage);
        } else {
            DB::beginTransaction();
            try {
                // Create cancelled transaction record
                $transactionData = [
                    'transaction_number' => Transaction::generateTransactionNumber(),
                    'amount' => $request->get('amount') ?? $order->amount,
                    'payment_method' => 'SSL Payment',
                    'status' => Transaction::STATUS_CANCELLED,
                    'notes' => 'SSL payment cancelled by user. Transaction ID: ' . $transactionId,
                    
                    // SSL specific fields for cancelled transaction
                    'ssl_transaction_id' => $transactionId,
                    'ssl_status' => 'CANCELLED',
                    'ssl_response_data' => $request->all(),

                    // Customer information
                    'customer_name' => optional(Auth::user())->name,
                    'customer_email' => optional(Auth::user())->email,
                    'customer_phone' => optional(Auth::user())->phone,
                    'customer_address' => optional(Auth::user())->address,
                    'customer_city' => 'Dhaka',
                    'customer_country' => 'Bangladesh',
                    
                    // Order details
                    'order_type' => $type,
                    'order_details' => $this->getOrderDetails($order, $type),
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
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Failed to create cancelled transaction record: ' . $e->getMessage());
            }
        }
        
        // Store cancel message in session
        $cancelMessage = 'Payment was cancelled. You can try again when ready.';
        session(['payment_cancel' => $cancelMessage]);
        
        if (\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('customer.dashboard')->with('info', $cancelMessage);
        }
        return redirect()->route('home')->with('info', $cancelMessage);
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
    
    /**
     * Handle generic SSL callbacks without type and ID parameters.
     * This method extracts the type and ID from the request data and redirects to the appropriate handler.
     */
    public function handleGenericSSLCallback(Request $request)
    {
        // No need to manually disable CSRF here - we've added these routes to the except array in VerifyCsrfToken middleware
        
        // Log the callback for debugging
        Log::info('Generic SSL Callback Received', [
            'path' => $request->path(),
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'full_url' => $request->fullUrl(),
        ]);
        
        // Extract all possible parameters from the request data
        $type = $request->get('value_a');
        $id = $request->get('value_b');
        $transactionId = $request->get('tran_id');
        $amount = $request->get('amount');
        $status = $request->get('status');
        $valId = $request->get('val_id');
        
        // Log all parameters for debugging
        Log::info('SSL Callback Parameters', [
            'type' => $type,
            'id' => $id,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'status' => $status,
            'val_id' => $valId,
        ]);
        
        // If we can't determine the type and ID, handle based on callback type
        if (!$type || !$id) {
            Log::warning('SSL Callback missing type or ID', [
                'path' => $request->path(),
                'request_data' => $request->all()
            ]);
            
            // Determine which type of callback this is
            $path = $request->path();
            
            if (strpos($path, 'success') !== false) {
                // Create a generic success message
                $message = 'Your payment with transaction ID: ' . ($transactionId ?? 'Unknown') . ' has been processed. Please check your order status in your account.';
                
                // Try to find the transaction in the database if we have a transaction ID
                if ($transactionId) {
                    $transaction = \App\Models\Transaction::where('ssl_transaction_id', $transactionId)->first();
                    
                    if ($transaction) {
                        // If we found the transaction, we can determine the type and ID
                        if ($transaction->package_order_id) {
                            $type = 'package';
                            $id = $transaction->package_order_id;
                        } elseif ($transaction->service_order_id) {
                            $type = 'service';
                            $id = $transaction->service_order_id;
                        } elseif ($transaction->custom_service_request_id) {
                            $type = 'custom-service';
                            $id = $transaction->custom_service_request_id;
                        } elseif ($transaction->fund_request_id) {
                            $type = 'fund';
                            $id = $transaction->fund_request_id;
                        }
                        
                        // If we found the type and ID, we can redirect to the success handler
                        if ($type && $id) {
                            return $this->sslSuccess($request, $type, $id);
                        }
                    }
                }
                
                // Store in session and redirect to success page
                session([
                    'payment_success' => $message,
                    'ssl_transaction_id' => $transactionId
                ]);
                
                // Redirect directly to the success page with query parameters
                return redirect()->to(route('payment.success.page') . '?message=' . urlencode($message) . '&tran_id=' . urlencode($transactionId) . '&status=success');
            } elseif (strpos($path, 'fail') !== false) {
                // Create a failure message
                $errorMessage = 'Payment failed. If you were attempting to make a payment, please try again or contact support with your transaction ID: ' . ($transactionId ?? 'Unknown');
                
                // Store in session and redirect
                session([
                    'payment_error' => $errorMessage,
                    'ssl_transaction_id' => $transactionId
                ]);
                
                // Redirect directly with query parameters
                return redirect()->to(route('home') . '?tran_id=' . urlencode($transactionId) . '&status=failed&error=' . urlencode($errorMessage));
            } elseif (strpos($path, 'cancel') !== false) {
                // Create a cancellation message
                $cancelMessage = 'Payment was cancelled. You can try again when ready.';
                
                // Store in session and redirect
                session([
                    'payment_cancel' => $cancelMessage,
                    'ssl_transaction_id' => $transactionId
                ]);
                
                // Redirect directly with query parameters
                return redirect()->to(route('home') . '?tran_id=' . urlencode($transactionId) . '&status=cancelled&info=' . urlencode($cancelMessage));
            } else {
                // Fallback to a safe redirect
                $warningMessage = 'Unknown payment callback received. Please contact support with your transaction ID: ' . ($transactionId ?? 'Unknown');
                
                // Redirect directly with query parameters
                return redirect()->to(route('home') . '?warning=' . urlencode($warningMessage));
            }
        }
        
        // Determine which callback method to use based on the request path
        $path = $request->path();
        
        if (strpos($path, 'success') !== false) {
            return $this->sslSuccess($request, $type, $id);
        } elseif (strpos($path, 'fail') !== false) {
            return $this->sslFail($request, $type, $id);
        } elseif (strpos($path, 'cancel') !== false) {
            return $this->sslCancel($request, $type, $id);
        } else {
            // Fallback to a safe redirect
            return redirect()->route('customer.dashboard')
                ->with('warning', 'Unknown payment callback received. Please contact support with your transaction ID: ' . ($transactionId ?? 'Unknown'));
        }
    }
    
    /**
     * Get comprehensive order details for transaction record.
     */
    private function getOrderDetails($order, $type)
    {
        $details = [
            'order_id' => $order->id,
            'order_type' => $type,
            'amount' => $order->amount ?? $order->total_amount,
        ];
        
        switch ($type) {
            case 'package':
                $details['package_id'] = $order->package_id;
                $details['package_name'] = $order->package->title ?? 'Unknown Package';
                $details['package_category'] = $order->package->category->name ?? 'Unknown Category';
                $details['duration'] = $order->duration;
                $details['start_date'] = $order->start_date;
                $details['end_date'] = $order->end_date;
                break;
                
            case 'service':
                $details['service_id'] = $order->service_id;
                $details['service_name'] = $order->service->title ?? 'Unknown Service';
                $details['service_category'] = $order->service->category->name ?? 'Unknown Category';
                $details['quantity'] = $order->quantity;
                $details['requirements'] = $order->requirements;
                break;
                
            case 'custom-service':
                $details['title'] = $order->title;
                $details['description'] = $order->description;
                $details['requirements'] = $order->requirements;
                $details['deadline'] = $order->deadline;
                $details['items'] = $order->items->map(function($item) {
                    return [
                        'title' => $item->title,
                        'description' => $item->description,
                        'price' => $item->price,
                        'duration_days' => $item->duration_days,
                    ];
                })->toArray();
                break;
                
            case 'fund':
                $details['fund_type'] = 'Account Balance Top-up';
                $details['description'] = $order->description ?? 'Fund request for account balance';
                break;
        }
        
        return $details;
    }
}
