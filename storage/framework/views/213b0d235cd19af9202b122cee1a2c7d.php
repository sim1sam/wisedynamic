<?php $__env->startSection('title','Footer Settings'); ?>
<?php $__env->startSection('page_title','Footer Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Manage Footer</h3>
  </div>
  <form method="POST" action="<?php echo e(route('admin.settings.footer.update')); ?>" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <div class="card-body">
      <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
      <?php endif; ?>
      <?php if($errors->any()): ?>
        <div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
      <?php endif; ?>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Company Name</label>
          <input type="text" class="form-control" name="company_name" value="<?php echo e(old('company_name', $setting->company_name ?? '')); ?>"/>
        </div>
        <div class="form-group col-md-6">
          <label>Tagline</label>
          <input type="text" class="form-control" name="tagline" value="<?php echo e(old('tagline', $setting->tagline ?? '')); ?>"/>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Phone</label>
          <input type="text" class="form-control" name="phone" value="<?php echo e(old('phone', $setting->phone ?? '')); ?>"/>
        </div>
        <div class="form-group col-md-6">
          <label>Email</label>
          <input type="email" class="form-control" name="email" value="<?php echo e(old('email', $setting->email ?? '')); ?>"/>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Facebook URL</label>
          <input type="url" class="form-control" name="facebook_url" value="<?php echo e(old('facebook_url', $setting->facebook_url ?? '')); ?>"/>
        </div>
        <div class="form-group col-md-6">
          <label>Twitter URL</label>
          <input type="url" class="form-control" name="twitter_url" value="<?php echo e(old('twitter_url', $setting->twitter_url ?? '')); ?>"/>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>LinkedIn URL</label>
          <input type="url" class="form-control" name="linkedin_url" value="<?php echo e(old('linkedin_url', $setting->linkedin_url ?? '')); ?>"/>
        </div>
        <div class="form-group col-md-6">
          <label>Instagram URL</label>
          <input type="url" class="form-control" name="instagram_url" value="<?php echo e(old('instagram_url', $setting->instagram_url ?? '')); ?>"/>
        </div>
      </div>

      <div class="form-group">
        <label>Copyright Text</label>
        <input type="text" class="form-control" name="copyright_text" value="<?php echo e(old('copyright_text', $setting->copyright_text ?? '')); ?>" placeholder="© <?php echo e(date('Y')); ?> Wise Dynamic. All rights reserved."/>
      </div>

      <div class="form-group">
        <label>SSL Logo</label>
        <input type="file" class="form-control-file" name="ssl_logo" accept="image/*"/>
        <?php if(!empty($setting->ssl_logo)): ?>
          <div class="mt-2">
            <small class="text-muted">Current SSL Logo:</small><br>
            <img src="<?php echo e(asset($setting->ssl_logo)); ?>" alt="Current SSL Logo" class="img-thumbnail" style="max-width: 200px; max-height: 100px;">
          </div>
        <?php endif; ?>
        <small class="form-text text-muted">Upload an image file (JPEG, PNG, JPG, GIF, SVG). Max size: 2MB</small>
      </div>

    </div>
    <div class="card-footer text-right">
      <button class="btn btn-primary">Save</button>
    </div>
  </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/settings/footer.blade.php ENDPATH**/ ?>