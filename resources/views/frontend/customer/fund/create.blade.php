@php $title = 'Add Funds'; @endphp
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
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Add Funds</h1>
                <p class="text-sm text-gray-600">Add money to your account balance</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('customer.fund.index') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700 shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Fund Management
                </a>
            </div>
        </div>
    </div>

    <!-- Fund Request Form -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Fund Request Details</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('customer.fund.store') }}" method="POST" enctype="multipart/form-data" id="fundRequestForm">
                @csrf
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Amount -->
                    <div class="md:col-span-2">
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (BDT) *</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required min="1" max="100000" step="0.01">
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Service Info -->
                    <div class="md:col-span-2">
                        <label for="service_info" class="block text-sm font-medium text-gray-700 mb-1">Service Information (Optional)</label>
                        <textarea name="service_info" id="service_info" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  placeholder="Describe what service you need or any additional information...">{{ old('service_info') }}</textarea>
                        @error('service_info')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Payment Method *</label>
                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- SSL Payment -->
                            <div class="border border-gray-300 rounded-lg p-4 hover:border-blue-500 transition cursor-pointer payment-method-card" data-method="ssl">
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="ssl" class="sr-only payment-method-radio" {{ old('payment_method') === 'ssl' ? 'checked' : '' }}>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">SSL Payment</h4>
                                            <p class="text-sm text-gray-500">Pay online with credit/debit card</p>
                                        </div>
                                        <div class="text-blue-600">
                                            <i class="fas fa-credit-card text-2xl"></i>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-green-600">
                                        <i class="fas fa-check mr-1"></i> Instant approval
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Manual Payment -->
                            <div class="border border-gray-300 rounded-lg p-4 hover:border-blue-500 transition cursor-pointer payment-method-card" data-method="manual">
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="manual" class="sr-only payment-method-radio" {{ old('payment_method') === 'manual' ? 'checked' : '' }}>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">Bank Transfer</h4>
                                            <p class="text-sm text-gray-500">Manual bank transfer</p>
                                        </div>
                                        <div class="text-green-600">
                                            <i class="fas fa-university text-2xl"></i>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-yellow-600">
                                        <i class="fas fa-clock mr-1"></i> Requires admin approval
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Manual Payment Fields -->
                    <div id="manualPaymentFields" class="md:col-span-2 hidden">
                        <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                            <h4 class="font-medium text-gray-900">Bank Transfer Details</h4>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1">Bank Name *</label>
                                    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           placeholder="e.g., Dutch Bangla Bank">
                                    @error('bank_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="account_number" class="block text-sm font-medium text-gray-700 mb-1">Account Number *</label>
                                    <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           placeholder="Your account number">
                                    @error('account_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="payment_screenshot" class="block text-sm font-medium text-gray-700 mb-1">Payment Screenshot *</label>
                                <input type="file" name="payment_screenshot" id="payment_screenshot" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">Upload a screenshot of your bank transfer receipt (JPG, PNG, GIF - Max 2MB)</p>
                                @error('payment_screenshot')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6 pt-6 border-t">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Fund Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
    const paymentMethodRadios = document.querySelectorAll('.payment-method-radio');
    const manualPaymentFields = document.getElementById('manualPaymentFields');
    
    // Handle payment method selection
    paymentMethodCards.forEach(card => {
        card.addEventListener('click', function() {
            const method = this.dataset.method;
            const radio = this.querySelector('.payment-method-radio');
            
            // Clear all selections
            paymentMethodCards.forEach(c => c.classList.remove('border-blue-500', 'bg-blue-50'));
            paymentMethodRadios.forEach(r => r.checked = false);
            
            // Select current
            this.classList.add('border-blue-500', 'bg-blue-50');
            radio.checked = true;
            
            // Show/hide manual payment fields
            if (method === 'manual') {
                manualPaymentFields.classList.remove('hidden');
            } else {
                manualPaymentFields.classList.add('hidden');
            }
        });
    });
    
    // Initialize based on old input
    const checkedRadio = document.querySelector('.payment-method-radio:checked');
    if (checkedRadio) {
        const card = checkedRadio.closest('.payment-method-card');
        card.classList.add('border-blue-500', 'bg-blue-50');
        
        if (checkedRadio.value === 'manual') {
            manualPaymentFields.classList.remove('hidden');
        }
    }
});
</script>
@endpush
@endsection