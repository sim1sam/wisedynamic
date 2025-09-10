<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceOrder;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ServiceOrderController extends Controller
{
    /**
     * Display a listing of the customer's service orders.
     */
    public function index()
    {
        try {
            // Get orders for the current user by email
            $orders = ServiceOrder::where('email', Auth::user()->email)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('frontend.customer.service-orders.index', [
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading customer service orders', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('customer.dashboard')
                ->with('error', 'Failed to load your service orders: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified service order.
     */
    public function show(ServiceOrder $serviceOrder)
    {
        try {
            // Ensure the order belongs to the current user
            if ($serviceOrder->email !== Auth::user()->email) {
                return redirect()->route('customer.service-orders.index')
                    ->with('error', 'You do not have permission to view this order.');
            }
            
            // Load transactions for this order
            $transactions = $serviceOrder->transactions()->orderBy('created_at', 'desc')->get();
            
            return view('frontend.customer.service-orders.show', [
                'order' => $serviceOrder,
                'transactions' => $transactions
            ]);
        } catch (\Exception $e) {
            Log::error('Error viewing customer service order', [
                'error' => $e->getMessage(),
                'order_id' => $serviceOrder->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('customer.service-orders.index')
                ->with('error', 'Failed to view order: ' . $e->getMessage());
        }
    }
    
    /**
     * Process payment for the specified service order.
     */
    public function processPayment(Request $request, ServiceOrder $serviceOrder)
    {
        try {
            // Ensure the order belongs to the current user
            if ($serviceOrder->email !== Auth::user()->email) {
                return redirect()->route('customer.service-orders.index')
                    ->with('error', 'You do not have permission to process payment for this order.');
            }
            
            // Ensure the order is in processing status (accepted by admin)
            if ($serviceOrder->status !== 'processing') {
                return redirect()->route('customer.service-orders.show', $serviceOrder)
                    ->with('error', 'This order is not ready for payment yet.');
            }
            
            // Validate the payment amount
            $validated = $request->validate([
                'payment_amount' => 'required|numeric|min:1',
                'payment_method' => 'nullable|string',
            ]);
            
            $paymentAmount = $validated['payment_amount'];
            $paymentMethod = $validated['payment_method'] ?? 'online';
            
            // Check if payment amount exceeds due amount
            if ($paymentAmount > $serviceOrder->due_amount) {
                return redirect()->route('customer.service-orders.show', $serviceOrder)
                    ->with('error', 'Payment amount cannot exceed the due amount of BDT ' . number_format($serviceOrder->due_amount));
            }
            
            // Update paid and due amounts
            $newPaidAmount = $serviceOrder->paid_amount + $paymentAmount;
            $newDueAmount = $serviceOrder->amount - $newPaidAmount;
            
            // Create transaction record
            $transaction = new Transaction([
                'transaction_number' => Transaction::generateTransactionNumber(),
                'service_order_id' => $serviceOrder->id,
                'amount' => $paymentAmount,
                'payment_method' => $paymentMethod,
                'notes' => 'Customer payment via website',
            ]);
            $transaction->save();
            
            // Update the order with payment information
            $serviceOrder->update([
                'paid_amount' => $newPaidAmount,
                'due_amount' => $newDueAmount,
                // Keep the status as processing regardless of payment amount
                'status' => 'processing'
            ]);
            
            $message = 'Payment of BDT ' . number_format($paymentAmount) . ' processed successfully. Transaction #' . $transaction->transaction_number;
            $message .= '. Remaining balance: BDT ' . number_format($newDueAmount) . '.';
            
            return redirect()->route('customer.service-orders.show', $serviceOrder)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error processing customer service payment', [
                'error' => $e->getMessage(),
                'order_id' => $serviceOrder->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('customer.service-orders.show', $serviceOrder)
                ->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }
}
