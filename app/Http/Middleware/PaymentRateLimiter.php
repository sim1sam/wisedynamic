<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentRateLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Generate a unique key for this user and endpoint
        $key = Str::lower($request->ip() . '|' . $request->path());
        
        // Set limits based on the endpoint
        $maxAttempts = 10; // Default limit per minute
        
        // Reduce limit for payment processing endpoints
        if ($request->is('*/payment/*/ssl') || $request->is('*/payment/*/manual')) {
            $maxAttempts = 5;
        }
        
        // Further reduce for users with failed attempts
        $failedAttempts = RateLimiter::attempts($key . ':failed');
        if ($failedAttempts > 2) {
            $maxAttempts = 2;
        }
        
        // Check if the rate limit has been exceeded
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            // Log the rate limiting event
            Log::warning('Payment rate limit exceeded', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'user_id' => auth()->id(),
                'attempts' => RateLimiter::attempts($key)
            ]);
            
            // Return a 429 Too Many Requests response
            return response()->json([
                'error' => 'Too many payment attempts. Please try again later.',
                'retry_after' => RateLimiter::availableIn($key)
            ], 429);
        }
        
        // Increment the rate limiter counter
        RateLimiter::hit($key, 60);
        
        // If this is a payment failure, increment the failed attempts counter
        if ($request->is('*/payment/*/fail') || $request->is('*/payment/*/cancel')) {
            RateLimiter::hit($key . ':failed', 300); // 5 minutes
        }
        
        return $next($request);
    }
}
