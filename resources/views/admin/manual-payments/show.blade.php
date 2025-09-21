@php $title = 'Manual Payment Details'; @endphp
@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $title }} #{{ $manualPayment->id }}</h1>
        <a href="{{ route('admin.manual-payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
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

    <div class="row">
        <!-- Payment Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-university mr-2"></i>Payment Information
                    </h3>
                    <div class="card-tools">
                        @if($manualPayment->status === 'pending')
                            <span class="badge badge-warning badge-lg">
                                <i class="fas fa-clock mr-1"></i>Pending Verification
                            </span>
                        @elseif($manualPayment->status === 'approved')
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-check mr-1"></i>Approved
                            </span>
                        @else
                            <span class="badge badge-danger badge-lg">
                                <i class="fas fa-times mr-1"></i>Rejected
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Payment ID:</th>
                                    <td><strong>#{{ $manualPayment->id }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td><strong class="text-success">BDT {{ number_format($manualPayment->amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Bank Name:</th>
                                    <td>{{ $manualPayment->bank_name }}</td>
                                </tr>
                                <tr>
                                    <th>Account Number:</th>
                                    <td>{{ $manualPayment->account_number }}</td>
                                </tr>
                                @if($manualPayment->transaction_id)
                                <tr>
                                    <th>Transaction ID:</th>
                                    <td><code>{{ $manualPayment->transaction_id }}</code></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Submitted:</th>
                                    <td>{{ $manualPayment->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @if($manualPayment->verified_at)
                                <tr>
                                    <th>Verified:</th>
                                    <td>{{ $manualPayment->verified_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endif
                                @if($manualPayment->verifiedBy)
                                <tr>
                                    <th>Verified By:</th>
                                    <td>{{ $manualPayment->verifiedBy->name }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($manualPayment->status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($manualPayment->status === 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($manualPayment->admin_notes)
                        <div class="mt-3">
                            <h6><strong>Admin Notes:</strong></h6>
                            <div class="alert alert-info">
                                {{ $manualPayment->admin_notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-2"></i>Customer Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Name:</th>
                                    <td>{{ $manualPayment->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $manualPayment->user->email }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Phone:</th>
                                    <td>{{ $manualPayment->user->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Balance:</th>
                                    <td><strong class="text-info">BDT {{ number_format($manualPayment->user->balance ?? 0, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-cart mr-2"></i>Order Information
                    </h3>
                </div>
                <div class="card-body">
                    @php $order = $manualPayment->payable; @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Order Type:</th>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $manualPayment->payable_type === 'App\Models\PackageOrder' ? 'Package Order' : 'Service Order' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Order ID:</th>
                                    <td>
                                        <strong>#{{ $order->id }}</strong>
                                        <a href="{{ $manualPayment->payable_type === 'App\Models\PackageOrder' ? route('admin.package-orders.show', $order) : route('admin.service-orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-primary ml-2">
                                            <i class="fas fa-external-link-alt"></i> View Order
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Item:</th>
                                    <td>{{ $manualPayment->payable_type === 'App\Models\PackageOrder' ? $order->package->name : $order->service->name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Total Amount:</th>
                                    <td><strong>BDT {{ number_format($order->amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Paid Amount:</th>
                                    <td><strong class="text-success">BDT {{ number_format($order->paid_amount ?? 0, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Due Amount:</th>
                                    <td><strong class="text-danger">BDT {{ number_format($order->due_amount ?? $order->amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions & Screenshot -->
        <div class="col-md-4">
            <!-- Payment Screenshot -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-image mr-2"></i>Payment Screenshot
                    </h3>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $manualPayment->getScreenshotUrl() }}" 
                         alt="Payment Screenshot" 
                         class="img-fluid rounded shadow"
                         style="max-height: 300px; cursor: pointer;"
                         onclick="showImageModal('{{ $manualPayment->getScreenshotUrl() }}')">
                    <div class="mt-2">
                        <a href="{{ $manualPayment->getScreenshotUrl() }}" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt mr-1"></i>View Full Size
                        </a>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($manualPayment->status === 'pending')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cogs mr-2"></i>Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-block" onclick="approvePayment()">
                                <i class="fas fa-check mr-2"></i>Approve Payment
                            </button>
                            <button type="button" class="btn btn-danger btn-block" onclick="rejectPayment()">
                                <i class="fas fa-times mr-2"></i>Reject Payment
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Screenshot</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Payment Screenshot" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    $('#imageModal').modal('show');
}

function approvePayment() {
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
            form.action = '{{ route("admin.manual-payments.approve", $manualPayment) }}';
            
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

function rejectPayment() {
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
            form.action = '{{ route("admin.manual-payments.reject", $manualPayment) }}';
            
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
</script>
@stop