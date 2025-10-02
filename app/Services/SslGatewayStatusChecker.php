<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Jobs\RetryFailedSslStatusUpdate;

class SslGatewayStatusChecker
{
    private $storeId;
    private $storePassword;
    private $baseUrl;

    public function __construct()
    {
        $this->storeId = config('sslcommerz.store_id');
        $this->storePassword = config('sslcommerz.store_password');
        $this->baseUrl = config('sslcommerz.sandbox') ? 
            'https://sandbox.sslcommerz.com' : 
            'https://securepay.sslcommerz.com';
    }

    /**
     * Check transaction status with SSL Commerz API
     */
    public function checkTransactionStatus($transactionId, $sessionId = null)
    {
        try {
            $params = [
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'tran_id' => $transactionId,
            ];

            if ($sessionId) {
                $params['sessionkey'] = $sessionId;
            }

            $response = Http::timeout(30)->get($this->baseUrl . '/validator/api/merchantTransIDvalidationAPI.php', $params);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('SSL Gateway Status Check Response', [
                    'transaction_id' => $transactionId,
                    'response' => $data
                ]);

                return $this->parseStatusResponse($data);
            }

            Log::error('SSL Gateway Status Check Failed', [
                'transaction_id' => $transactionId,
                'status_code' => $response->status(),
                'response' => $response->body()
            ]);

            return [
                'status' => 'failed',
                'message' => 'Failed to check status with SSL Gateway',
                'raw_response' => $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('SSL Gateway Status Check Exception', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);

            return [
                'status' => 'failed',
                'message' => 'Exception occurred while checking status: ' . $e->getMessage(),
                'raw_response' => null
            ];
        }
    }

    /**
     * Parse SSL Commerz status response
     */
    private function parseStatusResponse($data)
    {
        if (!is_array($data) || empty($data)) {
            return [
                'status' => 'failed',
                'message' => 'Invalid response from SSL Gateway',
                'raw_response' => $data
            ];
        }

        // Check if transaction exists and is valid
        if (isset($data['status']) && strtolower($data['status']) === 'valid') {
            return [
                'status' => 'success',
                'message' => 'Transaction is valid and successful',
                'ssl_data' => $data,
                'raw_response' => $data
            ];
        }

        if (isset($data['status']) && strtolower($data['status']) === 'failed') {
            return [
                'status' => 'failed',
                'message' => $data['failedreason'] ?? 'Transaction failed',
                'ssl_data' => $data,
                'raw_response' => $data
            ];
        }

        if (isset($data['status']) && in_array(strtolower($data['status']), ['pending', 'processing', 'unattempted'])) {
            return [
                'status' => 'pending',
                'message' => 'Transaction is still pending',
                'ssl_data' => $data,
                'raw_response' => $data
            ];
        }

        // Default to failed if status is unclear
        return [
            'status' => 'failed',
            'message' => 'Unknown transaction status: ' . ($data['status'] ?? 'No status'),
            'ssl_data' => $data,
            'raw_response' => $data
        ];
    }

    /**
     * Update transaction status based on SSL Gateway check
     */
    public function updateTransactionStatus(Transaction $transaction)
    {
        if (!$transaction->ssl_transaction_id) {
            Log::warning('Cannot check SSL status: No SSL transaction ID', [
                'transaction_id' => $transaction->id
            ]);
            return false;
        }

        $statusResult = $this->checkTransactionStatus(
            $transaction->ssl_transaction_id,
            $transaction->ssl_session_id
        );

        // Update transaction based on SSL Gateway response
        $updateData = [
            'ssl_status' => $statusResult['status'],
            'ssl_response_data' => json_encode($statusResult['raw_response']),
            'updated_at' => now()
        ];

        if ($statusResult['status'] === 'failed' && isset($statusResult['message'])) {
            $updateData['ssl_fail_reason'] = $statusResult['message'];
        }

        $transaction->update($updateData);

        // If status check failed and transaction is still pending, schedule retry
        if ($statusResult['status'] === 'pending' && $transaction->ssl_status === 'pending') {
            Log::info('Scheduling SSL status retry for pending transaction', [
                'transaction_id' => $transaction->id,
                'ssl_transaction_id' => $transaction->ssl_transaction_id
            ]);
            
            // Dispatch retry job with delay
            RetryFailedSslStatusUpdate::dispatch($transaction)->delay(now()->addMinutes(5));
        }

        Log::info('Transaction status updated from SSL Gateway', [
            'transaction_id' => $transaction->id,
            'ssl_transaction_id' => $transaction->ssl_transaction_id,
            'old_status' => $transaction->getOriginal('ssl_status'),
            'new_status' => $statusResult['status']
        ]);

        return true;
    }

    /**
     * Bulk check and update multiple transactions
     */
    public function bulkUpdateTransactionStatuses($transactionIds = null)
    {
        $query = Transaction::whereNotNull('ssl_transaction_id')
            ->whereIn('ssl_status', ['pending', null]);

        if ($transactionIds) {
            $query->whereIn('id', $transactionIds);
        }

        $transactions = $query->get();
        $updated = 0;
        $failed = 0;

        foreach ($transactions as $transaction) {
            try {
                if ($this->updateTransactionStatus($transaction)) {
                    $updated++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                Log::error('Failed to update transaction status', [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage()
                ]);
                $failed++;
            }
        }

        Log::info('Bulk SSL status update completed', [
            'total_processed' => $transactions->count(),
            'updated' => $updated,
            'failed' => $failed
        ]);

        return [
            'total_processed' => $transactions->count(),
            'updated' => $updated,
            'failed' => $failed
        ];
    }
}