<?php $__env->startSection('title', 'Packages'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between">
        <h1>Packages</h1>
        <a href="<?php echo e(route('admin.packages.create')); ?>" class="btn btn-primary">Add New Package</a>
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
                    <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($package->id); ?></td>
                            <td><?php echo e($package->title); ?></td>
                            <td><?php echo e($package->category->name ?? 'N/A'); ?></td>
                            <td>
                                <?php if($package->price): ?>
                                    BDT <?php echo e(number_format($package->price)); ?><?php echo e($package->price_unit ? '/'.$package->price_unit : ''); ?>

                                <?php else: ?>
                                    Contact for pricing
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($package->status): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($package->featured): ?>
                                    <span class="badge badge-info">Featured</span>
                                <?php else: ?>
                                    <span class="badge badge-light">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('admin.packages.slug', $package->slug)); ?>" class="btn btn-sm btn-success">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="<?php echo e(route('admin.packages.edit', ['package' => $package])); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="<?php echo e(route('admin.packages.destroy', ['package' => $package])); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');" class="d-inline">
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
                            <td colspan="7" class="text-center">No packages found.</td>
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
<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/packages/index.blade.php ENDPATH**/ ?>