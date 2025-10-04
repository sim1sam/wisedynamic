<?php $__env->startSection('content'); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Customer Requests</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo e(route('admin.requests.index')); ?>">Requests</a></li>
          <li class="breadcrumb-item active">List</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <?php if(session('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">All Requests</h3>
            <div class="card-tools">
              <a href="<?php echo e(route('admin.requests.create')); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Request</a>
            </div>
          </div>
          <div class="card-body table-responsive p-0">
            <table id="requestsTable" class="table table-striped table-hover">
              <thead>
                <tr>
                  <th style="width: 220px;">Customer</th>
                  <th>Page Name</th>
                  <th>Social Media</th>
                  <th>Budget (BDT)</th>
                  <th>Days</th>
                  <th>Post Link</th>
                  <th>Status</th>
                  <th>Conversion</th>
                  <th>Created</th>
                  <th class="text-right" style="width: 240px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <tr>
                    <td>
                      <div class="font-weight-bold"><?php echo e($req->user->name ?? 'N/A'); ?></div>
                      <div class="text-muted small"><?php echo e($req->user->email ?? ''); ?></div>
                    </td>
                    <td><?php echo e($req->page_name); ?></td>
                    <td><?php echo e($req->social_media); ?></td>
                    <td><?php echo e(number_format((float)$req->ads_budget_bdt, 2)); ?></td>
                    <td><?php echo e($req->days); ?></td>
                    <td>
                      <?php if($req->post_link): ?>
                        <a href="<?php echo e($req->post_link); ?>" target="_blank" rel="noopener">Open</a>
                      <?php else: ?>
                        -
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="badge badge-secondary text-uppercase"><?php echo e(\Illuminate\Support\Str::headline($req->status)); ?></span>
                    </td>
                    <td>
                      <?php if($req->is_converted): ?>
                        <span class="badge badge-success">
                          <i class="fas fa-check-circle"></i> Converted
                        </span>
                        <?php if($req->service_order_id): ?>
                          <br><small class="text-muted">Order #<?php echo e($req->service_order_id); ?></small>
                        <?php endif; ?>
                      <?php else: ?>
                        <span class="badge badge-light">
                          <i class="fas fa-clock"></i> Not Converted
                        </span>
                      <?php endif; ?>
                    </td>
                    <td><?php echo e($req->created_at->format('Y-m-d H:i')); ?></td>
                    <td class="text-right">
                      <form method="POST" action="<?php echo e(route('admin.requests.status', $req)); ?>" class="form-inline justify-content-end">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <div class="input-group input-group-sm mr-2" style="width: 180px;">
                          <select name="status" class="form-control">
                            <option value="pending" <?php if($req->status==='pending'): echo 'selected'; endif; ?>>Pending</option>
                            <option value="in_progress" <?php if($req->status==='in_progress'): echo 'selected'; endif; ?>>In Progress</option>
                            <option value="done" <?php if($req->status==='done'): echo 'selected'; endif; ?>>Done</option>
                          </select>
                          <div class="input-group-append">
                            <button class="btn btn-info">Update</button>
                          </div>
                        </div>
                        <a href="<?php echo e(route('admin.requests.show', $req)); ?>" class="btn btn-default btn-sm mr-1"><i class="far fa-eye"></i></a>
                        <a href="<?php echo e(route('admin.requests.edit', $req)); ?>" class="btn btn-default btn-sm"><i class="far fa-edit"></i></a>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <tr>
                    <td colspan="10" class="text-center text-muted py-5">No requests found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <?php $__env->startSection('js'); ?>
          <script>
            $(function(){
              $('#requestsTable').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true,
                order: [[0, 'desc']]
              });
            });
          </script>
          <?php $__env->stopSection(); ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/requests/index.blade.php ENDPATH**/ ?>