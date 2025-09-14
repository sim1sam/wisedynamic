@extends('adminlte::page')

@section('title', 'Fund Request Details')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Fund Request #{{ $fundRequest->id }}</h1>
        <div>
            <a href="{{ route('admin.fund-requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            @if($fundRequest->status === 'pending')
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal">
                    <i class="fas fa-check"></i> Approve
                </button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                    <i class="fas fa-times"></i> Reject
                </button>
            @endif
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Request Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Request Information</h3>
                    <div class="card-tools">
                        @if($fundRequest->status === 'pending')
                            <span class="badge badge-warning badge-lg">Pending Review</span>
                        @elseif($fundRequest->status === 'approved')
                            <span class="badge badge-success badge-lg">Approved</span>
                        @else
                            <span class="badge badge-danger badge-lg">Rejected</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Request ID:</strong> #{{ $fundRequest->id }}<br>
                            <strong>Amount:</strong> BDT {{ number_format($fundRequest->amount, 2) }}<br>
                            <strong>Payment Method:</strong> 
                            @if($fundRequest->payment_method === 'ssl')
                                <span class="badge badge-info">SSL Payment</span>
                            @else
                                <span class="badge badge-secondary">Manual Bank Transfer</span>
                            @endif<br>
                            <strong>Request Date:</strong> {{ $fundRequest->created_at->format('M d, Y H:i A') }}<br>
                        </div>
                        <div class="col-md-6">
                            @if($fundRequest->approved_at)
                                <strong>Approved Date:</strong> {{ $fundRequest->approved_at->format('M d, Y H:i A') }}<br>
                            @endif
                            @if($fundRequest->approvedBy)
                                <strong>Approved By:</strong> {{ $fundRequest->approvedBy->name }}<br>
                            @endif
                            @if($fundRequest->ssl_transaction_id)
                                <strong>SSL Transaction ID:</strong> {{ $fundRequest->ssl_transaction_id }}<br>
                            @endif
                        </div>
                    </div>
                    
                    @if($fundRequest->service_info)
                        <hr>
                        <strong>Service Information:</strong>
                        <p class="mt-2">{{ $fundRequest->service_info }}</p>
                    @endif
                    
                    @if($fundRequest->admin_notes)
                        <hr>
                        <strong>Admin Notes:</strong>
                        <p class="mt-2">{{ $fundRequest->admin_notes }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Manual Payment Details -->
            @if($fundRequest->payment_method === 'manual')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bank Transfer Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Bank Name:</strong> {{ $fundRequest->bank_name ?: 'Not provided' }}<br>
                                <strong>Account Number:</strong> {{ $fundRequest->account_number ?: 'Not provided' }}<br>
                            </div>
                        </div>
                        
                        @if($fundRequest->payment_screenshot)
                            <hr>
                            <strong>Payment Screenshot:</strong>
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $fundRequest->payment_screenshot) }}" 
                                     alt="Payment Screenshot" 
                                     class="img-fluid" 
                                     style="max-width: 500px; cursor: pointer;" 
                                     data-toggle="modal" 
                                     data-target="#screenshotModal">
                                <br><small class="text-muted">Click to view full size</small>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- SSL Payment Details -->
            @if($fundRequest->payment_method === 'ssl' && $fundRequest->ssl_response)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">SSL Payment Response</h3>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3">{{ json_encode($fundRequest->ssl_response, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Customer Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer Information</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($fundRequest->user->profile_image)
                            <img src="{{ asset('storage/' . $fundRequest->user->profile_image) }}" 
                                 alt="Profile Image" 
                                 class="img-circle" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="img-circle bg-secondary d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        @endif
                    </div>
                    
                    <strong>Name:</strong> {{ $fundRequest->user->name }}<br>
                    <strong>Email:</strong> {{ $fundRequest->user->email }}<br>
                    <strong>Phone:</strong> {{ $fundRequest->user->phone ?: 'Not provided' }}<br>
                    <strong>Current Balance:</strong> BDT {{ number_format($fundRequest->user->balance, 2) }}<br>
                    <strong>Status:</strong> 
                    @if($fundRequest->user->status === 'active')
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">{{ ucfirst($fundRequest->user->status) }}</span>
                    @endif<br>
                    <strong>Member Since:</strong> {{ $fundRequest->user->created_at->format('M d, Y') }}<br>
                    
                    <hr>
                    <strong>Total Fund Requests:</strong> {{ $fundRequest->user->fundRequests()->count() }}<br>
                    <strong>Approved Requests:</strong> {{ $fundRequest->user->fundRequests()->approved()->count() }}<br>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Screenshot Modal -->
    @if($fundRequest->payment_method === 'manual' && $fundRequest->payment_screenshot)
        <div class="modal fade" id="screenshotModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Payment Screenshot</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('storage/' . $fundRequest->payment_screenshot) }}" 
                             alt="Payment Screenshot" 
                             class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Approve Modal -->
    @if($fundRequest->status === 'pending')
        <div class="modal fade" id="approveModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.fund-requests.approve', $fundRequest) }}">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Approve Fund Request</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to approve this fund request?</p>
                            <div class="alert alert-info">
                                <strong>Amount:</strong> BDT {{ number_format($fundRequest->amount, 2) }}<br>
                                <strong>Customer:</strong> {{ $fundRequest->user->name }}<br>
                                <strong>Current Balance:</strong> BDT {{ number_format($fundRequest->user->balance, 2) }}<br>
                                <strong>New Balance:</strong> BDT {{ number_format($fundRequest->user->balance + $fundRequest->amount, 2) }}
                            </div>
                            <div class="form-group">
                                <label for="admin_notes">Admin Notes (Optional)</label>
                                <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" placeholder="Add any notes about this approval..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Approve Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.fund-requests.reject', $fundRequest) }}">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Reject Fund Request</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to reject this fund request?</p>
                            <div class="alert alert-warning">
                                <strong>Amount:</strong> BDT {{ number_format($fundRequest->amount, 2) }}<br>
                                <strong>Customer:</strong> {{ $fundRequest->user->name }}
                            </div>
                            <div class="form-group">
                                <label for="reject_notes">Rejection Reason *</label>
                                <textarea name="admin_notes" id="reject_notes" class="form-control" rows="3" placeholder="Please provide a clear reason for rejection..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop

@section('css')
    <style>
        .badge-lg {
            font-size: 1em;
            padding: 0.5em 0.75em;
        }
        .img-circle {
            border-radius: 50%;
        }
    </style>
@stop