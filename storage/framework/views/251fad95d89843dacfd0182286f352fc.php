<?php $title = 'Fund Management'; ?>


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
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Fund Management</h1>
                <p class="text-sm text-gray-600">Add funds to your account and view transaction history</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('customer.fund.create')); ?>" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow">
                    <i class="fas fa-plus mr-2"></i> Add Funds
                </a>
            </div>
        </div>
    </div>

    <!-- Balance Card -->
    <div class="bg-white rounded-lg shadow dashboard-card card-green">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Account Balance</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Available Balance</p>
                    <p class="text-3xl font-bold text-green-600">BDT <?php echo e(number_format(auth()->user()->balance, 2)); ?></p>
                </div>
                <div class="text-green-500">
                    <i class="fas fa-wallet text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Fund Requests History -->
    <div class="bg-white rounded-lg shadow dashboard-card card-blue">
        <div class="card-header-themed p-4 rounded-t-lg">
            <h3 class="text-lg font-bold section-header mb-0">Fund Request History</h3>
        </div>
        <div class="p-6">
            <?php if($fundRequests->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $fundRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">#<?php echo e($request->id); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">BDT <?php echo e(number_format($request->amount, 2)); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo e($request->payment_method === 'ssl' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'); ?>">
                                            <?php echo e($request->payment_method === 'ssl' ? 'SSL Payment' : 'Manual Transfer'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if($request->status === 'pending'): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        <?php elseif($request->status === 'approved'): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?php echo e($request->created_at->format('M d, Y H:i')); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="<?php echo e(route('customer.fund.show', $request)); ?>" class="text-indigo-600 hover:text-indigo-900">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    <?php echo e($fundRequests->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-money-bill-wave text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No fund requests yet</h3>
                    <p class="text-gray-500 mb-4">You haven't made any fund requests. Add funds to your account to get started.</p>
                    <a href="<?php echo e(route('customer.fund.create')); ?>" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Add Funds Now
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/customer/fund/index.blade.php ENDPATH**/ ?>