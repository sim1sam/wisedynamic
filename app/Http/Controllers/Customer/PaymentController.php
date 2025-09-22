<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PackageOrder;
use App\Models\ServiceOrder;
use App\Models\ManualPayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        
        // Check if order is accepted/processing and payment is pending
        if (!in_array($order->status, ['accepted', 'processing']) || $order->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Payment is not available for this order.');
        }
        
        return view('frontend.customer.payment.options', compact('order', 'type'));
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
        
        $validated = $request->validate([
            'payment_amount' => ['required', 'numeric', 'min:1', 'max:' . $order->amount],
        ]);
        
        $paymentAmount = $validated['payment_amount'];
        
        // SSL Commerz payment gateway integration
        $sslConfig = config('sslcommerz');
        
        // Generate unique transaction ID
        $transactionId = 'TXN_' . $order->id . '_' . time();
        
        // Prepare SSL Commerz payment data
        $postData = [
            'store_id' => $sslConfig['store_id'],
            'store_passwd' => $sslConfig['store_password'],
            'total_amount' => $paymentAmount,
            'currency' => 'BDT',
            'tran_id' => $transactionId,
            'success_url' => route('customer.payment.ssl.success', ['type' => $type, 'id' => $id, 'tran_id' => $transactionId]),
            'fail_url' => route('customer.payment.ssl.fail', ['type' => $type, 'id' => $id]),
            'cancel_url' => route('customer.payment.ssl.cancel', ['type' => $type, 'id' => $id]),
            'ipn_url' => $sslConfig['ipn_url'],
            
            // Customer information
            'cus_name' => Auth::user()->name,
            'cus_email' => Auth::user()->email,
            'cus_add1' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_country' => 'Bangladesh',
            'cus_phone' => Auth::user()->phone ?? '01700000000',
            
            // Product information
            'product_name' => $type === 'package' ? 'Package Order #' . $order->id : 'Service Order #' . $order->id,
            'product_category' => ucfirst($type) . ' Order',
            'product_profile' => 'general',
            
            // Shipping information
            'shipping_method' => 'NO',
            'ship_name' => Auth::user()->name,
            'ship_add1' => 'Dhaka',
            'ship_city' => 'Dhaka',
            'ship_country' => 'Bangladesh',
        ];
        
        // Store transaction data in session for later verification
        session([
            'ssl_payment_data' => [
                'transaction_id' => $transactionId,
                'order_type' => $type,
                'order_id' => $id,
                'amount' => $paymentAmount,
                'user_id' => Auth::id(),
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
        curl_close($ch);
        
        if ($httpCode !== 200 || !$response) {
            return redirect()->back()->with('error', 'Unable to connect to payment gateway. Please try again.');
        }
        
        $responseData = json_decode($response, true);
        
        if (isset($responseData['status']) && $responseData['status'] === 'SUCCESS') {
            // Redirect to SSL Commerz payment page
            return redirect($responseData['GatewayPageURL']);
        } else {
            return redirect()->back()->with('error', 'Payment gateway error: ' . ($responseData['failedreason'] ?? 'Unknown error'));
        }
    }
    
    /**
     * Handle SSL payment success callback.
     */
    public function sslSuccess(Request $request)
    {
        $transactionId = $request->get('tran_id');
        $paymentData = session('ssl_payment_data');
        
        if (!$paymentData || $paymentData['transaction_id'] !== $transactionId) {
            return redirect()->route('customer.dashboard')->with('error', 'Invalid payment session.');
        }
        
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
        curl_close($ch);
        
        $validationResponse = json_decode($response, true);
        
        if ($validationResponse['status'] === 'VALID' && $validationResponse['tran_id'] === $transactionId) {
            // Payment is valid, process the order
            $order = $this->getOrder($paymentData['order_type'], $paymentData['order_id']);
            
            DB::beginTransaction();
            
            try {
                // Update order payment status
                $newPaidAmount = ($order->paid_amount ?? 0) + $paymentData['amount'];
                $newDueAmount = $order->amount - $newPaidAmount;
                
                $order->update([
                    'payment_status' => $newDueAmount <= 0 ? 'paid' : 'pending_verification',
                    'payment_method' => 'SSL Payment',
                    'paid_amount' => $newPaidAmount,
                    'due_amount' => $newDueAmount,
                ]);
                
                // Create transaction record
                Transaction::create([
                    'transaction_number' => Transaction::generateTransactionNumber(),
                    $paymentData['order_type'] === 'package' ? 'package_order_id' : 'service_order_id' => $order->id,
                    'amount' => $paymentData['amount'],
                    'payment_method' => 'SSL Payment',
                    'status' => 'completed',
                    'notes' => 'SSL payment completed successfully. Transaction ID: ' . $transactionId . ', Amount: BDT ' . number_format($paymentData['amount'], 2),
                ]);
                
                DB::commit();
                
                // Clear session data
                session()->forget('ssl_payment_data');
                
                $message = $newDueAmount <= 0 ? 'Payment completed successfully!' : 'Partial payment of BDT ' . number_format($paymentData['amount'], 2) . ' completed successfully!';
                
                return redirect()->route('customer.' . ($paymentData['order_type'] === 'package' ? 'orders' : 'service-orders') . '.show', $order)
                    ->with('success', $message);
                    
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
    public function sslFail(Request $request)
    {
        $paymentData = session('ssl_payment_data');
        session()->forget('ssl_payment_data');
        
        if ($paymentData) {
            return redirect()->route('customer.payment.options', [$paymentData['order_type'], $paymentData['order_id']])
                ->with('error', 'Payment failed. Please try again.');
        }
        
        return redirect()->route('customer.dashboard')->with('error', 'Payment failed.');
    }
    
    /**
     * Handle SSL payment cancellation callback.
     */
    public function sslCancel(Request $request)
    {
        $paymentData = session('ssl_payment_data');
        session()->forget('ssl_payment_data');
        
        if ($paymentData) {
            return redirect()->route('customer.payment.options', [$paymentData['order_type'], $paymentData['order_id']])
                ->with('info', 'Payment was cancelled.');
        }
        
        return redirect()->route('customer.dashboard')->with('info', 'Payment was cancelled.');
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
            'account_number' => ['required', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'payment_screenshot' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);
        
        $paymentAmount = $validated['payment_amount'];
        
        DB::beginTransaction();
        
        try {
            // Store the screenshot
            $screenshotPath = $request->file('payment_screenshot')->store('payment-screenshots', 'public');
            
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
                ]
            );
            
            // Update order payment status
            $order->update([
                'payment_status' => 'pending_verification',
                'payment_method' => 'Manual Bank Transfer',
            ]);
            
            $message = $paymentAmount < $order->amount ? 
                'Partial payment proof of BDT ' . number_format($paymentAmount, 2) . ' submitted successfully! Please wait for admin verification.' :
                'Payment proof submitted successfully! Please wait for admin verification.';
            
            DB::commit();
            
            return redirect()->route('customer.' . ($type === 'package' ? 'orders' : 'service-orders') . '.show', $order)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollback();
            
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
        }
        
        return null;
    }
}
