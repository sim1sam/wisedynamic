@extends('adminlte::page')

@section('title', 'Contact Page Settings')
@section('page_title', 'Contact Page Settings')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Contact Page Settings</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.settings.contact.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Header Section</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Page Title</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                            value="{{ old('title', $contactSetting->title ?? 'Contact Us') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="subtitle">Page Subtitle</label>
                        <textarea name="subtitle" id="subtitle" rows="3" class="form-control @error('subtitle') is-invalid @enderror">{{ old('subtitle', $contactSetting->subtitle ?? 'Have a question or want to work together? Reach out to us using the contact information below or fill out the form.') }}</textarea>
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Contact Information</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $contactSetting->address ?? '123 Business Street, Suite 100, City, Country') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                            value="{{ old('phone', $contactSetting->phone ?? '+1 (555) 123-4567') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email', $contactSetting->email ?? 'info@wisedynamic.com') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Map Embed</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="map_embed">Google Maps Embed Code</label>
                        <textarea name="map_embed" id="map_embed" rows="5" class="form-control @error('map_embed') is-invalid @enderror">{{ old('map_embed', $contactSetting->map_embed ?? '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.9008212777105!2d90.38426661498136!3d23.750858084589382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8bd5c3bbd77%3A0x3d12c1a7e70a3c13!2sWise%20Dynamic!5e0!3m2!1sen!2sbd!4v1598123456789!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>') }}</textarea>
                        <small class="form-text text-muted">Paste the iframe embed code from Google Maps</small>
                        @error('map_embed')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($contactSetting->map_embed ?? false)
                        <div class="mt-3">
                            <label>Current Map Preview:</label>
                            <div class="embed-responsive embed-responsive-16by9">
                                {!! $contactSetting->map_embed !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Office Hours</h4>
                </div>
                <div class="card-body">
                    <div id="office-hours-container">
                        @php
                            $defaultOfficeHours = [
                                ['day' => 'Monday - Friday', 'hours' => '9:00 AM - 6:00 PM'],
                                ['day' => 'Saturday', 'hours' => '10:00 AM - 4:00 PM'],
                                ['day' => 'Sunday', 'hours' => 'Closed'],
                            ];
                            $officeHours = old('office_hours', $contactSetting->office_hours ?? $defaultOfficeHours);
                        @endphp
                        
                        @foreach(is_array($officeHours) ? $officeHours : [] as $index => $item)
                            <div class="office-hour-row card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Day</label>
                                                <input type="text" name="office_hours[{{ $index }}][day]" class="form-control" 
                                                    value="{{ $item['day'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Hours</label>
                                                <input type="text" name="office_hours[{{ $index }}][hours]" class="form-control" 
                                                    value="{{ $item['hours'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @if($index > 0)
                                        <button type="button" class="btn btn-sm btn-danger remove-office-hour float-right">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <button type="button" id="add-office-hour" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Office Hours
                    </button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Social Links</h4>
                </div>
                <div class="card-body">
                    <div id="social-links-container">
                        @php
                            $defaultSocialLinks = [
                                ['platform' => 'Facebook', 'url' => 'https://facebook.com/wisedynamic', 'icon' => 'fab fa-facebook'],
                                ['platform' => 'Twitter', 'url' => 'https://twitter.com/wisedynamic', 'icon' => 'fab fa-twitter'],
                                ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com/company/wisedynamic', 'icon' => 'fab fa-linkedin'],
                                ['platform' => 'Instagram', 'url' => 'https://instagram.com/wisedynamic', 'icon' => 'fab fa-instagram'],
                            ];
                            $socialLinks = old('social_links', $contactSetting->social_links ?? $defaultSocialLinks);
                        @endphp
                        
                        @foreach(is_array($socialLinks) ? $socialLinks : [] as $index => $item)
                            <div class="social-link-row card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Platform</label>
                                                <input type="text" name="social_links[{{ $index }}][platform]" class="form-control" 
                                                    value="{{ $item['platform'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>URL</label>
                                                <input type="text" name="social_links[{{ $index }}][url]" class="form-control" 
                                                    value="{{ $item['url'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Icon Class</label>
                                                <input type="text" name="social_links[{{ $index }}][icon]" class="form-control" 
                                                    value="{{ $item['icon'] ?? '' }}" placeholder="fab fa-facebook">
                                                <small class="form-text text-muted">
                                                    Use FontAwesome classes. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    @if($index > 0)
                                        <button type="button" class="btn btn-sm btn-danger remove-social-link float-right">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <button type="button" id="add-social-link" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Social Link
                    </button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Contact Form Settings</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="form_title">Form Title</label>
                        <input type="text" name="form_title" id="form_title" class="form-control @error('form_title') is-invalid @enderror" 
                            value="{{ old('form_title', $contactSetting->form_title ?? 'Send Us a Message') }}">
                        @error('form_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="form_subtitle">Form Subtitle</label>
                        <textarea name="form_subtitle" id="form_subtitle" rows="3" class="form-control @error('form_subtitle') is-invalid @enderror">{{ old('form_subtitle', $contactSetting->form_subtitle ?? 'Fill out the form below and we\'ll get back to you as soon as possible.') }}</textarea>
                        @error('form_subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
@parent
<script>
    $(function() {
        // Office Hours
        let officeHourIndex = {{ count(is_array(old('office_hours', $contactSetting->office_hours ?? $defaultOfficeHours)) ? old('office_hours', $contactSetting->office_hours ?? $defaultOfficeHours) : []) }};
        
        $('#add-office-hour').click(function() {
            const template = `
                <div class="office-hour-row card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Day</label>
                                    <input type="text" name="office_hours[\${officeHourIndex}][day]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hours</label>
                                    <input type="text" name="office_hours[\${officeHourIndex}][hours]" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-office-hour float-right">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
            
            $('#office-hours-container').append(template);
            officeHourIndex++;
        });
        
        $(document).on('click', '.remove-office-hour', function() {
            $(this).closest('.office-hour-row').remove();
        });

        // Social Links
        let socialLinkIndex = {{ count(is_array(old('social_links', $contactSetting->social_links ?? $defaultSocialLinks)) ? old('social_links', $contactSetting->social_links ?? $defaultSocialLinks) : []) }};
        
        $('#add-social-link').click(function() {
            const template = `
                <div class="social-link-row card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Platform</label>
                                    <input type="text" name="social_links[\${socialLinkIndex}][platform]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>URL</label>
                                    <input type="text" name="social_links[\${socialLinkIndex}][url]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Icon Class</label>
                                    <input type="text" name="social_links[\${socialLinkIndex}][icon]" class="form-control" 
                                        placeholder="fab fa-facebook">
                                    <small class="form-text text-muted">
                                        Use FontAwesome classes. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-social-link float-right">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
            
            $('#social-links-container').append(template);
            socialLinkIndex++;
        });
        
        $(document).on('click', '.remove-social-link', function() {
            $(this).closest('.social-link-row').remove();
        });
    });
</script>
@endsection
