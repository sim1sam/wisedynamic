<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManualPayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManualPaymentController extends Controller
{
    /**
     * Display a listing of manual payments.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $query = ManualPayment::with(['user', 'payable', 'verifiedBy'])
            ->orderBy('created_at', 'desc');
            
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        // Use client-side DataTables pagination by loading full result set
        $payments = $query->get();

        return view('admin.manual-payments.index', compact('payments', 'status'));
    }

    /**
     * Display the specified manual payment.
     */
    public function show(ManualPayment $manualPayment)
    {
        $manualPayment->load(['user', 'payable', 'verifiedBy']);
        
        return view('admin.manual-payments.show', compact('manualPayment'));
    }

    /**
     * Approve the specified manual payment.
     */
    public function approve(Request $request, ManualPayment $manualPayment)
    {
        if ($manualPayment->status !== ManualPayment::STATUS_PENDING) {
            return redirect()->back()->with('error', 'This payment has already been processed.');
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Update manual payment status
            $manualPayment->update([
                'status' => ManualPayment::STATUS_APPROVED,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // Get the order
            $order = $manualPayment->payable;
            
            // Create transaction record
            $transaction = new Transaction([
                'transaction_number' => Transaction::generateTransactionNumber(),
                'package_order_id' => $order instanceof \App\Models\PackageOrder ? $order->id : null,
                'service_order_id' => $order instanceof \App\Models\ServiceOrder ? $order->id : null,
                'amount' => $manualPayment->amount,
                'payment_method' => 'bank_transfer',
                'notes' => 'Manual payment approved by admin',
            ]);
            $transaction->save();

            // Update order payment information
            $newPaidAmount = $order->paid_amount + $manualPayment->amount;
            $newDueAmount = $order->amount - $newPaidAmount;
            
            $order->update([
                'paid_amount' => $newPaidAmount,
                'due_amount' => $newDueAmount,
                'payment_status' => $newDueAmount <= 0 ? 'paid' : 'partial',
                'payment_method' => 'Manual Bank Transfer',
            ]);

            DB::commit();

            return redirect()->route('admin.manual-payments.show', $manualPayment)
                ->with('success', 'Payment approved successfully. Transaction #' . $transaction->transaction_number . ' created.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error approving manual payment', [
                'error' => $e->getMessage(),
                'payment_id' => $manualPayment->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to approve payment: ' . $e->getMessage());
        }
    }

    /**
     * Reject the specified manual payment.
     */
    public function reject(Request $request, ManualPayment $manualPayment)
    {
        if ($manualPayment->status !== ManualPayment::STATUS_PENDING) {
            return redirect()->back()->with('error', 'This payment has already been processed.');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Update manual payment status
            $manualPayment->update([
                'status' => ManualPayment::STATUS_REJECTED,
                'admin_notes' => $validated['admin_notes'],
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // Update order payment status back to pending
            $order = $manualPayment->payable;
            $order->update([
                'payment_status' => 'pending',
                'payment_method' => null,
            ]);

            DB::commit();

            return redirect()->route('admin.manual-payments.show', $manualPayment)
                ->with('success', 'Payment rejected successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error rejecting manual payment', [
                'error' => $e->getMessage(),
                'payment_id' => $manualPayment->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to reject payment: ' . $e->getMessage());
        }
    }
}