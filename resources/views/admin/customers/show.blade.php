@extends('adminlte::page')

@section('title', 'Customer Details')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Customer: {{ $customer->name }}</h1>
        <div>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Customers
            </a>
            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Customer
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>
        
        <!-- Customer Information -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer Information</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">ID</th>
                            <td>{{ $customer->id }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $customer->phone ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($customer->status === 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($customer->status === 'blocked')
                                    <span class="badge badge-danger">Blocked</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($customer->status ?? 'Unknown') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Current Balance</th>
                            <td>
                                <span class="badge badge-success">৳{{ number_format($customer->balance ?? 0, 2) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Registered</th>
                            <td>{{ $customer->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    @if($customer->status === 'active')
                        <form action="{{ route('admin.customers.update-status', $customer) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="blocked">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to block this customer?')">
                                <i class="fas fa-ban"></i> Block Customer
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.customers.update-status', $customer) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to activate this customer?')">
                                <i class="fas fa-check"></i> Activate Customer
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline float-right">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                            <i class="fas fa-trash"></i> Delete Customer
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Fund Information -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fund Information</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%">Total Fund Requested</th>
                            <td>
                                <span class="badge badge-info">৳{{ number_format($totalFundRequested ?? 0, 2) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Total Fund Approved</th>
                            <td>
                                <span class="badge badge-success">৳{{ number_format($totalFundApproved ?? 0, 2) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Pending Fund Requests</th>
                            <td>
                                <span class="badge badge-warning">৳{{ number_format($pendingFundRequests ?? 0, 2) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Available Balance</th>
                            <td>
                                <span class="badge badge-primary">৳{{ number_format($customer->balance ?? 0, 2) }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.fund-requests.create') }}?user_id={{ $customer->id }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Create Fund Request
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Address Information -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Address Information</h3>
                </div>
                <div class="card-body">
                    @if($customer->address || $customer->city || $customer->state || $customer->postal_code || $customer->country)
                        <table class="table table-bordered">
                            @if($customer->address)
                                <tr>
                                    <th style="width: 20%">Address</th>
                                    <td>{{ $customer->address }}</td>
                                </tr>
                            @endif
                            @if($customer->city)
                                <tr>
                                    <th>City</th>
                                    <td>{{ $customer->city }}</td>
                                </tr>
                            @endif
                            @if($customer->state)
                                <tr>
                                    <th>State/Province</th>
                                    <td>{{ $customer->state }}</td>
                                </tr>
                            @endif
                            @if($customer->postal_code)
                                <tr>
                                    <th>Postal Code</th>
                                    <td>{{ $customer->postal_code }}</td>
                                </tr>
                            @endif
                            @if($customer->country)
                                <tr>
                                    <th>Country</th>
                                    <td>{{ $customer->country }}</td>
                                </tr>
                            @endif
                        </table>
                    @else
                        <div class="text-muted">No address information provided</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fund Requests -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fund Requests</h3>
                </div>
                <div class="card-body">
                    @if($fundRequests->count() > 0)
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
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
                                        <td>{{ $request->id }}</td>
                                        <td>৳{{ number_format($request->amount, 2) }}</td>
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
                                            @elseif($request->status === 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($request->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.fund-requests.show', $request) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-muted">No fund requests found</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Package Orders -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Package Orders</h3>
                </div>
                <div class="card-body">
                    @if($packageOrders->count() > 0)
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packageOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->package->name ?? 'Unknown Package' }}</td>
                                        <td>${{ number_format($order->amount, 2) }}</td>
                                        <td>
                                            @if($order->status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($order->status === 'processing')
                                                <span class="badge badge-info">Processing</span>
                                            @elseif($order->status === 'completed')
                                                <span class="badge badge-success">Completed</span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="badge badge-danger">Cancelled</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.package-orders.show', $order) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-muted">No package orders found</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Service Orders -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service Orders</h3>
                </div>
                <div class="card-body">
                    @if($serviceOrders->count() > 0)
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Service</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviceOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->service->name ?? 'Unknown Service' }}</td>
                                        <td>${{ number_format($order->amount, 2) }}</td>
                                        <td>
                                            @if($order->status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($order->status === 'processing')
                                                <span class="badge badge-info">Processing</span>
                                            @elseif($order->status === 'completed')
                                                <span class="badge badge-success">Completed</span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="badge badge-danger">Cancelled</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.service-orders.show', $order) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-muted">No service orders found</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@stop
