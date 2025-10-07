<?php $__env->startSection('title', 'View Page'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>View Page</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.pages.index')); ?>">Pages</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo e($page->title); ?></h3>
                        <div class="card-tools">
                            <a href="<?php echo e(route('admin.pages.edit', $page)); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?php echo e(route('page.show', $page->slug)); ?>" class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-external-link-alt"></i> View on Site
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <?php if($page->short_description): ?>
                                    <div class="mb-4">
                                        <h5>Short Description</h5>
                                        <p class="text-muted"><?php echo e($page->short_description); ?></p>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-4">
                                    <h5>Content</h5>
                                    <div class="border p-3 bg-light">
                                        <?php echo $page->content; ?>

                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h5>Page Details</h5>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th style="width: 200px;">Slug</th>
                                                <td><?php echo e($page->slug); ?></td>
                                            </tr>
                                            <tr>
                                                <th>URL</th>
                                                <td>
                                                    <a href="<?php echo e(route('page.show', $page->slug)); ?>" target="_blank">
                                                        <?php echo e(route('page.show', $page->slug)); ?>

                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Display Location</th>
                                                <td>
                                                    <?php if($page->show_in_footer): ?>
                                                        <span class="badge badge-info">Footer</span>
                                                    <?php endif; ?>
                                                    <?php if($page->show_in_header): ?>
                                                        <span class="badge badge-primary">Header</span>
                                                    <?php endif; ?>
                                                    <?php if(!$page->show_in_footer && !$page->show_in_header): ?>
                                                        <span class="badge badge-secondary">None</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Display Order</th>
                                                <td><?php echo e($page->order); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <?php if($page->is_active): ?>
                                                        <span class="badge badge-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created At</th>
                                                <td><?php echo e($page->created_at->format('M d, Y H:i')); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Last Updated</th>
                                                <td><?php echo e($page->updated_at->format('M d, Y H:i')); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <?php if($page->image): ?>
                                    <div class="mb-4">
                                        <h5>Featured Image</h5>
                                        <img src="<?php echo e(asset('storage/' . $page->image)); ?>" alt="<?php echo e($page->title); ?>" class="img-fluid img-thumbnail">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="btn-group">
                            <a href="<?php echo e(route('admin.pages.edit', $page)); ?>" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?php echo e(route('admin.pages.index')); ?>" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <form action="<?php echo e(route('admin.pages.destroy', $page)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this page?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/pages/show.blade.php ENDPATH**/ ?>