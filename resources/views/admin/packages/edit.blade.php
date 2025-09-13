@extends('adminlte::page')

@section('title', 'Edit Package')

@section('content_header')
    <h1>Edit Package</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.packages.update', ['package' => $package]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $package->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $package->slug) }}">
                            <small class="form-text text-muted">Leave empty to auto-generate from title. Use only lowercase letters, numbers, and hyphens.</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="short_description">Short Description <span class="text-danger">*</span></label>
                            <textarea name="short_description" id="short_description" rows="3" class="form-control @error('short_description') is-invalid @enderror" required>{{ old('short_description', $package->short_description) }}</textarea>
                            <small class="form-text text-muted">Brief summary that appears in package listings (max 200 characters recommended)</small>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Full Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="10" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="package_category_id">Category <span class="text-danger">*</span></label>
                            <select name="package_category_id" id="package_category_id" class="form-control @error('package_category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('package_category_id', $package->package_category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('package_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">BDT</span>
                                </div>
                                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $package->price) }}" step="0.01">
                            </div>
                            <small class="form-text text-muted">Leave empty for "Contact for pricing"</small>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="price_unit">Price Unit</label>
                            <input type="text" name="price_unit" id="price_unit" class="form-control @error('price_unit') is-invalid @enderror" value="{{ old('price_unit', $package->price_unit) }}" placeholder="e.g., monthly, one-time">
                            <small class="form-text text-muted">Optional unit for the price (e.g., monthly, one-time)</small>
                            @error('price_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Package Image</label>
                            @if($package->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->title }}" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image">
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Recommended size: 800x600px, max 2MB. Leave empty to keep current image.</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="status" name="status" {{ $package->status ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status">Active</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="featured" name="featured" {{ $package->featured ? 'checked' : '' }}>
                                <label class="custom-control-label" for="featured">Featured</label>
                            </div>
                            <small class="form-text text-muted">Featured packages appear prominently on the homepage</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Package</button>
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
                
            // Auto-generate slug from title
            $('#title').on('keyup change', function() {
                // Only auto-generate if slug field is empty or hasn't been manually edited
                if ($('#slug').data('manually-edited') !== true) {
                    const title = $(this).val();
                    const slug = title.toLowerCase()
                        .replace(/[^\w\s-]/g, '') // Remove special characters
                        .replace(/\s+/g, '-')     // Replace spaces with hyphens
                        .replace(/-+/g, '-')      // Replace multiple hyphens with single hyphen
                        .trim();                 // Trim whitespace
                    
                    $('#slug').val(slug);
                }
            });
            
            // Mark slug as manually edited when user types in it
            $('#slug').on('keyup', function() {
                $(this).data('manually-edited', true);
            });
                
            // Update file input label with selected filename
            $('input[type="file"]').change(function(e){
                var fileName = e.target.files[0].name;
                $(this).next('.custom-file-label').html(fileName);
            });
        });
    </script>
@stop
