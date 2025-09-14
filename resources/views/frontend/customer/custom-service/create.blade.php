@php $title = 'Custom Service Request'; @endphp
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
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Custom Service Request</h1>
                <p class="text-sm text-gray-600">Request custom marketing or web/app development services</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('customer.custom-service.index') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700 shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Requests
                </a>
            </div>
        </div>
    </div>

    <!-- Service Request Form -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Service Request Details</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('customer.custom-service.store') }}" method="POST" id="customServiceForm">
                @csrf
                
                <!-- Service Type Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Service Type *</label>
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Marketing Services -->
                        <div class="border border-gray-300 rounded-lg p-4 cursor-pointer service-type-card" data-type="marketing">
                            <label class="cursor-pointer">
                                <input type="radio" name="service_type" value="marketing" class="sr-only service-type-radio" {{ old('service_type') === 'marketing' ? 'checked' : '' }}>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Marketing Services</h4>
                                        <p class="text-sm text-gray-500">Social media, content, advertising</p>
                                    </div>
                                    <div class="text-pink-600">
                                        <i class="fas fa-bullhorn text-2xl"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Web/App Development -->
                        <div class="border border-gray-300 rounded-lg p-4 cursor-pointer service-type-card" data-type="web_app">
                            <label class="cursor-pointer">
                                <input type="radio" name="service_type" value="web_app" class="sr-only service-type-radio" {{ old('service_type') === 'web_app' ? 'checked' : '' }}>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Web/App Development</h4>
                                        <p class="text-sm text-gray-500">Websites, web apps, mobile apps</p>
                                    </div>
                                    <div class="text-blue-600">
                                        <i class="fas fa-code text-2xl"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('service_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Service Items -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-gray-700">Service Items *</label>
                        <button type="button" id="addServiceItem" class="inline-flex items-center px-3 py-1 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                    </div>
                    
                    <div id="serviceItemsContainer">
                        <!-- Service items will be added here dynamically -->
                    </div>
                    
                    <!-- Total Amount Display -->
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-700">Total Amount:</span>
                            <span id="totalAmount" class="text-xl font-bold text-green-600">BDT 0.00</span>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Payment Method *</label>
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Balance Payment -->
                        <div class="border border-gray-300 rounded-lg p-4 cursor-pointer payment-method-card" data-method="balance">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="balance" class="sr-only payment-method-radio" {{ old('payment_method') === 'balance' ? 'checked' : '' }}>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Account Balance</h4>
                                        <p class="text-sm text-gray-500">Pay using your account balance</p>
                                        <p class="text-xs text-green-600 mt-1">Available: BDT {{ number_format(auth()->user()->balance, 2) }}</p>
                                    </div>
                                    <div class="text-green-600">
                                        <i class="fas fa-wallet text-2xl"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        <!-- SSL Payment -->
                        <div class="border border-gray-300 rounded-lg p-4 cursor-pointer payment-method-card" data-method="ssl">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="ssl" class="sr-only payment-method-radio" {{ old('payment_method') === 'ssl' ? 'checked' : '' }}>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">SSL Payment</h4>
                                        <p class="text-sm text-gray-500">Pay with credit/debit card</p>
                                        <p class="text-xs text-blue-600 mt-1">Secure online payment</p>
                                    </div>
                                    <div class="text-blue-600">
                                        <i class="fas fa-credit-card text-2xl"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('payment_method')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition" id="submitButton" disabled>
                        <i class="fas fa-paper-plane mr-2"></i>Submit Service Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Service Item Templates -->
<template id="marketingItemTemplate">
    <div class="service-item border border-gray-200 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-medium text-gray-700">Marketing Service Item</h4>
            <button type="button" class="remove-item text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Service Name *</label>
                <input type="text" name="items[INDEX][service_name]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount (BDT) *</label>
                <input type="number" name="items[INDEX][amount]" class="amount-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required min="1" step="0.01">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                <input type="text" name="items[INDEX][platform]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Facebook, Instagram">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration (Days)</label>
                <input type="number" name="items[INDEX][duration_days]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" max="365" placeholder="e.g., 7">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Post Link</label>
                <input type="url" name="items[INDEX][post_link]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="items[INDEX][description]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Additional details about the service..."></textarea>
            </div>
        </div>
    </div>
</template>

<template id="webAppItemTemplate">
    <div class="service-item border border-gray-200 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-medium text-gray-700">Web/App Development Item</h4>
            <button type="button" class="remove-item text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Service Name *</label>
                <input type="text" name="items[INDEX][service_name]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount (BDT) *</label>
                <input type="number" name="items[INDEX][amount]" class="amount-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required min="1" step="0.01">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Domain Name</label>
                <input type="text" name="items[INDEX][domain_name]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., example.com">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration (Months)</label>
                <input type="number" name="items[INDEX][duration_months]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" max="60" placeholder="e.g., 6">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="items[INDEX][description]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Additional details about the service..."></textarea>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 0;
    let currentServiceType = null;
    
    const serviceTypeCards = document.querySelectorAll('.service-type-card');
    const serviceTypeRadios = document.querySelectorAll('.service-type-radio');
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
    const paymentMethodRadios = document.querySelectorAll('.payment-method-radio');
    const addServiceItemBtn = document.getElementById('addServiceItem');
    const serviceItemsContainer = document.getElementById('serviceItemsContainer');
    const submitButton = document.getElementById('submitButton');
    
    // Handle service type selection
    serviceTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            const type = this.dataset.type;
            const radio = this.querySelector('.service-type-radio');
            
            // Clear all selections
            serviceTypeCards.forEach(c => c.classList.remove('border-blue-500', 'bg-blue-50'));
            serviceTypeRadios.forEach(r => r.checked = false);
            
            // Select current
            this.classList.add('border-blue-500', 'bg-blue-50');
            radio.checked = true;
            
            // Clear existing items if service type changed
            if (currentServiceType !== type) {
                serviceItemsContainer.innerHTML = '';
                itemIndex = 0;
                updateTotal();
            }
            
            currentServiceType = type;
            addServiceItemBtn.disabled = false;
            addServiceItemBtn.style.opacity = '1';
            addServiceItemBtn.style.cursor = 'pointer';
            
            // Add first item automatically
            if (serviceItemsContainer.children.length === 0) {
                addServiceItem();
            }
        });
    });
    
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
            
            updateSubmitButton();
        });
    });
    
    // Add service item
    addServiceItemBtn.addEventListener('click', function(e) {
        e.preventDefault();
        addServiceItem();
    });
    
    function addServiceItem() {
        if (!currentServiceType) {
            alert('Please select a service type first.');
            return;
        }
        
        const templateId = currentServiceType === 'marketing' ? 'marketingItemTemplate' : 'webAppItemTemplate';
        const template = document.getElementById(templateId);
        
        if (!template) {
            console.error('Template not found:', templateId);
            return;
        }
        
        const clone = template.content.cloneNode(true);
        
        // Replace INDEX with actual index in all elements
        const elements = clone.querySelectorAll('*');
        elements.forEach(element => {
            if (element.innerHTML) {
                element.innerHTML = element.innerHTML.replace(/INDEX/g, itemIndex);
            }
            // Update name attributes
            if (element.name) {
                element.name = element.name.replace(/INDEX/g, itemIndex);
            }
        });
        
        // Add event listeners
        const removeBtn = clone.querySelector('.remove-item');
        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                this.closest('.service-item').remove();
                updateTotal();
                updateSubmitButton();
            });
        }
        
        const amountInput = clone.querySelector('.amount-input');
        if (amountInput) {
            amountInput.addEventListener('input', updateTotal);
        }
        
        serviceItemsContainer.appendChild(clone);
        itemIndex++;
        
        updateTotal();
        updateSubmitButton();
    }
    
    function updateTotal() {
        const amountInputs = document.querySelectorAll('.amount-input');
        let total = 0;
        
        amountInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });
        
        document.getElementById('totalAmount').textContent = 'BDT ' + total.toFixed(2);
    }
    
    function updateSubmitButton() {
        const hasServiceType = document.querySelector('.service-type-radio:checked');
        const hasPaymentMethod = document.querySelector('.payment-method-radio:checked');
        const hasItems = serviceItemsContainer.children.length > 0;
        
        submitButton.disabled = !(hasServiceType && hasPaymentMethod && hasItems);
    }
    
    // Initialize button as disabled
    addServiceItemBtn.disabled = true;
    addServiceItemBtn.style.opacity = '0.5';
    addServiceItemBtn.style.cursor = 'not-allowed';
    
    // Initialize based on old input
    const checkedServiceType = document.querySelector('.service-type-radio:checked');
    if (checkedServiceType) {
        const card = checkedServiceType.closest('.service-type-card');
        card.classList.add('border-blue-500', 'bg-blue-50');
        currentServiceType = checkedServiceType.value;
        addServiceItemBtn.disabled = false;
        addServiceItemBtn.style.opacity = '1';
        addServiceItemBtn.style.cursor = 'pointer';
    }
    
    const checkedPaymentMethod = document.querySelector('.payment-method-radio:checked');
    if (checkedPaymentMethod) {
        const card = checkedPaymentMethod.closest('.payment-method-card');
        card.classList.add('border-blue-500', 'bg-blue-50');
    }
    
    // Add event listener to amount inputs for total calculation
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('amount-input')) {
            updateTotal();
        }
    });
});
</script>
@endpush
@endsection