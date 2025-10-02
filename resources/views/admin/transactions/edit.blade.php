@extends('adminlte::page')

@section('title', 'Edit Transaction')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Edit Transaction #{{ $transaction->transaction_number }}</h1>
        <div>
            <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Transaction
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Transaction Details</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.transactions.update', $transaction) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        @foreach(\App\Models\Transaction::getStatusOptions() as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', $transaction->status) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Current status: <span class="badge {{ $transaction->getStatusBadgeClass() }}">{{ $transaction->getStatusDisplayName() }}</span>
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="amount">Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount', $transaction->amount) }}" 
                                           step="0.01" min="0" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                                        <option value="SSL Payment" {{ old('payment_method', $transaction->payment_method) === 'SSL Payment' ? 'selected' : '' }}>SSL Payment</option>
                                        <option value="Manual Bank Transfer" {{ old('payment_method', $transaction->payment_method) === 'Manual Bank Transfer' ? 'selected' : '' }}>Manual Bank Transfer</option>
                                        <option value="Cash" {{ old('payment_method', $transaction->payment_method) === 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Mobile Banking" {{ old('payment_method', $transaction->payment_method) === 'Mobile Banking' ? 'selected' : '' }}>Mobile Banking</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                @if($transaction->isSSLTransaction())
                                <div class="form-group">
                                    <label for="ssl_transaction_id">SSL Transaction ID</label>
                                    <input type="text" name="ssl_transaction_id" id="ssl_transaction_id" 
                                           class="form-control @error('ssl_transaction_id') is-invalid @enderror" 
                                           value="{{ old('ssl_transaction_id', $transaction->ssl_transaction_id) }}">
                                    @error('ssl_transaction_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="customer_name">Customer Name</label>
                                    <input type="text" name="customer_name" id="customer_name" 
                                           class="form-control @error('customer_name') is-invalid @enderror" 
                                           value="{{ old('customer_name', $transaction->customer_name) }}">
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="customer_email">Customer Email</label>
                                    <input type="email" name="customer_email" id="customer_email" 
                                           class="form-control @error('customer_email') is-invalid @enderror" 
                                           value="{{ old('customer_email', $transaction->customer_email) }}">
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="customer_phone">Customer Phone</label>
                                    <input type="text" name="customer_phone" id="customer_phone" 
                                           class="form-control @error('customer_phone') is-invalid @enderror" 
                                           value="{{ old('customer_phone', $transaction->customer_phone) }}">
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Transaction Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      placeholder="Add any notes about this transaction...">{{ old('notes', $transaction->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="admin_notes">Admin Notes</label>
                            <textarea name="admin_notes" id="admin_notes" rows="3" 
                                      class="form-control @error('admin_notes') is-invalid @enderror" 
                                      placeholder="Internal admin notes (not visible to customers)...">{{ old('admin_notes', $transaction->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Transaction
                            </button>
                            <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Change Impact</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Important Notes:</h6>
                        <ul class="mb-0">
                            <li><strong>Failed → Success:</strong> Will update related order payment status and add balance (for fund requests)</li>
                            <li><strong>Success → Failed:</strong> Will reverse payment and deduct balance if applicable</li>
                            <li><strong>Network Issues:</strong> Use this form to manually correct transaction status after SSL gateway issues</li>
                        </ul>
                    </div>

                    @if($transaction->packageOrder || $transaction->serviceOrder)
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle"></i> Order Impact:</h6>
                            <p class="mb-0">Changing this transaction status will affect the related order's payment status and due amount.</p>
                        </div>
                    @endif

                    @if($transaction->fundRequest)
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-wallet"></i> Balance Impact:</h6>
                            <p class="mb-0">Changing this transaction status will affect the user's account balance.</p>
                        </div>
                    @endif

                    @if($transaction->updated_by_admin)
                        <div class="mt-3">
                            <h6>Last Updated By Admin:</h6>
                            <p class="text-muted mb-0">
                                Admin ID: {{ $transaction->updated_by_admin }}<br>
                                Date: {{ $transaction->admin_updated_at ? $transaction->admin_updated_at->format('M d, Y h:i A') : 'N/A' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Show/hide SSL fields based on payment method
    $('#payment_method').change(function() {
        var isSSL = $(this).val() === 'SSL Payment';
        $('.ssl-fields').toggle(isSSL);
    });
    
    // Status change confirmation
    $('#status').change(function() {
        var newStatus = $(this).val();
        var currentStatus = '{{ $transaction->status }}';
        
        if (currentStatus !== newStatus) {
            var message = 'Are you sure you want to change the transaction status from "' + 
                         currentStatus + '" to "' + newStatus + '"?';
            
            if (currentStatus === 'completed' && (newStatus === 'failed' || newStatus === 'cancelled')) {
                message += '\n\nThis will reverse the payment and may affect the related order.';
            } else if ((currentStatus === 'failed' || currentStatus === 'cancelled') && newStatus === 'completed') {
                message += '\n\nThis will process the payment and may affect the related order.';
            }
            
            if (!confirm(message)) {
                $(this).val(currentStatus);
            }
        }
    });
});
</script>
@stop