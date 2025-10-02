<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentSuccessController extends Controller
{
    /**
     * Show payment success page.
     */
    public function show(Request $request)
    {
        $message = session('payment_success');
        $orderType = session('payment_order_type');
        $orderId = session('payment_order_id');
        
        if (!$message) {
            return redirect()->route('home')->with('info', 'No payment information found.');
        }
        
        return view('payment.success', compact('message', 'orderType', 'orderId'));
    }
    
    /**
     * Redirect to customer dashboard after login.
     */
    public function redirectToDashboard(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to view your order details.');
        }
        
        $orderType = session('payment_order_type');
        $orderId = session('payment_order_id');
        
        if ($orderType && $orderId) {
            $routeMap = [
                'package' => 'customer.package-orders.show',
                'service' => 'customer.service-orders.show',
                'custom-service' => 'customer.custom-service.show',
                'fund' => 'customer.fund.show',
            ];
            
            if (isset($routeMap[$orderType])) {
                return redirect()->route($routeMap[$orderType], $orderId);
            }
        }
        
        return redirect()->route('customer.dashboard');
    }
}