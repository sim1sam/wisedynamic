@php $title = 'Manual Payment Requests'; @endphp
@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $title }}</h1>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.manual-payments.index', ['status' => 'all']) }}" 
               class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                All
            </a>
            <a href="{{ route('admin.manual-payments.index', ['status' => 'pending']) }}" 
               class="btn btn-sm {{ $status === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                Pending
            </a>
            <a href="{{ route('admin.manual-payments.index', ['status' => 'approved']) }}" 
               class="btn btn-sm {{ $status === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                Approved
            </a>
            <a href="{{ route('admin.manual-payments.index', ['status' => 'rejected']) }}" 
               class="btn btn-sm {{ $status === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                Rejected
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-university mr-2"></i>
                Manual Payment Requests
                @if($status !== 'all')
                    <span class="badge badge-{{ $status === 'pending' ? 'warning' : ($status === 'approved' ? 'success' : 'danger') }} ml-2">
                        {{ ucfirst($status) }}
                    </span>
                @endif
            </h3>
        </div>
        <div class="card-body p-0">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table id="manualPaymentsTable" class="table table-striped table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Order</th>
                                <th>Amount</th>
                                <th>Bank Details</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <strong>#{{ $payment->id }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $payment->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $payment->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>
                                                {{ $payment->payable_type === 'App\Models\PackageOrder' ? 'Package' : 'Service' }} 
                                                Order #{{ $payment->payable->id }}
                                            </strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $payment->payable_type === 'App\Models\PackageOrder' ? $payment->payable->package->name : $payment->payable->service->name }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success">BDT {{ number_format($payment->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $payment->bank_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $payment->account_number }}</small>
                                            @if($payment->transaction_id)
                                                <br>
                                                <small class="text-info">TXN: {{ $payment->transaction_id }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($payment->status === 'pending')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @elseif($payment->status === 'approved')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check mr-1"></i>Approved
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times mr-1"></i>Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            {{ $payment->created_at->format('M d, Y') }}
                                            <br>
                                            <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.manual-payments.show', $payment) }}" 
                                               class="btn btn-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($payment->status === 'pending')
                                                <button type="button" class="btn btn-success btn-sm" 
                                                        onclick="approvePayment({{ $payment->id }})" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="rejectPayment({{ $payment->id }})" title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-university fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No manual payment requests found</h5>
                    <p class="text-muted">
                        @if($status === 'pending')
                            There are no pending payment requests at the moment.
                        @elseif($status === 'approved')
                            No approved payment requests found.
                        @elseif($status === 'rejected')
                            No rejected payment requests found.
                        @else
                            No payment requests have been submitted yet.
                        @endif
                    </p>
                </div>
            @endif
        </div>
        
    </div>
@stop
@section('js')
<script>
function approvePayment(paymentId) {
    Swal.fire({
        title: 'Approve Payment?',
        text: 'This will create a transaction and update the order payment status.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Approve',
        cancelButtonText: 'Cancel',
        input: 'textarea',
        inputPlaceholder: 'Add admin notes (optional)...',
        inputAttributes: {
            'aria-label': 'Admin notes'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/manual-payments/${paymentId}/approve`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            if (result.value) {
                const notesInput = document.createElement('input');
                notesInput.type = 'hidden';
                notesInput.name = 'admin_notes';
                notesInput.value = result.value;
                form.appendChild(notesInput);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function rejectPayment(paymentId) {
    Swal.fire({
        title: 'Reject Payment?',
        text: 'Please provide a reason for rejecting this payment.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel',
        input: 'textarea',
        inputPlaceholder: 'Reason for rejection (required)...',
        inputAttributes: {
            'aria-label': 'Rejection reason'
        },
        inputValidator: (value) => {
            if (!value) {
                return 'You need to provide a reason for rejection!'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/manual-payments/${paymentId}/reject`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'admin_notes';
            notesInput.value = result.value;
            form.appendChild(notesInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

$(document).ready(function() {
    $('#manualPaymentsTable').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        order: [[0, 'desc']]
    });
});
</script>
@stop