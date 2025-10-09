@extends('adminlte::page')

@section('title', 'Payment Audit Logs')

@section('content_header')
    <h1>Payment Audit Logs</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payment Activity Logs</h3>
        <div class="card-tools">
            <a href="{{ route('admin.payment-audit.statistics') }}" class="btn btn-sm btn-info">
                <i class="fas fa-chart-bar"></i> View Statistics
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.payment-audit.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Action</label>
                        <select name="action" class="form-control">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $action)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control" value="{{ request('transaction_id') }}" placeholder="Transaction ID">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>User</label>
                        <select name="user_id" class="form-control">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date Range</label>
                        <div class="input-group">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            <div class="input-group-append input-group-prepend">
                                <span class="input-group-text">to</span>
                            </div>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.payment-audit.index') }}" class="btn btn-default">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Action</th>
                        <th>Transaction ID</th>
                        <th>User</th>
                        <th>Customer Info</th>
                        <th>IP Address</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>
                                @if($log->action == 'payment_attempt')
                                    <span class="badge badge-info">Attempt</span>
                                @elseif($log->action == 'payment_success')
                                    <span class="badge badge-success">Success</span>
                                @elseif($log->action == 'payment_failure')
                                    <span class="badge badge-danger">Failure</span>
                                @else
                                    <span class="badge badge-secondary">{{ $log->action }}</span>
                                @endif
                            </td>
                            <td>
                                @if($log->transaction_id)
                                    {{ $log->transaction_id }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($log->user)
                                    <span class="text-primary">{{ $log->user->name }}</span>
                                    <br>
                                    <small>{{ $log->user->email }}</small>
                                @else
                                    <span class="text-muted">Guest</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($transactions[$log->transaction_id]))
                                    @php $transaction = $transactions[$log->transaction_id]; @endphp
                                    <strong>{{ $transaction->customer_name }}</strong><br>
                                    <small>{{ $transaction->customer_email }}</small>
                                @elseif($log->data && isset($log->data['customer_name']))
                                    <strong>{{ $log->data['customer_name'] }}</strong><br>
                                    <small>{{ $log->data['customer_email'] ?? 'No email' }}</small>
                                @else
                                    <button class="btn btn-sm btn-outline-info load-customer-info" data-transaction-id="{{ $log->transaction_id }}">
                                        <i class="fas fa-user"></i> Load Info
                                    </button>
                                @endif
                            </td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <a href="{{ route('admin.payment-audit.show', $log->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No payment audit logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(function() {
        $('.load-customer-info').on('click', function() {
            const button = $(this);
            const transactionId = button.data('transaction-id');
            
            button.html('<i class="fas fa-spinner fa-spin"></i> Loading...');
            button.prop('disabled', true);
            
            $.ajax({
                url: '{{ url("admin/payment-audit-customer-info") }}/' + transactionId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const customer = response.customer;
                        let html = '';
                        
                        if (customer.name) {
                            html += '<strong>' + customer.name + '</strong><br>';
                        }
                        
                        if (customer.email) {
                            html += '<small>' + customer.email + '</small>';
                            
                            if (customer.is_registered) {
                                html += ' <span class="badge badge-success">Registered</span>';
                            }
                        }
                        
                        button.parent().html(html);
                    } else {
                        button.html('<i class="fas fa-times"></i> Not Found');
                        setTimeout(function() {
                            button.html('<i class="fas fa-user"></i> Load Info');
                            button.prop('disabled', false);
                        }, 3000);
                    }
                },
                error: function() {
                    button.html('<i class="fas fa-times"></i> Error');
                    setTimeout(function() {
                        button.html('<i class="fas fa-user"></i> Load Info');
                        button.prop('disabled', false);
                    }, 3000);
                }
            });
        });
    });
</script>
@stop
