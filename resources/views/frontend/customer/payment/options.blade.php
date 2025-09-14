@php $title = 'Payment Options'; @endphp
@extends('layouts.customer')

@section('content')
<div class="w-full px-4 md:px-6 space-y-6">
    @if(session('success'))
        <div class="p-4 rounded-lg bg-green-100 text-green-700 border border-green-200">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-lg bg-red-100 text-red-700 border border-red-200">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Payment Options</h1>
                <p class="text-sm text-gray-600">Choose your preferred payment method for {{ $type === 'package' ? 'Package' : 'Service' }} Order #{{ $order->id }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('customer.' . ($type === 'package' ? 'orders' : 'service-orders') . '.show', $order) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700 shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Order
                </a>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Order Summary</h3>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order Type</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $type === 'package' ? 'Package Order' : 'Service Order' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order ID</label>
                    <p class="text-lg font-semibold text-gray-900">#{{ $order->id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                    <p class="text-2xl font-bold text-green-600">BDT {{ number_format($order->total_amount, 2) }}</p>
                </div>
                @if($type === 'package')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Package</label>
                        <p class="text-sm text-gray-900">{{ $order->package->name }}</p>
                    </div>
                @else
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <p class="text-sm text-gray-900">{{ $order->service->name }}</p>
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Choose Payment Method</h3>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- SSL Payment -->
                <div class="border border-gray-300 rounded-lg p-6 text-center">
                    <div class="mb-4">
                        <i class="fas fa-credit-card text-4xl text-blue-600 mb-3"></i>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">SSL Payment</h4>
                        <p class="text-gray-600 mb-4">Pay securely with your credit/debit card through our SSL payment gateway</p>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center justify-center space-x-2 mb-2">
                            <i class="fas fa-shield-alt text-green-600"></i>
                            <span class="text-sm text-gray-600">Secure & Encrypted</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2 mb-2">
                            <i class="fas fa-bolt text-yellow-600"></i>
                            <span class="text-sm text-gray-600">Instant Processing</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-check text-green-600"></i>
                            <span class="text-sm text-gray-600">Automatic Confirmation</span>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('customer.payment.ssl', [$type, $order->id]) }}">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-semibold">
                            <i class="fas fa-credit-card mr-2"></i>Pay with SSL
                        </button>
                    </form>
                </div>

                <!-- Manual Bank Transfer -->
                <div class="border border-gray-300 rounded-lg p-6 text-center">
                    <div class="mb-4">
                        <i class="fas fa-university text-4xl text-green-600 mb-3"></i>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Bank Transfer</h4>
                        <p class="text-gray-600 mb-4">Pay through manual bank transfer and upload payment proof for verification</p>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center justify-center space-x-2 mb-2">
                            <i class="fas fa-clock text-orange-600"></i>
                            <span class="text-sm text-gray-600">Manual Verification</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2 mb-2">
                            <i class="fas fa-upload text-blue-600"></i>
                            <span class="text-sm text-gray-600">Upload Payment Proof</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-user-check text-green-600"></i>
                            <span class="text-sm text-gray-600">Admin Approval Required</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('customer.payment.manual', [$type, $order->id]) }}" class="w-full inline-block px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-university mr-2"></i>Pay via Bank Transfer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Instructions -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Payment Instructions</h3>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">SSL Payment</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mt-1 mr-2"></i>
                            <span>Click "Pay with SSL" button</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mt-1 mr-2"></i>
                            <span>Enter your card details securely</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mt-1 mr-2"></i>
                            <span>Payment will be processed instantly</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mt-1 mr-2"></i>
                            <span>Order will be activated immediately</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Bank Transfer</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mt-1 mr-2"></i>
                            <span>Transfer money to our bank account</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mt-1 mr-2"></i>
                            <span>Fill in bank details and transaction ID</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mt-1 mr-2"></i>
                            <span>Upload clear screenshot of payment</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mt-1 mr-2"></i>
                            <span>Wait for admin verification (1-2 business days)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection