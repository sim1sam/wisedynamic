<?php $__env->startSection('title', 'Fund Request Details'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between">
        <h1>Fund Request #<?php echo e($fundRequest->id); ?></h1>
        <div>
            <a href="<?php echo e(route('admin.fund-requests.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <?php if($fundRequest->status === 'pending'): ?>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal">
                    <i class="fas fa-check"></i> Approve
                </button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                    <i class="fas fa-times"></i> Reject
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Request Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Request Information</h3>
                    <div class="card-tools">
                        <?php if($fundRequest->status === 'pending'): ?>
                            <span class="badge badge-warning badge-lg">Pending Review</span>
                        <?php elseif($fundRequest->status === 'approved'): ?>
                            <span class="badge badge-success badge-lg">Approved</span>
                        <?php else: ?>
                            <span class="badge badge-danger badge-lg">Rejected</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Request ID:</strong> #<?php echo e($fundRequest->id); ?><br>
                            <strong>Amount:</strong> BDT <?php echo e(number_format($fundRequest->amount, 2)); ?><br>
                            <strong>Payment Method:</strong> 
                            <?php if($fundRequest->payment_method === 'ssl'): ?>
                                <span class="badge badge-info">SSL Payment</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Manual Bank Transfer</span>
                            <?php endif; ?><br>
                            <strong>Request Date:</strong> <?php echo e($fundRequest->created_at->format('M d, Y H:i A')); ?><br>
                        </div>
                        <div class="col-md-6">
                            <?php if($fundRequest->approved_at): ?>
                                <strong>Approved Date:</strong> <?php echo e($fundRequest->approved_at->format('M d, Y H:i A')); ?><br>
                            <?php endif; ?>
                            <?php if($fundRequest->approvedBy): ?>
                                <strong>Approved By:</strong> <?php echo e($fundRequest->approvedBy->name); ?><br>
                            <?php endif; ?>
                            <?php if($fundRequest->payment_method === 'ssl'): ?>
                                <div class="mt-2 p-2 bg-light rounded">
                                    <strong>SSL Transaction ID:</strong> 
                                    <?php if($fundRequest->ssl_transaction_id): ?>
                                        <span class="text-monospace"><?php echo e($fundRequest->ssl_transaction_id); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Not initiated</span>
                                    <?php endif; ?>
                                    <br>
                                    
                                    <strong>SSL Payment Status:</strong> 
                                    <?php if($fundRequest->transaction): ?>
                                        <span class="badge badge-success">Completed</span>
                                    <?php elseif($fundRequest->ssl_transaction_id): ?>
                                        <span class="badge badge-warning">Initiated</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Not Started</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if($fundRequest->service_info): ?>
                        <hr>
                        <strong>Service Information:</strong>
                        <p class="mt-2"><?php echo e($fundRequest->service_info); ?></p>
                    <?php endif; ?>
                    
                    <?php if($fundRequest->admin_notes): ?>
                        <hr>
                        <strong>Admin Notes:</strong>
                        <p class="mt-2"><?php echo e($fundRequest->admin_notes); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Manual Payment Details -->
            <?php if($fundRequest->payment_method === 'manual'): ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bank Transfer Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Bank Name:</strong> <?php echo e($fundRequest->bank_name ?: 'Not provided'); ?><br>
                                <strong>Account Number:</strong> <?php echo e($fundRequest->account_number ?: 'Not provided'); ?><br>
                            </div>
                        </div>
                        
                        <?php if($fundRequest->payment_screenshot): ?>
                            <hr>
                            <strong>Payment Screenshot:</strong>
                            <div class="mt-2">
                                <img src="<?php echo e(asset('storage/' . $fundRequest->payment_screenshot)); ?>" 
                                     alt="Payment Screenshot" 
                                     class="img-fluid" 
                                     style="max-width: 500px; cursor: pointer;" 
                                     data-toggle="modal" 
                                     data-target="#screenshotModal">
                                <br><small class="text-muted">Click to view full size</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- SSL Payment Details -->
            <?php if($fundRequest->payment_method === 'ssl'): ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">SSL Payment Details</h3>
                        <?php if($fundRequest->transaction): ?>
                            <div class="card-tools">
                                <span class="badge badge-success">Payment Verified</span>
                            </div>
                        <?php elseif($fundRequest->ssl_transaction_id): ?>
                            <div class="card-tools">
                                <span class="badge badge-warning">Payment Pending Verification</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if($fundRequest->transaction): ?>
                            <!-- Transaction exists, payment is complete -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Transaction ID:</strong> <span class="text-monospace"><?php echo e($fundRequest->transaction->transaction_number); ?></span><br>
                                    <strong>Amount:</strong> BDT <?php echo e(number_format($fundRequest->amount, 2)); ?><br>
                                    <?php if($fundRequest->ssl_response && isset($fundRequest->ssl_response['card_type'])): ?>
                                        <strong>Card Type:</strong> <?php echo e($fundRequest->ssl_response['card_type'] ?? 'N/A'); ?><br>
                                    <?php endif; ?>
                                    <?php if($fundRequest->ssl_response && isset($fundRequest->ssl_response['card_brand'])): ?>
                                        <strong>Card Brand:</strong> <?php echo e($fundRequest->ssl_response['card_brand'] ?? 'N/A'); ?><br>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Payment Status:</strong> <span class="badge badge-success">COMPLETED</span><br>
                                    <strong>Transaction Date:</strong> <?php echo e($fundRequest->transaction->created_at->format('M d, Y H:i A')); ?><br>
                                    <?php if($fundRequest->ssl_transaction_id): ?>
                                        <strong>SSL Transaction ID:</strong> <span class="text-monospace"><?php echo e($fundRequest->ssl_transaction_id); ?></span><br>
                                    <?php endif; ?>
                                    <?php if($fundRequest->ssl_response && isset($fundRequest->ssl_response['tran_date'])): ?>
                                        <strong>Gateway Date:</strong> <?php echo e($fundRequest->ssl_response['tran_date'] ?? 'N/A'); ?><br>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if($fundRequest->ssl_response): ?>
                                <div class="mt-3">
                                    <strong>Full Response:</strong>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-info" type="button" data-toggle="collapse" data-target="#sslResponseCollapse" aria-expanded="false">
                                            <i class="fas fa-code mr-1"></i> View Raw Response
                                        </button>
                                    </div>
                                    <div class="collapse mt-2" id="sslResponseCollapse">
                                        <pre class="bg-light p-3 mb-0" style="max-height: 300px; overflow-y: auto;"><?php echo e(json_encode($fundRequest->ssl_response, JSON_PRETTY_PRINT)); ?></pre>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php elseif($fundRequest->ssl_transaction_id): ?>
                            <!-- SSL transaction initiated but not completed -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>SSL Transaction ID:</strong> <span class="text-monospace"><?php echo e($fundRequest->ssl_transaction_id); ?></span><br>
                                    <strong>Amount:</strong> BDT <?php echo e(number_format($fundRequest->amount, 2)); ?><br>
                                    <?php if($fundRequest->ssl_response && isset($fundRequest->ssl_response['card_type'])): ?>
                                        <strong>Card Type:</strong> <?php echo e($fundRequest->ssl_response['card_type'] ?? 'N/A'); ?><br>
                                    <?php endif; ?>
                                    <?php if($fundRequest->ssl_response && isset($fundRequest->ssl_response['card_brand'])): ?>
                                        <strong>Card Brand:</strong> <?php echo e($fundRequest->ssl_response['card_brand'] ?? 'N/A'); ?><br>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <?php if($fundRequest->ssl_response && isset($fundRequest->ssl_response['status'])): ?>
                                        <strong>Gateway Status:</strong> 
                                        <?php if(strtoupper($fundRequest->ssl_response['status']) === 'VALID' || strtoupper($fundRequest->ssl_response['status']) === 'VALIDATED'): ?>
                                            <span class="badge badge-success"><?php echo e(strtoupper($fundRequest->ssl_response['status'])); ?></span>
                                        <?php elseif(strtoupper($fundRequest->ssl_response['status']) === 'FAILED'): ?>
                                            <span class="badge badge-danger"><?php echo e(strtoupper($fundRequest->ssl_response['status'])); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-info"><?php echo e(strtoupper($fundRequest->ssl_response['status'])); ?></span>
                                        <?php endif; ?>
                                        <br>
                                    <?php endif; ?>
                                    <?php if($fundRequest->ssl_response && isset($fundRequest->ssl_response['tran_date'])): ?>
                                        <strong>Transaction Date:</strong> <?php echo e($fundRequest->ssl_response['tran_date'] ?? 'N/A'); ?><br>
                                    <?php endif; ?>
                                    <?php if($fundRequest->ssl_response && isset($fundRequest->ssl_response['error'])): ?>
                                        <strong>Error:</strong> <span class="text-danger"><?php echo e($fundRequest->ssl_response['error']); ?></span><br>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if($fundRequest->ssl_response): ?>
                                <div class="mt-3">
                                    <strong>Full Response:</strong>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-info" type="button" data-toggle="collapse" data-target="#sslResponseCollapse" aria-expanded="false">
                                            <i class="fas fa-code mr-1"></i> View Raw Response
                                        </button>
                                        
                                        <?php if($fundRequest->status === 'pending'): ?>
                                            <form action="<?php echo e(route('admin.transactions.verify-ssl', $fundRequest->ssl_transaction_id)); ?>" method="POST" class="d-inline ml-2">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-sync-alt mr-1"></i> Verify Payment Status
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                    <div class="collapse mt-2" id="sslResponseCollapse">
                                        <pre class="bg-light p-3 mb-0" style="max-height: 300px; overflow-y: auto;"><?php echo e(json_encode($fundRequest->ssl_response, JSON_PRETTY_PRINT)); ?></pre>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- No SSL transaction initiated -->
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle mr-2"></i> No SSL payment has been initiated for this fund request yet.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Customer Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer Information</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <?php if($fundRequest->user->profile_image): ?>
                            <img src="<?php echo e(asset('storage/' . $fundRequest->user->profile_image)); ?>" 
                                 alt="Profile Image" 
                                 class="img-circle" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        <?php else: ?>
                            <div class="img-circle bg-secondary d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <strong>Name:</strong> <?php echo e($fundRequest->user->name); ?><br>
                    <strong>Email:</strong> <?php echo e($fundRequest->user->email); ?><br>
                    <strong>Phone:</strong> <?php echo e($fundRequest->user->phone ?: 'Not provided'); ?><br>
                    <strong>Current Balance:</strong> BDT <?php echo e(number_format($fundRequest->user->balance, 2)); ?><br>
                    <strong>Status:</strong> 
                    <?php if($fundRequest->user->status === 'active'): ?>
                        <span class="badge badge-success">Active</span>
                    <?php else: ?>
                        <span class="badge badge-danger"><?php echo e(ucfirst($fundRequest->user->status)); ?></span>
                    <?php endif; ?><br>
                    <strong>Member Since:</strong> <?php echo e($fundRequest->user->created_at->format('M d, Y')); ?><br>
                    
                    <hr>
                    <strong>Total Fund Requests:</strong> <?php echo e($fundRequest->user->fundRequests()->count()); ?><br>
                    <strong>Approved Requests:</strong> <?php echo e($fundRequest->user->fundRequests()->approved()->count()); ?><br>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Screenshot Modal -->
    <?php if($fundRequest->payment_method === 'manual' && $fundRequest->payment_screenshot): ?>
        <div class="modal fade" id="screenshotModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Payment Screenshot</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="<?php echo e(asset('storage/' . $fundRequest->payment_screenshot)); ?>" 
                             alt="Payment Screenshot" 
                             class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Approve Modal -->
    <?php if($fundRequest->status === 'pending'): ?>
        <div class="modal fade" id="approveModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="<?php echo e(route('admin.fund-requests.approve', $fundRequest)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="modal-header">
                            <h4 class="modal-title">Approve Fund Request</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to approve this fund request?</p>
                            <div class="alert alert-info">
                                <strong>Amount:</strong> BDT <?php echo e(number_format($fundRequest->amount, 2)); ?><br>
                                <strong>Customer:</strong> <?php echo e($fundRequest->user->name); ?><br>
                                <strong>Current Balance:</strong> BDT <?php echo e(number_format($fundRequest->user->balance, 2)); ?><br>
                                <strong>New Balance:</strong> BDT <?php echo e(number_format($fundRequest->user->balance + $fundRequest->amount, 2)); ?>

                            </div>
                            <div class="form-group">
                                <label for="admin_notes">Admin Notes (Optional)</label>
                                <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" placeholder="Add any notes about this approval..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Approve Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="<?php echo e(route('admin.fund-requests.reject', $fundRequest)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="modal-header">
                            <h4 class="modal-title">Reject Fund Request</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to reject this fund request?</p>
                            <div class="alert alert-warning">
                                <strong>Amount:</strong> BDT <?php echo e(number_format($fundRequest->amount, 2)); ?><br>
                                <strong>Customer:</strong> <?php echo e($fundRequest->user->name); ?>

                            </div>
                            <div class="form-group">
                                <label for="reject_notes">Rejection Reason *</label>
                                <textarea name="admin_notes" id="reject_notes" class="form-control" rows="3" placeholder="Please provide a clear reason for rejection..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .badge-lg {
            font-size: 1em;
            padding: 0.5em 0.75em;
        }
        .img-circle {
            border-radius: 50%;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/fund-requests/show.blade.php ENDPATH**/ ?>