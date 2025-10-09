<?php $__env->startSection('content'); ?>
<div class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Order #<?php echo e($order->id); ?></h1>
            <a href="<?php echo e(route('customer.orders.index')); ?>" class="btn-outline-primary px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Back to Orders
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Order Summary -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Order Summary</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Package Details -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-3">Package Details</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-gray-700 font-medium">Package:</p>
                                    <p class="text-gray-900 mb-2"><?php echo e($order->package_name); ?></p>
                                    
                                    <p class="text-gray-700 font-medium">Amount:</p>
                                    <p class="text-gray-900 text-2xl font-bold mb-2">BDT <?php echo e(number_format($order->amount)); ?></p>
                                    
                                    <p class="text-gray-700 font-medium">Order Date:</p>
                                    <p class="text-gray-900 mb-2"><?php echo \App\Helpers\DateHelper::formatDateTime12Hour($order->created_at); ?></p>
                                    
                                    <p class="text-gray-700 font-medium">Status:</p>
                                    <p class="mb-2">
                                        <?php if($order->status === 'pending'): ?>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        <?php elseif($order->status === 'processing'): ?>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Processing
                                            </span>
                                        <?php elseif($order->status === 'completed'): ?>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        <?php elseif($order->status === 'accepted'): ?>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Accepted
                                            </span>
                                        <?php elseif($order->status === 'cancelled'): ?>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled
                                            </span>
                                        <?php endif; ?>
                                    </p>
                                    
                                    <?php if(($order->status === 'accepted' || $order->status === 'processing') && $order->payment_status !== 'paid'): ?>
                                        <div class="mt-4">
                                            <a href="<?php echo e(route('customer.payment.options', ['package', $order->id])); ?>" class="w-full inline-block text-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                                <i class="fas fa-credit-card mr-2"></i>Pay Now
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="mt-4">
                                        <p class="text-gray-700 font-medium">Payment Status:</p>
                                        <div class="bg-gray-50 p-3 rounded-lg mb-3">
                                            <div class="flex justify-between mb-1">
                                                <span>Total Amount:</span>
                                                <span class="font-bold">BDT <?php echo e(number_format($order->amount)); ?></span>
                                            </div>
                                            <div class="flex justify-between mb-1">
                                                <span>Paid Amount:</span>
                                                <span class="font-bold">BDT <?php echo e($order->payment_status === 'paid' ? number_format($order->amount) : number_format($order->paid_amount ?? 0)); ?></span>
                                            </div>
                                            <div class="flex justify-between mb-1">
                                                <span>Due Amount:</span>
                                                <span class="font-bold">BDT <?php echo e($order->payment_status === 'paid' ? '0' : number_format($order->due_amount ?? $order->amount)); ?></span>
                                            </div>
                                            <div class="flex justify-between mb-1">
                                                <span>Payment Status:</span>
                                                <span>
                                                    <?php if($order->payment_status === 'pending_verification'): ?>
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                            <i class="fas fa-clock mr-1"></i>Pending Verification
                                                        </span>
                                                    <?php elseif($order->payment_status === 'paid'): ?>
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Paid
                                                        </span>
                                                    <?php elseif(($order->paid_amount ?? 0) <= 0): ?>
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Not Paid
                                                        </span>
                                                    <?php elseif(($order->due_amount ?? $order->amount) <= 0): ?>
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Fully Paid
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            Partially Paid
                                                        </span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if($order->payment_method): ?>
                                        <div class="mt-4">
                                            <p class="text-gray-700 font-medium">Payment Method:</p>
                                            <div class="bg-blue-50 p-3 rounded-lg">
                                                <?php if($order->payment_method === 'SSL Payment'): ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-credit-card mr-2"></i>SSL Payment Gateway
                                                    </span>
                                                <?php elseif($order->payment_method === 'Manual Bank Transfer'): ?>
                                                    <div class="space-y-2">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-university mr-2"></i>Manual Bank Transfer
                                                        </span>
                                                        <?php if($order->payment_status === 'pending_verification'): ?>
                                                            <div class="mt-2 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                                                <div class="flex items-center">
                                                                    <i class="fas fa-clock text-orange-500 mr-2"></i>
                                                                    <span class="text-orange-800 font-medium">Payment Under Review</span>
                                                                </div>
                                                                <p class="text-orange-700 text-sm mt-1">
                                                                    Your payment proof has been submitted and is being verified by our admin team. 
                                                                    You will be notified once the verification is complete.
                                                                </p>
                                                                <?php if($order->manualPayment): ?>
                                                                    <div class="mt-2 text-sm text-orange-700">
                                                                        <strong>Submitted:</strong> <?php echo \App\Helpers\DateHelper::formatDateTime($order->manualPayment->created_at); ?><br>
                                                                        <strong>Amount:</strong> BDT <?php echo e(number_format($order->manualPayment->amount, 2)); ?>

                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                        <i class="fas fa-credit-card mr-2"></i><?php echo e($order->payment_method); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Customer Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-3">Customer Information</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-gray-700 font-medium">Name:</p>
                                    <p class="text-gray-900 mb-2"><?php echo e($order->full_name); ?></p>
                                    
                                    <p class="text-gray-700 font-medium">Email:</p>
                                    <p class="text-gray-900 mb-2"><?php echo e($order->email); ?></p>
                                    
                                    <p class="text-gray-700 font-medium">Phone:</p>
                                    <p class="text-gray-900 mb-2"><?php echo e($order->phone); ?></p>
                                    
                                    <?php if($order->company): ?>
                                        <p class="text-gray-700 font-medium">Company:</p>
                                        <p class="text-gray-900 mb-2"><?php echo e($order->company); ?></p>
                                    <?php endif; ?>
                                    
                                    <p class="text-gray-700 font-medium">Billing Address:</p>
                                    <p class="text-gray-900">
                                        <?php echo e($order->address_line1); ?><br>
                                        <?php if($order->address_line2): ?>
                                            <?php echo e($order->address_line2); ?><br>
                                        <?php endif; ?>
                                        <?php echo e($order->city); ?>, <?php echo e($order->state ?? ''); ?><br>
                                        <?php echo e($order->postal_code); ?>, <?php echo e($order->country); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Project Details -->
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-3">Project Details</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php if($order->website_name): ?>
                                        <div>
                                            <p class="text-gray-700 font-medium">Website/Business Name:</p>
                                            <p class="text-gray-900 mb-2"><?php echo e($order->website_name); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if($order->website_type): ?>
                                        <div>
                                            <p class="text-gray-700 font-medium">Website Type:</p>
                                            <p class="text-gray-900 mb-2"><?php echo e($order->website_type); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if($order->page_count): ?>
                                        <div>
                                            <p class="text-gray-700 font-medium">Page Count:</p>
                                            <p class="text-gray-900 mb-2"><?php echo e($order->page_count); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    
                                </div>
                                
                                <!-- Transaction History -->
                                <div class="mt-6">
                                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Transaction History</h3>
                                    <?php if(count($transactions) > 0): ?>
                                        <div class="overflow-x-auto bg-white rounded-lg shadow">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction #</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($transaction->transaction_number); ?></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo \App\Helpers\DateHelper::formatDateOnly($transaction->created_at); ?></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BDT <?php echo e(number_format($transaction->amount)); ?></td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                    <?php echo e(ucfirst($transaction->payment_method)); ?>

                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                                <tfoot class="bg-gray-50">
                                                    <tr>
                                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">Total Paid:</td>
                                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BDT <?php echo e($order->payment_status === 'paid' ? number_format($order->amount) : number_format($order->paid_amount ?? 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">Remaining:</td>
                                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BDT <?php echo e($order->payment_status === 'paid' ? '0' : number_format($order->due_amount ?? $order->amount)); ?></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-gray-500">No transactions have been recorded yet.</p>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($order->notes): ?>
                                    <div class="mt-4">
                                        <p class="text-gray-700 font-medium">Notes:</p>
                                        <p class="text-gray-900 whitespace-pre-line"><?php echo e($order->notes); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Timeline -->
            <div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Order Timeline</h2>
                    </div>
                    <div class="p-6">
                        <div class="relative border-l-2 border-gray-200 ml-3">
                            <!-- Order Placed -->
                            <div class="mb-8 flex items-center">
                                <div class="absolute -left-3.5">
                                    <div class="h-7 w-7 rounded-full bg-blue-500 flex items-center justify-center">
                                        <i class="fas fa-shopping-cart text-white text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-6">
                                    <h3 class="text-sm font-semibold text-gray-900">Order Placed</h3>
                                    <p class="text-xs text-gray-500"><?php echo \App\Helpers\DateHelper::formatDateTime12Hour($order->created_at); ?></p>
                                    <p class="mt-1 text-sm text-gray-700">Your order has been placed successfully.</p>
                                </div>
                            </div>
                            
                            <!-- Order Processing -->
                            <?php if($order->status !== 'pending' && $order->status !== 'cancelled'): ?>
                                <div class="mb-8 flex items-center">
                                    <div class="absolute -left-3.5">
                                        <div class="h-7 w-7 rounded-full bg-yellow-500 flex items-center justify-center">
                                            <i class="fas fa-cog text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-6">
                                        <h3 class="text-sm font-semibold text-gray-900">Order Accepted</h3>
                                        <p class="text-xs text-gray-500"><?php echo e($order->updated_at->format('M d, Y h:i A')); ?></p>
                                        <p class="mt-1 text-sm text-gray-700">Your order has been accepted and is being processed.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Order Completed -->
                            <?php if($order->status === 'completed'): ?>
                                <div class="mb-8 flex items-center">
                                    <div class="absolute -left-3.5">
                                        <div class="h-7 w-7 rounded-full bg-green-500 flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-6">
                                        <h3 class="text-sm font-semibold text-gray-900">Order Completed</h3>
                                        <p class="text-xs text-gray-500"><?php echo e($order->updated_at->format('M d, Y h:i A')); ?></p>
                                        <p class="mt-1 text-sm text-gray-700">Your order has been completed successfully.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Order Cancelled -->
                            <?php if($order->status === 'cancelled'): ?>
                                <div class="mb-8 flex items-center">
                                    <div class="absolute -left-3.5">
                                        <div class="h-7 w-7 rounded-full bg-red-500 flex items-center justify-center">
                                            <i class="fas fa-times text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-6">
                                        <h3 class="text-sm font-semibold text-gray-900">Order Cancelled</h3>
                                        <p class="text-xs text-gray-500"><?php echo e($order->updated_at->format('M d, Y h:i A')); ?></p>
                                        <p class="mt-1 text-sm text-gray-700">Your order has been cancelled.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Next Steps -->
                            <?php if($order->status === 'pending'): ?>
                                <div class="mb-8 flex items-center">
                                    <div class="absolute -left-3.5">
                                        <div class="h-7 w-7 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-clock text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-6">
                                        <h3 class="text-sm font-semibold text-gray-900">Awaiting Confirmation</h3>
                                        <p class="mt-1 text-sm text-gray-700">Your order is pending confirmation from our team.</p>
                                    </div>
                                </div>
                            <?php elseif($order->status === 'processing'): ?>
                                <div class="mb-8 flex items-center">
                                    <div class="absolute -left-3.5">
                                        <div class="h-7 w-7 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-credit-card text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-6">
                                        <h3 class="text-sm font-semibold text-gray-900">Payment Required</h3>
                                        <p class="mt-1 text-sm text-gray-700">Please complete your payment to proceed with the order.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/customer/orders/show.blade.php ENDPATH**/ ?>