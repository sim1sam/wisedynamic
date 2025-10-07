@extends('adminlte::page')

@section('title', 'View Transaction')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Transaction #{{ $transaction->transaction_number }}</h1>
        <div>
            <a href="{{ route('admin.transactions.edit', $transaction) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Transaction
            </a>
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
                            <td>@formatDateTime12Hour($transaction->created_at)</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge {{ $transaction->getStatusBadgeClass() }}">{{ $transaction->getStatusDisplayName() }}</span>
                                @if($transaction->updated_by_admin)
                                    <br><small class="text-muted">Last updated by admin on @formatDateTime12Hour($transaction->admin_updated_at)</small>
                                @endif
                            </td>
                        </tr>
                        @if($transaction->notes)
                        <tr>
                            <th>Notes</th>
                            <td>{{ $transaction->notes }}</td>
                        </tr>
                        @endif
                        @if($transaction->admin_notes)
                        <tr>
                            <th>Admin Notes</th>
                            <td>{{ $transaction->admin_notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- SSL Payment Details - Only show for SSL transactions --}}
            @if($transaction->isSSLTransaction())
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">SSL Payment Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>SSL Transaction ID</th>
                                    <td>{{ $transaction->ssl_transaction_id }}</td>
                                </tr>
                                @if($transaction->ssl_session_id)
                                <tr>
                                    <th>SSL Session ID</th>
                                    <td>{{ $transaction->ssl_session_id }}</td>
                                </tr>
                                @endif
                                @if($transaction->ssl_bank_transaction_id)
                                <tr>
                                    <th>Bank Transaction ID</th>
                                    <td>{{ $transaction->ssl_bank_transaction_id }}</td>
                                </tr>
                                @endif
                                @if($transaction->ssl_card_type)
                                <tr>
                                    <th>Card Type</th>
                                    <td>{{ $transaction->ssl_card_type }}</td>
                                </tr>
                                @endif
                                @if($transaction->ssl_card_no)
                                <tr>
                                    <th>Card Number</th>
                                    <td>{{ $transaction->ssl_card_no }}</td>
                                </tr>
                                @endif
                                @if($transaction->ssl_card_issuer)
                                <tr>
                                    <th>Card Issuer</th>
                                    <td>{{ $transaction->ssl_card_issuer }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                @if($transaction->customer_name)
                                <tr>
                                    <th>Customer Name</th>
                                    <td>{{ $transaction->customer_name }}</td>
                                </tr>
                                @endif
                                @if($transaction->customer_email)
                                <tr>
                                    <th>Customer Email</th>
                                    <td>{{ $transaction->customer_email }}</td>
                                </tr>
                                @endif
                                @if($transaction->customer_phone)
                                <tr>
                                    <th>Customer Phone</th>
                                    <td>{{ $transaction->customer_phone }}</td>
                                </tr>
                                @endif
                                @if($transaction->customer_address)
                                <tr>
                                    <th>Customer Address</th>
                                    <td>{{ $transaction->customer_address }}</td>
                                </tr>
                                @endif
                                @if($transaction->ssl_currency_type && $transaction->ssl_currency_type !== 'BDT')
                                <tr>
                                    <th>Currency</th>
                                    <td>{{ $transaction->ssl_currency_type }}</td>
                                </tr>
                                @endif
                                @if($transaction->ssl_currency_amount)
                                <tr>
                                    <th>Currency Amount</th>
                                    <td>{{ $transaction->ssl_currency_amount }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    @if($transaction->ssl_response_data)
                    <div class="mt-3">
                        <h5>Full SSL Response Data</h5>
                        <div class="bg-light p-3 rounded">
                            <pre class="mb-0" style="max-height: 300px; overflow-y: auto;">{{ json_encode($transaction->ssl_response_data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
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
