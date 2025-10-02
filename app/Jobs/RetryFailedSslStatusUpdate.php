<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;
use App\Services\SslGatewayStatusChecker;
use App\Jobs\SendPaymentStatusNotification;
use Illuminate\Support\Facades\Log;

class RetryFailedSslStatusUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;
    public $tries = 3; // Maximum number of retry attempts
    public $backoff = [60, 300, 900]; // Retry after 1 minute, 5 minutes, 15 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Only process SSL transactions that are still pending
            if ($this->transaction->payment_method !== 'SSL Payment' || 
                $this->transaction->ssl_status !== 'pending') {
                Log::info('Skipping SSL retry for non-pending transaction', [
                    'transaction_id' => $this->transaction->id,
                    'payment_method' => $this->transaction->payment_method,
                    'ssl_status' => $this->transaction->ssl_status
                ]);
                return;
            }

            $sslChecker = new SslGatewayStatusChecker();
            $newSslStatus = $sslChecker->checkStatus($this->transaction->ssl_transaction_id);
            
            if ($newSslStatus && $newSslStatus !== $this->transaction->ssl_status) {
                $oldStatus = $this->transaction->ssl_status;
                $this->transaction->ssl_status = $newSslStatus;
                $this->transaction->save();
                
                // Handle status change (update order status, etc.)
                $this->handleStatusChange($this->transaction, $newSslStatus);
                
                Log::info('SSL status retry successful', [
                    'transaction_id' => $this->transaction->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newSslStatus,
                    'attempt' => $this->attempts()
                ]);
            } else {
                Log::info('SSL status unchanged during retry', [
                    'transaction_id' => $this->transaction->id,
                    'current_status' => $this->transaction->ssl_status,
                    'attempt' => $this->attempts()
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('SSL status retry failed', [
                'transaction_id' => $this->transaction->id,
                'attempt' => $this->attempts(),
                'error' => $e->getMessage()
            ]);
            
            // If this is the last attempt, log final failure
            if ($this->attempts() >= $this->tries) {
                Log::error('SSL status retry exhausted all attempts', [
                    'transaction_id' => $this->transaction->id,
                    'total_attempts' => $this->attempts(),
                    'final_error' => $e->getMessage()
                ]);
            }
            
            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    /**
     * Handle status change and update related orders
     */
    private function handleStatusChange(Transaction $transaction, string $newStatus): void
    {
        try {
            $oldStatus = $transaction->status;
            
            // Update transaction status based on SSL status
            if ($newStatus === 'success') {
                $transaction->status = 'completed';
            } elseif ($newStatus === 'failed') {
                $transaction->status = 'failed';
            }
            
            $transaction->save();
            
            // Update related order status
            if ($transaction->packageOrder) {
                if ($newStatus === 'success') {
                    $transaction->packageOrder->status = 'active';
                } elseif ($newStatus === 'failed') {
                    $transaction->packageOrder->status = 'cancelled';
                }
                $transaction->packageOrder->save();
            }
            
            if ($transaction->serviceOrder) {
                if ($newStatus === 'success') {
                    $transaction->serviceOrder->status = 'active';
                } elseif ($newStatus === 'failed') {
                    $transaction->serviceOrder->status = 'cancelled';
                }
                $transaction->serviceOrder->save();
            }
            
            // Send notification to customer about status change
            if ($oldStatus !== $transaction->status) {
                try {
                    SendPaymentStatusNotification::dispatch($transaction, $oldStatus, $transaction->status);
                    
                    Log::info('Payment status notification dispatched during SSL retry', [
                        'transaction_id' => $transaction->id,
                        'transaction_number' => $transaction->transaction_number,
                        'old_status' => $oldStatus,
                        'new_status' => $transaction->status
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to dispatch payment status notification during SSL retry', [
                        'transaction_id' => $transaction->id,
                        'old_status' => $oldStatus,
                        'new_status' => $transaction->status,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            Log::info('Related order status updated during SSL retry', [
                'transaction_id' => $transaction->id,
                'new_status' => $newStatus,
                'package_order_id' => $transaction->packageOrder ? $transaction->packageOrder->id : null,
                'service_order_id' => $transaction->serviceOrder ? $transaction->serviceOrder->id : null
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to update related order status during SSL retry', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SSL status retry job failed permanently', [
            'transaction_id' => $this->transaction->id,
            'error' => $exception->getMessage(),
            'total_attempts' => $this->attempts()
        ]);
    }
}