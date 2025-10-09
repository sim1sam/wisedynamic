<?php $__env->startSection('title', 'Service Orders'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between">
        <h1>Service Orders</h1>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($order->id); ?></td>
                            <td><?php echo e($order->service_name); ?></td>
                            <td><?php echo e($order->full_name); ?></td>
                            <td>BDT <?php echo e(number_format($order->amount)); ?></td>
                            <td>
                                <?php if($order->status === 'pending'): ?>
                                    <span class="badge badge-warning">Pending</span>
                                <?php elseif($order->status === 'processing'): ?>
                                    <span class="badge badge-info">Processing</span>
                                <?php elseif($order->status === 'completed'): ?>
                                    <span class="badge badge-success">Completed</span>
                                <?php elseif($order->status === 'cancelled'): ?>
                                    <span class="badge badge-danger">Cancelled</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($order->payment_status === 'pending_verification'): ?>
                                    <span class="badge badge-warning">Pending Verification</span>
                                <?php elseif($order->payment_status === 'paid'): ?>
                                    <span class="badge badge-success">Paid</span>
                                <?php elseif(($order->paid_amount ?? 0) <= 0): ?>
                                    <span class="badge badge-danger">Not Paid</span>
                                <?php elseif(($order->due_amount ?? $order->amount) <= 0): ?>
                                    <span class="badge badge-success">Fully Paid</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Partially Paid</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($order->created_at->format('M d, Y')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.service-orders.show', $order)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center">No service orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .btn-group .btn {
            margin-right: 5px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        $(document).ready(function() {
            $('table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "order": [[0, "desc"]]
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/service-orders/index.blade.php ENDPATH**/ ?>