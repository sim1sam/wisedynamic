

<?php $__env->startSection('title', 'Create Fund Request'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Fund Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.fund-requests.index')); ?>">Fund Requests</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Fund Request Details</h3>
                        </div>
                        
                        <form action="<?php echo e(route('admin.fund-requests.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <!-- User Selection -->
                                <div class="form-group">
                                    <label for="user_id">Select Customer <span class="text-danger">*</span></label>
                                    <select class="form-control" id="user_id" name="user_id" required>
                                        <option value="">Choose a customer...</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
                                                <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Amount -->
                                <div class="form-group">
                                    <label for="amount">Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="amount" name="amount" 
                                           value="<?php echo e(old('amount')); ?>" min="1" max="100000" step="0.01" required>
                                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Service Info -->
                                <div class="form-group">
                                    <label for="service_info">Service Information</label>
                                    <textarea class="form-control" id="service_info" name="service_info" rows="3" 
                                              placeholder="Optional: Describe the service or purpose for this fund request"><?php echo e(old('service_info')); ?></textarea>
                                    <?php $__errorArgs = ['service_info'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Payment Method -->
                                <div class="form-group">
                                    <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="">Select payment method...</option>
                                        <option value="ssl" <?php echo e(old('payment_method') == 'ssl' ? 'selected' : ''); ?>>SSL Payment</option>
                                        <option value="manual" <?php echo e(old('payment_method') == 'manual' ? 'selected' : ''); ?>>Manual Bank Transfer</option>
                                    </select>
                                    <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Bank Details (shown when manual is selected) -->
                                <div id="bank-details" style="display: none;">
                                    <div class="form-group">
                                        <label for="bank_name">Bank Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bank_name" name="bank_name" 
                                               value="<?php echo e(old('bank_name')); ?>" placeholder="Enter bank name">
                                        <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="form-group">
                                        <label for="account_number">Account Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="account_number" name="account_number" 
                                               value="<?php echo e(old('account_number')); ?>" placeholder="Enter account number">
                                        <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Admin Notes -->
                                <div class="form-group">
                                    <label for="admin_notes">Admin Notes</label>
                                    <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                              placeholder="Optional: Add any admin notes or comments"><?php echo e(old('admin_notes')); ?></textarea>
                                    <?php $__errorArgs = ['admin_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Auto Approve -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="auto_approve" name="auto_approve" value="1" <?php echo e(old('auto_approve') ? 'checked' : ''); ?>>
                                        <label class="custom-control-label" for="auto_approve">
                                            Auto-approve and add balance immediately
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        If checked, the fund request will be automatically approved and the amount will be added to the customer's balance.
                                    </small>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Fund Request
                                </button>
                                <a href="<?php echo e(route('admin.fund-requests.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Information</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Creating Fund Requests:</strong></p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Select a customer from the dropdown</li>
                                <li><i class="fas fa-check text-success"></i> Enter the amount (1-100,000 BDT)</li>
                                <li><i class="fas fa-check text-success"></i> Choose payment method</li>
                                <li><i class="fas fa-check text-success"></i> Optionally auto-approve</li>
                            </ul>
                            
                            <hr>
                            
                            <p><strong>Auto-Approval:</strong></p>
                            <p class="text-sm text-muted">
                                When auto-approval is enabled, the fund request will be immediately approved, 
                                the customer's balance will be updated, and a transaction record will be created.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Show/hide bank details based on payment method
    $('#payment_method').change(function() {
        if ($(this).val() === 'manual') {
            $('#bank-details').show();
            $('#bank_name, #account_number').prop('required', true);
        } else {
            $('#bank-details').hide();
            $('#bank_name, #account_number').prop('required', false).val(''); // Clear values when SSL selected
        }
    });

    // Trigger change event on page load to handle old values
    $('#payment_method').trigger('change');
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/fund-requests/create.blade.php ENDPATH**/ ?>