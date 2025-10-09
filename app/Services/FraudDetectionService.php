<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class FraudDetectionService
{
    /**
     * Check a transaction for potential fraud.
     *
     * @param \App\Models\Transaction $transaction
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function checkTransaction(Transaction $transaction, Request $request)
    {
        $score = 0;
        $flags = [];
        
        // Check for multiple failed attempts from the same email
        $recentFailures = Transaction::where('customer_email', $transaction->customer_email)
            ->where('status', 'failed')
            ->where('created_at', '>', now()->subHours(24))
            ->count();
            
        if ($recentFailures > 3) {
            $score += 50;
            $flags[] = 'multiple_failures';
        }
        
        // Check for unusual amount patterns
        $averageAmount = Transaction::where('customer_email', $transaction->customer_email)
            ->where('status', 'completed')
            ->avg('amount');
            
        if ($averageAmount && $transaction->amount > $averageAmount * 5) {
            $score += 30;
            $flags[] = 'unusual_amount';
        }
        
        // Check for rapid transactions
        $recentTransactions = Transaction::where('customer_email', $transaction->customer_email)
            ->where('created_at', '>', now()->subMinutes(5))
            ->count();
            
        if ($recentTransactions > 3) {
            $score += 40;
            $flags[] = 'rapid_transactions';
        }
        
        // Check for multiple different payment methods
        $paymentMethods = Transaction::where('customer_email', $transaction->customer_email)
            ->where('created_at', '>', now()->subHours(24))
            ->distinct('payment_method')
            ->count('payment_method');
            
        if ($paymentMethods > 2) {
            $score += 30;
            $flags[] = 'multiple_payment_methods';
        }
        
        // Log the fraud score
        if ($score > 30) {
            Log::warning('Potential fraud detected', [
                'transaction_id' => $transaction->id,
                'customer_email' => $transaction->customer_email,
                'score' => $score,
                'flags' => $flags,
                'ip' => $request->ip()
            ]);
        }
        
        return [
            'score' => $score,
            'flags' => $flags,
            'is_suspicious' => $score > 70,
            'requires_review' => $score > 50,
        ];
    }
    
    /**
     * Get recommended action based on fraud check results.
     *
     * @param array $fraudCheck
     * @return string
     */
    public function getRecommendedAction(array $fraudCheck)
    {
        if ($fraudCheck['is_suspicious']) {
            return 'block';
        }
        
        if ($fraudCheck['requires_review']) {
            return 'review';
        }
        
        return 'allow';
    }
}
