@php $title = 'Custom Service Request Details'; @endphp
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
    @if(session('info'))
        <div class="p-4 rounded-lg bg-blue-100 text-blue-700 border border-blue-200">
            <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Custom Service Request #{{ $customServiceRequest->id }}</h1>
                <p class="text-sm text-gray-600">{{ $customServiceRequest->getServiceTypeLabel() }} - {{ $customServiceRequest->getStatusLabel() }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('customer.custom-service.index') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700 shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Requests
                </a>
                @if($customServiceRequest->status === 'pending' && $customServiceRequest->payment_method === 'ssl' && !$customServiceRequest->ssl_transaction_id)
                    <a href="{{ route('customer.custom-service.ssl-payment', $customServiceRequest) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 shadow">
                        <i class="fas fa-credit-card mr-2"></i> Complete Payment
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Request Overview -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Request Overview</h3>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Request ID</label>
                    <p class="text-lg font-semibold text-gray-900">#{{ $customServiceRequest->id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Type</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $customServiceRequest->getServiceTypeLabel() }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-{{ $customServiceRequest->getStatusColorClass() }}-100 text-{{ $customServiceRequest->getStatusColorClass() }}-800">
                        {{ $customServiceRequest->getStatusLabel() }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                    <p class="text-lg font-bold text-green-600">BDT {{ number_format($customServiceRequest->total_amount, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <div class="flex items-center">
                        @if($customServiceRequest->payment_method === 'balance')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-wallet mr-1"></i>Balance Payment
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-credit-card mr-1"></i>SSL Payment
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Created Date</label>
                    <p class="text-sm text-gray-900">{{ $customServiceRequest->created_at->format('M d, Y H:i') }}</p>
                </div>
                @if($customServiceRequest->started_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Started Date</label>
                        <p class="text-sm text-gray-900">{{ $customServiceRequest->started_at->format('M d, Y H:i') }}</p>
                    </div>
                @endif
                @if($customServiceRequest->completed_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Completed Date</label>
                        <p class="text-sm text-gray-900">{{ $customServiceRequest->completed_at->format('M d, Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Service Items -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Service Items ({{ $customServiceRequest->items->count() }})</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($customServiceRequest->items as $item)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between mb-3">
                            <h4 class="text-lg font-semibold text-gray-900">{{ $item->service_name }}</h4>
                            <span class="text-lg font-bold text-green-600">BDT {{ number_format($item->amount, 2) }}</span>
                        </div>
                        
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            @if($customServiceRequest->service_type === 'marketing')
                                @if($item->platform)
                                    <div>
                                        <span class="font-medium text-gray-700">Platform:</span>
                                        <span class="text-gray-900">{{ $item->platform }}</span>
                                    </div>
                                @endif
                                @if($item->post_link)
                                    <div class="md:col-span-2">
                                        <span class="font-medium text-gray-700">Post Link:</span>
                                        <a href="{{ $item->post_link }}" target="_blank" class="text-blue-600 hover:text-blue-800 break-all">
                                            {{ $item->post_link }}
                                        </a>
                                    </div>
                                @endif
                                @if($item->duration_days)
                                    <div>
                                        <span class="font-medium text-gray-700">Duration:</span>
                                        <span class="text-gray-900">{{ $item->duration_days }} days</span>
                                    </div>
                                @endif
                                @if($item->service_date)
                                    <div>
                                        <span class="font-medium text-gray-700">Service Date:</span>
                                        <span class="text-gray-900">{{ $item->service_date->format('M d, Y') }}</span>
                                    </div>
                                @endif
                            @elseif($customServiceRequest->service_type === 'web_app')
                                @if($item->domain_name)
                                    <div>
                                        <span class="font-medium text-gray-700">Domain:</span>
                                        <span class="text-gray-900">{{ $item->domain_name }}</span>
                                    </div>
                                @endif
                                @if($item->duration_months)
                                    <div>
                                        <span class="font-medium text-gray-700">Duration:</span>
                                        <span class="text-gray-900">{{ $item->duration_months }} months</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                        @if($item->description)
                            <div class="mt-3">
                                <span class="font-medium text-gray-700">Description:</span>
                                <p class="text-gray-900 mt-1">{{ $item->description }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    @if($customServiceRequest->transaction || $customServiceRequest->ssl_transaction_id)
        <div class="bg-white rounded-lg shadow dashboard-card card-blue">
            <div class="card-header-themed p-4 rounded-t-lg">
                <h3 class="text-lg font-bold section-header mb-0">Payment Information</h3>
            </div>
            <div class="p-6">
                @if($customServiceRequest->transaction)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Number</label>
                            <p class="text-sm font-mono text-gray-900">{{ $customServiceRequest->transaction->transaction_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <p class="text-sm text-gray-900">{{ $customServiceRequest->transaction->payment_method }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($customServiceRequest->transaction->status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                            <p class="text-sm text-gray-900">{{ $customServiceRequest->transaction->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if($customServiceRequest->transaction->notes)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                <p class="text-sm text-gray-900">{{ $customServiceRequest->transaction->notes }}</p>
                            </div>
                        @endif
                    </div>
                @endif
                
                @if($customServiceRequest->ssl_transaction_id)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h4 class="font-medium text-gray-700 mb-2">SSL Payment Details</h4>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SSL Transaction ID</label>
                                <p class="text-sm font-mono text-gray-900">{{ $customServiceRequest->ssl_transaction_id }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Admin Notes -->
    @if($customServiceRequest->admin_notes)
        <div class="bg-white rounded-lg shadow dashboard-card card-blue">
            <div class="card-header-themed p-4 rounded-t-lg">
                <h3 class="text-lg font-bold section-header mb-0">Admin Notes</h3>
            </div>
            <div class="p-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-sticky-note text-yellow-600 mt-1 mr-3"></i>
                        <p class="text-gray-900">{{ $customServiceRequest->admin_notes }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection