@extends('adminlte::page')

@section('title', 'Transactions')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Transactions</h1>
        <div>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#sslVerificationModal">
                <i class="fas fa-shield-alt"></i> SSL Verification Tools
            </button>
        </div>
    </div>
@stop

@section('content')
    <!-- SSL Status Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['ssl_transactions'] ?? 0 }}</h3>
                    <p>SSL Transactions</p>
                </div>
                <div class="icon">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['ssl_success'] ?? 0 }}</h3>
                    <p>SSL Success</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['ssl_pending'] ?? 0 }}</h3>
                    <p>SSL Pending</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['ssl_failed'] ?? 0 }}</h3>
                    <p>SSL Failed</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Transaction Management</h3>
            <div class="card-tools">
                <!-- Filter Form -->
                <form method="GET" class="form-inline">
                    <div class="input-group input-group-sm mr-2">
                        <select name="ssl_status" class="form-control">
                            <option value="">All SSL Status</option>
                            <option value="success" {{ request('ssl_status') == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="pending" {{ request('ssl_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('ssl_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mr-2">
                        <select name="payment_method" class="form-control">
                            <option value="">All Methods</option>
                            <option value="SSL Payment" {{ request('payment_method') == 'SSL Payment' ? 'selected' : '' }}>SSL Payment</option>
                            <option value="Manual" {{ request('payment_method') == 'Manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped" id="transactionsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Transaction #</th>
                        <th>Customer</th>
                        <th>Order Type</th>
                        <th>Order #</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>SSL ID</th>
                        <th>SSL Status</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->transaction_number }}</td>
                            <td>
                                @if($transaction->customer_name && $transaction->customer_email)
                                    <strong>{{ $transaction->customer_name }}</strong><br>
                                    <small class="text-muted">{{ $transaction->customer_email }}</small>
                                @elseif($transaction->fund_request_id && $transaction->fundRequest && $transaction->fundRequest->user)
                                    <strong>{{ $transaction->fundRequest->user->name }}</strong><br>
                                    <small class="text-muted">{{ $transaction->fundRequest->user->email }}</small>
                                @elseif($transaction->custom_service_request_id && $transaction->customServiceRequest && $transaction->customServiceRequest->user)
                                    <strong>{{ $transaction->customServiceRequest->user->name }}</strong><br>
                                    <small class="text-muted">{{ $transaction->customServiceRequest->user->email }}</small>
                                @elseif($transaction->package_order_id && $transaction->packageOrder && $transaction->packageOrder->user)
                                    <strong>{{ $transaction->packageOrder->user->name ?? $transaction->packageOrder->full_name }}</strong><br>
                                    <small class="text-muted">{{ $transaction->packageOrder->user->email ?? $transaction->packageOrder->email }}</small>
                                @elseif($transaction->service_order_id && $transaction->serviceOrder && $transaction->serviceOrder->user)
                                    <strong>{{ $transaction->serviceOrder->user->name ?? $transaction->serviceOrder->full_name }}</strong><br>
                                    <small class="text-muted">{{ $transaction->serviceOrder->user->email ?? $transaction->serviceOrder->email }}</small>
                                @elseif($transaction->isSSLTransaction())
                                    <strong>{{ $transaction->ssl_transaction_id }}</strong><br>
                                    <small class="text-muted">SSL Transaction</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->package_order_id)
                                    <span class="badge badge-info">Package</span>
                                @elseif($transaction->service_order_id)
                                    <span class="badge badge-success">Service</span>
                                @elseif($transaction->fund_request_id)
                                    <span class="badge badge-warning">Fund Request</span>
                                @elseif($transaction->custom_service_request_id)
                                    <span class="badge badge-primary">Custom Service</span>
                                @else
                                    <span class="badge badge-secondary">Unknown</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->package_order_id)
                                    <a href="{{ route('admin.package-orders.show', $transaction->package_order_id) }}">
                                        #{{ $transaction->package_order_id }}
                                    </a>
                                @elseif($transaction->service_order_id)
                                    <a href="{{ route('admin.service-orders.show', $transaction->service_order_id) }}">
                                        #{{ $transaction->service_order_id }}
                                    </a>
                                @elseif($transaction->fund_request_id)
                                    <a href="{{ route('admin.fund-requests.show', $transaction->fund_request_id) }}">
                                        #{{ $transaction->fund_request_id }}
                                    </a>
                                @elseif($transaction->custom_service_request_id)
                                    <a href="{{ route('admin.custom-service-requests.show', $transaction->custom_service_request_id) }}">
                                        #{{ $transaction->custom_service_request_id }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</td>
                            <td>
                                @if($transaction->payment_method == 'SSL Payment')
                                    <span class="badge badge-primary">
                                        <i class="fas fa-shield-alt"></i> SSL Payment
                                    </span>
                                @else
                                    <span class="badge badge-secondary">{{ $transaction->payment_method }}</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->ssl_transaction_id)
                                    <code>{{ $transaction->ssl_transaction_id }}</code>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->ssl_status)
                                    @if($transaction->ssl_status == 'success')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Success
                                        </span>
                                    @elseif($transaction->ssl_status == 'pending')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @elseif($transaction->ssl_status == 'failed')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> Failed
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">{{ $transaction->ssl_status }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                @if($transaction->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @elseif($transaction->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($transaction->status == 'failed')
                                    <span class="badge badge-danger">Failed</span>
                                @elseif($transaction->status == 'cancelled')
                                    <span class="badge badge-secondary">Cancelled</span>
                                @else
                                    <span class="badge badge-info">{{ ucfirst($transaction->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.transactions.edit', $transaction) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($transaction->payment_method == 'SSL Payment')
                                        <button type="button" class="btn btn-sm btn-primary ssl-verify-btn" 
                                                data-transaction-id="{{ $transaction->id }}"
                                                data-ssl-id="{{ $transaction->ssl_transaction_id }}"
                                                title="Verify with SSL">
                                            <i class="fas fa-shield-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success ssl-update-btn" 
                                                data-transaction-id="{{ $transaction->id }}"
                                                data-current-status="{{ $transaction->ssl_status }}"
                                                title="Update SSL Status">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    <!-- SSL Verification Modal -->
    <div class="modal fade" id="sslVerificationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">SSL Verification Tools</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Bulk SSL Verification</h5>
                                </div>
                                <div class="card-body">
                                    <p>Verify all pending SSL transactions with SSL Commerz API</p>
                                    <button type="button" class="btn btn-warning btn-block" id="bulkVerifyBtn">
                                        <i class="fas fa-sync"></i> Verify All Pending
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Manual Status Update</h5>
                                </div>
                                <div class="card-body">
                                    <form id="manualUpdateForm">
                                        <div class="form-group">
                                            <label>Transaction ID</label>
                                            <input type="number" class="form-control" name="transaction_id" required>
                                        </div>
                                        <div class="form-group">
                                            <label>New Status</label>
                                            <select class="form-control" name="status" required>
                                                <option value="success">Success</option>
                                                <option value="failed">Failed</option>
                                                <option value="pending">Pending</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Admin Notes</label>
                                            <textarea class="form-control" name="admin_notes" rows="3"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-save"></i> Update Status
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SSL Status Update Modal -->
    <div class="modal fade" id="sslUpdateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update SSL Status</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="sslUpdateForm">
                        <input type="hidden" name="transaction_id" id="updateTransactionId">
                        <div class="form-group">
                            <label>New SSL Status</label>
                            <select class="form-control" name="status" id="updateStatus" required>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Admin Notes</label>
                            <textarea class="form-control" name="admin_notes" rows="3" placeholder="Optional notes about this status update"></textarea>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="verify_with_ssl" id="verifyWithSsl">
                            <label class="form-check-label" for="verifyWithSsl">
                                Verify with SSL Commerz before updating
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSslUpdate">Update Status</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .btn-group .btn {
            margin-right: 5px;
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#transactionsTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "pageLength": 25,
        "order": [[ 0, "desc" ]]
    });

    // SSL Verify Button Click
    $(document).on('click', '.ssl-verify-btn', function() {
        console.log('SSL Verify button clicked');
        const transactionId = $(this).data('transaction-id');
        const sslId = $(this).data('ssl-id');
        const button = $(this);
        
        console.log('Transaction ID:', transactionId, 'SSL ID:', sslId);
        
        if (!transactionId) {
            console.error('No transaction ID found');
            alert('Transaction ID not found');
            return;
        }
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: `/admin/transactions/${transactionId}/verify-ssl`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('SSL Verify response:', response);
                if (response.success) {
                    alert('Success: ' + (response.message || 'SSL verification completed'));
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert('Error: ' + (response.message || 'SSL verification failed'));
                }
            },
            error: function(xhr) {
                console.error('SSL Verify error:', xhr);
                const response = xhr.responseJSON;
                alert(response?.message || 'SSL verification failed');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fas fa-shield-alt"></i>');
            }
        });
    });

    // SSL Update Button Click
    $(document).on('click', '.ssl-update-btn', function() {
        console.log('SSL Update button clicked');
        const transactionId = $(this).data('transaction-id');
        const currentStatus = $(this).data('current-status');
        
        console.log('Transaction ID:', transactionId, 'Current Status:', currentStatus);
        
        if (!transactionId) {
            console.error('No transaction ID found');
            alert('Transaction ID not found');
            return;
        }
        
        $('#updateTransactionId').val(transactionId);
        $('#updateStatus').val(currentStatus);
        $('#sslUpdateModal').modal('show');
    });

    // Confirm SSL Update
    $('#confirmSslUpdate').click(function() {
        console.log('Confirm SSL Update clicked');
        const formData = new FormData($('#sslUpdateForm')[0]);
        const transactionId = $('#updateTransactionId').val();
        
        console.log('Transaction ID:', transactionId);
        console.log('Form data:', Object.fromEntries(formData));
        
        if (!transactionId) {
            console.error('No transaction ID found');
            alert('Transaction ID not found');
            return;
        }
        
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        
        $.ajax({
            url: `/admin/transactions/${transactionId}/update-status`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('SSL Update response:', response);
                if (response.success) {
                    alert(response.message || 'Status updated successfully');
                    $('#sslUpdateModal').modal('hide');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert(response.message || 'Status update failed');
                }
            },
            error: function(xhr) {
                console.error('SSL Update error:', xhr);
                const response = xhr.responseJSON;
                alert(response?.message || 'Status update failed');
            },
            complete: function() {
                $('#confirmSslUpdate').prop('disabled', false).html('Update Status');
            }
        });
    });

    // Bulk Verify Button
    $('#bulkVerifyBtn').click(function() {
        const button = $(this);
        
        if (!confirm('This will verify all pending SSL transactions. Continue?')) {
            return;
        }
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Verifying...');
        
        $.ajax({
            url: '/admin/transactions/bulk-verify-ssl',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert(`Verified ${response.verified_count || 0} transactions`);
                    $('#sslVerificationModal').modal('hide');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    alert(response.message || 'Bulk verification failed');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response?.message || 'Bulk verification failed');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fas fa-sync"></i> Verify All Pending');
            }
        });
    });

    // Manual Update Form Submit
    $('#manualUpdateForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const transactionId = formData.get('transaction_id');
        
        $.ajax({
            url: `/admin/transactions/${transactionId}/update-status`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message || 'Status updated successfully');
                    $('#sslVerificationModal').modal('hide');
                    $('#manualUpdateForm')[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert(response.message || 'Status update failed');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response?.message || 'Status update failed');
            }
        });
    });
});
</script>
@stop
