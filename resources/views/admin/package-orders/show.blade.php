@extends('adminlte::page')

@section('title', 'View Package Order')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Package Order #{{ $order->id }}</h1>
        <div>
            <a href="{{ route('admin.package-orders.edit', $order) }}" class="btn btn-info mr-2">
                <i class="fas fa-edit"></i> Edit Order
            </a>
            <a href="{{ route('admin.package-orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
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
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('admin.package-orders.update-status', $order) }}" method="POST" class="d-flex align-items-center mr-2">
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
                                            
                                            @if($order->status === 'pending')
                                            <form action="{{ route('admin.package-orders.accept', $order) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Accept Order
                                                </button>
                                            </form>
                                            @endif
                                            
                                            @if($order->status === 'processing')
                                            <form action="{{ route('admin.package-orders.complete', $order) }}" method="POST" class="ml-2">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check-double"></i> Mark Completed
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th>Payment Status</th>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1"><strong>Total Amount:</strong> BDT {{ number_format($order->amount) }}</p>
                                                <p class="mb-1"><strong>Paid Amount:</strong> BDT {{ number_format($order->paid_amount ?? 0) }}</p>
                                                <p class="mb-1"><strong>Due Amount:</strong> BDT {{ number_format($order->due_amount ?? $order->amount) }}</p>
                                                <p class="mb-1">
                                                    <strong>Payment Status:</strong>
                                                    @if(($order->paid_amount ?? 0) <= 0)
                                                        <span class="badge badge-danger">Not Paid</span>
                                                    @elseif(($order->due_amount ?? $order->amount) <= 0)
                                                        <span class="badge badge-success">Fully Paid</span>
                                                    @else
                                                        <span class="badge badge-warning">Partially Paid</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                
                                @if($order->status === 'processing')
                                <tr>
                                    <th>Process Payment</th>
                                    <td>
                                        <form action="{{ route('admin.package-orders.process-payment', $order) }}" method="POST">
                                            @csrf
                                            <div class="form-group mb-2">
                                                <label for="payment_amount">Payment Amount</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">BDT</span>
                                                    </div>
                                                    <input type="number" id="payment_amount" name="payment_amount" class="form-control" value="{{ $order->due_amount }}" min="1" max="{{ $order->due_amount }}" required>
                                                </div>
                                                <small class="text-muted">Enter any amount up to the full due amount</small>
                                            </div>
                                            
                                            <div class="form-group mb-2">
                                                <label for="payment_method">Payment Method</label>
                                                <select id="payment_method" name="payment_method" class="form-control">
                                                    <option value="cash">Cash</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                    <option value="card">Credit/Debit Card</option>
                                                    <option value="mobile_banking">Mobile Banking</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group mb-2">
                                                <label for="notes">Notes</label>
                                                <textarea id="notes" name="notes" class="form-control" rows="2"></textarea>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-money-bill-wave"></i> Process Payment
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endif
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
                            </table>
                        </div>
                    </div>

                    <!-- Transactions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Transaction History</h5>
                            @if(count($transactions) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Transaction #</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Method</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transactions as $transaction)
                                                <tr>
                                                    <td>{{ $transaction->transaction_number }}</td>
                                                    <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                                                    <td>BDT {{ number_format($transaction->amount) }}</td>
                                                    <td>
                                                        <span class="badge badge-info">{{ ucfirst($transaction->payment_method) }}</span>
                                                    </td>
                                                    <td>{{ $transaction->notes }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-right">Total Paid:</th>
                                                <th colspan="3">BDT {{ number_format($order->paid_amount ?? 0) }}</th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" class="text-right">Remaining:</th>
                                                <th colspan="3">BDT {{ number_format($order->due_amount ?? $order->amount) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No transactions have been recorded yet.</p>
                            @endif
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
