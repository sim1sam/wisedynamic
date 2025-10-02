@extends('layouts.admin')

@section('title', 'Create Fund Request')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Fund Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fund-requests.index') }}">Fund Requests</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Fund Request Details</h3>
                        </div>
                        
                        <form action="{{ route('admin.fund-requests.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <!-- User Selection -->
                                <div class="form-group">
                                    <label for="user_id">Select Customer <span class="text-danger">*</span></label>
                                    <select class="form-control" id="user_id" name="user_id" required>
                                        <option value="">Choose a customer...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Amount -->
                                <div class="form-group">
                                    <label for="amount">Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="amount" name="amount" 
                                           value="{{ old('amount') }}" min="1" max="100000" step="0.01" required>
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Service Info -->
                                <div class="form-group">
                                    <label for="service_info">Service Information</label>
                                    <textarea class="form-control" id="service_info" name="service_info" rows="3" 
                                              placeholder="Optional: Describe the service or purpose for this fund request">{{ old('service_info') }}</textarea>
                                    @error('service_info')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Payment Method -->
                                <div class="form-group">
                                    <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="">Select payment method...</option>
                                        <option value="ssl" {{ old('payment_method') == 'ssl' ? 'selected' : '' }}>SSL Payment</option>
                                        <option value="manual" {{ old('payment_method') == 'manual' ? 'selected' : '' }}>Manual Bank Transfer</option>
                                    </select>
                                    @error('payment_method')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Bank Details (shown when manual is selected) -->
                                <div id="bank-details" style="display: none;">
                                    <div class="form-group">
                                        <label for="bank_name">Bank Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bank_name" name="bank_name" 
                                               value="{{ old('bank_name') }}" placeholder="Enter bank name">
                                        @error('bank_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="account_number">Account Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="account_number" name="account_number" 
                                               value="{{ old('account_number') }}" placeholder="Enter account number">
                                        @error('account_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Admin Notes -->
                                <div class="form-group">
                                    <label for="admin_notes">Admin Notes</label>
                                    <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                              placeholder="Optional: Add any admin notes or comments">{{ old('admin_notes') }}</textarea>
                                    @error('admin_notes')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Auto Approve -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="auto_approve" name="auto_approve" value="1" {{ old('auto_approve') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="auto_approve">
                                            Auto-approve and add balance immediately
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        If checked, the fund request will be automatically approved and the amount will be added to the customer's balance.
                                    </small>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Fund Request
                                </button>
                                <a href="{{ route('admin.fund-requests.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Information</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Creating Fund Requests:</strong></p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Select a customer from the dropdown</li>
                                <li><i class="fas fa-check text-success"></i> Enter the amount (1-100,000 BDT)</li>
                                <li><i class="fas fa-check text-success"></i> Choose payment method</li>
                                <li><i class="fas fa-check text-success"></i> Optionally auto-approve</li>
                            </ul>
                            
                            <hr>
                            
                            <p><strong>Auto-Approval:</strong></p>
                            <p class="text-sm text-muted">
                                When auto-approval is enabled, the fund request will be immediately approved, 
                                the customer's balance will be updated, and a transaction record will be created.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide bank details based on payment method
    $('#payment_method').change(function() {
        if ($(this).val() === 'manual') {
            $('#bank-details').show();
            $('#bank_name, #account_number').prop('required', true);
        } else {
            $('#bank-details').hide();
            $('#bank_name, #account_number').prop('required', false).val(''); // Clear values when SSL selected
        }
    });

    // Trigger change event on page load to handle old values
    $('#payment_method').trigger('change');
});
</script>
@endpush
@stop
