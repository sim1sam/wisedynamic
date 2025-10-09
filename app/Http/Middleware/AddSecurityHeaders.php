<?php

namespace App\Http\Middleware;

use Closure;

class AddSecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        // Add security headers
        $response->headers->set('Content-Security-Policy', 
            "default-src 'self'; " .
            "script-src 'self' https://secure.sslcommerz.com; " .
            "frame-src 'self' https://secure.sslcommerz.com; " .
            "img-src 'self' data: https:; " .
            "connect-src 'self' https://api.sslcommerz.com; " .
            "form-action 'self' https://secure.sslcommerz.com; " .
            "upgrade-insecure-requests;");
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        return $response;
    }
}
