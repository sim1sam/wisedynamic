@extends('adminlte::page')

@section('title', 'View Transaction')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Transaction #{{ $transaction->transaction_number }}</h1>
        <div>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Transactions
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaction Details</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <table class="table table-bordered">
                        <tr>
                            <th>Transaction ID</th>
                            <td>{{ $transaction->id }}</td>
                        </tr>
                        <tr>
                            <th>Transaction Number</th>
                            <td>{{ $transaction->transaction_number }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>BDT {{ number_format($transaction->amount) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($transaction->payment_method) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($transaction->status === 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @else
                                    <span class="badge badge-warning">{{ ucfirst($transaction->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @if($transaction->notes)
                        <tr>
                            <th>Notes</th>
                            <td>{{ $transaction->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Related Order</h3>
                </div>
                <div class="card-body">
                    @if($transaction->packageOrder)
                        <h5>Package Order #{{ $transaction->packageOrder->id }}</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Package</th>
                                <td>{{ $transaction->packageOrder->package_name }}</td>
                            </tr>
                            <tr>
                                <th>Customer</th>
                                <td>{{ $transaction->packageOrder->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>BDT {{ number_format($transaction->packageOrder->amount) }}</td>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <td>BDT {{ number_format($transaction->packageOrder->paid_amount) }}</td>
                            </tr>
                            <tr>
                                <th>Due Amount</th>
                                <td>BDT {{ number_format($transaction->packageOrder->due_amount) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($transaction->packageOrder->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($transaction->packageOrder->status === 'processing')
                                        <span class="badge badge-info">Processing</span>
                                    @elseif($transaction->packageOrder->status === 'completed')
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($transaction->packageOrder->status === 'cancelled')
                                        <span class="badge badge-danger">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('admin.package-orders.show', $transaction->packageOrder) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-eye"></i> View Order
                        </a>
                    @elseif($transaction->serviceOrder)
                        <h5>Service Order #{{ $transaction->serviceOrder->id }}</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Service</th>
                                <td>{{ $transaction->serviceOrder->service_name }}</td>
                            </tr>
                            <tr>
                                <th>Customer</th>
                                <td>{{ $transaction->serviceOrder->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>BDT {{ number_format($transaction->serviceOrder->amount) }}</td>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <td>BDT {{ number_format($transaction->serviceOrder->paid_amount) }}</td>
                            </tr>
                            <tr>
                                <th>Due Amount</th>
                                <td>BDT {{ number_format($transaction->serviceOrder->due_amount) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($transaction->serviceOrder->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($transaction->serviceOrder->status === 'processing')
                                        <span class="badge badge-info">Processing</span>
                                    @elseif($transaction->serviceOrder->status === 'completed')
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($transaction->serviceOrder->status === 'cancelled')
                                        <span class="badge badge-danger">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('admin.service-orders.show', $transaction->serviceOrder) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-eye"></i> View Order
                        </a>
                    @else
                        <p class="text-muted">No related order found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
