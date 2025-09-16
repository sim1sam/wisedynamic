@extends('adminlte::page')

@section('title', 'Website Settings')

@section('content_header')
    <h1>Website Settings</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Manage Website Settings</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.settings.website.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="site_name">Site Name</label>
                <input type="text" name="site_name" id="site_name" class="form-control @error('site_name') is-invalid @enderror" 
                    value="{{ old('site_name', $websiteSetting->site_name ?? 'Wise Dynamic') }}" required>
                @error('site_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="meta_title">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" class="form-control @error('meta_title') is-invalid @enderror" 
                    value="{{ old('meta_title', $websiteSetting->meta_title) }}" maxlength="60">
                @error('meta_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">SEO meta title for the website (recommended: 50-60 characters)</small>
            </div>
            
            <div class="form-group">
                <label for="meta_description">Meta Description</label>
                <textarea name="meta_description" id="meta_description" class="form-control @error('meta_description') is-invalid @enderror" 
                    rows="3" maxlength="160">{{ old('meta_description', $websiteSetting->meta_description) }}</textarea>
                @error('meta_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">SEO meta description for the website (recommended: 150-160 characters)</small>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="site_logo">Site Logo</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="site_logo" id="site_logo" class="custom-file-input @error('site_logo') is-invalid @enderror" accept="image/*">
                                <label class="custom-file-label" for="site_logo">Choose file</label>
                            </div>
                        </div>
                        @error('site_logo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Recommended size: 200x50 pixels</small>
                        
                        @if($websiteSetting->site_logo ?? null)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $websiteSetting->site_logo) }}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                                <p class="text-muted">Current logo</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="site_favicon">Site Favicon</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="site_favicon" id="site_favicon" class="custom-file-input @error('site_favicon') is-invalid @enderror" accept="image/x-icon,image/png">
                                <label class="custom-file-label" for="site_favicon">Choose file</label>
                            </div>
                        </div>
                        @error('site_favicon')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Recommended size: 32x32 pixels (ICO or PNG)</small>
                        
                        @if($websiteSetting->site_favicon ?? null)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $websiteSetting->site_favicon) }}" alt="Current Favicon" class="img-thumbnail" style="max-height: 32px;">
                                <p class="text-muted">Current favicon</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="logo_alt_text">Logo Alt Text</label>
                <input type="text" name="logo_alt_text" id="logo_alt_text" class="form-control @error('logo_alt_text') is-invalid @enderror" 
                    value="{{ old('logo_alt_text', $websiteSetting->logo_alt_text ?? 'Wise Dynamic Logo') }}">
                @error('logo_alt_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Alternative text for the logo image (for accessibility)</small>
            </div>
            
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="show_site_name_with_logo" name="show_site_name_with_logo" 
                        {{ old('show_site_name_with_logo', $websiteSetting->show_site_name_with_logo ?? true) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="show_site_name_with_logo">Show Site Name with Logo</label>
                </div>
                <small class="form-text text-muted">If checked, the site name will be displayed alongside the logo</small>
            </div>
            
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
@parent
<script>
    $(function() {
        // Show filename when file is selected
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@stop
