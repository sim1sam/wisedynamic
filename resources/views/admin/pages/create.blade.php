@extends('admin.layouts.app')

@section('title', 'Create Page')

@section('styles')
    <!-- CKEditor does not require CSS; keep section for consistency -->
@endsection

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Page</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Page</h3>
                    </div>
                    <!-- /.card-header -->
                    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug (optional)</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}">
                                <small class="form-text text-muted">Leave empty to generate automatically from title.</small>
                                @error('slug')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="short_description">Short Description <span class="text-muted">(Recommended: 150-200 characters)</span></label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3" placeholder="A brief summary of the page content. This will appear in search results and at the top of the page.">{{ old('short_description') }}</textarea>
                                <small class="form-text text-muted">
                                    This description will be shown at the top of the page and in search results.
                                </small>
                                @error('short_description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">Page Content</label>
                                <textarea class="form-control textarea-editor @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content') }}</textarea>
                                <small class="form-text text-muted">
                                    Enter the main content for your page. HTML formatting is supported.
                                </small>
                                @error('content')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image">Featured Image</label>
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Page Header Image</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>
                                        <div id="image-preview-container" class="mt-3 text-center d-none">
                                            <img id="image-preview" src="#" alt="Image Preview" class="img-fluid img-thumbnail" style="max-height: 200px;">
                                            <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">Remove Image</button>
                                        </div>
                                        <small class="form-text text-muted">
                                            <ul class="pl-3 mb-0">
                                                <li>Recommended size: 1200 x 600 pixels (2:1 ratio)</li>
                                                <li>Maximum file size: 2MB</li>
                                                <li>Allowed formats: JPG, PNG, GIF</li>
                                                <li>This image will appear at the top of the page</li>
                                            </ul>
                                        </small>
                                    </div>
                                </div>
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order">Display Order</label>
                                        <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}">
                                        @error('order')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="show_in_footer" name="show_in_footer" value="1" {{ old('show_in_footer') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="show_in_footer">Show in Footer</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="show_in_header" name="show_in_header" value="1" {{ old('show_in_header') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="show_in_header">Show in Header</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Create Page</button>
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- CKEditor 5 Classic -->
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <script>
        $(function () {
            // Initialize CKEditor for content
            ClassicEditor
                .create(document.querySelector('#content'))
                .catch(error => {
                    console.error(error);
                });

            // Auto-generate slug from title
            $('#title').on('blur', function() {
                if ($('#slug').val() === '') {
                    const title = $(this).val();
                    const slug = title.toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .trim();
                    $('#slug').val(slug);
                }
            });

            // BS-custom-file-input
            bsCustomFileInput.init();
        });
        
        // Image preview function
        function previewImage(input) {
            const previewContainer = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Remove image function
        function removeImage() {
            const input = document.getElementById('image');
            const previewContainer = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');
            
            input.value = '';
            preview.src = '#';
            previewContainer.classList.add('d-none');
            document.querySelector('.custom-file-label').innerHTML = 'Choose file';
        }
    </script>
@endsection
