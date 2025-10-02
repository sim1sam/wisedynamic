<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Services\SslGatewayStatusChecker;
use App\Jobs\SendPaymentStatusNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Admin-only manual transaction status update
     */
    public function adminUpdateStatus(Request $request)
    {
        // Check if user is authenticated and is admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            Log::warning('Unauthorized admin status update attempt', [
                'user_id' => Auth::id(),
                'is_admin' => Auth::check() ? Auth::user()->is_admin : false,
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|integer|exists:transactions,id',
            'status' => 'required|in:success,failed,pending',
            'admin_notes' => 'nullable|string|max:500',
            'verify_with_ssl' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            Log::error('Admin SSL Status Update Validation Failed', [
                'admin_id' => Auth::id(),
                'errors' => $validator->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        DB::beginTransaction();
        
        try {
            $transaction = Transaction::findOrFail($request->transaction_id);
            $oldStatus = $transaction->ssl_status;
            $newStatus = $request->status;

            // If verify_with_ssl is true, check with SSL Commerz first
            if ($request->verify_with_ssl && $transaction->ssl_transaction_id) {
                $sslChecker = new SslGatewayStatusChecker();
                $sslResult = $sslChecker->checkTransactionStatus(
                    $transaction->ssl_transaction_id,
                    $transaction->ssl_session_id
                );

                Log::info('Admin requested SSL verification', [
                    'admin_id' => Auth::id(),
                    'transaction_id' => $transaction->id,
                    'ssl_result' => $sslResult
                ]);

                // If SSL status differs from requested status, warn admin
                if ($sslResult['status'] !== $newStatus) {
                    return response()->json([
                        'success' => false,
                        'message' => 'SSL Gateway status mismatch',
                        'ssl_status' => $sslResult['status'],
                        'requested_status' => $newStatus,
                        'ssl_message' => $sslResult['message'] ?? 'No message',
                        'warning' => 'The SSL Gateway reports a different status. Please verify before proceeding.'
                    ], 409);
                }
            }

            // Update transaction status
            $updateData = [
                'ssl_status' => $newStatus,
                'status' => $this->mapGatewayStatus($newStatus),
                'updated_at' => now(),
            ];

            // Add admin notes if provided
            if ($request->filled('admin_notes')) {
                $updateData['admin_notes'] = $request->admin_notes;
            }

            $transaction->update($updateData);

            // Handle status change effects on related orders
            if ($oldStatus !== $newStatus) {
                $this->handleStatusChange($transaction, $this->mapGatewayStatus($oldStatus), $this->mapGatewayStatus($newStatus));
            }

            DB::commit();

            Log::info('Admin SSL Status Update Successful', [
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->name,
                'transaction_id' => $transaction->id,
                'ssl_transaction_id' => $transaction->ssl_transaction_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'admin_notes' => $request->admin_notes,
                'verified_with_ssl' => $request->verify_with_ssl ?? false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction status updated successfully by admin',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'ssl_transaction_id' => $transaction->ssl_transaction_id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'updated_by' => Auth::user()->name,
                    'updated_at' => $transaction->updated_at->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Admin SSL Status Update Failed', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update transaction status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify transaction status with SSL Commerz (Admin only)
     */
    public function verifyWithSsl(Request $request)
    {
        // Check if user is authenticated and is admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|integer|exists:transactions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $transaction = Transaction::findOrFail($request->transaction_id);

            if (!$transaction->ssl_transaction_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No SSL transaction ID found for this transaction'
                ], 400);
            }

            $sslChecker = new SslGatewayStatusChecker();
            $sslResult = $sslChecker->checkTransactionStatus(
                $transaction->ssl_transaction_id,
                $transaction->ssl_session_id
            );

            Log::info('Admin SSL verification request', [
                'admin_id' => Auth::id(),
                'transaction_id' => $transaction->id,
                'ssl_result' => $sslResult
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'transaction_id' => $transaction->id,
                    'ssl_transaction_id' => $transaction->ssl_transaction_id,
                    'current_status' => $transaction->ssl_status,
                    'ssl_gateway_status' => $sslResult['status'],
                    'ssl_message' => $sslResult['message'] ?? 'No message',
                    'status_match' => $transaction->ssl_status === $sslResult['status'],
                    'ssl_data' => $sslResult['ssl_data'] ?? null,
                    'verified_at' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('SSL verification failed', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to verify with SSL Gateway',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update transaction status via SSL gateway callback
     */
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string',
            'ssl_transaction_id' => 'required|string',
            'status' => 'required|in:success,failed,cancelled',
            'gateway_response' => 'nullable|array',
            'fail_reason' => 'nullable|string',
            'bank_transaction_id' => 'nullable|string',
            'card_type' => 'nullable|string',
            'card_no' => 'nullable|string',
            'card_issuer' => 'nullable|string',
            'currency_type' => 'nullable|string',
            'currency_amount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            Log::error('SSL Gateway Status Update Validation Failed', [
                'errors' => $validator->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        DB::beginTransaction();
        
        try {
            // Find transaction by SSL transaction ID or transaction number
            $transaction = Transaction::where('ssl_transaction_id', $request->ssl_transaction_id)
                ->orWhere('transaction_number', $request->transaction_id)
                ->first();

            if (!$transaction) {
                Log::error('Transaction not found for SSL status update', [
                    'ssl_transaction_id' => $request->ssl_transaction_id,
                    'transaction_id' => $request->transaction_id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            $oldStatus = $transaction->status;
            $newStatus = $this->mapGatewayStatus($request->status);

            // Update transaction with SSL gateway response
            $updateData = [
                'status' => $newStatus,
                'ssl_status' => $request->status,
                'ssl_response_data' => $request->gateway_response,
                'updated_at' => now(),
            ];

            // Add additional SSL data if provided
            if ($request->filled('fail_reason')) {
                $updateData['ssl_fail_reason'] = $request->fail_reason;
            }
            
            if ($request->filled('bank_transaction_id')) {
                $updateData['ssl_bank_transaction_id'] = $request->bank_transaction_id;
            }
            
            if ($request->filled('card_type')) {
                $updateData['ssl_card_type'] = $request->card_type;
            }
            
            if ($request->filled('card_no')) {
                $updateData['ssl_card_no'] = $request->card_no;
            }
            
            if ($request->filled('card_issuer')) {
                $updateData['ssl_card_issuer'] = $request->card_issuer;
            }
            
            if ($request->filled('currency_type')) {
                $updateData['ssl_currency_type'] = $request->currency_type;
            }
            
            if ($request->filled('currency_amount')) {
                $updateData['ssl_currency_amount'] = $request->currency_amount;
            }

            $transaction->update($updateData);

            // Handle status change effects on related orders
            if ($oldStatus !== $newStatus) {
                $this->handleStatusChange($transaction, $oldStatus, $newStatus);
            }

            DB::commit();

            Log::info('SSL Gateway Status Update Successful', [
                'transaction_id' => $transaction->id,
                'ssl_transaction_id' => $request->ssl_transaction_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'gateway_status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction status updated successfully',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'updated_at' => $transaction->updated_at->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('SSL Gateway Status Update Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update transaction status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction status by SSL transaction ID
     */
    public function getStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ssl_transaction_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $transaction = Transaction::where('ssl_transaction_id', $request->ssl_transaction_id)->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'transaction_id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'ssl_transaction_id' => $transaction->ssl_transaction_id,
                    'status' => $transaction->status,
                    'ssl_status' => $transaction->ssl_status,
                    'amount' => $transaction->amount,
                    'currency_type' => $transaction->ssl_currency_type,
                    'currency_amount' => $transaction->ssl_currency_amount,
                    'created_at' => $transaction->created_at->toISOString(),
                    'updated_at' => $transaction->updated_at->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get Transaction Status Failed', [
                'error' => $e->getMessage(),
                'ssl_transaction_id' => $request->ssl_transaction_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get transaction status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Map SSL gateway status to internal status
     */
    private function mapGatewayStatus($gatewayStatus)
    {
        switch($gatewayStatus) {
            case 'success':
                return Transaction::STATUS_COMPLETED;
            case 'failed':
                return Transaction::STATUS_FAILED;
            case 'cancelled':
                return Transaction::STATUS_CANCELLED;
            default:
                return Transaction::STATUS_PENDING;
        }
    }

    /**
     * Handle transaction status changes and update related orders
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
        if (in_array($oldStatus, [Transaction::STATUS_FAILED, Transaction::STATUS_CANCELLED]) && 
            $newStatus === Transaction::STATUS_COMPLETED) {
            
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
        if ($oldStatus === Transaction::STATUS_COMPLETED && 
            in_array($newStatus, [Transaction::STATUS_FAILED, Transaction::STATUS_CANCELLED])) {
            
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
                
                Log::info('Payment status notification dispatched', [
                    'transaction_id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to dispatch payment status notification', [
                    'transaction_id' => $transaction->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Get payment status for real-time verification on success page
     */
    public function getPaymentStatus($orderType, $orderId)
    {
        try {
            // Find transaction based on order type and ID
            $transaction = null;
            
            if ($orderType === 'package') {
                $transaction = Transaction::where('package_order_id', $orderId)->first();
            } elseif ($orderType === 'service') {
                $transaction = Transaction::where('service_order_id', $orderId)->first();
            } elseif ($orderType === 'custom_service') {
                $transaction = Transaction::where('custom_service_request_id', $orderId)->first();
            }
            
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }
            
            // Get current status
            $currentStatus = $transaction->status;
            $sslStatus = $transaction->ssl_status;
            
            // If status is still pending, try to verify with SSL
            if ($currentStatus === 'pending' || $sslStatus === 'pending') {
                try {
                    $sslChecker = new SslGatewayStatusChecker();
                    $sslResponse = $sslChecker->checkTransactionStatus($transaction->transaction_id);
                    
                    if ($sslResponse && isset($sslResponse['status'])) {
                        $newSslStatus = $this->mapGatewayStatus($sslResponse['status']);
                        
                        // Update SSL status if it changed
                        if ($newSslStatus !== $sslStatus) {
                            $transaction->ssl_status = $newSslStatus;
                            $transaction->save();
                            
                            // Handle status change (update order status, etc.)
                            $this->handleStatusChange($transaction, $newSslStatus);
                            
                            $sslStatus = $newSslStatus;
                        }
                    }
                } catch (\Exception $e) {
                    // Log error but don't fail the request
                    \Log::error('SSL status check failed for transaction ' . $transaction->id . ': ' . $e->getMessage());
                }
            }
            
            return response()->json([
                'success' => true,
                'status' => $transaction->fresh()->status,
                'ssl_status' => $transaction->fresh()->ssl_status,
                'transaction_id' => $transaction->transaction_id,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'created_at' => $transaction->created_at->format('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Payment status check failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to check payment status'
            ], 500);
        }
    }

    /**
     * Admin-only individual SSL transaction verification
     */
    public function verifyIndividualSsl(Request $request, $transactionId)
    {
        // Check if user is authenticated and is admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        try {
            $transaction = Transaction::findOrFail($transactionId);
            
            // Only verify SSL transactions
            if ($transaction->payment_method !== 'SSL Payment') {
                return response()->json([
                    'success' => false,
                    'message' => 'This is not an SSL transaction.'
                ], 400);
            }

            $sslChecker = new SslGatewayStatusChecker();
            $sslStatus = $sslChecker->checkStatus($transaction->ssl_transaction_id);
            
            $oldStatus = $transaction->ssl_status;
            $transaction->ssl_status = $sslStatus;
            $transaction->save();
            
            // Handle status change if needed
            if ($oldStatus !== $sslStatus) {
                $this->handleStatusChange($transaction, $sslStatus);
            }
            
            Log::info('Admin SSL verification completed', [
                'admin_id' => Auth::id(),
                'transaction_id' => $transaction->id,
                'old_status' => $oldStatus,
                'new_status' => $sslStatus
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'SSL status verified successfully.',
                'old_status' => $oldStatus,
                'new_status' => $sslStatus,
                'transaction' => [
                    'id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'ssl_status' => $transaction->ssl_status,
                    'status' => $transaction->status
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Admin SSL verification failed', [
                'admin_id' => Auth::id(),
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'SSL verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin-only bulk SSL verification for pending transactions
     */
    public function bulkVerifySsl(Request $request)
    {
        // Check if user is authenticated and is admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        try {
            $pendingTransactions = Transaction::where('payment_method', 'SSL Payment')
                ->where('ssl_status', 'pending')
                ->get();
            
            if ($pendingTransactions->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No pending SSL transactions found.',
                    'verified_count' => 0
                ]);
            }
            
            $sslChecker = new SslGatewayStatusChecker();
            $verifiedCount = 0;
            $results = [];
            
            foreach ($pendingTransactions as $transaction) {
                try {
                    $sslStatus = $sslChecker->checkStatus($transaction->ssl_transaction_id);
                    $oldStatus = $transaction->ssl_status;
                    
                    if ($oldStatus !== $sslStatus) {
                        $transaction->ssl_status = $sslStatus;
                        $transaction->save();
                        
                        // Handle status change
                        $this->handleStatusChange($transaction, $sslStatus);
                        
                        $verifiedCount++;
                        $results[] = [
                            'transaction_id' => $transaction->id,
                            'transaction_number' => $transaction->transaction_number,
                            'old_status' => $oldStatus,
                            'new_status' => $sslStatus
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error('Bulk SSL verification failed for transaction', [
                        'transaction_id' => $transaction->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            Log::info('Admin bulk SSL verification completed', [
                'admin_id' => Auth::id(),
                'total_pending' => $pendingTransactions->count(),
                'verified_count' => $verifiedCount
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Bulk verification completed. {$verifiedCount} transactions updated.",
                'verified_count' => $verifiedCount,
                'total_pending' => $pendingTransactions->count(),
                'results' => $results
            ]);
            
        } catch (\Exception $e) {
            Log::error('Admin bulk SSL verification failed', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bulk SSL verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin-only manual SSL status update
     */
    public function updateSslStatus(Request $request, $transactionId)
    {
        // Check if user is authenticated and is admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'ssl_status' => 'required|in:success,failed,pending',
            'admin_notes' => 'nullable|string|max:500',
            'verify_with_ssl' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $transaction = Transaction::findOrFail($transactionId);
            
            // Only update SSL transactions
            if ($transaction->payment_method !== 'SSL Payment') {
                return response()->json([
                    'success' => false,
                    'message' => 'This is not an SSL transaction.'
                ], 400);
            }

            $oldStatus = $transaction->ssl_status;
            
            // If verify_with_ssl is true, check with SSL first
            if ($request->verify_with_ssl) {
                try {
                    $sslChecker = new SslGatewayStatusChecker();
                    $verifiedStatus = $sslChecker->checkStatus($transaction->ssl_transaction_id);
                    $transaction->ssl_status = $verifiedStatus;
                } catch (\Exception $e) {
                    Log::error('SSL verification failed during manual update', [
                        'transaction_id' => $transaction->id,
                        'error' => $e->getMessage()
                    ]);
                    // Continue with manual update if SSL verification fails
                    $transaction->ssl_status = $request->ssl_status;
                }
            } else {
                $transaction->ssl_status = $request->ssl_status;
            }
            
            // Add admin notes if provided
            if ($request->admin_notes) {
                $transaction->admin_notes = $request->admin_notes;
            }
            
            $transaction->save();
            
            // Handle status change if needed
            if ($oldStatus !== $transaction->ssl_status) {
                $this->handleStatusChange($transaction, $transaction->ssl_status);
            }
            
            Log::info('Admin SSL status update completed', [
                'admin_id' => Auth::id(),
                'transaction_id' => $transaction->id,
                'old_status' => $oldStatus,
                'new_status' => $transaction->ssl_status,
                'verified_with_ssl' => $request->verify_with_ssl ?? false
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'SSL status updated successfully.',
                'old_status' => $oldStatus,
                'new_status' => $transaction->ssl_status,
                'transaction' => [
                    'id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'ssl_status' => $transaction->ssl_status,
                    'status' => $transaction->status
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Admin SSL status update failed', [
                'admin_id' => Auth::id(),
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'SSL status update failed: ' . $e->getMessage()
            ], 500);
        }
    }
}