@extends('adminlte::page')

@section('title','Footer Settings')
@section('page_title','Footer Settings')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Manage Footer</h3>
  </div>
  <form method="POST" action="{{ route('admin.settings.footer.update') }}">
    @csrf
    @method('PUT')
    <div class="card-body">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
      @endif

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Company Name</label>
          <input type="text" class="form-control" name="company_name" value="{{ old('company_name', $setting->company_name ?? '') }}"/>
        </div>
        <div class="form-group col-md-6">
          <label>Tagline</label>
          <input type="text" class="form-control" name="tagline" value="{{ old('tagline', $setting->tagline ?? '') }}"/>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Phone</label>
          <input type="text" class="form-control" name="phone" value="{{ old('phone', $setting->phone ?? '') }}"/>
        </div>
        <div class="form-group col-md-6">
          <label>Email</label>
          <input type="email" class="form-control" name="email" value="{{ old('email', $setting->email ?? '') }}"/>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Facebook URL</label>
          <input type="url" class="form-control" name="facebook_url" value="{{ old('facebook_url', $setting->facebook_url ?? '') }}"/>
        </div>
        <div class="form-group col-md-6">
          <label>Twitter URL</label>
          <input type="url" class="form-control" name="twitter_url" value="{{ old('twitter_url', $setting->twitter_url ?? '') }}"/>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>LinkedIn URL</label>
          <input type="url" class="form-control" name="linkedin_url" value="{{ old('linkedin_url', $setting->linkedin_url ?? '') }}"/>
        </div>
        <div class="form-group col-md-6">
          <label>Instagram URL</label>
          <input type="url" class="form-control" name="instagram_url" value="{{ old('instagram_url', $setting->instagram_url ?? '') }}"/>
        </div>
      </div>

      <div class="form-group">
        <label>Copyright Text</label>
        <input type="text" class="form-control" name="copyright_text" value="{{ old('copyright_text', $setting->copyright_text ?? '') }}" placeholder="© {{ date('Y') }} Wise Dynamic. All rights reserved."/>
      </div>

    </div>
    <div class="card-footer text-right">
      <button class="btn btn-primary">Save</button>
    </div>
  </form>
</div>
@endsection
