<?php $title = 'Payment Options'; ?>


<?php $__env->startSection('content'); ?>
<div class="w-full px-4 md:px-6 space-y-6">
    <?php if(session('success')): ?>
        <div class="p-4 rounded-lg bg-green-100 text-green-700 border border-green-200">
            <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="p-4 rounded-lg bg-red-100 text-red-700 border border-red-200">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Payment Options</h1>
                <p class="text-sm text-gray-600">Choose your preferred payment method for <?php echo e($type === 'package' ? 'Package' : 'Service'); ?> Order #<?php echo e($order->id); ?></p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('customer.' . ($type === 'package' ? 'orders' : 'service-orders') . '.show', $order)); ?>" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700 shadow">
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
                    <p class="text-lg font-semibold text-gray-900"><?php echo e($type === 'package' ? 'Package Order' : 'Service Order'); ?></p>
                    <?php if(isset($recentPayments) && count($recentPayments) > 0): ?>
                        <!-- Recent Payments -->
                        <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-history text-blue-600 mr-2"></i>
                                Recent Payments
                            </h3>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-check text-green-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">৳<?php echo e(number_format($payment->amount, 2)); ?></p>
                                                <p class="text-sm text-gray-500"><?php echo e($payment->created_at->format('M d, Y H:i')); ?></p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                <?php echo e(ucfirst($payment->payment_method)); ?>

                                            </span>
                                            <?php if($payment->transaction_id): ?>
                                                <p class="text-xs text-gray-500 mt-1">TXN: <?php echo e($payment->transaction_id); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order ID</label>
                    <p class="text-lg font-semibold text-gray-900">#<?php echo e($order->id); ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                    <p class="text-2xl font-bold text-green-600">৳<?php echo e(number_format($paymentStats['total_amount'], 2)); ?></p>
                </div>
                <?php if($type === 'package'): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Package</label>
                        <p class="text-sm text-gray-900"><?php echo e($order->package_name ?? 'N/A'); ?></p>
                    </div>
                <?php else: ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <p class="text-sm text-gray-900"><?php echo e($order->service_name ?? 'N/A'); ?></p>
                    </div>
                <?php endif; ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        <?php echo e(ucfirst($order->status)); ?>

                    </span>
                </div>
                <?php if($paymentStats['is_partial_payment']): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paid Amount</label>
                        <p class="text-lg font-semibold text-blue-600">৳<?php echo e(number_format($paymentStats['paid_amount'], 2)); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Remaining</label>
                        <p class="text-lg font-bold text-orange-600">৳<?php echo e(number_format($paymentStats['remaining_amount'], 2)); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if($paymentStats['is_partial_payment']): ?>
                <!-- Payment Progress -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-blue-900">Payment Progress</span>
                        <span class="text-sm text-blue-700"><?php echo e($paymentStats['payment_progress']); ?>% Complete</span>
                    </div>
                    <div class="w-full bg-blue-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: <?php echo e($paymentStats['payment_progress']); ?>%"></div>
                    </div>
                    <p class="text-xs text-blue-700 mt-2">
                        You have paid ৳<?php echo e(number_format($paymentStats['paid_amount'], 2)); ?> out of ৳<?php echo e(number_format($paymentStats['total_amount'], 2)); ?>

                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payment Amount Selection -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Payment Amount</h3>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-2">Amount to Pay (BDT)</label>
                <input type="number" id="payment_amount" name="payment_amount" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    value="<?php echo e($order->amount); ?>" 
                    min="1" 
                    max="<?php echo e($order->amount); ?>" 
                    step="0.01" 
                    required>
                <p class="text-sm text-gray-500 mt-1">You can pay any amount up to the full order amount</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-2">Payment Options</h4>
                        <p class="text-blue-800 text-sm">Choose your preferred payment method below. You can pay the full amount or make a partial payment.</p>
                    </div>
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
                <div class="border-2 border-blue-200 rounded-xl p-6 text-center hover:border-blue-400 hover:shadow-lg transition-all duration-300 bg-gradient-to-br from-blue-50 to-white">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-credit-card text-2xl text-blue-600"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Online Payment</h4>
                        <p class="text-gray-600 mb-4">Pay instantly with credit/debit card, mobile banking, or internet banking</p>
                        
                        <!-- Payment Method Icons -->
                        <div class="flex justify-center items-center space-x-3 mb-4">
                            <div class="bg-white p-2 rounded-lg shadow-sm border">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAzMiAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjMyIiBoZWlnaHQ9IjIwIiByeD0iNCIgZmlsbD0iIzAwNTFBNSIvPgo8cGF0aCBkPSJNMTMuNSA3SDEwLjVWMTNIMTMuNVY3WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTE1IDEwQzE1IDguMzQzMTUgMTYuMzQzMSA3IDE4IDdDMTkuNjU2OSA3IDIxIDguMzQzMTUgMjEgMTBDMjEgMTEuNjU2OSAxOS42NTY5IDEzIDE4IDEzQzE2LjM0MzEgMTMgMTUgMTEuNjU2OSAxNSAxMFoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=" alt="Visa" class="w-8 h-5">
                            </div>
                            <div class="bg-white p-2 rounded-lg shadow-sm border">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAzMiAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjMyIiBoZWlnaHQ9IjIwIiByeD0iNCIgZmlsbD0iI0VCMDAxQiIvPgo8Y2lyY2xlIGN4PSIxMiIgY3k9IjEwIiByPSI1IiBmaWxsPSIjRkY1RjAwIi8+CjxjaXJjbGUgY3g9IjIwIiBjeT0iMTAiIHI9IjUiIGZpbGw9IiNGRkY1RjAiLz4KPC9zdmc+" alt="Mastercard" class="w-8 h-5">
                            </div>
                            <div class="bg-white p-2 rounded-lg shadow-sm border">
                                <span class="text-xs font-bold text-green-600">bKash</span>
                            </div>
                            <div class="bg-white p-2 rounded-lg shadow-sm border">
                                <span class="text-xs font-bold text-red-600">Rocket</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6 space-y-3">
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-shield-alt text-green-600"></i>
                            <span class="text-sm text-gray-700 font-medium">256-bit SSL Encryption</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-bolt text-yellow-500"></i>
                            <span class="text-sm text-gray-700 font-medium">Instant Processing</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-check-circle text-green-600"></i>
                            <span class="text-sm text-gray-700 font-medium">Auto Confirmation</span>
                        </div>
                    </div>
                    
                    <form method="POST" action="<?php echo e(route('customer.payment.ssl', [$type, $order->id])); ?>" id="ssl-payment-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="payment_amount" id="ssl_payment_amount" value="<?php echo e($order->amount); ?>">
                        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-credit-card mr-2"></i>Pay Now - BDT <span id="ssl-amount-display"><?php echo e(number_format($order->amount, 2)); ?></span>
                        </button>
                    </form>
                    
                    <p class="text-xs text-gray-500 mt-3">
                        <i class="fas fa-lock mr-1"></i>Secured by SSLCommerz
                    </p>
                </div>

                <!-- Manual Bank Transfer -->
                <div class="border-2 border-green-200 rounded-xl p-6 text-center hover:border-green-400 hover:shadow-lg transition-all duration-300 bg-gradient-to-br from-green-50 to-white">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-university text-2xl text-green-600"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Bank Transfer</h4>
                        <p class="text-gray-600 mb-4">Transfer directly to our bank account and upload payment proof</p>
                        
                        <!-- Bank Icons -->
                        <div class="flex justify-center items-center space-x-2 mb-4">
                            <div class="bg-white p-2 rounded-lg shadow-sm border">
                                <span class="text-xs font-bold text-blue-600">DBBL</span>
                            </div>
                            <div class="bg-white p-2 rounded-lg shadow-sm border">
                                <span class="text-xs font-bold text-green-600">BRAC</span>
                            </div>
                            <div class="bg-white p-2 rounded-lg shadow-sm border">
                                <span class="text-xs font-bold text-red-600">City</span>
                            </div>
                            <div class="bg-white p-2 rounded-lg shadow-sm border">
                                <span class="text-xs font-bold text-purple-600">EBL</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6 space-y-3">
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-clock text-orange-500"></i>
                            <span class="text-sm text-gray-700 font-medium">1-2 Business Days</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-upload text-blue-600"></i>
                            <span class="text-sm text-gray-700 font-medium">Upload Payment Proof</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-user-check text-green-600"></i>
                            <span class="text-sm text-gray-700 font-medium">Manual Verification</span>
                        </div>
                    </div>
                    
                    <form method="GET" action="<?php echo e(route('customer.payment.manual', [$type, $order->id])); ?>" id="manual-payment-form">
                        <input type="hidden" name="payment_amount" id="manual_payment_amount" value="<?php echo e($order->amount); ?>">
                        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-university mr-2"></i>Bank Transfer - BDT <span id="manual-amount-display"><?php echo e(number_format($order->amount, 2)); ?></span>
                        </button>
                    </form>
                    
                    <p class="text-xs text-gray-500 mt-3">
                        <i class="fas fa-info-circle mr-1"></i>No additional charges
                    </p>
                </div>
            </div>
            
            <!-- Payment Security Notice -->
            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-center space-x-4 text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-shield-alt text-green-600"></i>
                        <span>SSL Secured</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-lock text-blue-600"></i>
                        <span>256-bit Encryption</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span>PCI Compliant</span>
                    </div>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentAmountInput = document.getElementById('payment_amount');
    const sslAmountInput = document.getElementById('ssl_payment_amount');
    const manualAmountInput = document.getElementById('manual_payment_amount');
    const sslAmountDisplay = document.getElementById('ssl-amount-display');
    const manualAmountDisplay = document.getElementById('manual-amount-display');
    
    // Update hidden fields and display amounts when payment amount changes
    paymentAmountInput.addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        const formattedAmount = new Intl.NumberFormat('en-BD', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
        
        sslAmountInput.value = amount;
        manualAmountInput.value = amount;
        sslAmountDisplay.textContent = formattedAmount;
        manualAmountDisplay.textContent = formattedAmount;
        
        // Update button states based on amount
        updateButtonStates(amount);
    });
    
    function updateButtonStates(amount) {
        const maxAmount = parseFloat(paymentAmountInput.max);
        const sslButton = document.querySelector('#ssl-payment-form button');
        const manualButton = document.querySelector('#manual-payment-form button');
        
        if (amount <= 0 || amount > maxAmount) {
            sslButton.disabled = true;
            manualButton.disabled = true;
            sslButton.classList.add('opacity-50', 'cursor-not-allowed');
            manualButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            sslButton.disabled = false;
            manualButton.disabled = false;
            sslButton.classList.remove('opacity-50', 'cursor-not-allowed');
            manualButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Validate payment amount before form submission
    document.getElementById('ssl-payment-form').addEventListener('submit', function(e) {
        const amount = parseFloat(paymentAmountInput.value);
        const maxAmount = parseFloat(paymentAmountInput.max);
        
        if (amount <= 0 || amount > maxAmount) {
            e.preventDefault();
            showAlert('Please enter a valid payment amount between 1 and ' + maxAmount + ' BDT.', 'error');
            return false;
        }
        
        // Show loading state
        const button = this.querySelector('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
        button.disabled = true;
        
        // Re-enable button after 10 seconds (in case of redirect failure)
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 10000);
    });
    
    document.getElementById('manual-payment-form').addEventListener('submit', function(e) {
        const amount = parseFloat(paymentAmountInput.value);
        const maxAmount = parseFloat(paymentAmountInput.max);
        
        if (amount <= 0 || amount > maxAmount) {
            e.preventDefault();
            showAlert('Please enter a valid payment amount between 1 and ' + maxAmount + ' BDT.', 'error');
            return false;
        }
    });
    
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
    
    // Initialize button states
    updateButtonStates(parseFloat(paymentAmountInput.value));
    
    // Add smooth scroll to payment methods when amount is changed
    paymentAmountInput.addEventListener('focus', function() {
        document.querySelector('.grid.md\\:grid-cols-2').scrollIntoView({
            behavior: 'smooth',
            block: 'nearest'
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/customer/payment/options.blade.php ENDPATH**/ ?>