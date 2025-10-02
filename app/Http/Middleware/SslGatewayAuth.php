<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SslGatewayAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get API key from header or query parameter
        $apiKey = $request->header('X-API-Key') ?? $request->query('api_key');
        
        // Get expected API key from environment
        $expectedApiKey = config('services.ssl_gateway.api_key', env('SSL_GATEWAY_API_KEY'));
        
        // Check if API key is provided
        if (!$apiKey) {
            Log::warning('SSL Gateway API request without API key', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'API key is required',
                'error' => 'Unauthorized'
            ], 401);
        }
        
        // Validate API key
        if (!$expectedApiKey || $apiKey !== $expectedApiKey) {
            Log::warning('SSL Gateway API request with invalid API key', [
                'provided_key' => substr($apiKey, 0, 8) . '...',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key',
                'error' => 'Unauthorized'
            ], 401);
        }
        
        // Log successful authentication
        Log::info('SSL Gateway API request authenticated', [
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method()
        ]);
        
        return $next($request);
    }
}