<?php $__env->startSection('title', 'Contact Page Settings'); ?>
<?php $__env->startSection('page_title', 'Contact Page Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Contact Page Settings</h3>
    </div>
    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <form action="<?php echo e(route('admin.settings.contact.update')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Header Section</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Page Title</label>
                        <input type="text" name="title" id="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('title', $contactSetting->title ?? 'Contact Us')); ?>">
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="subtitle">Page Subtitle</label>
                        <textarea name="subtitle" id="subtitle" rows="3" class="form-control <?php $__errorArgs = ['subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('subtitle', $contactSetting->subtitle ?? 'Have a question or want to work together? Reach out to us using the contact information below or fill out the form.')); ?></textarea>
                        <?php $__errorArgs = ['subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Contact Information</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" rows="3" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('address', $contactSetting->address ?? '123 Business Street, Suite 100, City, Country')); ?></textarea>
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('phone', $contactSetting->phone ?? '+1 (555) 123-4567')); ?>">
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="whatsapp">WhatsApp Number</label>
                        <input type="text" name="whatsapp" id="whatsapp" class="form-control <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('whatsapp', $contactSetting->whatsapp ?? '+8801805081012')); ?>">
                        <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text text-muted">Use international format without spaces (e.g., +8801805081012)</small>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('email', $contactSetting->email ?? 'info@wisedynamic.com')); ?>">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Map Embed</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="map_embed">Google Maps Embed Code</label>
                        <textarea name="map_embed" id="map_embed" rows="5" class="form-control <?php $__errorArgs = ['map_embed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('map_embed', $contactSetting->map_embed ?? '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.9008212777105!2d90.38426661498136!3d23.750858084589382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8bd5c3bbd77%3A0x3d12c1a7e70a3c13!2sWise%20Dynamic!5e0!3m2!1sen!2sbd!4v1598123456789!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>')); ?></textarea>
                        <small class="form-text text-muted">Paste the iframe embed code from Google Maps</small>
                        <?php $__errorArgs = ['map_embed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <?php if($contactSetting->map_embed ?? false): ?>
                        <div class="mt-3">
                            <label>Current Map Preview:</label>
                            <div class="embed-responsive embed-responsive-16by9">
                                <?php echo $contactSetting->map_embed; ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Office Hours</h4>
                </div>
                <div class="card-body">
                    <div id="office-hours-container">
                        <?php
                            $defaultOfficeHours = [
                                ['day' => 'Monday - Friday', 'hours' => '9:00 AM - 6:00 PM'],
                                ['day' => 'Saturday', 'hours' => '10:00 AM - 4:00 PM'],
                                ['day' => 'Sunday', 'hours' => 'Closed'],
                            ];
                            $officeHours = old('office_hours', $contactSetting->office_hours ?? $defaultOfficeHours);
                        ?>
                        
                        <?php $__currentLoopData = is_array($officeHours) ? $officeHours : []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="office-hour-row card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Day</label>
                                                <input type="text" name="office_hours[<?php echo e($index); ?>][day]" class="form-control" 
                                                    value="<?php echo e($item['day'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Hours</label>
                                                <input type="text" name="office_hours[<?php echo e($index); ?>][hours]" class="form-control" 
                                                    value="<?php echo e($item['hours'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($index > 0): ?>
                                        <button type="button" class="btn btn-sm btn-danger remove-office-hour float-right">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <button type="button" id="add-office-hour" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Office Hours
                    </button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Social Links</h4>
                </div>
                <div class="card-body">
                    <div id="social-links-container">
                        <?php
                            $defaultSocialLinks = [
                                ['platform' => 'Facebook', 'url' => 'https://facebook.com/wisedynamic', 'icon' => 'fab fa-facebook'],
                                ['platform' => 'Twitter', 'url' => 'https://twitter.com/wisedynamic', 'icon' => 'fab fa-twitter'],
                                ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com/company/wisedynamic', 'icon' => 'fab fa-linkedin'],
                                ['platform' => 'Instagram', 'url' => 'https://instagram.com/wisedynamic', 'icon' => 'fab fa-instagram'],
                            ];
                            $socialLinks = old('social_links', $contactSetting->social_links ?? $defaultSocialLinks);
                        ?>
                        
                        <?php $__currentLoopData = is_array($socialLinks) ? $socialLinks : []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="social-link-row card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Platform</label>
                                                <input type="text" name="social_links[<?php echo e($index); ?>][platform]" class="form-control" 
                                                    value="<?php echo e($item['platform'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>URL</label>
                                                <input type="text" name="social_links[<?php echo e($index); ?>][url]" class="form-control" 
                                                    value="<?php echo e($item['url'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Icon Class</label>
                                                <input type="text" name="social_links[<?php echo e($index); ?>][icon]" class="form-control" 
                                                    value="<?php echo e($item['icon'] ?? ''); ?>" placeholder="fab fa-facebook">
                                                <small class="form-text text-muted">
                                                    Use FontAwesome classes. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($index > 0): ?>
                                        <button type="button" class="btn btn-sm btn-danger remove-social-link float-right">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <button type="button" id="add-social-link" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Social Link
                    </button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Contact Form Settings</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="form_title">Form Title</label>
                        <input type="text" name="form_title" id="form_title" class="form-control <?php $__errorArgs = ['form_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('form_title', $contactSetting->form_title ?? 'Send Us a Message')); ?>">
                        <?php $__errorArgs = ['form_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="form_subtitle">Form Subtitle</label>
                        <textarea name="form_subtitle" id="form_subtitle" rows="3" class="form-control <?php $__errorArgs = ['form_subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('form_subtitle', $contactSetting->form_subtitle ?? 'Fill out the form below and we\'ll get back to you as soon as possible.')); ?></textarea>
                        <?php $__errorArgs = ['form_subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
<script>
    $(function() {
        // Office Hours
        let officeHourIndex = <?php echo e(count(is_array(old('office_hours', $contactSetting->office_hours ?? $defaultOfficeHours)) ? old('office_hours', $contactSetting->office_hours ?? $defaultOfficeHours) : [])); ?>;
        
        $('#add-office-hour').click(function() {
            const template = `
                <div class="office-hour-row card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Day</label>
                                    <input type="text" name="office_hours[\${officeHourIndex}][day]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hours</label>
                                    <input type="text" name="office_hours[\${officeHourIndex}][hours]" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-office-hour float-right">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
            
            $('#office-hours-container').append(template);
            officeHourIndex++;
        });
        
        $(document).on('click', '.remove-office-hour', function() {
            $(this).closest('.office-hour-row').remove();
        });

        // Social Links
        let socialLinkIndex = <?php echo e(count(is_array(old('social_links', $contactSetting->social_links ?? $defaultSocialLinks)) ? old('social_links', $contactSetting->social_links ?? $defaultSocialLinks) : [])); ?>;
        
        $('#add-social-link').click(function() {
            const template = `
                <div class="social-link-row card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Platform</label>
                                    <input type="text" name="social_links[\${socialLinkIndex}][platform]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>URL</label>
                                    <input type="text" name="social_links[\${socialLinkIndex}][url]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Icon Class</label>
                                    <input type="text" name="social_links[\${socialLinkIndex}][icon]" class="form-control" 
                                        placeholder="fab fa-facebook">
                                    <small class="form-text text-muted">
                                        Use FontAwesome classes. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-social-link float-right">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
            
            $('#social-links-container').append(template);
            socialLinkIndex++;
        });
        
        $(document).on('click', '.remove-social-link', function() {
            $(this).closest('.social-link-row').remove();
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/settings/contact/edit.blade.php ENDPATH**/ ?>