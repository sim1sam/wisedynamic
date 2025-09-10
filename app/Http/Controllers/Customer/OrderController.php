<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackageOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the customer's orders.
     */
    public function index()
    {
        try {
            // Get orders for the current user by email
            $orders = PackageOrder::where('email', Auth::user()->email)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('frontend.customer.orders.index', [
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading customer orders', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('customer.dashboard')
                ->with('error', 'Failed to load your orders: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified order.
     */
    public function show(PackageOrder $order)
    {
        try {
            // Ensure the order belongs to the current user
            if ($order->email !== Auth::user()->email) {
                return redirect()->route('customer.orders.index')
                    ->with('error', 'You do not have permission to view this order.');
            }
            
            // Load transactions for this order
            $transactions = $order->transactions()->orderBy('created_at', 'desc')->get();
            
            return view('frontend.customer.orders.show', [
                'order' => $order,
                'transactions' => $transactions
            ]);
        } catch (\Exception $e) {
            Log::error('Error viewing customer order', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('customer.orders.index')
                ->with('error', 'Failed to view order: ' . $e->getMessage());
        }
    }
    
    /**
     * Process payment for the specified order.
     */
    public function processPayment(Request $request, PackageOrder $order)
    {
        try {
            // Ensure the order belongs to the current user
            if ($order->email !== Auth::user()->email) {
                return redirect()->route('customer.orders.index')
                    ->with('error', 'You do not have permission to process payment for this order.');
            }
            
            // Ensure the order is in processing status (accepted by admin)
            if ($order->status !== 'processing') {
                return redirect()->route('customer.orders.show', $order)
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
            if ($paymentAmount > $order->due_amount) {
                return redirect()->route('customer.orders.show', $order)
                    ->with('error', 'Payment amount cannot exceed the due amount of BDT ' . number_format($order->due_amount));
            }
            
            // Update paid and due amounts
            $newPaidAmount = $order->paid_amount + $paymentAmount;
            $newDueAmount = $order->amount - $newPaidAmount;
            
            // Create transaction record
            $transaction = new \App\Models\Transaction([
                'transaction_number' => \App\Models\Transaction::generateTransactionNumber(),
                'package_order_id' => $order->id,
                'amount' => $paymentAmount,
                'payment_method' => $paymentMethod,
                'notes' => 'Customer payment via website',
            ]);
            $transaction->save();
            
            // Update the order with payment information
            $order->update([
                'paid_amount' => $newPaidAmount,
                'due_amount' => $newDueAmount,
                // Keep the status as processing regardless of payment amount
                'status' => 'processing'
            ]);
            
            $message = 'Payment of BDT ' . number_format($paymentAmount) . ' processed successfully. Transaction #' . $transaction->transaction_number;
            $message .= '. Remaining balance: BDT ' . number_format($newDueAmount) . '.';
            
            return redirect()->route('customer.orders.show', $order)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error processing customer payment', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }
}
