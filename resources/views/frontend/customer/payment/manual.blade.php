@php $title = 'Manual Payment'; @endphp
@extends('layouts.customer')

@push('styles')
<style>
    .payment-step {
        transition: all 0.3s ease;
    }
    .payment-step:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .bank-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .copy-btn {
        transition: all 0.2s ease;
    }
    .copy-btn:hover {
        transform: scale(1.05);
    }
    .progress-bar {
        background: linear-gradient(90deg, #4CAF50 0%, #45a049 100%);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <!-- Session Messages -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="w-full px-4 sm:px-6 lg:px-8 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.payment.options', [$type, $order->id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Payment Options
                    </a>
                    <div class="h-8 w-px bg-gray-300"></div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-university text-blue-600 mr-2"></i>
                        Manual Bank Transfer
                    </h1>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Payment Method</div>
                    <div class="text-lg font-semibold text-blue-600">Manual Bank Transfer</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Indicator -->
    <div class="w-full px-4 sm:px-6 lg:px-8 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-700">Payment Progress</span>
                <span class="text-sm text-gray-500">Step 1 of 3</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="progress-bar h-2 rounded-full" style="width: 33%"></div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-500">
                <span>Transfer Details</span>
                <span>Make Payment</span>
                <span>Confirmation</span>
            </div>
        </div>
    </div>

    <!-- Main Content - Full Width Layout -->
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-6">

                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-receipt text-green-600 mr-2"></i>
                        Order Summary
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Type:</span>
                                <span class="font-medium capitalize">{{ $type === 'package' ? 'Package' : 'Service' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order ID:</span>
                                <span class="font-medium">#{{ $order->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                    Payment Pending
                                </span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Amount:</span>
                                <span class="font-bold text-lg text-green-600">৳{{ number_format($order->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created:</span>
                                <span class="font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Amount -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave text-blue-600 mr-2"></i>
                        Payment Amount
                    </h2>
                    <div class="mb-6">
                        <label for="manual_payment_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Enter Payment Amount (BDT)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">৳</span>
                            </div>
                            <input type="number" 
                                   id="manual_payment_amount" 
                                   name="payment_amount"
                                   class="block w-full pl-8 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                                   placeholder="0.00"
                                   value="{{ $paymentAmount }}"
                                   min="1" 
                                   max="{{ $order->amount }}" 
                                   step="0.01" 
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">BDT</span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Maximum amount: ৳{{ number_format($order->amount, 2) }}
                        </p>
                        @if($paymentAmount < $order->amount)
                            <p class="text-sm text-gray-500">Default: Partial payment</p>
                        @endif
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-list-ol text-purple-600 mr-2"></i>
                        Payment Instructions
                    </h2>
                    <div class="space-y-4">
                        <div class="payment-step bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Transfer Money</h3>
                                    <p class="text-gray-600 mt-1">Use the bank details provided to transfer your payment amount.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="payment-step bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Keep Transaction Receipt</h3>
                                    <p class="text-gray-600 mt-1">Save your bank transfer receipt or transaction ID for verification.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="payment-step bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-900">Submit Payment Details</h3>
                                    <p class="text-gray-600 mt-1">Upload your payment receipt using the form below.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Bank Details Card -->
                <div class="bank-card rounded-lg shadow-lg p-6 text-white">
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-university mr-2"></i>
                        Bank Details
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm opacity-90 mb-1">Bank Name</label>
                            <div class="flex items-center justify-between bg-white bg-opacity-20 rounded-lg p-3">
                                <span class="font-semibold">Dutch Bangla Bank Limited</span>
                                <button onclick="copyToClipboard('Dutch Bangla Bank Limited')" class="copy-btn text-white hover:text-yellow-300">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm opacity-90 mb-1">Account Name</label>
                            <div class="flex items-center justify-between bg-white bg-opacity-20 rounded-lg p-3">
                                <span class="font-semibold">Wise Dynamic Solutions</span>
                                <button onclick="copyToClipboard('Wise Dynamic Solutions')" class="copy-btn text-white hover:text-yellow-300">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm opacity-90 mb-1">Account Number</label>
                            <div class="flex items-center justify-between bg-white bg-opacity-20 rounded-lg p-3">
                                <span class="font-semibold font-mono">1234567890123456</span>
                                <button onclick="copyToClipboard('1234567890123456')" class="copy-btn text-white hover:text-yellow-300">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm opacity-90 mb-1">Branch</label>
                            <div class="flex items-center justify-between bg-white bg-opacity-20 rounded-lg p-3">
                                <span class="font-semibold">Dhanmondi Branch</span>
                                <button onclick="copyToClipboard('Dhanmondi Branch')" class="copy-btn text-white hover:text-yellow-300">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Payment Amount:</span>
                            <span class="font-medium" id="summary-amount">৳{{ number_format($paymentAmount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Processing Fee:</span>
                            <span class="font-medium text-green-600">Free</span>
                        </div>
                        <hr class="my-3">
                        <div class="flex justify-between font-semibold">
                            <span>Total to Transfer:</span>
                            <span class="text-lg text-blue-600" id="summary-total">৳{{ number_format($paymentAmount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h4 class="font-semibold text-yellow-800 mb-2 flex items-center">
                        <i class="fas fa-question-circle mr-2"></i>
                        Need Help?
                    </h4>
                    <p class="text-sm text-yellow-700 mb-3">
                        Having trouble with your bank transfer? Our support team is here to help.
                    </p>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center text-yellow-700">
                            <i class="fas fa-phone mr-2"></i>
                            <span>+880 1234-567890</span>
                        </div>
                        <div class="flex items-center text-yellow-700">
                            <i class="fas fa-envelope mr-2"></i>
                            <span>support@wisedynamic.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Width Payment Status Section -->
    @if($existingPayment)
        <div class="w-full px-4 sm:px-6 lg:px-8 mt-6">
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
        </div>
    @endif

    <!-- Full Width Payment Form Section -->
    @if(!$existingPayment || $existingPayment->status === 'rejected')
        <div class="w-full px-4 sm:px-6 lg:px-8 mt-6">
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
                                <div id="upload-area" class="mt-1 flex justify-center px-4 sm:px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition cursor-pointer">
                                    <div class="space-y-1 text-center w-full">
                                        <div id="upload-icon">
                                            <svg class="mx-auto h-8 sm:h-12 w-8 sm:w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-center items-center text-sm text-gray-600">
                                            <button type="button" id="upload-button" class="relative cursor-pointer bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-2 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 mb-2 sm:mb-0 sm:mr-2">
                                                <span id="upload-text">Choose Screenshot</span>
                                                <input id="payment_screenshot" name="payment_screenshot" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" required>
                                            </button>
                                            <p class="text-gray-500">or drag and drop here</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                        <div id="file-info" class="hidden mt-2 p-2 bg-green-50 border border-green-200 rounded text-sm text-green-700">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            <span id="file-name"></span>
                                        </div>
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
                                        <li>• The amount transferred must match your entered amount</li>
                                        <li>• Payment verification may take 1-2 business days</li>
                                        <li>• You will receive an email notification once payment is verified</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 transform hover:scale-105 font-semibold">
                                <i class="fas fa-upload mr-2"></i>Submit Payment Proof
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'Copied to clipboard!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Payment amount validation and summary update
    const paymentAmountInput = document.getElementById('manual_payment_amount');
    const summaryAmount = document.getElementById('summary-amount');
    const summaryTotal = document.getElementById('summary-total');
    const proceedBtn = document.getElementById('proceed-btn');
    
    if (paymentAmountInput) {
        const maxAmount = parseFloat(paymentAmountInput.max);
        
        function updateSummary(amount) {
            const formattedAmount = new Intl.NumberFormat('en-BD', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
            
            if (summaryAmount) summaryAmount.textContent = '৳' + formattedAmount;
            if (summaryTotal) summaryTotal.textContent = '৳' + formattedAmount;
        }
        
        function updateButtonState(amount) {
            if (proceedBtn) {
                if (amount > 0 && amount <= maxAmount) {
                    proceedBtn.disabled = false;
                    proceedBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    proceedBtn.disabled = true;
                    proceedBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        }
        
        paymentAmountInput.addEventListener('input', function(e) {
            const amount = parseFloat(e.target.value) || 0;
            
            // Update summary
            updateSummary(amount);
            updateButtonState(amount);
            
            // Remove any existing validation classes
            e.target.classList.remove('border-red-500', 'border-green-500');
            
            if (amount > 0 && amount <= maxAmount) {
                e.target.classList.add('border-green-500');
            } else if (amount > maxAmount) {
                e.target.classList.add('border-red-500');
            }
        });
        
        // Initialize
        const initialAmount = parseFloat(paymentAmountInput.value) || 0;
        updateSummary(initialAmount);
        updateButtonState(initialAmount);
    }
    
    // File upload functionality (if exists)
    const fileInput = document.getElementById('payment_screenshot');
    const uploadArea = document.getElementById('upload-area');
    const uploadIcon = document.getElementById('upload-icon');
    const uploadText = document.getElementById('upload-text');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    
    // Handle file selection
    function handleFileSelect(file) {
        if (file) {
            // Validate file type
            if (!file.type.match('image.*')) {
                showAlert('Please select an image file (PNG, JPG, JPEG)', 'error');
                return;
            }
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showAlert('File size must be less than 2MB', 'error');
                return;
            }
            
            // Update UI
            if (uploadText) uploadText.textContent = 'Change Screenshot';
            if (fileName) fileName.textContent = file.name;
            if (fileInfo) fileInfo.classList.remove('hidden');
            if (uploadArea) {
                uploadArea.classList.add('border-green-400', 'bg-green-50');
                uploadArea.classList.remove('border-gray-300');
            }
            
            // Hide upload icon and show success
            if (uploadIcon) {
                uploadIcon.innerHTML = '<i class="fas fa-check-circle text-green-500 text-2xl sm:text-4xl"></i>';
            }
        }
    }
    
    // File input change event
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            handleFileSelect(file);
        });
    }
    
    // Click on upload area
    if (uploadArea) {
        uploadArea.addEventListener('click', function(e) {
            if (e.target !== fileInput) {
                fileInput.click();
            }
        });
        
        // Drag and drop functionality
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('border-blue-400', 'bg-blue-50');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });
        
        // Mobile touch support
        uploadArea.addEventListener('touchstart', function(e) {
            uploadArea.classList.add('border-blue-400');
        });
        
        uploadArea.addEventListener('touchend', function(e) {
            uploadArea.classList.remove('border-blue-400');
        });
    }
    
    // Form submission validation
    const form = document.querySelector('form');
    if (form && paymentAmountInput) {
        const maxAmount = parseFloat(paymentAmountInput.max);
        
        form.addEventListener('submit', function(e) {
            const amount = parseFloat(paymentAmountInput.value);
            
            if (amount <= 0 || amount > maxAmount) {
                e.preventDefault();
                showAlert('Please enter a valid payment amount between 1 and ' + maxAmount + ' BDT.', 'error');
                paymentAmountInput.focus();
                return false;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                submitBtn.disabled = true;
                
                // Re-enable button after 10 seconds (in case of redirect failure)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            }
        });
    }
    
    // Enhanced alert function
    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-md ${
            type === 'error' ? 'bg-red-100 text-red-700 border border-red-200' : 
            type === 'success' ? 'bg-green-100 text-green-700 border border-green-200' :
            'bg-blue-100 text-blue-700 border border-blue-200'
        }`;
        
        alertDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} mr-2"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentElement) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>
@endpush
@endsection