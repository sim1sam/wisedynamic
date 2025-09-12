@extends('adminlte::page')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Request Details</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.requests.index') }}">Requests</a></li>
          <li class="breadcrumb-item active">Show</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Marketing Information</h3>
          </div>
          <div class="card-body">
            <dl class="row mb-0">
              <dt class="col-sm-4">Page Name</dt>
              <dd class="col-sm-8">{{ $customerRequest->page_name }}</dd>

              <dt class="col-sm-4">Social Media</dt>
              <dd class="col-sm-8">{{ $customerRequest->social_media }}</dd>

              <dt class="col-sm-4">Ads Budget (BDT)</dt>
              <dd class="col-sm-8">{{ number_format((float)$customerRequest->ads_budget_bdt, 2) }}</dd>

              <dt class="col-sm-4">Days</dt>
              <dd class="col-sm-8">{{ $customerRequest->days }}</dd>

              <dt class="col-sm-4">Post Link</dt>
              <dd class="col-sm-8">
                @if($customerRequest->post_link)
                  <a href="{{ $customerRequest->post_link }}" target="_blank" rel="noopener">{{ $customerRequest->post_link }}</a>
                @else
                  -
                @endif
              </dd>
            </dl>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Meta</h3>
          </div>
          <div class="card-body">
            <p class="mb-2"><strong>Customer:</strong> {{ optional($customerRequest->user)->name }} <span class="text-muted">{{ optional($customerRequest->user)->email }}</span></p>
            <p class="mb-2"><strong>Status:</strong> <span class="badge badge-secondary">{{ \Illuminate\Support\Str::headline($customerRequest->status) }}</span></p>
            <p class="mb-2"><strong>Created:</strong> {{ $customerRequest->created_at->format('Y-m-d H:i') }}</p>
            <p class="mb-0"><strong>Updated:</strong> {{ $customerRequest->updated_at->format('Y-m-d H:i') }}</p>
          </div>
          <div class="card-footer d-flex justify-content-between">
            {{-- Wire these up when routes are available --}}
            <a href="#" class="btn btn-default btn-sm disabled" title="Edit route not defined yet"><i class="far fa-edit mr-1"></i> Edit</a>
            <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-list mr-1"></i> Back to List</a>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Update Status</h3>
          </div>
          <form method="POST" action="{{ route('admin.requests.status', $customerRequest) }}">
            @csrf
            @method('PATCH')
            <div class="card-body">
              <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                  <option value="pending" @selected($customerRequest->status==='pending')>Pending</option>
                  <option value="in_progress" @selected($customerRequest->status==='in_progress')>In Progress</option>
                  <option value="done" @selected($customerRequest->status==='done')>Done</option>
                </select>
              </div>
            </div>
            <div class="card-footer text-right">
              <button class="btn btn-info">Save</button>
            </div>
          </form>
        </div>
        
        <div class="card">
          <div class="card-header bg-primary">
            <h3 class="card-title">Convert to Service Order</h3>
          </div>
          <form method="POST" action="{{ route('admin.requests.convert', $customerRequest) }}">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="service_id">Select Service</label>
                <!-- Debug info: {{ $services->count() }} services found -->
                <select name="service_id" id="service_id" class="form-control @error('service_id') is-invalid @enderror" required>
                  <option value="">-- Select a Service --</option>
                  @forelse($services as $service)
                    <option value="{{ $service->id }}" data-price="{{ $service->price }}">{{ $service->title }} - {{ number_format($service->price, 2) }} {{ $service->price_unit ?? '$' }}</option>
                  @empty
                    <option value="" disabled>No services available</option>
                  @endforelse
                </select>
                <small class="text-muted">Total services: {{ $services->count() }}</small>
                @error('service_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="amount">Order Amount</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $customerRequest->ads_budget_bdt) }}" step="0.01" min="0" required>
                </div>
                @error('amount')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Default is set to the customer's budget amount.</small>
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Convert to Service Order</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('js')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Get the service select and amount input elements
    const serviceSelect = document.getElementById('service_id');
    const amountInput = document.getElementById('amount');
    
    // Add event listener to update amount when service is selected
    serviceSelect.addEventListener('change', function() {
      const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
      if (selectedOption.value) {
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        if (!isNaN(price)) {
          amountInput.value = price.toFixed(2);
        }
      }
    });
  });
</script>
@endsection
