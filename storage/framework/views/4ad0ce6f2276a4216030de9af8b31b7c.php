

<?php $__env->startSection('title', 'Customers'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between">
        <h1>Customers</h1>
        <div>
            <a href="<?php echo e(route('admin.customers.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Customer
            </a>
        </div>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($customer->id); ?></td>
                            <td><?php echo e($customer->name); ?></td>
                            <td><?php echo e($customer->email); ?></td>
                            <td><?php echo e($customer->phone ?? '—'); ?></td>
                            <td>
                                <?php if($customer->status === 'active'): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php elseif($customer->status === 'blocked'): ?>
                                    <span class="badge badge-danger">Blocked</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo e(ucfirst($customer->status ?? 'Unknown')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($customer->created_at->format('M d, Y')); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('admin.customers.show', $customer)); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="<?php echo e(route('admin.customers.edit', $customer)); ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <?php if($customer->status === 'active'): ?>
                                        <form action="<?php echo e(route('admin.customers.update-status', $customer)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="status" value="blocked">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to block this customer?')">
                                                <i class="fas fa-ban"></i> Block
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('admin.customers.update-status', $customer)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to activate this customer?')">
                                                <i class="fas fa-check"></i> Activate
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">No customers found.</td>
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
<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/customers/index.blade.php ENDPATH**/ ?>