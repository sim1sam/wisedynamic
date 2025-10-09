@extends('adminlte::page')

@section('title', 'Payment Audit Log Details')

@section('content_header')
    <h1>Payment Audit Log Details</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Log Information</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.payment-audit.index') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">ID</th>
                        <td>{{ $log->id }}</td>
                    </tr>
                    <tr>
                        <th>Action</th>
                        <td>
                            @if($log->action == 'payment_attempt')
                                <span class="badge badge-info">Payment Attempt</span>
                            @elseif($log->action == 'payment_success')
                                <span class="badge badge-success">Payment Success</span>
                            @elseif($log->action == 'payment_failure')
                                <span class="badge badge-danger">Payment Failure</span>
                            @else
                                <span class="badge badge-secondary">{{ ucwords(str_replace('_', ' ', $log->action)) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Transaction ID</th>
                        <td>{{ $log->transaction_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>
                            @if($log->user)
                                <span class="text-primary">{{ $log->user->name }}</span>
                                <br>
                                <small>{{ $log->user->email }}</small>
                            @else
                                <span class="text-muted">Guest</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>IP Address</th>
                        <td>{{ $log->ip_address }}</td>
                    </tr>
                    <tr>
                        <th>User Agent</th>
                        <td>
                            <small>{{ $log->user_agent }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        @if($transaction)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaction Information</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Transaction Number</th>
                            <td>{{ $transaction->transaction_number }}</td>
                        </tr>
                        <tr>
                            <th>SSL Transaction ID</th>
                            <td>{{ $transaction->ssl_transaction_id }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>{{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($transaction->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @elseif($transaction->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($transaction->status == 'failed')
                                    <span class="badge badge-danger">Failed</span>
                                @elseif($transaction->status == 'blocked')
                                    <span class="badge badge-dark">Blocked</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($transaction->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td>{{ $transaction->payment_method }}</td>
                        </tr>
                        <tr>
                            <th>Customer Name</th>
                            <td>{{ $transaction->customer_name }}</td>
                        </tr>
                        <tr>
                            <th>Customer Email</th>
                            <td>{{ $transaction->customer_email }}</td>
                        </tr>
                        <tr>
                            <th>Customer Phone</th>
                            <td>{{ $transaction->customer_phone }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Additional Data</h3>
            </div>
            <div class="card-body">
                @if($log->data)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($log->data as $key => $value)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>
                                            @if(is_array($value))
                                                <pre>{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        No additional data available.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
