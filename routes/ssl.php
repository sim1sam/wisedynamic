<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\PaymentController;

/*
|--------------------------------------------------------------------------
| SSL Payment Routes
|--------------------------------------------------------------------------
|
| These routes are loaded outside the web middleware group to ensure
| they are not subject to CSRF protection or session expiration.
|
*/

// SSL Commerz callback routes (no middleware)
Route::match(['get', 'post'], '/customer/payment/ssl/success/{type}/{id}', [PaymentController::class, 'sslSuccess']);
Route::match(['get', 'post'], '/customer/payment/ssl/fail/{type}/{id}', [PaymentController::class, 'sslFail']);
Route::match(['get', 'post'], '/customer/payment/ssl/cancel/{type}/{id}', [PaymentController::class, 'sslCancel']);
Route::match(['get', 'post'], '/customer/payment/ssl/ipn', [PaymentController::class, 'sslIpn']);

// Generic SSL callback routes
Route::match(['get', 'post'], '/customer/payment/ssl/success', [PaymentController::class, 'handleGenericSSLCallback']);
Route::match(['get', 'post'], '/customer/payment/ssl/fail', [PaymentController::class, 'handleGenericSSLCallback']);
Route::match(['get', 'post'], '/customer/payment/ssl/cancel', [PaymentController::class, 'handleGenericSSLCallback']);

// Basic SSL endpoints
Route::match(['get', 'post'], '/success', [PaymentController::class, 'handleGenericSSLCallback']);
Route::match(['get', 'post'], '/fail', [PaymentController::class, 'handleGenericSSLCallback']);
Route::match(['get', 'post'], '/cancel', [PaymentController::class, 'handleGenericSSLCallback']);
Route::match(['get', 'post'], '/ssl-callback', [PaymentController::class, 'handleGenericSSLCallback']);
