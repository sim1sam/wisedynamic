<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class PaymentExceptionHandler
{
    /**
     * Handle a payment-related exception.
     *
     * @param \Throwable $exception
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function handle(Throwable $exception, $request)
    {
        // Generate a unique reference ID for this error
        $referenceId = Str::uuid()->toString();
        
        // Log detailed error information for debugging
        Log::error('Payment processing error', [
            'reference_id' => $referenceId,
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);
        
        // For API requests, return a JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Payment processing failed',
                'error_code' => $this->getSafeErrorCode($exception),
                'reference' => $referenceId,
            ], 400);
        }
        
        // For web requests, redirect with a flash message
        return redirect()->back()
            ->with('error', 'Payment processing failed. Please try again or contact support with reference: ' . $referenceId);
    }
    
    /**
     * Map internal error types to safe public error codes.
     *
     * @param \Throwable $exception
     * @return string
     */
    private function getSafeErrorCode(Throwable $exception)
    {
        // Map exception classes to safe error codes
        $errorMap = [
            'PDOException' => 'database_error',
            'GuzzleHttp\Exception\ConnectException' => 'gateway_connection_error',
            'GuzzleHttp\Exception\RequestException' => 'gateway_request_error',
            'App\Exceptions\PaymentValidationException' => 'validation_error',
            'App\Exceptions\PaymentGatewayException' => 'gateway_error',
            'App\Exceptions\PaymentDeclinedException' => 'payment_declined',
            'App\Exceptions\InsufficientFundsException' => 'insufficient_funds',
        ];
        
        $exceptionClass = get_class($exception);
        
        return $errorMap[$exceptionClass] ?? 'general_error';
    }
}
