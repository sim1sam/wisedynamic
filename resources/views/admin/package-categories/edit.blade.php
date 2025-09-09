@extends('adminlte::page')

@section('title', 'Edit Package Category')

@section('content_header')
    <h1>Edit Package Category</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('package-categories.update', ['package_category' => $packageCategory]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $packageCategory->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="icon">Icon Class <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i id="icon-preview" class="{{ $packageCategory->icon }}"></i></span>
                        </div>
                        <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $packageCategory->icon) }}" required>
                    </div>
                    <small class="form-text text-muted">Enter a FontAwesome icon class (e.g., fas fa-laptop-code). <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a></small>
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $packageCategory->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="status" name="status" {{ $packageCategory->status ? 'checked' : '' }}>
                        <label class="custom-control-label" for="status">Active</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <a href="{{ route('package-categories.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Update icon preview when input changes
            $('#icon').on('input', function() {
                const iconClass = $(this).val();
                $('#icon-preview').attr('class', iconClass);
            });
        });
    </script>
@stop
