<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page_title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<!-- Summary Stats -->
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3><?php echo e($totalServiceOrders + $totalPackageOrders); ?></h3>
        <p>Total Orders</p>
      </div>
      <div class="icon">
        <i class="fas fa-shopping-cart"></i>
      </div>
      <a href="<?php echo e(route('admin.service-orders.index')); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3><?php echo e(number_format($totalRevenue, 0)); ?> <sup style="font-size: 20px">BDT</sup></h3>
        <p>Total Revenue</p>
      </div>
      <div class="icon">
        <i class="fas fa-money-bill-wave"></i>
      </div>
      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3><?php echo e($totalUsers); ?></h3>
        <p>User Registrations</p>
      </div>
      <div class="icon">
        <i class="fas fa-user-plus"></i>
      </div>
      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3><?php echo e($pendingServiceOrders + $pendingPackageOrders); ?></h3>
        <p>Pending Orders</p>
      </div>
      <div class="icon">
        <i class="fas fa-clock"></i>
      </div>
      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>

<!-- Project Stats -->
<div class="row">
  <!-- Services Stats -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Services Overview</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-light">
              <span class="info-box-icon"><i class="fas fa-cogs"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Services</span>
                <span class="info-box-number"><?php echo e($totalServices); ?></span>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-light">
              <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Active Services</span>
                <span class="info-box-number"><?php echo e($activeServices); ?></span>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-light">
              <span class="info-box-icon"><i class="fas fa-star"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Featured</span>
                <span class="info-box-number"><?php echo e($featuredServices); ?></span>
              </div>
            </div>
          </div>
        </div>
        <div class="progress-group mt-3">
          <span class="progress-text">Service Orders Completion</span>
          <span class="float-right"><b><?php echo e($completedServiceOrders); ?></b>/<?php echo e($totalServiceOrders); ?></span>
          <div class="progress">
            <?php $completionPercentage = $totalServiceOrders > 0 ? ($completedServiceOrders / $totalServiceOrders) * 100 : 0; ?>
            <div class="progress-bar bg-primary" style="width: <?php echo e($completionPercentage); ?>%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Packages Stats -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Packages Overview</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-light">
              <span class="info-box-icon"><i class="fas fa-box"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Packages</span>
                <span class="info-box-number"><?php echo e($totalPackages); ?></span>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-light">
              <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Active Packages</span>
                <span class="info-box-number"><?php echo e($activePackages); ?></span>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-light">
              <span class="info-box-icon"><i class="fas fa-star"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Featured</span>
                <span class="info-box-number"><?php echo e($featuredPackages); ?></span>
              </div>
            </div>
          </div>
        </div>
        <div class="progress-group mt-3">
          <span class="progress-text">Package Orders Completion</span>
          <span class="float-right"><b><?php echo e($completedPackageOrders); ?></b>/<?php echo e($totalPackageOrders); ?></span>
          <div class="progress">
            <?php $completionPercentage = $totalPackageOrders > 0 ? ($completedPackageOrders / $totalPackageOrders) * 100 : 0; ?>
            <div class="progress-bar bg-success" style="width: <?php echo e($completionPercentage); ?>%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Activity -->
<div class="row">
  <!-- Recent Service Orders -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header border-transparent">
        <h3 class="card-title">Recent Service Orders</h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table m-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Service</th>
                <th>Customer</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $recentServiceOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td><a href="<?php echo e(route('admin.service-orders.show', $order->id)); ?>">#<?php echo e($order->id); ?></a></td>
                <td><?php echo e(Str::limit($order->service_name, 20)); ?></td>
                <td><?php echo e(Str::limit($order->full_name, 20)); ?></td>
                <td>
                  <?php if($order->status == 'pending'): ?>
                    <span class="badge badge-warning">Pending</span>
                  <?php elseif($order->status == 'in_progress'): ?>
                    <span class="badge badge-info">In Progress</span>
                  <?php elseif($order->status == 'completed'): ?>
                    <span class="badge badge-success">Completed</span>
                  <?php else: ?>
                    <span class="badge badge-secondary"><?php echo e(ucfirst($order->status)); ?></span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="4" class="text-center">No recent service orders</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer clearfix">
        <a href="<?php echo e(route('admin.service-orders.index')); ?>" class="btn btn-sm btn-info float-right">View All Orders</a>
      </div>
    </div>
  </div>
  
  <!-- Recent Package Orders -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header border-transparent">
        <h3 class="card-title">Recent Package Orders</h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table m-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Package</th>
                <th>Customer</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $recentPackageOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td><a href="#">#<?php echo e($order->id); ?></a></td>
                <td><?php echo e(Str::limit($order->package_name ?? 'N/A', 20)); ?></td>
                <td><?php echo e(Str::limit($order->full_name ?? 'N/A', 20)); ?></td>
                <td>
                  <?php if($order->status == 'pending'): ?>
                    <span class="badge badge-warning">Pending</span>
                  <?php elseif($order->status == 'in_progress'): ?>
                    <span class="badge badge-info">In Progress</span>
                  <?php elseif($order->status == 'completed'): ?>
                    <span class="badge badge-success">Completed</span>
                  <?php else: ?>
                    <span class="badge badge-secondary"><?php echo e(ucfirst($order->status)); ?></span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="4" class="text-center">No recent package orders</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer clearfix">
        <a href="#" class="btn btn-sm btn-info float-right">View All Orders</a>
      </div>
    </div>
  </div>
</div>

<!-- Customer Requests -->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Recent Customer Requests</h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table m-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Page Name</th>
                <th>Social Media</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $recentRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td><a href="<?php echo e(route('admin.requests.show', $request->id)); ?>">#<?php echo e($request->id); ?></a></td>
                <td><?php echo e($request->user ? $request->user->name : 'N/A'); ?></td>
                <td><?php echo e(Str::limit($request->page_name, 20)); ?></td>
                <td><?php echo e($request->social_media); ?></td>
                <td>
                  <?php if($request->status == \App\Models\CustomerRequest::STATUS_PENDING): ?>
                    <span class="badge badge-warning">Pending</span>
                  <?php elseif($request->status == \App\Models\CustomerRequest::STATUS_IN_PROGRESS): ?>
                    <span class="badge badge-info">In Progress</span>
                  <?php elseif($request->status == \App\Models\CustomerRequest::STATUS_DONE): ?>
                    <span class="badge badge-success">Done</span>
                  <?php else: ?>
                    <span class="badge badge-secondary"><?php echo e(ucfirst($request->status)); ?></span>
                  <?php endif; ?>
                </td>
                <td><?php echo e($request->created_at->format('M d, Y')); ?></td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="6" class="text-center">No recent customer requests</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer clearfix">
        <a href="<?php echo e(route('admin.requests.index')); ?>" class="btn btn-sm btn-info float-right">View All Requests</a>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
<?php echo $__env->make('admin.partials.notification-test', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>