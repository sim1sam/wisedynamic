<?php $__env->startSection('title', 'View Transaction'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between">
        <h1>Transaction #<?php echo e($transaction->transaction_number); ?></h1>
        <div>
            <a href="<?php echo e(route('admin.transactions.edit', $transaction)); ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Transaction
            </a>
            <a href="<?php echo e(route('admin.transactions.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Transactions
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaction Details</h3>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                    <?php endif; ?>

                    <table class="table table-bordered">
                        <tr>
                            <th>Transaction ID</th>
                            <td><?php echo e($transaction->id); ?></td>
                        </tr>
                        <tr>
                            <th>Transaction Number</th>
                            <td><?php echo e($transaction->transaction_number); ?></td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>BDT <?php echo e(number_format($transaction->amount)); ?></td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td>
                                <span class="badge badge-info"><?php echo e(ucfirst($transaction->payment_method)); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td><?php echo e($transaction->created_at->format('M d, Y h:i A')); ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge <?php echo e($transaction->getStatusBadgeClass()); ?>"><?php echo e($transaction->getStatusDisplayName()); ?></span>
                                <?php if($transaction->updated_by_admin): ?>
                                    <br><small class="text-muted">Last updated by admin on <?php echo e($transaction->admin_updated_at->format('M d, Y h:i A')); ?></small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if($transaction->notes): ?>
                        <tr>
                            <th>Notes</th>
                            <td><?php echo e($transaction->notes); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($transaction->admin_notes): ?>
                        <tr>
                            <th>Admin Notes</th>
                            <td><?php echo e($transaction->admin_notes); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            
            <?php if($transaction->isSSLTransaction()): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">SSL Payment Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>SSL Transaction ID</th>
                                    <td><?php echo e($transaction->ssl_transaction_id); ?></td>
                                </tr>
                                <?php if($transaction->ssl_session_id): ?>
                                <tr>
                                    <th>SSL Session ID</th>
                                    <td><?php echo e($transaction->ssl_session_id); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($transaction->ssl_bank_transaction_id): ?>
                                <tr>
                                    <th>Bank Transaction ID</th>
                                    <td><?php echo e($transaction->ssl_bank_transaction_id); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($transaction->ssl_card_type): ?>
                                <tr>
                                    <th>Card Type</th>
                                    <td><?php echo e($transaction->ssl_card_type); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($transaction->ssl_card_no): ?>
                                <tr>
                                    <th>Card Number</th>
                                    <td><?php echo e($transaction->ssl_card_no); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($transaction->ssl_card_issuer): ?>
                                <tr>
                                    <th>Card Issuer</th>
                                    <td><?php echo e($transaction->ssl_card_issuer); ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <?php if($transaction->customer_name): ?>
                                <tr>
                                    <th>Customer Name</th>
                                    <td><?php echo e($transaction->customer_name); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($transaction->customer_email): ?>
                                <tr>
                                    <th>Customer Email</th>
                                    <td><?php echo e($transaction->customer_email); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($transaction->customer_phone): ?>
                                <tr>
                                    <th>Customer Phone</th>
                                    <td><?php echo e($transaction->customer_phone); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($transaction->customer_address): ?>
                                <tr>
                                    <th>Customer Address</th>
                                    <td><?php echo e($transaction->customer_address); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($transaction->ssl_currency_type && $transaction->ssl_currency_type !== 'BDT'): ?>
                                <tr>
                                    <th>Currency</th>
                                    <td><?php echo e($transaction->ssl_currency_type); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($transaction->ssl_currency_amount): ?>
                                <tr>
                                    <th>Currency Amount</th>
                                    <td><?php echo e($transaction->ssl_currency_amount); ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    
                    <?php if($transaction->ssl_response_data): ?>
                    <div class="mt-3">
                        <h5>Full SSL Response Data</h5>
                        <div class="bg-light p-3 rounded">
                            <pre class="mb-0" style="max-height: 300px; overflow-y: auto;"><?php echo e(json_encode($transaction->ssl_response_data, JSON_PRETTY_PRINT)); ?></pre>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Related Order</h3>
                </div>
                <div class="card-body">
                    <?php if($transaction->packageOrder): ?>
                        <h5>Package Order #<?php echo e($transaction->packageOrder->id); ?></h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Package</th>
                                <td><?php echo e($transaction->packageOrder->package_name); ?></td>
                            </tr>
                            <tr>
                                <th>Customer</th>
                                <td><?php echo e($transaction->packageOrder->full_name); ?></td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>BDT <?php echo e(number_format($transaction->packageOrder->amount)); ?></td>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <td>BDT <?php echo e(number_format($transaction->packageOrder->paid_amount)); ?></td>
                            </tr>
                            <tr>
                                <th>Due Amount</th>
                                <td>BDT <?php echo e(number_format($transaction->packageOrder->due_amount)); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php if($transaction->packageOrder->status === 'pending'): ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php elseif($transaction->packageOrder->status === 'processing'): ?>
                                        <span class="badge badge-info">Processing</span>
                                    <?php elseif($transaction->packageOrder->status === 'completed'): ?>
                                        <span class="badge badge-success">Completed</span>
                                    <?php elseif($transaction->packageOrder->status === 'cancelled'): ?>
                                        <span class="badge badge-danger">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                        <a href="<?php echo e(route('admin.package-orders.show', $transaction->packageOrder)); ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-eye"></i> View Order
                        </a>
                    <?php elseif($transaction->serviceOrder): ?>
                        <h5>Service Order #<?php echo e($transaction->serviceOrder->id); ?></h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Service</th>
                                <td><?php echo e($transaction->serviceOrder->service_name); ?></td>
                            </tr>
                            <tr>
                                <th>Customer</th>
                                <td><?php echo e($transaction->serviceOrder->full_name); ?></td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>BDT <?php echo e(number_format($transaction->serviceOrder->amount)); ?></td>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <td>BDT <?php echo e(number_format($transaction->serviceOrder->paid_amount)); ?></td>
                            </tr>
                            <tr>
                                <th>Due Amount</th>
                                <td>BDT <?php echo e(number_format($transaction->serviceOrder->due_amount)); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php if($transaction->serviceOrder->status === 'pending'): ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php elseif($transaction->serviceOrder->status === 'processing'): ?>
                                        <span class="badge badge-info">Processing</span>
                                    <?php elseif($transaction->serviceOrder->status === 'completed'): ?>
                                        <span class="badge badge-success">Completed</span>
                                    <?php elseif($transaction->serviceOrder->status === 'cancelled'): ?>
                                        <span class="badge badge-danger">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                        <a href="<?php echo e(route('admin.service-orders.show', $transaction->serviceOrder)); ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-eye"></i> View Order
                        </a>
                    <?php else: ?>
                        <p class="text-muted">No related order found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/transactions/show.blade.php ENDPATH**/ ?>