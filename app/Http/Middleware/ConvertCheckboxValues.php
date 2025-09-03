<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertCheckboxValues
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Convert checkbox values from 'on' to true for validation
        $input = $request->all();
        
        // Common checkbox field names
        $checkboxFields = ['status', 'featured', 'active', 'is_active', 'is_featured', 'is_published', 'published'];
        
        foreach ($checkboxFields as $field) {
            if (isset($input[$field]) && $input[$field] === 'on') {
                $input[$field] = true;
            } elseif (!isset($input[$field]) && $request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch')) {
                // For unchecked checkboxes, set to false if not present in request
                $input[$field] = false;
            }
        }
        
        $request->replace($input);
        return $next($request);
    }
}
