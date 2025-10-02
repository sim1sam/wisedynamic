<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Services\SslGatewayStatusChecker;
use Illuminate\Support\Facades\Log;

class VerifySslTransactionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssl:verify-status 
                            {--transaction-id= : Specific transaction ID to verify}
                            {--limit=50 : Maximum number of transactions to process}
                            {--status=pending : Status filter (pending, failed, success, all)}
                            {--force : Force verification even for completed transactions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify SSL transaction statuses with SSL Commerz gateway and update accordingly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting SSL transaction status verification...');
        
        $sslChecker = new SslGatewayStatusChecker();
        $transactionId = $this->option('transaction-id');
        $limit = (int) $this->option('limit');
        $statusFilter = $this->option('status');
        $force = $this->option('force');

        try {
            if ($transactionId) {
                // Verify specific transaction
                $this->verifySpecificTransaction($sslChecker, $transactionId);
            } else {
                // Bulk verification
                $this->bulkVerifyTransactions($sslChecker, $limit, $statusFilter, $force);
            }
            
            $this->info('SSL transaction status verification completed successfully.');
            
        } catch (\Exception $e) {
            $this->error('SSL verification failed: ' . $e->getMessage());
            Log::error('SSL verification command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }

    /**
     * Verify specific transaction by ID
     */
    private function verifySpecificTransaction(SslGatewayStatusChecker $sslChecker, $transactionId)
    {
        $transaction = Transaction::find($transactionId);
        
        if (!$transaction) {
            $this->error("Transaction with ID {$transactionId} not found.");
            return;
        }

        if (!$transaction->ssl_transaction_id) {
            $this->warn("Transaction {$transactionId} has no SSL transaction ID. Skipping.");
            return;
        }

        $this->info("Verifying transaction {$transactionId} (SSL ID: {$transaction->ssl_transaction_id})...");
        
        $oldStatus = $transaction->ssl_status;
        $result = $sslChecker->updateTransactionStatus($transaction);
        
        if ($result) {
            $transaction->refresh();
            $newStatus = $transaction->ssl_status;
            
            if ($oldStatus !== $newStatus) {
                $this->info("✓ Transaction {$transactionId} status updated: {$oldStatus} → {$newStatus}");
            } else {
                $this->info("✓ Transaction {$transactionId} status verified: {$newStatus} (no change)");
            }
        } else {
            $this->warn("✗ Failed to verify transaction {$transactionId}");
        }
    }

    /**
     * Bulk verify transactions
     */
    private function bulkVerifyTransactions(SslGatewayStatusChecker $sslChecker, $limit, $statusFilter, $force)
    {
        $query = Transaction::whereNotNull('ssl_transaction_id');

        // Apply status filter
        if ($statusFilter !== 'all') {
            if ($force) {
                // If force is enabled, include all statuses but still filter if specified
                if ($statusFilter !== 'all') {
                    $query->where('ssl_status', $statusFilter);
                }
            } else {
                // Normal mode: only check pending and null statuses, or specific status
                if ($statusFilter === 'pending') {
                    $query->whereIn('ssl_status', ['pending', null]);
                } else {
                    $query->where('ssl_status', $statusFilter);
                }
            }
        }

        $transactions = $query->limit($limit)->get();
        
        if ($transactions->isEmpty()) {
            $this->info('No transactions found matching the criteria.');
            return;
        }

        $this->info("Found {$transactions->count()} transactions to verify.");
        
        $progressBar = $this->output->createProgressBar($transactions->count());
        $progressBar->start();

        $stats = [
            'processed' => 0,
            'updated' => 0,
            'unchanged' => 0,
            'failed' => 0,
            'status_changes' => []
        ];

        foreach ($transactions as $transaction) {
            $oldStatus = $transaction->ssl_status;
            
            try {
                $result = $sslChecker->updateTransactionStatus($transaction);
                
                if ($result) {
                    $transaction->refresh();
                    $newStatus = $transaction->ssl_status;
                    
                    if ($oldStatus !== $newStatus) {
                        $stats['updated']++;
                        $stats['status_changes'][] = [
                            'id' => $transaction->id,
                            'ssl_id' => $transaction->ssl_transaction_id,
                            'old' => $oldStatus,
                            'new' => $newStatus
                        ];
                    } else {
                        $stats['unchanged']++;
                    }
                } else {
                    $stats['failed']++;
                }
                
                $stats['processed']++;
                
            } catch (\Exception $e) {
                $stats['failed']++;
                Log::error('Failed to verify transaction in bulk', [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Display results
        $this->displayResults($stats);
    }

    /**
     * Display verification results
     */
    private function displayResults(array $stats)
    {
        $this->info('=== Verification Results ===');
        $this->info("Processed: {$stats['processed']}");
        $this->info("Updated: {$stats['updated']}");
        $this->info("Unchanged: {$stats['unchanged']}");
        $this->info("Failed: {$stats['failed']}");

        if (!empty($stats['status_changes'])) {
            $this->newLine();
            $this->info('Status Changes:');
            
            $headers = ['Transaction ID', 'SSL Transaction ID', 'Old Status', 'New Status'];
            $rows = [];
            
            foreach ($stats['status_changes'] as $change) {
                $rows[] = [
                    $change['id'],
                    $change['ssl_id'],
                    $change['old'] ?? 'null',
                    $change['new']
                ];
            }
            
            $this->table($headers, $rows);
        }

        // Log summary
        Log::info('SSL status verification completed', $stats);
    }
}
