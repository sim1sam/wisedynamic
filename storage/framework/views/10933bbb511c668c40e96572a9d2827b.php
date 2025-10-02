

<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between">
        <h1>Notifications</h1>
        <div>
            <button class="btn btn-primary" id="mark-all-read-btn">
                <i class="fas fa-check"></i> Mark All as Read
            </button>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-body">
            <?php if($notifications->count() > 0): ?>
                <div class="list-group">
                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item <?php echo e($notification->is_read ? '' : 'list-group-item-warning'); ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-<?php echo e(getNotificationIcon($notification->type)); ?> mr-3 text-<?php echo e(getNotificationColor($notification->type)); ?>"></i>
                                    <div>
                                        <h5 class="mb-1"><?php echo e($notification->title); ?></h5>
                                        <p class="mb-1"><?php echo e($notification->message); ?></p>
                                        <small class="text-muted"><?php echo e($notification->created_at->diffForHumans()); ?></small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <?php if($notification->url): ?>
                                        <a href="<?php echo e($notification->url); ?>" class="btn btn-sm btn-outline-primary mr-2">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    <?php endif; ?>
                                    <?php if(!$notification->is_read): ?>
                                        <button class="btn btn-sm btn-success mark-read-btn" data-id="<?php echo e($notification->id); ?>">
                                            <i class="fas fa-check"></i> Mark Read
                                        </button>
                                    <?php else: ?>
                                        <span class="badge badge-success">Read</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="mt-4">
                    <?php echo e($notifications->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Notifications</h4>
                    <p class="text-muted">You don't have any notifications yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
$(document).ready(function() {
    // Mark individual notification as read
    $('.mark-read-btn').click(function() {
        const notificationId = $(this).data('id');
        const button = $(this);
        
        $.post('<?php echo e(route("admin.notifications.read", ":id")); ?>'.replace(':id', notificationId), {
            _token: '<?php echo e(csrf_token()); ?>'
        }, function() {
            button.closest('.list-group-item').removeClass('list-group-item-warning');
            button.replaceWith('<span class="badge badge-success">Read</span>');
        }).fail(function() {
            alert('Failed to mark notification as read');
        });
    });
    
    // Mark all notifications as read
    $('#mark-all-read-btn').click(function() {
        $.post('<?php echo e(route("admin.notifications.read-all")); ?>', {
            _token: '<?php echo e(csrf_token()); ?>'
        }, function() {
            location.reload();
        }).fail(function() {
            alert('Failed to mark all notifications as read');
        });
    });
});
 </script>
 <?php $__env->stopSection(); ?>

<?php
function getNotificationIcon($type) {
    $icons = [
        'package_order' => 'box',
        'service_order' => 'cogs',
        'fund_request' => 'wallet',
        'custom_service' => 'tools',
        'message' => 'envelope',
        'customer_request' => 'clipboard-list'
    ];
    return $icons[$type] ?? 'bell';
}

function getNotificationColor($type) {
    $colors = [
        'package_order' => 'primary',
        'service_order' => 'info',
        'fund_request' => 'success',
        'custom_service' => 'warning',
        'message' => 'secondary',
        'customer_request' => 'dark'
    ];
    return $colors[$type] ?? 'primary';
}
?>
<?php echo $__env->make('layouts.admin-with-notifications', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/notifications/index.blade.php ENDPATH**/ ?>