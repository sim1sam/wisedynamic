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
        
        // Check if order is accepted and payment is pending
        if ($order->status !== 'accepted' || $order->payment_status === 'paid') {
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
        
        // For now, simulate SSL payment success
        // In production, integrate with actual SSL payment gateway
        
        DB::beginTransaction();
        
        try {
            // Update order payment status
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => 'SSL Payment',
            ]);
            
            // Create transaction record
            Transaction::create([
                'transaction_number' => Transaction::generateTransactionNumber(),
                $type === 'package' ? 'package_order_id' : 'service_order_id' => $order->id,
                'amount' => $order->total_amount,
                'payment_method' => 'SSL Payment',
                'status' => 'completed',
                'notes' => 'SSL payment completed successfully.',
            ]);
            
            DB::commit();
            
            return redirect()->route('customer.' . ($type === 'package' ? 'orders' : 'service-orders') . '.show', $order)
                ->with('success', 'Payment completed successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Payment failed. Please try again.');
        }
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
        
        // Check if manual payment already exists
        $existingPayment = $order->manualPayment;
        
        return view('frontend.customer.payment.manual', compact('order', 'type', 'existingPayment'));
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
            'bank_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'payment_screenshot' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);
        
        DB::beginTransaction();
        
        try {
            // Store the screenshot
            $screenshotPath = $request->file('payment_screenshot')->store('payment-screenshots', 'public');
            
            // Create or update manual payment record
            $order->manualPayment()->updateOrCreate(
                ['payable_type' => get_class($order), 'payable_id' => $order->id],
                [
                    'user_id' => Auth::id(),
                    'amount' => $order->total_amount,
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
            
            DB::commit();
            
            return redirect()->route('customer.' . ($type === 'package' ? 'orders' : 'service-orders') . '.show', $order)
                ->with('success', 'Payment proof submitted successfully! Please wait for admin verification.');
                
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
