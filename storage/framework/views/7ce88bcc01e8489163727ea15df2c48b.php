<?php $__env->startSection('title', 'Payment Audit Log Details'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Payment Audit Log Details</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Log Information</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('admin.payment-audit.index')); ?>" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">ID</th>
                        <td><?php echo e($log->id); ?></td>
                    </tr>
                    <tr>
                        <th>Action</th>
                        <td>
                            <?php if($log->action == 'payment_attempt'): ?>
                                <span class="badge badge-info">Payment Attempt</span>
                            <?php elseif($log->action == 'payment_success'): ?>
                                <span class="badge badge-success">Payment Success</span>
                            <?php elseif($log->action == 'payment_failure'): ?>
                                <span class="badge badge-danger">Payment Failure</span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?php echo e(ucwords(str_replace('_', ' ', $log->action))); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Transaction ID</th>
                        <td><?php echo e($log->transaction_id ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>
                            <?php if($log->user): ?>
                                <span class="text-primary"><?php echo e($log->user->name); ?></span>
                                <br>
                                <small><?php echo e($log->user->email); ?></small>
                            <?php else: ?>
                                <span class="text-muted">Guest</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>IP Address</th>
                        <td><?php echo e($log->ip_address); ?></td>
                    </tr>
                    <tr>
                        <th>User Agent</th>
                        <td>
                            <small><?php echo e($log->user_agent); ?></small>
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td><?php echo e($log->created_at->format('Y-m-d H:i:s')); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <?php if($transaction): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaction Information</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Transaction Number</th>
                            <td><?php echo e($transaction->transaction_number); ?></td>
                        </tr>
                        <tr>
                            <th>SSL Transaction ID</th>
                            <td><?php echo e($transaction->ssl_transaction_id); ?></td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td><?php echo e(number_format($transaction->amount, 2)); ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if($transaction->status == 'completed'): ?>
                                    <span class="badge badge-success">Completed</span>
                                <?php elseif($transaction->status == 'pending'): ?>
                                    <span class="badge badge-warning">Pending</span>
                                <?php elseif($transaction->status == 'failed'): ?>
                                    <span class="badge badge-danger">Failed</span>
                                <?php elseif($transaction->status == 'blocked'): ?>
                                    <span class="badge badge-dark">Blocked</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo e(ucfirst($transaction->status)); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td><?php echo e($transaction->payment_method); ?></td>
                        </tr>
                        <tr>
                            <th>Customer Name</th>
                            <td><?php echo e($transaction->customer_name); ?></td>
                        </tr>
                        <tr>
                            <th>Customer Email</th>
                            <td><?php echo e($transaction->customer_email); ?></td>
                        </tr>
                        <tr>
                            <th>Customer Phone</th>
                            <td><?php echo e($transaction->customer_phone); ?></td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td><?php echo e($transaction->created_at->format('Y-m-d H:i:s')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Additional Data</h3>
            </div>
            <div class="card-body">
                <?php if($log->data): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $log->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key); ?></td>
                                        <td>
                                            <?php if(is_array($value)): ?>
                                                <pre><?php echo e(json_encode($value, JSON_PRETTY_PRINT)); ?></pre>
                                            <?php else: ?>
                                                <?php echo e($value); ?>

                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No additional data available.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/payment-audit/show.blade.php ENDPATH**/ ?>