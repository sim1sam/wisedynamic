@extends('layouts.app')

@section('title', 'SSL Payment')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-4 sm:py-8 lg:py-16 px-4">
    <div class="max-w-lg mx-auto w-full mt-4 sm:mt-8 lg:mt-16">
        <!-- Header Section -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full mb-3 sm:mb-4">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Secure Payment</h1>
            <p class="text-sm sm:text-base text-gray-600">Complete your payment securely with SSL Commerz</p>
        </div>

        <!-- Main Payment Card -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- SSL Commerz Branding Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-white font-bold text-base sm:text-lg">SSL Commerz</h2>
                            <p class="text-green-100 text-xs sm:text-sm">Secure Payment Gateway</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></div>
                        <span class="text-green-100 text-xs font-medium">SECURE</span>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-6">
                <!-- Order Summary -->
                <div class="mb-4 sm:mb-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Order Summary
                    </h3>
                    
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-gray-200">
                        <div class="grid grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Type</p>
                                <p class="font-semibold text-gray-900 capitalize text-sm sm:text-base">{{ $type }}</p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Order ID</p>
                                <p class="font-semibold text-gray-900 text-sm sm:text-base">#{{ $order->id }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-200">
                            @if($type === 'fund')
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm sm:text-base">Amount</span>
                                    <span class="text-xl sm:text-2xl font-bold text-green-600">৳{{ number_format($order->amount, 2) }}</span>
                                </div>
                            @elseif($type === 'custom-service')
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm sm:text-base">Total Amount</span>
                                    <span class="text-xl sm:text-2xl font-bold text-green-600">৳{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            @else
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 text-sm sm:text-base">Total Amount</span>
                                        <span class="font-semibold text-sm sm:text-base">৳{{ number_format($order->amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 text-sm sm:text-base">Paid</span>
                                        <span class="font-semibold text-green-600 text-sm sm:text-base">৳{{ number_format($order->paid_amount ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                        <span class="text-gray-900 font-semibold text-sm sm:text-base">Amount Due</span>
                                        <span class="text-xl sm:text-2xl font-bold text-red-600">৳{{ number_format($order->due_amount ?? $order->amount, 2) }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="mb-4 sm:mb-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 rounded-r-lg p-3 sm:p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-xs sm:text-sm font-semibold text-blue-800 mb-1">Secure Payment Process</h4>
                                <p class="text-xs sm:text-sm text-blue-700">
                                    You will be redirected to SSL Commerz secure payment gateway. Your payment information is protected with 256-bit SSL encryption.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form method="POST" action="{{ route('customer.payment.ssl', ['type' => $type, 'id' => $id]) }}">
                    @csrf
                    
                    <!-- Hidden input to pass the amount -->
                    <input type="hidden" name="payment_amount" 
                        @if($type === 'fund')
                            value="{{ $order->amount }}"
                        @elseif($type === 'custom-service')
                            value="{{ $order->total_amount }}"
                        @else
                            value="{{ $order->due_amount ?? $order->amount }}"
                        @endif
                    >

                    <!-- Payment Methods Info -->
                    <div class="mb-4 sm:mb-6">
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-700 mb-2 sm:mb-3">Accepted Payment Methods</h4>
                        <div class="flex flex-wrap gap-1 sm:gap-2">
                            <div class="flex items-center bg-gray-100 rounded-lg px-2 sm:px-3 py-1 sm:py-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <path d="M4 8h16v2H4z" fill="white"/>
                                </svg>
                                <span class="text-xs font-medium text-gray-700">Cards</span>
                            </div>
                            <div class="flex items-center bg-gray-100 rounded-lg px-2 sm:px-3 py-1 sm:py-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                <span class="text-xs font-medium text-gray-700">Mobile Banking</span>
                            </div>
                            <div class="flex items-center bg-gray-100 rounded-lg px-2 sm:px-3 py-1 sm:py-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-purple-600 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                <span class="text-xs font-medium text-gray-700">Net Banking</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4 sm:pt-6">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 sm:py-4 px-4 sm:px-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span class="text-sm sm:text-base">Pay Securely - ৳
                            @if($type === 'fund')
                                {{ number_format($order->amount, 2) }}
                            @elseif($type === 'custom-service')
                                {{ number_format($order->total_amount, 2) }}
                            @else
                                {{ number_format($order->due_amount ?? $order->amount, 2) }}
                            @endif
                            </span>
                        </button>
                        
                        <a href="{{ route('customer.payment.options', ['type' => $type, 'id' => $id]) }}" class="flex-1 sm:flex-none bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 sm:py-4 px-4 sm:px-6 rounded-lg transition-all duration-200 flex items-center justify-center border border-gray-300">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="text-sm sm:text-base">Back to Payment Options</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Trust Badges -->
        <div class="mt-6 text-center">
            <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    256-bit SSL
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    PCI Compliant
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Secure Gateway
                </div>
            </div>
        </div>
    </div>
</div>
@endsection