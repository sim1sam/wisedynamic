<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RegenerateSessionForPayment
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
        // Only regenerate session for payment-related routes
        if ($request->is('*/payment/*') && !$request->is('*/payment/*/success') && !$request->is('*/payment/*/fail')) {
            // Regenerate the session ID to prevent session fixation attacks
            $request->session()->regenerate();
            
            // Log session regeneration for payment
            \Illuminate\Support\Facades\Log::info('Session regenerated for payment', [
                'user_id' => auth()->id(),
                'path' => $request->path()
            ]);
        }
        
        return $next($request);
    }
}
