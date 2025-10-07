<?php $title = 'Custom Service Requests'; ?>


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
    <?php if(session('info')): ?>
        <div class="p-4 rounded-lg bg-blue-100 text-blue-700 border border-blue-200">
            <i class="fas fa-info-circle mr-2"></i><?php echo e(session('info')); ?>

        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Custom Service Requests</h1>
                <p class="text-sm text-gray-600">Manage your custom marketing and development service requests</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('customer.custom-service.create')); ?>" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow">
                    <i class="fas fa-plus mr-2"></i> New Request
                </a>
            </div>
        </div>
    </div>

    <!-- Service Requests List -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Your Service Requests</h3>
        </div>
        <div class="p-6">
            <?php if($customServiceRequests->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $customServiceRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="text-lg font-semibold text-gray-900">
                                            <?php echo e($request->getServiceTypeLabel()); ?>

                                        </h4>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?php echo e($request->getStatusColorClass()); ?>-100 text-<?php echo e($request->getStatusColorClass()); ?>-800">
                                            <?php echo e($request->getStatusLabel()); ?>

                                        </span>
                                        <?php if($request->payment_method === 'balance'): ?>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-wallet mr-1"></i>Balance
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-credit-card mr-1"></i>SSL
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="grid md:grid-cols-3 gap-4 text-sm text-gray-600 mb-3">
                                        <div>
                                            <span class="font-medium">Request ID:</span> #<?php echo e($request->id); ?>

                                        </div>
                                        <div>
                                            <span class="font-medium">Items:</span> <?php echo e($request->items->count()); ?>

                                        </div>
                                        <div>
                                            <span class="font-medium">Total Amount:</span> 
                                            <span class="font-semibold text-green-600">BDT <?php echo e(number_format($request->total_amount, 2)); ?></span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Created:</span> <?php echo e($request->created_at->format('M d, Y H:i')); ?>

                                        </div>
                                        <?php if($request->started_at): ?>
                                            <div>
                                                <span class="font-medium">Started:</span> <?php echo e($request->started_at->format('M d, Y H:i')); ?>

                                            </div>
                                        <?php endif; ?>
                                        <?php if($request->completed_at): ?>
                                            <div>
                                                <span class="font-medium">Completed:</span> <?php echo e($request->completed_at->format('M d, Y H:i')); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Service Items Preview -->
                                    <div class="mb-3">
                                        <h5 class="font-medium text-gray-700 mb-2">Service Items:</h5>
                                        <div class="space-y-2">
                                            <?php $__currentLoopData = $request->items->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="flex items-center justify-between bg-gray-50 rounded px-3 py-2">
                                                    <div>
                                                        <span class="font-medium text-gray-900"><?php echo e($item->service_name); ?></span>
                                                        <?php if($request->service_type === 'marketing' && $item->platform): ?>
                                                            <span class="text-sm text-gray-500">- <?php echo e($item->platform); ?></span>
                                                        <?php elseif($request->service_type === 'web_app' && $item->domain_name): ?>
                                                            <span class="text-sm text-gray-500">- <?php echo e($item->domain_name); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="font-semibold text-green-600">BDT <?php echo e(number_format($item->amount, 2)); ?></span>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($request->items->count() > 3): ?>
                                                <div class="text-sm text-gray-500 text-center py-1">
                                                    +<?php echo e($request->items->count() - 3); ?> more items
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php if($request->admin_notes): ?>
                                        <div class="mb-3">
                                            <h5 class="font-medium text-gray-700 mb-1">Admin Notes:</h5>
                                            <p class="text-sm text-gray-600 bg-yellow-50 border border-yellow-200 rounded p-2"><?php echo e($request->admin_notes); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex flex-col gap-2 ml-4">
                                    <a href="<?php echo e(route('customer.custom-service.show', $request)); ?>" class="inline-flex items-center px-3 py-1 rounded-md bg-blue-600 text-white hover:bg-blue-700 text-sm">
                                        <i class="fas fa-eye mr-1"></i> View Details
                                    </a>
                                    
                                    <?php if($request->status === 'pending' && $request->payment_method === 'ssl' && !$request->ssl_transaction_id && $request->payment_status !== 'paid' && !($request->transaction && $request->transaction->status === 'completed')): ?>
                                        <a href="<?php echo e(route('customer.custom-service.ssl-payment', $request)); ?>" class="inline-flex items-center px-3 py-1 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm">
                                            <i class="fas fa-credit-card mr-1"></i> Pay Now
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    <?php echo e($customServiceRequests->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-cogs text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No service requests yet</h3>
                    <p class="text-gray-500 mb-4">You haven't created any custom service requests. Get started by requesting marketing or development services.</p>
                    <a href="<?php echo e(route('customer.custom-service.create')); ?>" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Create First Request
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/customer/custom-service/index.blade.php ENDPATH**/ ?>