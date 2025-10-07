<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Illuminate\Http\Request;

class VerifyCsrfToken extends Middleware
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
        // Check if the request has the bypass_csrf attribute or session variable
        if ($request->attributes->get('bypass_csrf') === true || session('bypass_csrf_for_payment') === true) {
            return $next($request);
        }
        
        // Check if the request is for a payment callback URL
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return $next($request);
            }
        }
        
        return parent::handle($request, $next);
    }
    
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     * @var array
     */
    protected $except = [
        // SSL Commerz basic endpoints
        '/success',
        '/cancel',
        '/fail',
        '/ipn',
        '/pay-via-ajax',
        '/ssl-callback',
        
        // Customer payment SSL endpoints (with and without leading slash)
        '/customer/payment/ssl/*',
        'customer/payment/ssl/*',
        
        // Specific SSL callback endpoints
        '/customer/payment/ssl/success',
        '/customer/payment/ssl/fail',
        '/customer/payment/ssl/cancel',
        '/customer/payment/ssl/ipn',
        
        // Specific SSL callback endpoints with parameters
        '/customer/payment/ssl/success/*',
        '/customer/payment/ssl/fail/*',
        '/customer/payment/ssl/cancel/*',
        
        // API endpoints
        'api/ssl-gateway/*',
        'api/gateway/*',
        
        // Fund request SSL endpoints
        '/fund/*/ssl-success',
        '/fund/*/ssl-fail',
        
        // Custom service SSL endpoints
        '/custom-service/*/ssl-success',
        '/custom-service/*/ssl-fail',
        
        // Payment success page
        '/payment/success',
        '/payment/success/*',
    ];
}