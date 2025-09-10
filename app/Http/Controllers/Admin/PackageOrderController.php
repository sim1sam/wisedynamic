<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackageOrder;
use App\Models\Package;
use Illuminate\Support\Facades\Log;

class PackageOrderController extends Controller
{
    /**
     * Display a listing of package orders.
     */
    public function index()
    {
        try {
            $orders = PackageOrder::orderBy('created_at', 'desc')->get();
            
            return view('admin.package-orders.index', [
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading package orders', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to load package orders: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified package order.
     */
    public function show(PackageOrder $packageOrder)
    {
        try {
            // Load transactions for this order
            $transactions = $packageOrder->transactions()->orderBy('created_at', 'desc')->get();
            
            return view('admin.package-orders.show', [
                'order' => $packageOrder,
                'transactions' => $transactions
            ]);
        } catch (\Exception $e) {
            Log::error('Error viewing package order', [
                'error' => $e->getMessage(),
                'order_id' => $packageOrder->id
            ]);
            
            return redirect()->route('admin.package-orders.index')
                ->with('error', 'Failed to view package order: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified package order.
     */
    public function edit(PackageOrder $packageOrder)
    {
        try {
            $packages = Package::where('status', true)->get();
            
            return view('admin.package-orders.edit', [
                'order' => $packageOrder,
                'packages' => $packages
            ]);
        } catch (\Exception $e) {
            Log::error('Error editing package order', [
                'error' => $e->getMessage(),
                'order_id' => $packageOrder->id
            ]);
            
            return redirect()->route('admin.package-orders.index')
                ->with('error', 'Failed to edit package order: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the specified package order.
     */
    public function update(Request $request, PackageOrder $packageOrder)
    {
        try {
            $validated = $request->validate([
                'package_id' => 'nullable|exists:packages,id',
                'package_name' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:50',
                'company' => 'nullable|string|max:255',
                'address_line1' => 'required|string|max:255',
                'address_line2' => 'nullable|string|max:255',
                'city' => 'required|string|max:120',
                'state' => 'nullable|string|max:120',
                'postal_code' => 'required|string|max:20',
                'country' => 'required|string|max:120',
                'website_name' => 'nullable|string|max:255',
                'website_type' => 'nullable|string|max:255',
                'page_count' => 'nullable|integer|min:1',
                'page_url' => 'nullable|string|max:255',
                'ad_budget' => 'nullable|integer|min:0',
                'notes' => 'nullable|string|max:2000',
            ]);
            
            $packageOrder->update($validated);
            
            return redirect()->route('admin.package-orders.show', $packageOrder)
                ->with('success', 'Package order updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating package order', [
                'error' => $e->getMessage(),
                'order_id' => $packageOrder->id
            ]);
            
            return redirect()->route('admin.package-orders.edit', $packageOrder)
                ->with('error', 'Failed to update package order: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Update the status of the specified package order.
     */
    public function updateStatus(Request $request, PackageOrder $packageOrder)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,processing,completed,cancelled',
            ]);
            
            $packageOrder->update([
                'status' => $validated['status']
            ]);
            
            return redirect()->route('admin.package-orders.show', $packageOrder)
                ->with('success', 'Package order status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating package order status', [
                'error' => $e->getMessage(),
                'order_id' => $packageOrder->id
            ]);
            
            return redirect()->route('admin.package-orders.show', $packageOrder)
                ->with('error', 'Failed to update package order status: ' . $e->getMessage());
        }
    }
    
    /**
     * Accept the specified package order.
     */
    public function accept(PackageOrder $packageOrder)
    {
        try {
            // Calculate due amount (full amount initially)
            $dueAmount = $packageOrder->amount;
            
            // Update order status to processing (accepted) and set due amount
            $packageOrder->update([
                'status' => 'processing',
                'due_amount' => $dueAmount,
                'paid_amount' => 0,
                'current_installment' => 1,
                'payment_history' => []
            ]);
            
            return redirect()->route('admin.package-orders.show', $packageOrder)
                ->with('success', 'Package order accepted. Payment option is now available.');
        } catch (\Exception $e) {
            Log::error('Error accepting package order', [
                'error' => $e->getMessage(),
                'order_id' => $packageOrder->id
            ]);
            
            return redirect()->route('admin.package-orders.show', $packageOrder)
                ->with('error', 'Failed to accept package order: ' . $e->getMessage());
        }
    }
    
    /**
     * Mark the specified package order as completed.
     */
    public function markCompleted(PackageOrder $packageOrder)
    {
        try {
            $packageOrder->update([
                'status' => 'completed'
            ]);
            
            return redirect()->route('admin.package-orders.show', $packageOrder)
                ->with('success', 'Package order has been marked as completed.');
        } catch (\Exception $e) {
            Log::error('Error marking package order as completed', [
                'error' => $e->getMessage(),
                'order_id' => $packageOrder->id
            ]);
            
            return redirect()->route('admin.package-orders.show', $packageOrder)
                ->with('error', 'Failed to mark order as completed: ' . $e->getMessage());
        }
    }
    
    /**
     * Process payment for the specified package order.
     */
    public function processPayment(Request $request, PackageOrder $packageOrder)
    {
        try {
            // Validate the payment amount
            $validated = $request->validate([
                'payment_amount' => 'required|numeric|min:1',
                'payment_method' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);
            
            $paymentAmount = $validated['payment_amount'];
            $paymentMethod = $validated['payment_method'] ?? 'manual';
            $notes = $validated['notes'] ?? null;
            
            // Check if payment amount exceeds due amount
            if ($paymentAmount > $packageOrder->due_amount) {
                return redirect()->route('admin.package-orders.show', $packageOrder)
                    ->with('error', 'Payment amount cannot exceed the due amount.');
            }
            
            // Update paid and due amounts
            $newPaidAmount = $packageOrder->paid_amount + $paymentAmount;
            $newDueAmount = $packageOrder->amount - $newPaidAmount;
            
            // Create transaction record
            $transaction = new \App\Models\Transaction([
                'transaction_number' => \App\Models\Transaction::generateTransactionNumber(),
                'package_order_id' => $packageOrder->id,
                'amount' => $paymentAmount,
                'payment_method' => $paymentMethod,
                'notes' => $notes,
            ]);
            $transaction->save();
            
            // Update the order with payment information
            $packageOrder->update([
                'paid_amount' => $newPaidAmount,
                'due_amount' => $newDueAmount,
                // Keep the status as processing regardless of payment amount
                'status' => 'processing'
            ]);
            
            $message = 'Payment of BDT ' . number_format($paymentAmount) . ' processed successfully. Transaction #' . $transaction->transaction_number;
            $message .= '. Remaining balance: BDT ' . number_format($newDueAmount) . '.';
            
            return redirect()->route('admin.package-orders.show', $packageOrder)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error processing payment', [
                'error' => $e->getMessage(),
                'order_id' => $packageOrder->id
            ]);
            
            return redirect()->route('admin.package-orders.show', $packageOrder)
                ->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }
}
