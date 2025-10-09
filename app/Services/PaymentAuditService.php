<?php

namespace App\Services;

use App\Models\PaymentAuditLog;
use Illuminate\Support\Facades\Auth;

class PaymentAuditService
{
    /**
     * Log a payment-related action.
     *
     * @param string $action The action being performed
     * @param string|int|null $transactionId The ID of the transaction
     * @param array $data Additional data to log
     * @return \App\Models\PaymentAuditLog
     */
    public function logPaymentAction($action, $transactionId = null, $data = [])
    {
        return PaymentAuditLog::create([
            'action' => $action,
            'transaction_id' => $transactionId,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => $data,
        ]);
    }
    
    /**
     * Log a payment attempt.
     *
     * @param string $type The type of payment (e.g., 'ssl', 'manual')
     * @param string|int $orderId The ID of the order
     * @param string $orderType The type of order (e.g., 'service', 'package')
     * @param float $amount The payment amount
     * @return \App\Models\PaymentAuditLog
     */
    public function logPaymentAttempt($type, $orderId, $orderType, $amount, $transactionId = null)
    {
        return $this->logPaymentAction('payment_attempt', $transactionId, [
            'payment_type' => $type,
            'order_id' => $orderId,
            'order_type' => $orderType,
            'amount' => $amount,
        ]);
    }
    
    /**
     * Log a successful payment.
     *
     * @param string|int $transactionId The ID of the transaction
     * @param float $amount The payment amount
     * @param array $additionalData Additional data to log
     * @return \App\Models\PaymentAuditLog
     */
    public function logPaymentSuccess($transactionId, $amount, $additionalData = [])
    {
        return $this->logPaymentAction('payment_success', $transactionId, array_merge([
            'amount' => $amount,
        ], $additionalData));
    }
    
    /**
     * Log a failed payment.
     *
     * @param string|int|null $transactionId The ID of the transaction
     * @param string $reason The reason for failure
     * @param array $additionalData Additional data to log
     * @return \App\Models\PaymentAuditLog
     */
    public function logPaymentFailure($transactionId, $reason, $additionalData = [])
    {
        return $this->logPaymentAction('payment_failure', $transactionId, array_merge([
            'reason' => $reason,
        ], $additionalData));
    }
}
