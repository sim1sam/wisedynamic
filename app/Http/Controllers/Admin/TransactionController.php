<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\PackageOrder;
use App\Models\ServiceOrder;
use App\Models\CustomServiceRequest;
use App\Models\FundRequest;
use App\Jobs\SendPaymentStatusNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of all transactions.
     */
    public function index(Request $request)
    {
        try {
            $query = Transaction::with(['packageOrder', 'serviceOrder', 'fundRequest.user', 'customServiceRequest.user']);
            
            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            // Filter by payment method
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }
            
            // Filter by SSL status
            if ($request->filled('ssl_status')) {
                $query->where('ssl_status', $request->ssl_status);
            }
            
            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
            
            // Search by transaction number or SSL transaction ID
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('transaction_number', 'like', "%{$search}%")
                      ->orWhere('ssl_transaction_id', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%");
                });
            }
            
            $transactions = $query->orderBy('created_at', 'desc')->paginate(20);
            
            // Get statistics for dashboard
            $stats = [
                'total_transactions' => Transaction::count(),
                'completed_transactions' => Transaction::where('status', 'completed')->count(),
                'failed_transactions' => Transaction::where('status', 'failed')->count(),
                'pending_transactions' => Transaction::where('status', 'pending')->count(),
                'total_amount' => Transaction::where('status', 'completed')->sum('amount'),
                'ssl_transactions' => Transaction::where('payment_method', 'SSL Payment')->count(),
                'ssl_success' => Transaction::where('payment_method', 'SSL Payment')->where('ssl_status', 'success')->count(),
                'ssl_pending' => Transaction::where('payment_method', 'SSL Payment')->where('ssl_status', 'pending')->count(),
                'ssl_failed' => Transaction::where('payment_method', 'SSL Payment')->where('ssl_status', 'failed')->count(),
            ];
            
            return view('admin.transactions.index', compact('transactions', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error loading transactions', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to load transactions: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        try {
            // Load related orders and fund requests
            $transaction->load(['packageOrder', 'serviceOrder', 'fundRequest.user', 'customServiceRequest.user']);
            
            return view('admin.transactions.show', compact('transaction'));
        } catch (\Exception $e) {
            Log::error('Error viewing transaction', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
            
            return redirect()->route('admin.transactions.index')
                ->with('error', 'Failed to view transaction: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(Transaction $transaction)
    {
        try {
            // Load related orders and fund requests
            $transaction->load(['packageOrder', 'serviceOrder', 'fundRequest.user', 'customServiceRequest.user']);
            
            return view('admin.transactions.edit', compact('transaction'));
        } catch (\Exception $e) {
            Log::error('Error loading transaction for edit', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
            
            return redirect()->route('admin.transactions.index')
                ->with('error', 'Failed to load transaction: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the specified transaction.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
            'ssl_transaction_id' => 'nullable|string|max:255',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);
        
        DB::beginTransaction();
        
        try {
            $oldStatus = $transaction->status;
            $newStatus = $validated['status'];
            
            // Update transaction
            $transaction->update([
                'status' => $newStatus,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'],
                'admin_notes' => $validated['admin_notes'],
                'ssl_transaction_id' => $validated['ssl_transaction_id'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'updated_by_admin' => Auth::id(),
                'admin_updated_at' => now(),
            ]);
            
            // Handle status changes that affect orders
            if ($oldStatus !== $newStatus) {
                $this->handleStatusChange($transaction, $oldStatus, $newStatus);
            }
            
            DB::commit();
            
            Log::info('Transaction updated by admin', [
                'transaction_id' => $transaction->id,
                'admin_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);
            
            return redirect()->route('admin.transactions.show', $transaction)
                ->with('success', 'Transaction updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error updating transaction', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
                'admin_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update transaction: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle transaction status changes and update related orders.
     */
    private function handleStatusChange(Transaction $transaction, $oldStatus, $newStatus)
    {
        // Get the related order
        $order = $transaction->packageOrder ?? $transaction->serviceOrder ?? 
                $transaction->customServiceRequest ?? $transaction->fundRequest;
        
        if (!$order) {
            return;
        }
        
        // Handle status change from failed/cancelled to completed
        if (in_array($oldStatus, ['failed', 'cancelled']) && $newStatus === 'completed') {
            if ($transaction->packageOrder || $transaction->serviceOrder) {
                // Update order payment status
                $newPaidAmount = ($order->paid_amount ?? 0) + $transaction->amount;
                $newDueAmount = $order->amount - $newPaidAmount;
                
                $order->update([
                    'payment_status' => $newDueAmount <= 0 ? 'paid' : 'pending_verification',
                    'paid_amount' => $newPaidAmount,
                    'due_amount' => $newDueAmount,
                ]);
            } elseif ($transaction->customServiceRequest) {
                // For custom service, mark as paid
                $order->update([
                    'ssl_transaction_id' => $transaction->ssl_transaction_id,
                ]);
            } elseif ($transaction->fundRequest) {
                // For fund request, approve and add balance
                $order->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);
                $order->user->addBalance($transaction->amount);
            }
        }
        
        // Handle status change from completed to failed/cancelled
        if ($oldStatus === 'completed' && in_array($newStatus, ['failed', 'cancelled'])) {
            if ($transaction->packageOrder || $transaction->serviceOrder) {
                // Reverse payment
                $newPaidAmount = max(0, ($order->paid_amount ?? 0) - $transaction->amount);
                $newDueAmount = $order->amount - $newPaidAmount;
                
                $order->update([
                    'payment_status' => $newDueAmount > 0 ? 'pending' : 'paid',
                    'paid_amount' => $newPaidAmount,
                    'due_amount' => $newDueAmount,
                ]);
            } elseif ($transaction->customServiceRequest) {
                // For custom service, remove payment reference
                $order->update([
                    'ssl_transaction_id' => null,
                ]);
            } elseif ($transaction->fundRequest) {
                // For fund request, reverse approval and deduct balance
                $order->update([
                    'status' => 'pending',
                    'approved_at' => null,
                ]);
                $order->user->deductBalance($transaction->amount);
            }
        }
        
        // Send notification to customer about status change
        if ($oldStatus !== $newStatus) {
            try {
                SendPaymentStatusNotification::dispatch($transaction, $oldStatus, $newStatus);
                
                Log::info('Payment status notification dispatched by admin', [
                    'transaction_id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'admin_id' => Auth::id(),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to dispatch payment status notification from admin', [
                    'transaction_id' => $transaction->id,
                    'admin_id' => Auth::id(),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    
    /**
     * Bulk update transaction statuses.
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transactions,id',
            'bulk_action' => 'required|in:mark_completed,mark_failed,mark_cancelled',
        ]);
        
        switch($validated['bulk_action']) {
            case 'mark_completed':
                $status = 'completed';
                break;
            case 'mark_failed':
                $status = 'failed';
                break;
            case 'mark_cancelled':
                $status = 'cancelled';
                break;
        }
        
        DB::beginTransaction();
        
        try {
            $transactions = Transaction::whereIn('id', $validated['transaction_ids'])->get();
            $updatedCount = 0;
            
            foreach ($transactions as $transaction) {
                $oldStatus = $transaction->status;
                
                if ($oldStatus !== $status) {
                    $transaction->update([
                        'status' => $status,
                        'updated_by_admin' => Auth::id(),
                        'admin_updated_at' => now(),
                    ]);
                    
                    $this->handleStatusChange($transaction, $oldStatus, $status);
                    $updatedCount++;
                }
            }
            
            DB::commit();
            
            Log::info('Bulk transaction update', [
                'admin_id' => Auth::id(),
                'action' => $validated['bulk_action'],
                'updated_count' => $updatedCount,
            ]);
            
            return redirect()->route('admin.transactions.index')
                ->with('success', "Successfully updated {$updatedCount} transactions.");
                
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error in bulk transaction update', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to update transactions: ' . $e->getMessage());
        }
    }
    
    /**
     * Verify individual SSL transaction with SSL Commerz API.
     */
    public function verifyIndividualSsl(Request $request, $transactionId)
    {
        try {
            $transaction = Transaction::findOrFail($transactionId);
            
            if (!$transaction->isSSLTransaction() || !$transaction->ssl_transaction_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction is not an SSL transaction or missing SSL transaction ID.'
                ], 400);
            }
            
            // Call SSL Commerz validation API
            $sslConfig = config('sslcommerz');
            $validationUrl = $sslConfig['sandbox'] ? $sslConfig['validation_url']['sandbox'] : $sslConfig['validation_url']['live'];
            
            $postData = [
                'val_id' => $transaction->ssl_transaction_id,
                'store_id' => $sslConfig['store_id'],
                'store_passwd' => $sslConfig['store_password'],
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $validationUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                Log::error('SSL Verification cURL Error', ['error' => $error, 'transaction_id' => $transactionId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to connect to SSL Commerz: ' . $error
                ], 500);
            }
            
            if ($httpCode !== 200) {
                Log::error('SSL Verification HTTP Error', ['http_code' => $httpCode, 'transaction_id' => $transactionId]);
                return response()->json([
                    'success' => false,
                    'message' => 'SSL Commerz returned HTTP ' . $httpCode
                ], 500);
            }
            
            $validationResponse = json_decode($response, true);
            
            if (!$validationResponse || !isset($validationResponse['status'])) {
                Log::error('Invalid SSL verification response', ['response' => $response, 'transaction_id' => $transactionId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid response from SSL Commerz'
                ], 500);
            }
            
            // Update transaction based on SSL response
            $sslStatus = 'failed';
            $transactionStatus = 'failed';
            
            if (in_array($validationResponse['status'], ['VALID', 'VALIDATED'])) {
                $sslStatus = 'success';
                $transactionStatus = 'completed';
            } elseif ($validationResponse['status'] === 'PENDING') {
                $sslStatus = 'pending';
                $transactionStatus = 'pending';
            }
            
            $transaction->update([
                'ssl_status' => $sslStatus,
                'status' => $transactionStatus,
                'ssl_response' => $validationResponse,
                'updated_by_admin' => Auth::id(),
                'admin_updated_at' => now(),
            ]);
            
            Log::info('SSL transaction verified', [
                'transaction_id' => $transactionId,
                'ssl_transaction_id' => $transaction->ssl_transaction_id,
                'ssl_status' => $sslStatus,
                'admin_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'SSL transaction verified successfully',
                'ssl_status' => $sslStatus,
                'transaction_status' => $transactionStatus
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error verifying SSL transaction', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId,
                'admin_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify SSL transaction: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update SSL status manually.
     */
    public function updateSslStatus(Request $request, $transactionId)
    {
        $validated = $request->validate([
            'status' => 'required|in:success,failed,pending',
            'admin_notes' => 'nullable|string|max:1000',
            'verify_with_ssl' => 'boolean'
        ]);
        
        try {
            $transaction = Transaction::findOrFail($transactionId);
            
            if (!$transaction->isSSLTransaction()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction is not an SSL transaction.'
                ], 400);
            }
            
            // If verify_with_ssl is checked, call SSL API first
            if ($request->boolean('verify_with_ssl') && $transaction->ssl_transaction_id) {
                $verifyResponse = $this->verifyIndividualSsl($request, $transactionId);
                if (!$verifyResponse->getData()->success) {
                    return $verifyResponse;
                }
            } else {
                // Manual update
                $transactionStatus = $validated['status'] === 'success' ? 'completed' : 
                                   ($validated['status'] === 'failed' ? 'failed' : 'pending');
                
                $transaction->update([
                    'ssl_status' => $validated['status'],
                    'status' => $transactionStatus,
                    'admin_notes' => $validated['admin_notes'],
                    'updated_by_admin' => Auth::id(),
                    'admin_updated_at' => now(),
                ]);
            }
            
            Log::info('SSL status updated manually', [
                'transaction_id' => $transactionId,
                'ssl_status' => $validated['status'],
                'admin_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'SSL status updated successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating SSL status', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId,
                'admin_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update SSL status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk verify all pending SSL transactions.
     */
    public function bulkVerifySsl(Request $request)
    {
        try {
            $pendingTransactions = Transaction::where('payment_method', 'SSL Payment')
                ->where('ssl_status', 'pending')
                ->whereNotNull('ssl_transaction_id')
                ->get();
            
            $verifiedCount = 0;
            $errors = [];
            
            foreach ($pendingTransactions as $transaction) {
                try {
                    $verifyResponse = $this->verifyIndividualSsl($request, $transaction->id);
                    if ($verifyResponse->getData()->success) {
                        $verifiedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Transaction {$transaction->id}: " . $e->getMessage();
                }
            }
            
            Log::info('Bulk SSL verification completed', [
                'total_transactions' => $pendingTransactions->count(),
                'verified_count' => $verifiedCount,
                'errors' => $errors,
                'admin_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Verified {$verifiedCount} out of {$pendingTransactions->count()} transactions",
                'verified_count' => $verifiedCount,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in bulk SSL verification', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk verification: ' . $e->getMessage()
            ], 500);
        }
    }
}
