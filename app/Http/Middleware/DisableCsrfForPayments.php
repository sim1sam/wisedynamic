<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DisableCsrfForPayments
{
    /**
     * Payment routes that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'customer/payment/ssl/*',
        'ssl-callback',
        'payment/success',
        'payment/success/*',
        'success',
        'cancel',
        'fail',
        'ipn',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request matches any of the excluded patterns
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                // Explicitly set a session variable to indicate CSRF should be bypassed
                session(['bypass_csrf_for_payment' => true]);
                
                // Add a flag to the request to indicate CSRF should be bypassed
                $request->attributes->set('bypass_csrf', true);
                
                // Log that we're bypassing CSRF for this request
                \Illuminate\Support\Facades\Log::info('Bypassing CSRF for payment route', [
                    'path' => $request->path(),
                    'method' => $request->method()
                ]);
                
                break;
            }
        }

        return $next($request);
    }
}
