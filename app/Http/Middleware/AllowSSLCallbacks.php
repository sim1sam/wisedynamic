<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowSSLCallbacks
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
        // Disable CSRF protection for SSL payment callbacks
        $request->attributes->set('middleware.disable_csrf', true);
        
        // Disable session expiration checks
        $request->attributes->set('middleware.disable_session_expiry', true);
        
        return $next($request);
    }
}
