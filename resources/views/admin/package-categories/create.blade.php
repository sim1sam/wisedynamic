@extends('adminlte::page')

@section('title', 'Create Package Category')

@section('content_header')
    <h1>Create Package Category</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.package-categories.store') }}" method="POST" id="categoryForm">
                @csrf
                
                <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="icon">Icon Class <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i id="icon-preview" class="fas fa-box"></i></span>
                        </div>
                        <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', 'fas fa-box') }}" required>
                    </div>
                    <small class="form-text text-muted">Enter a FontAwesome icon class (e.g., fas fa-laptop-code). <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a></small>
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="status" name="status" checked>
                        <label class="custom-control-label" for="status">Active</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <a href="{{ route('admin.package-categories.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="button" id="submitBtn" class="btn btn-primary">Create Category</button>
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
            
            // Add click handler to submit button
            $('#submitBtn').on('click', function(e) {
                console.log('Submit button clicked');
                // Prevent any default action
                e.preventDefault();
                
                // Log form data for debugging
                console.log('Form data:', {
                    name: $('#name').val(),
                    icon: $('#icon').val(),
                    description: $('#description').val(),
                    status: $('#status').is(':checked')
                });
                
                // Manually submit the form
                document.getElementById('categoryForm').submit();
            });
        });
    </script>
@stop
