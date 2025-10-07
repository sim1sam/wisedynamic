<?php
// Include the CSRF bypass script
if (file_exists(public_path('force_no_csrf.php'))) {
    include public_path('force_no_csrf.php');
}
?>
@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <!-- Success Icon -->
        <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <!-- Success Message -->
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Payment Successful!</h1>
        
        @if($message)
            <p class="text-gray-600 mb-6">{{ $message }}</p>
        @endif
        
        @if(isset($transactionId) && $transactionId)
            <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-700">Transaction ID:</p>
                <p class="font-mono text-blue-700 font-semibold">{{ $transactionId }}</p>
                <p class="text-xs text-gray-500 mt-1">Please save this ID for your reference</p>
            </div>
        @endif

        <!-- Payment Status Verification -->
        <div id="payment-status-check" class="mb-6 p-4 bg-blue-50 rounded-lg">
            <div class="flex items-center justify-center space-x-2">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                <span class="text-sm text-blue-600">Verifying payment status...</span>
            </div>
            <p class="text-xs text-gray-500 mt-2">This may take a few moments</p>
        </div>

        <!-- Status Display -->
        <div id="status-display" class="mb-6 hidden">
            <div id="status-success" class="p-4 bg-green-50 border border-green-200 rounded-lg hidden">
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-green-700 font-medium">Payment Confirmed</span>
                </div>
                <p class="text-sm text-green-600 mt-1">Your payment has been successfully processed</p>
            </div>
            
            <div id="status-pending" class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-yellow-700 font-medium">Payment Processing</span>
                </div>
                <p class="text-sm text-yellow-600 mt-1">Your payment is being verified. This usually takes a few minutes.</p>
            </div>
            
            <div id="status-failed" class="p-4 bg-red-50 border border-red-200 rounded-lg hidden">
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="text-red-700 font-medium">Payment Issue</span>
                </div>
                <p class="text-sm text-red-600 mt-1">There was an issue with your payment. Please contact support.</p>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="space-y-3">
            @auth
                <a href="{{ route('payment.success.redirect') }}" 
                   class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 inline-block">
                    View Order Details
                </a>
                <a href="{{ route('customer.dashboard') }}" 
                   class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition duration-200 inline-block">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 inline-block">
                    Login to View Details
                </a>
                <a href="{{ route('register') }}" 
                   class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition duration-200 inline-block">
                    Create Account
                </a>
            @endauth
            
            <a href="{{ route('home') }}" 
               class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition duration-200 inline-block">
                Back to Home
            </a>
        </div>
        
        <!-- Additional Info -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Need help? Contact us at 
                <a href="mailto:support@wisedynamic.com.bd" class="text-blue-600 hover:underline">
                    support@wisedynamic.com.bd
                </a>
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get order information from session or query parameters
    const orderType = '{{ $orderType ?? session("payment_order_type") ?? request()->get("type") ?? "" }}';
    const orderId = '{{ $orderId ?? session("payment_order_id") ?? request()->get("id") ?? "" }}';
    const transactionId = '{{ $transactionId ?? request()->get("tran_id") ?? session("ssl_transaction_id") ?? "" }}';
    const status = '{{ request()->get("status") ?? "" }}';
    
    // Determine if we have enough information to check status
    const canCheckStatus = (orderType && orderId) || transactionId;
    
    // If we already have a status from the query parameter, show it immediately
    if (status === 'success') {
        setTimeout(function() {
            updateStatusDisplay('success', 'success');
        }, 1000);
    } else if (status === 'failed') {
        setTimeout(function() {
            updateStatusDisplay('failed', 'failed');
        }, 1000);
    } else if (status === 'cancelled') {
        setTimeout(function() {
            updateStatusDisplay('cancelled', 'cancelled');
        }, 1000);
    } else if (canCheckStatus) {
        // Check payment status every 3 seconds for up to 2 minutes
        let checkCount = 0;
        const maxChecks = 40; // 40 * 3 seconds = 2 minutes
        
        const statusInterval = setInterval(function() {
            checkCount++;
            
            // Make AJAX request to check payment status
            let url = '';
            if (orderType && orderId) {
                url = `/api/payment-status/${orderType}/${orderId}`;
            } else if (transactionId) {
                url = `/api/payment-status-by-transaction/${transactionId}`;
            }
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    updateStatusDisplay(data.status, data.ssl_status);
                    
                    // Stop checking if status is confirmed or failed
                    if (data.status === 'success' || data.status === 'completed' || data.status === 'failed') {
                        clearInterval(statusInterval);
                    }
                }
            })
            .catch(error => {
                console.log('Status check error:', error);
            });
            
            // Stop checking after max attempts
            if (checkCount >= maxChecks) {
                clearInterval(statusInterval);
                showTimeoutMessage();
            }
        }, 3000);
        
        // Initial status check after 2 seconds
        setTimeout(function() {
            let url = '';
            if (orderType && orderId) {
                url = `/api/payment-status/${orderType}/${orderId}`;
            } else if (transactionId) {
                url = `/api/payment-status-by-transaction/${transactionId}`;
            }
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    updateStatusDisplay(data.status, data.ssl_status);
                }
            })
            .catch(error => {
                console.log('Initial status check error:', error);
                showDefaultSuccess();
            });
        }, 2000);
    } else {
        // No order info, show default success
        setTimeout(showDefaultSuccess, 2000);
    }
    
    function updateStatusDisplay(status, sslStatus) {
        const checkDiv = document.getElementById('payment-status-check');
        const displayDiv = document.getElementById('status-display');
        
        checkDiv.classList.add('hidden');
        displayDiv.classList.remove('hidden');
        
        // Hide all status divs first
        document.getElementById('status-success').classList.add('hidden');
        document.getElementById('status-pending').classList.add('hidden');
        document.getElementById('status-failed').classList.add('hidden');
        
        // Show appropriate status
        if (status === 'success' || status === 'completed' || sslStatus === 'success') {
            document.getElementById('status-success').classList.remove('hidden');
        } else if (status === 'failed' || sslStatus === 'failed' || status === 'cancelled' || sslStatus === 'cancelled') {
            // Update the failed status div to show appropriate message
            const failedDiv = document.getElementById('status-failed');
            failedDiv.classList.remove('hidden');
            
            // If it was cancelled, show a different message
            if (status === 'cancelled' || sslStatus === 'cancelled') {
                failedDiv.innerHTML = `
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="text-yellow-700 font-medium">Payment Cancelled</span>
                    </div>
                    <p class="text-sm text-yellow-600 mt-1">Your payment was cancelled. You can try again when ready.</p>
                `;
            }
        } else {
            document.getElementById('status-pending').classList.remove('hidden');
        }
    }
    
    function showDefaultSuccess() {
        const checkDiv = document.getElementById('payment-status-check');
        const displayDiv = document.getElementById('status-display');
        
        checkDiv.classList.add('hidden');
        displayDiv.classList.remove('hidden');
        document.getElementById('status-success').classList.remove('hidden');
    }
    
    function showTimeoutMessage() {
        const checkDiv = document.getElementById('payment-status-check');
        checkDiv.innerHTML = `
            <div class="flex items-center justify-center space-x-2">
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm text-yellow-600">Status verification taking longer than expected</span>
            </div>
            <p class="text-xs text-gray-500 mt-2">Please check your order details or contact support</p>
        `;
    }
});
</script>
@endsection