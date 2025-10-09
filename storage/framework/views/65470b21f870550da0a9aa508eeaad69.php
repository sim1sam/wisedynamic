<?php $__env->startSection('title', 'Payment Audit Logs'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Payment Audit Logs</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payment Activity Logs</h3>
        <div class="card-tools">
            <a href="<?php echo e(route('admin.payment-audit.statistics')); ?>" class="btn btn-sm btn-info">
                <i class="fas fa-chart-bar"></i> View Statistics
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('admin.payment-audit.index')); ?>" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Action</label>
                        <select name="action" class="form-control">
                            <option value="">All Actions</option>
                            <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($action); ?>" <?php echo e(request('action') == $action ? 'selected' : ''); ?>>
                                    <?php echo e(ucwords(str_replace('_', ' ', $action))); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control" value="<?php echo e(request('transaction_id')); ?>" placeholder="Transaction ID">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>User</label>
                        <select name="user_id" class="form-control">
                            <option value="">All Users</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                    <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date Range</label>
                        <div class="input-group">
                            <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                            <div class="input-group-append input-group-prepend">
                                <span class="input-group-text">to</span>
                            </div>
                            <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="<?php echo e(route('admin.payment-audit.index')); ?>" class="btn btn-default">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Action</th>
                        <th>Transaction ID</th>
                        <th>User</th>
                        <th>Customer Info</th>
                        <th>IP Address</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($log->id); ?></td>
                            <td>
                                <?php if($log->action == 'payment_attempt'): ?>
                                    <span class="badge badge-info">Attempt</span>
                                <?php elseif($log->action == 'payment_success'): ?>
                                    <span class="badge badge-success">Success</span>
                                <?php elseif($log->action == 'payment_failure'): ?>
                                    <span class="badge badge-danger">Failure</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo e($log->action); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($log->transaction_id): ?>
                                    <?php echo e($log->transaction_id); ?>

                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($log->user): ?>
                                    <span class="text-primary"><?php echo e($log->user->name); ?></span>
                                    <br>
                                    <small><?php echo e($log->user->email); ?></small>
                                <?php else: ?>
                                    <span class="text-muted">Guest</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(isset($transactions[$log->transaction_id])): ?>
                                    <?php $transaction = $transactions[$log->transaction_id]; ?>
                                    <strong><?php echo e($transaction->customer_name); ?></strong><br>
                                    <small><?php echo e($transaction->customer_email); ?></small>
                                <?php elseif($log->data && isset($log->data['customer_name'])): ?>
                                    <strong><?php echo e($log->data['customer_name']); ?></strong><br>
                                    <small><?php echo e($log->data['customer_email'] ?? 'No email'); ?></small>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-info load-customer-info" data-transaction-id="<?php echo e($log->transaction_id); ?>">
                                        <i class="fas fa-user"></i> Load Info
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($log->ip_address); ?></td>
                            <td><?php echo e($log->created_at->format('Y-m-d H:i:s')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.payment-audit.show', $log->id)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center">No payment audit logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($logs->appends(request()->query())->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(function() {
        $('.load-customer-info').on('click', function() {
            const button = $(this);
            const transactionId = button.data('transaction-id');
            
            button.html('<i class="fas fa-spinner fa-spin"></i> Loading...');
            button.prop('disabled', true);
            
            $.ajax({
                url: '<?php echo e(url("admin/payment-audit-customer-info")); ?>/' + transactionId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const customer = response.customer;
                        let html = '';
                        
                        if (customer.name) {
                            html += '<strong>' + customer.name + '</strong><br>';
                        }
                        
                        if (customer.email) {
                            html += '<small>' + customer.email + '</small>';
                            
                            if (customer.is_registered) {
                                html += ' <span class="badge badge-success">Registered</span>';
                            }
                        }
                        
                        button.parent().html(html);
                    } else {
                        button.html('<i class="fas fa-times"></i> Not Found');
                        setTimeout(function() {
                            button.html('<i class="fas fa-user"></i> Load Info');
                            button.prop('disabled', false);
                        }, 3000);
                    }
                },
                error: function() {
                    button.html('<i class="fas fa-times"></i> Error');
                    setTimeout(function() {
                        button.html('<i class="fas fa-user"></i> Load Info');
                        button.prop('disabled', false);
                    }, 3000);
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/payment-audit/index.blade.php ENDPATH**/ ?>