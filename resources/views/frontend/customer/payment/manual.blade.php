@php $title = 'Manual Payment'; @endphp
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
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Manual Bank Transfer</h1>
                <p class="text-sm text-gray-600">Submit payment proof for {{ $type === 'package' ? 'Package' : 'Service' }} Order #{{ $order->id }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('customer.payment.options', [$type, $order->id]) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700 shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Payment Options
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
            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order ID</label>
                    <p class="text-lg font-semibold text-gray-900">#{{ $order->id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order Type</label>
                    <p class="text-sm text-gray-900">{{ $type === 'package' ? 'Package Order' : 'Service Order' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                    <p class="text-2xl font-bold text-green-600">BDT {{ number_format($order->total_amount, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                        Payment Pending
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bank Details -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Our Bank Details</h3>
        </div>
        <div class="p-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-2">Transfer Instructions</h4>
                        <p class="text-blue-800 text-sm">Please transfer the exact amount to the following bank account and submit the payment proof below.</p>
                    </div>
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                        <p class="text-lg font-semibold text-gray-900">Dutch Bangla Bank Limited</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                        <p class="text-lg font-semibold text-gray-900">Wise Dynamic Solutions</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                        <p class="text-lg font-semibold text-gray-900">1234567890123456</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                        <p class="text-lg font-semibold text-gray-900">Dhanmondi Branch</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($existingPayment)
        <!-- Existing Payment Status -->
        <div class="bg-white rounded-lg shadow dashboard-card card-blue">
            <div class="card-header-themed p-4 rounded-t-lg">
                <h3 class="text-lg font-bold section-header mb-0">Payment Status</h3>
            </div>
            <div class="p-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-yellow-600 mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-yellow-900">Payment Submitted</h4>
                                <p class="text-yellow-800 text-sm">Your payment proof has been submitted and is under review.</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-{{ $existingPayment->getStatusColorClass() }}-100 text-{{ $existingPayment->getStatusColorClass() }}-800">
                            {{ $existingPayment->getStatusLabel() }}
                        </span>
                    </div>
                    
                    <div class="mt-4 grid md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Bank:</span>
                            <span class="text-gray-900">{{ $existingPayment->bank_name }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Account:</span>
                            <span class="text-gray-900">{{ $existingPayment->account_number }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Submitted:</span>
                            <span class="text-gray-900">{{ $existingPayment->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                    
                    @if($existingPayment->admin_notes)
                        <div class="mt-4 p-3 bg-white rounded border">
                            <span class="font-medium text-gray-700">Admin Notes:</span>
                            <p class="text-gray-900 mt-1">{{ $existingPayment->admin_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Form -->
    @if(!$existingPayment || $existingPayment->status === 'rejected')
        <div class="bg-white rounded-lg shadow dashboard-card card-blue">
            <div class="card-header-themed p-4 rounded-t-lg">
                <h3 class="text-lg font-bold section-header mb-0">Submit Payment Proof</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('customer.payment.manual.submit', [$type, $order->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Bank Name *</label>
                            <input type="text" id="bank_name" name="bank_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('bank_name', $existingPayment->bank_name ?? '') }}" required placeholder="e.g., Dutch Bangla Bank">
                            @error('bank_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Your Account Number *</label>
                            <input type="text" id="account_number" name="account_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('account_number', $existingPayment->account_number ?? '') }}" required placeholder="Your bank account number">
                            @error('account_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">Transaction ID (Optional)</label>
                            <input type="text" id="transaction_id" name="transaction_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('transaction_id', $existingPayment->transaction_id ?? '') }}" placeholder="Bank transaction reference number">
                            @error('transaction_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="payment_screenshot" class="block text-sm font-medium text-gray-700 mb-2">Payment Screenshot *</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="payment_screenshot" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a screenshot</span>
                                            <input id="payment_screenshot" name="payment_screenshot" type="file" class="sr-only" accept="image/*" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                </div>
                            </div>
                            @error('payment_screenshot')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-yellow-900 mb-2">Important Notes</h4>
                                <ul class="text-yellow-800 text-sm space-y-1">
                                    <li>• Please ensure the screenshot clearly shows the transaction details</li>
                                    <li>• The amount transferred must match exactly: <strong>BDT {{ number_format($order->total_amount, 2) }}</strong></li>
                                    <li>• Payment verification may take 1-2 business days</li>
                                    <li>• You will receive an email notification once payment is verified</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-6 border-t mt-6">
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-semibold">
                            <i class="fas fa-upload mr-2"></i>Submit Payment Proof
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('payment_screenshot');
    const fileLabel = fileInput.parentElement;
    
    fileInput.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            fileLabel.querySelector('span').textContent = fileName;
        }
    });
});
</script>
@endpush
@endsection