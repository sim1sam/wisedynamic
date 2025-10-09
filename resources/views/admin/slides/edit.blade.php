@extends('adminlte::page')

@section('title','Edit Slide')
@section('page_title','Edit Slide')

@section('content')
<div class="card">
  <div class="card-header"><h3 class="card-title">Edit Slide</h3></div>
  <form action="{{ route('admin.slides.update', $slide) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
      @endif
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $slide->title) }}" required>
      </div>
      <div class="form-group">
        <label>Subtitle</label>
        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $slide->subtitle) }}">
      </div>
      <div class="form-group">
        <label>Price Text (pill)</label>
        <input type="text" name="price_text" class="form-control" value="{{ old('price_text', $slide->price_text) }}" placeholder="e.g. Starting from BDT 20,000/-">
      </div>
      <div class="form-group">
        <label>Link URL (optional)</label>
        <input type="url" name="link_url" class="form-control" value="{{ old('link_url', $slide->link_url) }}" placeholder="https://example.com/landing">
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label>Image Source</label>
          <select name="image_source" id="image_source" class="form-control">
            <option value="url" {{ old('image_source', $slide->image_source)==='url'?'selected':'' }}>URL</option>
            <option value="upload" {{ old('image_source', $slide->image_source)==='upload'?'selected':'' }}>Upload</option>
          </select>
        </div>
        <div class="form-group col-md-4" id="image_url_group">
          <label>Image URL</label>
          <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $slide->image_url) }}" placeholder="https://...">
        </div>
        <div class="form-group col-md-4 d-none" id="image_file_group">
          <label>Upload Image</label>
          <input type="file" name="image_file" class="form-control-file" accept="image/*">
          <small class="text-muted">Max 4MB</small>
          @if($slide->image_source==='upload' && $slide->image_path)
            <div class="mt-2">
              <img src="{{ asset($slide->image_path) }}" style="height:60px;border-radius:6px;object-fit:cover;" alt="current">
              <div class="text-muted small">Current image</div>
            </div>
          @endif
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-3">
          <label>Position</label>
          <input type="number" name="position" class="form-control" value="{{ old('position', $slide->position) }}" min="0">
        </div>
        <div class="form-group col-md-3 d-flex align-items-center">
          <div class="custom-control custom-switch mt-4">
            <input type="checkbox" name="active" class="custom-control-input" id="activeSwitch" {{ old('active', $slide->active)?'checked':'' }}>
            <label class="custom-control-label" for="activeSwitch">Active</label>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer text-right">
      <a href="{{ route('admin.slides.index') }}" class="btn btn-secondary">Cancel</a>
      <button class="btn btn-primary">Update</button>
    </div>
  </form>
</div>
@push('scripts')
<script>
  function toggleImageInputs(){
    const src = document.getElementById('image_source').value;
    document.getElementById('image_url_group').classList.toggle('d-none', src !== 'url');
    document.getElementById('image_file_group').classList.toggle('d-none', src !== 'upload');
  }
  document.getElementById('image_source').addEventListener('change', toggleImageInputs);
  toggleImageInputs();
</script>
@endpush
@endsection
