<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceOrder;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class ServiceOrderController extends Controller
{
    /**
     * Display a listing of service orders.
     */
    public function index()
    {
        try {
            $orders = ServiceOrder::orderBy('created_at', 'desc')->get();
            
            return view('admin.service-orders.index', [
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading service orders', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to load service orders: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified service order.
     */
    public function show(ServiceOrder $serviceOrder)
    {
        try {
            // Load transactions for this order
            $transactions = $serviceOrder->transactions()->orderBy('created_at', 'desc')->get();
            
            return view('admin.service-orders.show', [
                'order' => $serviceOrder,
                'transactions' => $transactions
            ]);
        } catch (\Exception $e) {
            Log::error('Error viewing service order', [
                'error' => $e->getMessage(),
                'order_id' => $serviceOrder->id
            ]);
            
            return redirect()->route('admin.service-orders.index')
                ->with('error', 'Failed to view service order: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified service order.
     */
    public function edit(ServiceOrder $serviceOrder)
    {
        try {
            $services = Service::where('status', true)->get();
            
            return view('admin.service-orders.edit', [
                'order' => $serviceOrder,
                'services' => $services
            ]);
        } catch (\Exception $e) {
            Log::error('Error editing service order', [
                'error' => $e->getMessage(),
                'order_id' => $serviceOrder->id
            ]);
            
            return redirect()->route('admin.service-orders.index')
                ->with('error', 'Failed to edit service order: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the specified service order.
     */
    public function update(Request $request, ServiceOrder $serviceOrder)
    {
        try {
            $validated = $request->validate([
                'service_id' => 'nullable|exists:services,id',
                'service_name' => 'required|string|max:255',
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
                'project_name' => 'nullable|string|max:255',
                'project_type' => 'nullable|string|max:255',
                'requirements' => 'nullable|string|max:2000',
                'notes' => 'nullable|string|max:2000',
            ]);
            
            $serviceOrder->update($validated);
            
            return redirect()->route('admin.service-orders.show', $serviceOrder)
                ->with('success', 'Service order updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating service order', [
                'error' => $e->getMessage(),
                'order_id' => $serviceOrder->id
            ]);
            
            return redirect()->route('admin.service-orders.edit', $serviceOrder)
                ->with('error', 'Failed to update service order: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Update the status of the specified service order.
     */
    public function updateStatus(Request $request, ServiceOrder $serviceOrder)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,processing,completed,cancelled',
            ]);
            
            $serviceOrder->update([
                'status' => $validated['status']
            ]);
            
            return redirect()->route('admin.service-orders.show', $serviceOrder)
                ->with('success', 'Service order status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating service order status', [
                'error' => $e->getMessage(),
                'order_id' => $serviceOrder->id
            ]);
            
            return redirect()->route('admin.service-orders.show', $serviceOrder)
                ->with('error', 'Failed to update service order status: ' . $e->getMessage());
        }
    }
    
    /**
     * Accept the specified service order.
     */
    public function accept(ServiceOrder $serviceOrder)
    {
        try {
            // Calculate due amount (full amount initially)
            $dueAmount = $serviceOrder->amount;
            
            // Update order status to processing (accepted) and set due amount
            $serviceOrder->update([
                'status' => 'processing',
                'due_amount' => $dueAmount,
                'paid_amount' => 0,
                'current_installment' => 1,
                'payment_history' => []
            ]);
            
            return redirect()->route('admin.service-orders.show', $serviceOrder)
                ->with('success', 'Service order accepted. Payment option is now available.');
        } catch (\Exception $e) {
            Log::error('Error accepting service order', [
                'error' => $e->getMessage(),
                'order_id' => $serviceOrder->id
            ]);
            
            return redirect()->route('admin.service-orders.show', $serviceOrder)
                ->with('error', 'Failed to accept service order: ' . $e->getMessage());
        }
    }
    
    /**
     * Mark the specified service order as completed.
     */
    public function markCompleted(ServiceOrder $serviceOrder)
    {
        try {
            $serviceOrder->update([
                'status' => 'completed'
            ]);
            
            return redirect()->route('admin.service-orders.show', $serviceOrder)
                ->with('success', 'Service order has been marked as completed.');
        } catch (\Exception $e) {
            Log::error('Error marking service order as completed', [
                'error' => $e->getMessage(),
                'order_id' => $serviceOrder->id
            ]);
            
            return redirect()->route('admin.service-orders.show', $serviceOrder)
                ->with('error', 'Failed to mark order as completed: ' . $e->getMessage());
        }
    }
    
    /**
     * Process payment for the specified service order.
     */
    public function processPayment(Request $request, ServiceOrder $serviceOrder)
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
            if ($paymentAmount > $serviceOrder->due_amount) {
                return redirect()->route('admin.service-orders.show', $serviceOrder)
                    ->with('error', 'Payment amount cannot exceed the due amount.');
            }
            
            // Update paid and due amounts
            $newPaidAmount = $serviceOrder->paid_amount + $paymentAmount;
            $newDueAmount = $serviceOrder->amount - $newPaidAmount;
            
            // Create transaction record
            $transaction = new Transaction([
                'transaction_number' => Transaction::generateTransactionNumber(),
                'package_order_id' => null, // Explicitly set to null
                'service_order_id' => $serviceOrder->id,
                'amount' => $paymentAmount,
                'payment_method' => $paymentMethod,
                'notes' => $notes,
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
            
            return redirect()->route('admin.service-orders.show', $serviceOrder)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error processing payment', [
                'error' => $e->getMessage(),
                'order_id' => $serviceOrder->id
            ]);
            
            return redirect()->route('admin.service-orders.show', $serviceOrder)
                ->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }
}
