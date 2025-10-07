<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin-only SSL Transaction Management Routes (requires authentication)
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    // Admin manual status update (with optional SSL verification)
    Route::post('/transaction/update-status', [TransactionController::class, 'adminUpdateStatus'])
        ->name('api.admin.transaction.status.update');
    
    // Admin SSL verification
    Route::post('/transaction/verify-ssl', [TransactionController::class, 'verifyWithSsl'])
        ->name('api.admin.transaction.verify.ssl');
    
    // Individual SSL transaction verification
    Route::post('/transactions/{transactionId}/verify-ssl', [TransactionController::class, 'verifyIndividualSsl'])
        ->name('api.admin.transactions.verify.ssl');
    
    // Bulk SSL verification for pending transactions
    Route::post('/transactions/bulk-verify-ssl', [TransactionController::class, 'bulkVerifySsl'])
        ->name('api.admin.transactions.bulk.verify.ssl');
    
    // Manual SSL status update
    Route::post('/transactions/{transactionId}/update-status', [TransactionController::class, 'updateSslStatus'])
        ->name('api.admin.transactions.update.status');
});

// SSL Gateway Transaction Status Update Routes (for gateway callbacks)
Route::prefix('ssl-gateway')->group(function () {
    // Update transaction status from SSL gateway
    Route::post('/transaction/status', [TransactionController::class, 'updateStatus'])
        ->name('api.ssl.transaction.status.update');
    
    // Get transaction status by SSL transaction ID
    Route::get('/transaction/status', [TransactionController::class, 'getStatus'])
        ->name('api.ssl.transaction.status.get');
});

// Alternative routes with API key authentication for SSL gateway
Route::middleware('ssl.gateway.auth')->prefix('gateway')->group(function () {
    Route::post('/transaction/update-status', [TransactionController::class, 'updateStatus'])
        ->name('api.gateway.transaction.update');
    
    Route::get('/transaction/get-status', [TransactionController::class, 'getStatus'])
        ->name('api.gateway.transaction.get');
});

// Public routes for payment status checking (used by success page)
Route::get('/payment-status/{orderType}/{orderId}', [TransactionController::class, 'getPaymentStatus']);
Route::get('/payment-status-by-transaction/{transactionId}', [TransactionController::class, 'getPaymentStatusByTransaction']);