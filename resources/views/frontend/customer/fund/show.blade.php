@php $title = 'Fund Request Details'; @endphp
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
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Fund Request Details</h1>
                <p class="text-sm text-gray-600">Request #{{ $fundRequest->id }} - {{ $fundRequest->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('customer.fund.index') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700 shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Fund Management
                </a>
            </div>
        </div>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-lg shadow dashboard-card">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Status</p>
                    @if($fundRequest->status === 'pending')
                        <div class="flex items-center">
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-medium">
                                <i class="fas fa-clock mr-2"></i>Pending
                            </span>
                        </div>
                    @elseif($fundRequest->status === 'approved')
                        <div class="flex items-center">
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 font-medium">
                                <i class="fas fa-check-circle mr-2"></i>Approved
                            </span>
                        </div>
                    @else
                        <div class="flex items-center">
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 font-medium">
                                <i class="fas fa-times-circle mr-2"></i>Rejected
                            </span>
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Amount</p>
                    <p class="text-2xl font-bold text-gray-900">BDT {{ number_format($fundRequest->amount, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Fund Request Details -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Request Information</h3>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Payment Method</h4>
                    <p class="text-base text-gray-900">
                        @if($fundRequest->payment_method === 'ssl')
                            <span class="inline-flex items-center">
                                <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                                SSL Payment
                            </span>
                        @else
                            <span class="inline-flex items-center">
                                <i class="fas fa-university mr-2 text-green-600"></i>
                                Manual Bank Transfer
                            </span>
                        @endif
                    </p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Request Date</h4>
                    <p class="text-base text-gray-900">{{ $fundRequest->created_at->format('M d, Y H:i') }}</p>
                </div>

                @if($fundRequest->payment_method === 'manual')
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Bank Name</h4>
                        <p class="text-base text-gray-900">{{ $fundRequest->bank_name }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Account Number</h4>
                        <p class="text-base text-gray-900">{{ $fundRequest->account_number }}</p>
                    </div>
                @endif

                @if($fundRequest->payment_method === 'ssl' && $fundRequest->ssl_transaction_id)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">SSL Transaction ID</h4>
                        <p class="text-base text-gray-900 font-mono">{{ $fundRequest->ssl_transaction_id }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Payment Status</h4>
                        @if($fundRequest->transaction)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Payment Completed
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-circle mr-1"></i> Payment Initiated
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Your payment has been initiated but not yet completed.</p>
                        @endif
                    </div>
                @endif

                @if($fundRequest->service_info)
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Service Information</h4>
                        <p class="text-base text-gray-900">{{ $fundRequest->service_info }}</p>
                    </div>
                @endif

                @if($fundRequest->status === 'approved')
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Approved Date</h4>
                        <p class="text-base text-gray-900">{{ $fundRequest->approved_at->format('M d, Y H:i') }}</p>
                    </div>
                @endif

                @if($fundRequest->admin_notes)
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Admin Notes</h4>
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-gray-700">
                            {{ $fundRequest->admin_notes }}
                        </div>
                    </div>
                @endif
            </div>

            @if($fundRequest->payment_method === 'manual' && $fundRequest->payment_screenshot)
                <div class="mt-6 pt-6 border-t">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Payment Screenshot</h4>
                    <div class="mt-2">
            <img src="{{ asset($fundRequest->payment_screenshot) }}" alt="Payment Screenshot" class="max-w-full h-auto rounded-lg border border-gray-200 shadow-sm" style="max-height: 400px;">
                    </div>
                </div>
            @endif

            @if($fundRequest->payment_method === 'ssl')
                <div class="mt-6 pt-6 border-t">
                    <div class="flex justify-center">
                        @if($fundRequest->transaction)
                            <!-- Transaction completed -->
                            <div class="text-center">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <p class="text-green-700"><i class="fas fa-check-circle mr-2"></i> Payment completed successfully</p>
                                    <p class="text-sm text-green-600 mt-2">Transaction ID: {{ $fundRequest->transaction->transaction_number }}</p>
                                </div>
                            </div>
                        @elseif($fundRequest->status === 'pending')
                            @if(!$fundRequest->ssl_transaction_id)
                                <!-- New payment, never initiated -->
                                <a href="{{ route('customer.fund.ssl-payment', $fundRequest) }}" class="inline-flex items-center px-6 py-3 rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow">
                                    <i class="fas fa-credit-card mr-2"></i> Proceed to Payment
                                </a>
                            @else
                                <!-- Payment initiated but not completed -->
                                <div class="text-center">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                        <p class="text-yellow-700"><i class="fas fa-exclamation-triangle mr-2"></i> Your payment has been initiated but appears to be incomplete.</p>
                                        <p class="text-sm text-yellow-600 mt-2">This could happen if the payment process was interrupted or if there was a network issue.</p>
                                    </div>
                                    <a href="{{ route('customer.fund.ssl-payment', $fundRequest) }}" class="inline-flex items-center px-6 py-3 rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow">
                                        <i class="fas fa-sync-alt mr-2"></i> Retry Payment
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Transaction Details -->
    @if($fundRequest->transaction)
        <div class="bg-white rounded-lg shadow dashboard-card card-green">
            <div class="card-header-themed p-4 rounded-t-lg">
                <h3 class="text-lg font-bold section-header mb-0">Transaction Details</h3>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Transaction ID</h4>
                        <p class="text-base text-gray-900 font-mono">{{ $fundRequest->transaction->transaction_number }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Transaction Date</h4>
                        <p class="text-base text-gray-900">{{ $fundRequest->transaction->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Amount</h4>
                        <p class="text-base text-gray-900">BDT {{ number_format($fundRequest->transaction->amount, 2) }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                        @if($fundRequest->transaction->status === 'completed')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        @elseif($fundRequest->transaction->status === 'pending')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                {{ ucfirst($fundRequest->transaction->status) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
