@extends('adminlte::page')

@section('title','Slider')
@section('page_title','Slider')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Slides</h3>
    <a href="{{ route('admin.slides.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Slide</a>
  </div>
  <div class="card-body p-0">
    <table class="table table-striped mb-0">
      <thead>
        <tr>
          <th style="width:60px">#</th>
          <th>Title</th>
          <th>Subtitle</th>
          <th>Price Text</th>
          <th>Link</th>
          <th>Image</th>
          <th>Active</th>
          <th style="width:160px">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($slides as $slide)
        <tr>
          <td>{{ $slide->position }}</td>
          <td>{{ $slide->title }}</td>
          <td>{{ $slide->subtitle }}</td>
          <td>{{ $slide->price_text }}</td>
          <td>
            @if($slide->link_url)
              <a href="{{ $slide->link_url }}" target="_blank" rel="noopener" class="btn btn-xs btn-outline-primary">
                <i class="fas fa-external-link-alt"></i>
              </a>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            @php
              $img = $slide->image_source === 'upload' && $slide->image_path
                ? asset($slide->image_path)
                : ($slide->image_url ?: null);
            @endphp
            @if($img)
              <img src="{{ $img }}" alt="" style="height:40px;object-fit:cover;border-radius:6px;">
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            <form action="{{ route('admin.slides.toggle-active', $slide) }}" method="post" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-sm {{ $slide->active ? 'btn-success' : 'btn-secondary' }}">
                @if($slide->active)
                  <i class="fas fa-eye"></i> Active
                @else
                  <i class="fas fa-eye-slash"></i> Hidden
                @endif
              </button>
            </form>
          </td>
          <td>
            <a href="{{ route('admin.slides.edit',$slide) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
            <form action="{{ route('admin.slides.destroy',$slide) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this slide?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted p-4">No slides yet. Click "Add Slide" to create one.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
