<?php $title = 'My Orders'; ?>


<?php $__env->startSection('content'); ?>
<div class="w-full px-4 md:px-6 space-y-6">
    <?php if(session('success')): ?>
        <div class="p-3 rounded bg-green-100 text-green-700"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="p-3 rounded bg-red-100 text-red-700"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">My Package Orders</h1>
                <p class="text-sm text-gray-600">Track and manage your package orders</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('packages')); ?>" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow">
                    <i class="fas fa-plus mr-2"></i> Browse Packages
                </a>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <?php if(count($orders) === 0): ?>
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-600">
            <div class="text-lg font-medium mb-2">No orders found</div>
            <p class="mb-4">You haven't placed any package orders yet.</p>
            <a href="<?php echo e(route('packages')); ?>" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Browse Packages
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow">
             <div class="overflow-x-auto">
                 <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order #
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Package
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo e($order->id); ?>

                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($order->package_name); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">BDT <?php echo e(number_format($order->amount)); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo e($order->created_at->format('M d, Y')); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($order->status === 'pending'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    <?php elseif($order->status === 'processing'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Processing
                                        </span>
                                    <?php elseif($order->status === 'completed'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    <?php elseif($order->status === 'cancelled'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Cancelled
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="<?php echo e(route('customer.orders.show', $order)); ?>" class="text-indigo-600 hover:text-indigo-900">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
             </div>
         </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/customer/orders/index.blade.php ENDPATH**/ ?>