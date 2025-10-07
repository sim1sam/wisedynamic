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
        // No need to manually disable CSRF here - we've added these routes to the except array in VerifyCsrfToken middleware
        
        // First check for query parameters (direct redirect from payment gateway)
        if ($request->has('message') || $request->has('tran_id')) {
            $message = $request->get('message') ?: 'Your payment has been processed successfully.';
            $transactionId = $request->get('tran_id');
            $orderType = $request->get('type');
            $orderId = $request->get('id');
            
            // If we have a transaction ID but no message, create a generic message
            if (!$message && $transactionId) {
                $message = 'Your payment with transaction ID: ' . $transactionId . ' has been processed successfully.';
            }
            
            // Store in session for potential future use
            if ($transactionId) {
                session(['ssl_transaction_id' => $transactionId]);
            }
            
            if ($message) {
                session(['payment_success' => $message]);
            }
            
            if ($orderType && $orderId) {
                session([
                    'payment_order_type' => $orderType,
                    'payment_order_id' => $orderId
                ]);
            }
            
            return view('payment.success', compact('message', 'orderType', 'orderId', 'transactionId'));
        }
        
        // Then check session data (from previous redirects)
        $message = session('payment_success');
        $orderType = session('payment_order_type');
        $orderId = session('payment_order_id');
        $transactionId = session('ssl_transaction_id');
        $errorMessage = session('payment_error');
        $cancelMessage = session('payment_cancel');
        
        // Check if we have an error or cancel message first
        if ($errorMessage) {
            // Clear the session data
            session()->forget(['payment_error', 'ssl_transaction_id']);
            return redirect()->route('home')->with('error', $errorMessage);
        }
        
        if ($cancelMessage) {
            // Clear the session data
            session()->forget(['payment_cancel', 'ssl_transaction_id']);
            return redirect()->route('home')->with('info', $cancelMessage);
        }
        
        // If we don't have a message but have a transaction ID, create a generic success message
        if (!$message && $transactionId) {
            $message = 'Your payment with transaction ID: ' . $transactionId . ' has been processed. Please check your order status in your account.';            
        } elseif (!$message && $request->has('tran_id')) {
            // If the transaction ID is in the request, use that
            $transactionId = $request->get('tran_id');
            $message = 'Your payment with transaction ID: ' . $transactionId . ' has been processed. Please check your order status in your account.';            
        } elseif (!$message) {
            // No payment information available
            return redirect()->route('home')->with('info', 'No payment information found.');
        }
        
        // Clear the session data after we've used it
        session()->forget(['payment_success', 'payment_order_type', 'payment_order_id', 'ssl_transaction_id']);
        
        return view('payment.success', compact('message', 'orderType', 'orderId', 'transactionId'));
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
        $transactionId = session('ssl_transaction_id');
        $successMessage = session('payment_success');
        
        // Clear all payment-related session data
        session()->forget([
            'payment_success', 
            'payment_order_type', 
            'payment_order_id', 
            'ssl_transaction_id',
            'payment_error',
            'payment_cancel'
        ]);
        
        if ($orderType && $orderId) {
            $routeMap = [
                'package' => 'customer.package-orders.show',
                'service' => 'customer.service-orders.show',
                'custom-service' => 'customer.custom-service.show',
                'fund' => 'customer.fund.show',
            ];
            
            if (isset($routeMap[$orderType])) {
                return redirect()->route($routeMap[$orderType], $orderId)
                    ->with('success', $successMessage ?? 'Your payment has been processed successfully.');
            }
        }
        
        // If we have a transaction ID but no order details
        if ($transactionId) {
            return redirect()->route('customer.dashboard')
                ->with('success', $successMessage ?? 'Your payment with transaction ID: ' . $transactionId . ' has been processed.');
        }
        
        return redirect()->route('customer.dashboard');
    }
}