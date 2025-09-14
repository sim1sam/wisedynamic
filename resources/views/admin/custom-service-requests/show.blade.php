@extends('adminlte::page')

@section('title', 'Custom Service Request #' . $customServiceRequest->id)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Custom Service Request #{{ $customServiceRequest->id }}</h1>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.custom-service-requests.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Request Overview -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Request Overview</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Request ID:</strong></td>
                                    <td>#{{ $customServiceRequest->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Service Type:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $customServiceRequest->service_type === 'marketing' ? 'warning' : 'info' }}">
                                            {{ $customServiceRequest->getServiceTypeLabel() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $customServiceRequest->getStatusColorClass() }}">
                                            {{ $customServiceRequest->getStatusLabel() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td><strong class="text-success">BDT {{ number_format($customServiceRequest->total_amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>
                                        @if($customServiceRequest->payment_method === 'balance')
                                            <span class="badge badge-success">
                                                <i class="fas fa-wallet"></i> Balance Payment
                                            </span>
                                        @else
                                            <span class="badge badge-primary">
                                                <i class="fas fa-credit-card"></i> SSL Payment
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $customServiceRequest->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @if($customServiceRequest->started_at)
                                    <tr>
                                        <td><strong>Started:</strong></td>
                                        <td>{{ $customServiceRequest->started_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endif
                                @if($customServiceRequest->completed_at)
                                    <tr>
                                        <td><strong>Completed:</strong></td>
                                        <td>{{ $customServiceRequest->completed_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>Assigned To:</strong></td>
                                    <td>
                                        @if($customServiceRequest->assignedTo)
                                            {{ $customServiceRequest->assignedTo->name }}
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Items Count:</strong></td>
                                    <td><span class="badge badge-secondary">{{ $customServiceRequest->items->count() }} items</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user"></i> Customer Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $customServiceRequest->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $customServiceRequest->user->email }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $customServiceRequest->user->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Balance:</strong></td>
                                    <td><strong class="text-success">BDT {{ number_format($customServiceRequest->user->balance, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Items -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list"></i> Service Items</h3>
                </div>
                <div class="card-body">
                    @foreach($customServiceRequest->items as $item)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $item->service_name }}</h5>
                                    <span class="badge badge-success">BDT {{ number_format($item->amount, 2) }}</span>
                                </div>
                                
                                <div class="row">
                                    @if($customServiceRequest->service_type === 'marketing')
                                        @if($item->platform)
                                            <div class="col-md-4">
                                                <small class="text-muted">Platform:</small><br>
                                                <strong>{{ $item->platform }}</strong>
                                            </div>
                                        @endif
                                        @if($item->duration_days)
                                            <div class="col-md-4">
                                                <small class="text-muted">Duration:</small><br>
                                                <strong>{{ $item->duration_days }} days</strong>
                                            </div>
                                        @endif
                                        @if($item->service_date)
                                            <div class="col-md-4">
                                                <small class="text-muted">Service Date:</small><br>
                                                <strong>{{ $item->service_date->format('M d, Y') }}</strong>
                                            </div>
                                        @endif
                                        @if($item->post_link)
                                            <div class="col-12 mt-2">
                                                <small class="text-muted">Post Link:</small><br>
                                                <a href="{{ $item->post_link }}" target="_blank" class="text-primary">{{ $item->post_link }}</a>
                                            </div>
                                        @endif
                                    @elseif($customServiceRequest->service_type === 'web_app')
                                        @if($item->domain_name)
                                            <div class="col-md-6">
                                                <small class="text-muted">Domain:</small><br>
                                                <strong>{{ $item->domain_name }}</strong>
                                            </div>
                                        @endif
                                        @if($item->duration_months)
                                            <div class="col-md-6">
                                                <small class="text-muted">Duration:</small><br>
                                                <strong>{{ $item->duration_months }} months</strong>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                
                                @if($item->description)
                                    <div class="mt-2">
                                        <small class="text-muted">Description:</small><br>
                                        <p class="mb-0">{{ $item->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Information -->
            @if($customServiceRequest->transaction || $customServiceRequest->ssl_transaction_id)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-credit-card"></i> Payment Information</h3>
                    </div>
                    <div class="card-body">
                        @if($customServiceRequest->transaction)
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Transaction #:</strong></td>
                                            <td>{{ $customServiceRequest->transaction->transaction_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Method:</strong></td>
                                            <td>{{ $customServiceRequest->transaction->payment_method }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge badge-success">
                                                    {{ ucfirst($customServiceRequest->transaction->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Amount:</strong></td>
                                            <td><strong class="text-success">BDT {{ number_format($customServiceRequest->transaction->amount, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Date:</strong></td>
                                            <td>{{ $customServiceRequest->transaction->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            @if($customServiceRequest->transaction->notes)
                                <div class="mt-3">
                                    <strong>Notes:</strong><br>
                                    <p class="text-muted">{{ $customServiceRequest->transaction->notes }}</p>
                                </div>
                            @endif
                        @endif
                        
                        @if($customServiceRequest->ssl_transaction_id)
                            <div class="mt-3">
                                <strong>SSL Transaction ID:</strong> {{ $customServiceRequest->ssl_transaction_id }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions Sidebar -->
        <div class="col-md-4">
            <!-- Status Management -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-tasks"></i> Status Management</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.custom-service-requests.update-status', $customServiceRequest) }}">
                        @csrf
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending" {{ $customServiceRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $customServiceRequest->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $customServiceRequest->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $customServiceRequest->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="admin_notes">Admin Notes</label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control" rows="4" placeholder="Add notes for the customer...">{{ $customServiceRequest->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Admin Notes -->
            @if($customServiceRequest->admin_notes)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-sticky-note"></i> Current Admin Notes</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <p class="mb-0">{{ $customServiceRequest->admin_notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Quick Stats</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Items</span>
                                    <span class="info-box-number">{{ $customServiceRequest->items->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Amount</span>
                                    <span class="info-box-number">{{ number_format($customServiceRequest->total_amount, 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table-borderless td {
            border: none;
            padding: 0.25rem 0.75rem;
        }
        .info-box {
            margin-bottom: 0;
        }
        .card-title {
            font-size: 1rem;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@stop