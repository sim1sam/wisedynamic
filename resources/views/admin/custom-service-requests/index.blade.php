@extends('adminlte::page')

@section('title', 'Custom Service Requests')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Custom Service Requests</h1>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.custom-service-requests.stats') }}" class="btn btn-info btn-sm">
                <i class="fas fa-chart-bar"></i> Statistics
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filters</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.custom-service-requests.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="service_type">Service Type</label>
                            <select name="service_type" id="service_type" class="form-control">
                                <option value="">All Types</option>
                                <option value="marketing" {{ request('service_type') === 'marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="web_app" {{ request('service_type') === 'web_app' ? 'selected' : '' }}>Web/App Development</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">Search Customer</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Name or email..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @if(request()->hasAny(['status', 'service_type', 'search']))
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.custom-service-requests.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-times"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Custom Service Requests Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-cogs"></i> Custom Service Requests ({{ $customServiceRequests->total() }})</h3>
        </div>
        <div class="card-body table-responsive p-0">
            @if($customServiceRequests->count() > 0)
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Service Type</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customServiceRequests as $request)
                            <tr>
                                <td><strong>#{{ $request->id }}</strong></td>
                                <td>
                                    <div>
                                        <strong>{{ $request->user->name }}</strong><br>
                                        <small class="text-muted">{{ $request->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $request->service_type === 'marketing' ? 'warning' : 'info' }}">
                                        {{ $request->getServiceTypeLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $request->items->count() }} items</span>
                                </td>
                                <td>
                                    <strong class="text-success">BDT {{ number_format($request->total_amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if($request->payment_method === 'balance')
                                        <span class="badge badge-success">
                                            <i class="fas fa-wallet"></i> Balance
                                        </span>
                                    @else
                                        <span class="badge badge-primary">
                                            <i class="fas fa-credit-card"></i> SSL
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $request->getStatusColorClass() }}">
                                        {{ $request->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>
                                    @if($request->assignedTo)
                                        <small>{{ $request->assignedTo->name }}</small>
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $request->created_at->format('M d, Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.custom-service-requests.show', $request) }}" class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($request->status === 'pending')
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#statusModal{{ $request->id }}" title="Update Status">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Status Update Modal -->
                            @if($request->status === 'pending')
                                <div class="modal fade" id="statusModal{{ $request->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.custom-service-requests.update-status', $request) }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Update Request #{{ $request->id }}</h4>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="status{{ $request->id }}">Status</label>
                                                        <select name="status" id="status{{ $request->id }}" class="form-control" required>
                                                            <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="in_progress">In Progress</option>
                                                            <option value="completed">Completed</option>
                                                            <option value="cancelled">Cancelled</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="admin_notes{{ $request->id }}">Admin Notes</label>
                                                        <textarea name="admin_notes" id="admin_notes{{ $request->id }}" class="form-control" rows="3" placeholder="Add notes for the customer...">{{ $request->admin_notes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Custom Service Requests Found</h4>
                    <p class="text-muted">No custom service requests match your current filters.</p>
                </div>
            @endif
        </div>
        @if($customServiceRequests->hasPages())
            <div class="card-footer">
                {{ $customServiceRequests->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.75em;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@stop