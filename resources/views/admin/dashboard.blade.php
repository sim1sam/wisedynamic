@extends('adminlte::page')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<!-- Summary Stats -->
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $totalServiceOrders + $totalPackageOrders }}</h3>
        <p>Total Orders</p>
      </div>
      <div class="icon">
        <i class="fas fa-shopping-cart"></i>
      </div>
      <a href="{{ route('admin.service-orders.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ number_format($totalRevenue, 0) }} <sup style="font-size: 20px">BDT</sup></h3>
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
        <h3>{{ $totalUsers }}</h3>
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
        <h3>{{ $pendingServiceOrders + $pendingPackageOrders }}</h3>
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
                <span class="info-box-number">{{ $totalServices }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-light">
              <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Active Services</span>
                <span class="info-box-number">{{ $activeServices }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-light">
              <span class="info-box-icon"><i class="fas fa-star"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Featured</span>
                <span class="info-box-number">{{ $featuredServices }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="progress-group mt-3">
          <span class="progress-text">Service Orders Completion</span>
          <span class="float-right"><b>{{ $completedServiceOrders }}</b>/{{ $totalServiceOrders }}</span>
          <div class="progress">
            @php $completionPercentage = $totalServiceOrders > 0 ? ($completedServiceOrders / $totalServiceOrders) * 100 : 0; @endphp
            <div class="progress-bar bg-primary" style="width: {{ $completionPercentage }}%"></div>
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
                <span class="info-box-number">{{ $totalPackages }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-light">
              <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Active Packages</span>
                <span class="info-box-number">{{ $activePackages }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-light">
              <span class="info-box-icon"><i class="fas fa-star"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Featured</span>
                <span class="info-box-number">{{ $featuredPackages }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="progress-group mt-3">
          <span class="progress-text">Package Orders Completion</span>
          <span class="float-right"><b>{{ $completedPackageOrders }}</b>/{{ $totalPackageOrders }}</span>
          <div class="progress">
            @php $completionPercentage = $totalPackageOrders > 0 ? ($completedPackageOrders / $totalPackageOrders) * 100 : 0; @endphp
            <div class="progress-bar bg-success" style="width: {{ $completionPercentage }}%"></div>
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
              @forelse($recentServiceOrders as $order)
              <tr>
                <td><a href="{{ route('admin.service-orders.show', $order->id) }}">#{{ $order->id }}</a></td>
                <td>{{ Str::limit($order->service_name, 20) }}</td>
                <td>{{ Str::limit($order->full_name, 20) }}</td>
                <td>
                  @if($order->status == 'pending')
                    <span class="badge badge-warning">Pending</span>
                  @elseif($order->status == 'in_progress')
                    <span class="badge badge-info">In Progress</span>
                  @elseif($order->status == 'completed')
                    <span class="badge badge-success">Completed</span>
                  @else
                    <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center">No recent service orders</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer clearfix">
        <a href="{{ route('admin.service-orders.index') }}" class="btn btn-sm btn-info float-right">View All Orders</a>
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
              @forelse($recentPackageOrders as $order)
              <tr>
                <td><a href="#">#{{ $order->id }}</a></td>
                <td>{{ Str::limit($order->package_name ?? 'N/A', 20) }}</td>
                <td>{{ Str::limit($order->full_name ?? 'N/A', 20) }}</td>
                <td>
                  @if($order->status == 'pending')
                    <span class="badge badge-warning">Pending</span>
                  @elseif($order->status == 'in_progress')
                    <span class="badge badge-info">In Progress</span>
                  @elseif($order->status == 'completed')
                    <span class="badge badge-success">Completed</span>
                  @else
                    <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center">No recent package orders</td>
              </tr>
              @endforelse
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
              @forelse($recentRequests as $request)
              <tr>
                <td><a href="{{ route('admin.requests.show', $request->id) }}">#{{ $request->id }}</a></td>
                <td>{{ $request->user ? $request->user->name : 'N/A' }}</td>
                <td>{{ Str::limit($request->page_name, 20) }}</td>
                <td>{{ $request->social_media }}</td>
                <td>
                  @if($request->status == \App\Models\CustomerRequest::STATUS_PENDING)
                    <span class="badge badge-warning">Pending</span>
                  @elseif($request->status == \App\Models\CustomerRequest::STATUS_IN_PROGRESS)
                    <span class="badge badge-info">In Progress</span>
                  @elseif($request->status == \App\Models\CustomerRequest::STATUS_DONE)
                    <span class="badge badge-success">Done</span>
                  @else
                    <span class="badge badge-secondary">{{ ucfirst($request->status) }}</span>
                  @endif
                </td>
                <td>{{ $request->created_at->format('M d, Y') }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center">No recent customer requests</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer clearfix">
        <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-info float-right">View All Requests</a>
      </div>
    </div>
  </div>
</div>
@endsection
