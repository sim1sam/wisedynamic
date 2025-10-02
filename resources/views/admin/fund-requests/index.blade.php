@extends('adminlte::page')

@section('title', 'Fund Requests')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Fund Requests</h1>
        <div>
            <a href="{{ route('admin.fund-requests.create') }}" class="btn btn-primary btn-sm mr-2">
                <i class="fas fa-plus"></i> Create Fund Request
            </a>
            <span class="badge badge-warning">{{ $fundRequests->where('status', 'pending')->count() }} Pending</span>
            <span class="badge badge-success">{{ $fundRequests->where('status', 'approved')->count() }} Approved</span>
            <span class="badge badge-danger">{{ $fundRequests->where('status', 'rejected')->count() }} Rejected</span>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Fund Requests</h3>
            <div class="card-tools">
                <!-- Filter Form -->
                <form method="GET" class="form-inline">
                    <div class="input-group input-group-sm mr-2">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mr-2">
                        <select name="payment_method" class="form-control">
                            <option value="">All Payment Methods</option>
                            <option value="ssl" {{ request('payment_method') === 'ssl' ? 'selected' : '' }}>SSL Payment</option>
                            <option value="manual" {{ request('payment_method') === 'manual' ? 'selected' : '' }}>Manual Transfer</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('admin.fund-requests.index') }}" class="btn btn-secondary btn-sm ml-1">Clear</a>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif
            
            @if($fundRequests->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fundRequests as $request)
                            <tr>
                                <td>#{{ $request->id }}</td>
                                <td>
                                    <strong>{{ $request->user->name }}</strong><br>
                                    <small class="text-muted">{{ $request->user->email }}</small>
                                </td>
                                <td>
                                    <strong>BDT {{ number_format($request->amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if($request->payment_method === 'ssl')
                                        <span class="badge badge-info">SSL Payment</span>
                                    @else
                                        <span class="badge badge-secondary">Manual Transfer</span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($request->status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $request->created_at->format('M d, Y H:i') }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.fund-requests.show', $request) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($request->status === 'pending')
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#approveModal{{ $request->id }}">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal{{ $request->id }}">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Approve Modal -->
                            @if($request->status === 'pending')
                                <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.fund-requests.approve', $request) }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Approve Fund Request #{{ $request->id }}</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to approve this fund request?</p>
                                                    <p><strong>Customer:</strong> {{ $request->user->name }}</p>
                                                    <p><strong>Amount:</strong> BDT {{ number_format($request->amount, 2) }}</p>
                                                    <div class="form-group">
                                                        <label for="admin_notes{{ $request->id }}">Admin Notes (Optional)</label>
                                                        <textarea name="admin_notes" id="admin_notes{{ $request->id }}" class="form-control" rows="3" placeholder="Add any notes about this approval..."></textarea>
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
                                <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.fund-requests.reject', $request) }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Reject Fund Request #{{ $request->id }}</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to reject this fund request?</p>
                                                    <p><strong>Customer:</strong> {{ $request->user->name }}</p>
                                                    <p><strong>Amount:</strong> BDT {{ number_format($request->amount, 2) }}</p>
                                                    <div class="form-group">
                                                        <label for="reject_notes{{ $request->id }}">Rejection Reason *</label>
                                                        <textarea name="admin_notes" id="reject_notes{{ $request->id }}" class="form-control" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
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
                            @endif
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $fundRequests->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center p-4">
                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                    <h4>No fund requests found</h4>
                    <p class="text-muted">No fund requests match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <style>
        .badge {
            font-size: 0.75em;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
    </style>
@stop

@section('scripts')
    <script>
        // ...
    </script>
@stop