@extends('adminlte::page')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Edit Request</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.requests.index') }}">Requests</a></li>
          <li class="breadcrumb-item active">Edit</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.requests.update', $customerRequest) }}">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Marketing Information</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="page_name">Page Name <span class="text-danger">*</span></label>
                <input type="text" id="page_name" name="page_name" class="form-control" value="{{ old('page_name', $customerRequest->page_name) }}" required />
              </div>

              <div class="form-group">
                <label for="social_media">Social Media <span class="text-danger">*</span></label>
                @php($current = old('social_media', $customerRequest->social_media))
                <select id="social_media" name="social_media" class="form-control" required>
                  <option value="">-- Select Platform --</option>
                  <option value="facebook" @selected($current==='facebook')>Facebook</option>
                  <option value="instagram" @selected($current==='instagram')>Instagram</option>
                  <option value="tiktok" @selected($current==='tiktok')>TikTok</option>
                  <option value="twitter" @selected($current==='twitter')>Twitter</option>
                  <option value="linkedin" @selected($current==='linkedin')>LinkedIn</option>
                  <option value="youtube" @selected($current==='youtube')>YouTube</option>
                </select>
              </div>

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="ads_budget_bdt">Ads Budget (BDT) <span class="text-danger">*</span></label>
                  <input type="number" step="0.01" min="0" id="ads_budget_bdt" name="ads_budget_bdt" class="form-control" value="{{ old('ads_budget_bdt', $customerRequest->ads_budget_bdt) }}" required />
                </div>
                <div class="form-group col-md-4">
                  <label for="days">Days <span class="text-danger">*</span></label>
                  <input type="number" min="1" id="days" name="days" class="form-control" value="{{ old('days', $customerRequest->days) }}" required />
                </div>
                <div class="form-group col-md-4">
                  <label for="post_link">Post Link</label>
                  <input type="url" id="post_link" name="post_link" class="form-control" value="{{ old('post_link', $customerRequest->post_link) }}" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Assignment & Status</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="user_id">Assign to User <span class="text-danger">*</span></label>
                <select id="user_id" name="user_id" class="form-control" required>
                  <option value="">-- Select User --</option>
                  @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(old('user_id', $customerRequest->user_id) == $u->id)>{{ $u->name }} {{ $u->email ? '('.$u->email.')' : '' }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select id="status" name="status" class="form-control" required>
                  <option value="pending" @selected(old('status', $customerRequest->status) === 'pending')>Pending</option>
                  <option value="in_progress" @selected(old('status', $customerRequest->status) === 'in_progress')>In Progress</option>
                  <option value="done" @selected(old('status', $customerRequest->status) === 'done')>Done</option>
                </select>
              </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
              <a href="{{ route('admin.requests.show', $customerRequest) }}" class="btn btn-default"><i class="far fa-eye mr-1"></i> View</a>
              <div>
                <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                <button class="btn btn-primary">Save Changes</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection
