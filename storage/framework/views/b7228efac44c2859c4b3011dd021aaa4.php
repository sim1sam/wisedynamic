<?php $__env->startSection('title', 'Services'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between">
        <h1>Services</h1>
        <a href="<?php echo e(route('admin.services.create')); ?>" class="btn btn-primary">Add New Service</a>
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
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($service->id); ?></td>
                            <td><?php echo e($service->title); ?></td>
                            <td><?php echo e($service->category->name ?? 'N/A'); ?></td>
                            <td>
                                <?php if($service->price): ?>
                                    BDT <?php echo e(number_format($service->price)); ?><?php echo e($service->price_unit ? '/'.$service->price_unit : ''); ?>

                                <?php else: ?>
                                    Contact for pricing
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($service->status): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($service->featured): ?>
                                    <span class="badge badge-info">Featured</span>
                                <?php else: ?>
                                    <span class="badge badge-light">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('admin.services.slug', $service->slug)); ?>" class="btn btn-sm btn-success">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="<?php echo e(route('admin.services.edit', ['service' => $service])); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="<?php echo e(route('admin.services.destroy', ['service' => $service])); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">No services found.</td>
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
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/services/index.blade.php ENDPATH**/ ?>