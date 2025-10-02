<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\SslGatewayStatusChecker;
use App\Models\Transaction;
use App\Jobs\RetryFailedSslStatusUpdate;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule SSL transaction status verification
Schedule::command('ssl:verify-status --status=pending --limit=100')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/ssl-verification.log'));

// Schedule comprehensive SSL verification (all statuses) every hour
Schedule::command('ssl:verify-status --status=all --limit=200 --force')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/ssl-verification-full.log'));

// Schedule retry mechanism for failed SSL transactions every 30 minutes
Schedule::call(function () {
    $failedTransactions = Transaction::where('payment_method', 'SSL Payment')
        ->where('ssl_status', 'pending')
        ->where('created_at', '>', now()->subDays(7)) // Only retry transactions from last 7 days
        ->whereDoesntHave('jobs', function ($query) {
            $query->where('queue', 'default')
                  ->where('payload', 'like', '%RetryFailedSslStatusUpdate%')
                  ->where('available_at', '>', now()->timestamp);
        })
        ->limit(50)
        ->get();
    
    foreach ($failedTransactions as $transaction) {
        RetryFailedSslStatusUpdate::dispatch($transaction)->delay(now()->addMinutes(rand(1, 10)));
    }
    
    \Log::info('Scheduled SSL retry jobs', [
        'count' => $failedTransactions->count(),
        'transaction_ids' => $failedTransactions->pluck('id')->toArray()
    ]);
})->everyThirtyMinutes()
  ->name('ssl-retry-failed-transactions')
  ->withoutOverlapping();
