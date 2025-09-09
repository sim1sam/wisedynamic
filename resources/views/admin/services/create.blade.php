@extends('adminlte::page')

@section('title', 'Create Service')

@section('content_header')
    <h1>Create Service</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="/admin/services" method="POST" enctype="multipart/form-data" id="serviceForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="short_description">Short Description <span class="text-danger">*</span></label>
                            <textarea name="short_description" id="short_description" rows="3" class="form-control @error('short_description') is-invalid @enderror" required>{{ old('short_description') }}</textarea>
                            <small class="form-text text-muted">Brief summary that appears in service listings (max 200 characters recommended)</small>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Full Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="10" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="service_category_id">Category <span class="text-danger">*</span></label>
                            <select name="service_category_id" id="service_category_id" class="form-control @error('service_category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('service_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">BDT</span>
                                </div>
                                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">
                            </div>
                            <small class="form-text text-muted">Leave empty for "Contact for pricing"</small>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="price_unit">Price Unit</label>
                            <input type="text" name="price_unit" id="price_unit" class="form-control @error('price_unit') is-invalid @enderror" value="{{ old('price_unit') }}" placeholder="e.g., hour, project, month">
                            <small class="form-text text-muted">Optional unit for the price (e.g., per hour, per project)</small>
                            @error('price_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Service Image</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image">
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Recommended size: 800x600px, max 2MB</small>
                            @error('image')
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
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="featured" name="featured">
                                <label class="custom-control-label" for="featured">Featured</label>
                            </div>
                            <small class="form-text text-muted">Featured services appear prominently on the homepage</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="button" id="submitBtn" class="btn btn-primary">Create Service</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize CKEditor for rich text editing
            ClassicEditor
                .create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });
                
            // Update file input label with selected filename
            $('input[type="file"]').change(function(e){
                var fileName = e.target.files[0].name;
                $(this).next('.custom-file-label').html(fileName);
            });
            
            // Add click handler to submit button
            $('#submitBtn').on('click', function(e) {
                console.log('Submit button clicked');
                // Prevent any default action
                e.preventDefault();
                
                // Log form data for debugging
                console.log('Form data:', {
                    title: $('#title').val(),
                    category: $('#service_category_id').val(),
                    status: $('#status').is(':checked'),
                    featured: $('#featured').is(':checked')
                });
                
                // Manually submit the form
                document.getElementById('serviceForm').submit();
            });
        });
    </script>
@stop
