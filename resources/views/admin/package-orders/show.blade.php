@extends('adminlte::page')

@section('title', 'View Package Order')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Package Order #{{ $order->id }}</h1>
        <a href="{{ route('admin.package-orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Details</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Package Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Package Name</th>
                                    <td>{{ $order->package_name }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>BDT {{ number_format($order->amount) }}</td>
                                </tr>
                                <tr>
                                    <th>Order Date</th>
                                    <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <form action="{{ route('admin.package-orders.update-status', $order) }}" method="POST" class="d-flex align-items-center">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-control form-control-sm mr-2">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $order->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $order->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $order->phone }}</td>
                                </tr>
                                @if($order->company)
                                <tr>
                                    <th>Company</th>
                                    <td>{{ $order->company }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Billing Address</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Address</th>
                                    <td>
                                        {{ $order->address_line1 }}<br>
                                        @if($order->address_line2)
                                            {{ $order->address_line2 }}<br>
                                        @endif
                                        {{ $order->city }}, {{ $order->state ?? '' }}<br>
                                        {{ $order->postal_code }}, {{ $order->country }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Project Details</h5>
                            <table class="table table-bordered">
                                @if($order->website_name)
                                <tr>
                                    <th>Website/Business Name</th>
                                    <td>{{ $order->website_name }}</td>
                                </tr>
                                @endif
                                @if($order->website_type)
                                <tr>
                                    <th>Website Type</th>
                                    <td>{{ $order->website_type }}</td>
                                </tr>
                                @endif
                                @if($order->page_count)
                                <tr>
                                    <th>Page Count</th>
                                    <td>{{ $order->page_count }}</td>
                                </tr>
                                @endif
                                @if($order->page_url)
                                <tr>
                                    <th>Page URL</th>
                                    <td>{{ $order->page_url }}</td>
                                </tr>
                                @endif
                                @if($order->ad_budget)
                                <tr>
                                    <th>Ad Budget</th>
                                    <td>BDT {{ number_format($order->ad_budget) }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($order->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Notes</h5>
                            <div class="p-3 bg-light rounded">
                                {{ $order->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Timeline</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div>
                            <i class="fas fa-shopping-cart bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ $order->created_at->format('h:i A') }}</span>
                                <h3 class="timeline-header">Order Placed</h3>
                                <div class="timeline-body">
                                    Order #{{ $order->id }} was placed on {{ $order->created_at->format('M d, Y') }}.
                                </div>
                            </div>
                        </div>
                        
                        @if($order->status !== 'pending')
                        <div>
                            <i class="fas fa-spinner bg-yellow"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ $order->updated_at->format('h:i A') }}</span>
                                <h3 class="timeline-header">Status Updated</h3>
                                <div class="timeline-body">
                                    Order status was updated to <strong>{{ ucfirst($order->status) }}</strong>.
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status === 'completed')
                        <div>
                            <i class="fas fa-check bg-green"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ $order->updated_at->format('h:i A') }}</span>
                                <h3 class="timeline-header">Order Completed</h3>
                                <div class="timeline-body">
                                    Order has been successfully completed.
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }
    
    .timeline > div {
        position: relative;
        margin-bottom: 15px;
    }
    
    .timeline > div > .timeline-item {
        margin-left: 60px;
        margin-right: 15px;
        margin-top: 0;
        background-color: #fff;
        color: #495057;
        padding: 0;
        position: relative;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: 0.25rem;
    }
    
    .timeline > div > .fa, 
    .timeline > div > .fas, 
    .timeline > div > .far, 
    .timeline > div > .fab, 
    .timeline > div > .glyphicon, 
    .timeline > div > .ion {
        width: 30px;
        height: 30px;
        font-size: .9rem;
        line-height: 30px;
        position: absolute;
        color: #fff;
        background-color: #007bff;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
    }
    
    .bg-blue {
        background-color: #007bff !important;
    }
    
    .bg-yellow {
        background-color: #ffc107 !important;
    }
    
    .bg-green {
        background-color: #28a745 !important;
    }
    
    .timeline-item > .timeline-header {
        margin: 0;
        padding: 10px;
        border-bottom: 1px solid rgba(0,0,0,.125);
        font-size: 16px;
        line-height: 1.1;
        color: #17a2b8;
    }
    
    .timeline-item > .time {
        float: right;
        padding: 10px;
        font-size: 12px;
        color: #6c757d;
    }
    
    .timeline-item > .timeline-body,
    .timeline-item > .timeline-footer {
        padding: 10px;
    }
</style>
@stop
