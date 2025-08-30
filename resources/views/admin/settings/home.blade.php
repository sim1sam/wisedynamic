@extends('adminlte::page')

@section('title', 'Home Settings')
@section('page_title', 'Home Settings')

@section('content')
<div class="card">
    <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="home-settings-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="about-tab" data-toggle="pill" href="#about" role="tab" aria-controls="about" aria-selected="true">About Section</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="why-choose-tab" data-toggle="pill" href="#why-choose" role="tab" aria-controls="why-choose" aria-selected="false">Why Choose Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="pill" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact Section</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.settings.home.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="tab-content" id="home-settings-tabContent">
                <!-- About Section Tab -->
                <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="about-tab">
                    <div class="form-group">
                        <label for="about_title">Title</label>
                        <input type="text" name="about_title" id="about_title" class="form-control @error('about_title') is-invalid @enderror" 
                            value="{{ old('about_title', $homeSetting->about_title ?? 'About Wise Dynamic') }}" required>
                        @error('about_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="about_subtitle">Subtitle</label>
                        <textarea name="about_subtitle" id="about_subtitle" rows="3" class="form-control @error('about_subtitle') is-invalid @enderror" 
                            required>{{ old('about_subtitle', $homeSetting->about_subtitle ?? 'We craft high-performing digital products and growth campaigns that help businesses move faster, scale smarter, and convert better.') }}</textarea>
                        @error('about_subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">About Items</h4>
                        </div>
                        <div class="card-body">
                            <div id="about-items-container">
                                @php
                                    $defaultAboutItems = [
                                        ['icon' => 'fas fa-users', 'title' => 'Customer-First', 'text' => 'Transparent communication and dedicated support'],
                                        ['icon' => 'fas fa-laptop-code', 'title' => 'Modern Tech', 'text' => 'Data-informed decisions with cutting-edge solutions'],
                                        ['icon' => 'fas fa-check-circle', 'title' => 'Quality Assured', 'text' => 'On-time delivery with rigorous testing'],
                                        ['icon' => 'fas fa-chart-line', 'title' => 'Scalable Solutions', 'text' => 'Built to grow with your business needs']
                                    ];
                                    $aboutItems = old('about_items', $homeSetting->about_items ?? $defaultAboutItems);
                                @endphp
                                
                                @foreach($aboutItems as $index => $item)
                                    <div class="item-row card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Icon Class</label>
                                                        <input type="text" name="about_items[{{ $index }}][icon]" class="form-control" 
                                                            value="{{ $item['icon'] }}" placeholder="fas fa-certificate" required>
                                                        <small class="form-text text-muted">
                                                            Use FontAwesome classes. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Title</label>
                                                        <input type="text" name="about_items[{{ $index }}][title]" class="form-control" 
                                                            value="{{ $item['title'] }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Text</label>
                                                        <input type="text" name="about_items[{{ $index }}][text]" class="form-control" 
                                                            value="{{ $item['text'] }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($index > 0)
                                                <button type="button" class="btn btn-sm btn-danger remove-about-item float-right">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="button" id="add-about-item" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Why Choose Us Tab -->
                <div class="tab-pane fade" id="why-choose" role="tabpanel" aria-labelledby="why-choose-tab">
                    <div class="form-group">
                        <label for="why_choose_title">Title</label>
                        <input type="text" name="why_choose_title" id="why_choose_title" class="form-control @error('why_choose_title') is-invalid @enderror" 
                            value="{{ old('why_choose_title', $homeSetting->why_choose_title ?? 'Why Choose Wise Dynamic?') }}" required>
                        @error('why_choose_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="why_choose_subtitle">Subtitle</label>
                        <textarea name="why_choose_subtitle" id="why_choose_subtitle" rows="3" class="form-control @error('why_choose_subtitle') is-invalid @enderror" 
                            required>{{ old('why_choose_subtitle', $homeSetting->why_choose_subtitle ?? 'We blend creativity, technology, and personalized support') }}</textarea>
                        @error('why_choose_subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="why_choose_clients_count">Clients Count</label>
                                <input type="number" name="why_choose_clients_count" id="why_choose_clients_count" class="form-control @error('why_choose_clients_count') is-invalid @enderror" 
                                    value="{{ old('why_choose_clients_count', $homeSetting->why_choose_clients_count ?? 100) }}" required>
                                @error('why_choose_clients_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Number will be displayed as "100+"</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="why_choose_experience">Experience Text</label>
                                <input type="text" name="why_choose_experience" id="why_choose_experience" class="form-control @error('why_choose_experience') is-invalid @enderror" 
                                    value="{{ old('why_choose_experience', $homeSetting->why_choose_experience ?? '4+ Years') }}" required>
                                @error('why_choose_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Example: "4+ Years"</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Why Choose Us Items</h4>
                        </div>
                        <div class="card-body">
                            <div id="why-choose-items-container">
                                @php
                                    $defaultWhyChooseItems = [
                                        ['icon' => 'fas fa-award', 'title' => 'BASIS Certified Excellence', 'text' => 'Official BASIS member since 2020, ensuring professional standards and reliability'],
                                        ['icon' => 'fas fa-rocket', 'title' => 'Startup-Friendly Pricing', 'text' => 'Affordable solutions designed for young entrepreneurs and growing businesses'],
                                        ['icon' => 'fas fa-tools', 'title' => 'Full-Spectrum Solutions', 'text' => 'From websites to mobile apps, marketing to music — everything under one roof'],
                                        ['icon' => 'fas fa-heart', 'title' => 'Dedicated Small Team', 'text' => 'Personal attention and care - we treat your success like our own']
                                    ];
                                    $whyChooseItems = old('why_choose_items', $homeSetting->why_choose_items ?? $defaultWhyChooseItems);
                                @endphp
                                
                                @foreach($whyChooseItems as $index => $item)
                                    <div class="item-row card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Icon Class</label>
                                                        <input type="text" name="why_choose_items[{{ $index }}][icon]" class="form-control" 
                                                            value="{{ $item['icon'] }}" placeholder="fas fa-award" required>
                                                        <small class="form-text text-muted">
                                                            Use FontAwesome classes. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Title</label>
                                                        <input type="text" name="why_choose_items[{{ $index }}][title]" class="form-control" 
                                                            value="{{ $item['title'] }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Text</label>
                                                        <input type="text" name="why_choose_items[{{ $index }}][text]" class="form-control" 
                                                            value="{{ $item['text'] }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($index > 0)
                                                <button type="button" class="btn btn-sm btn-danger remove-why-choose-item float-right">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="button" id="add-why-choose-item" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Section Tab -->
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="form-group">
                        <label for="contact_title">Title</label>
                        <input type="text" name="contact_title" id="contact_title" class="form-control @error('contact_title') is-invalid @enderror" 
                            value="{{ old('contact_title', $homeSetting->contact_title ?? 'Let\'s Build Something Amazing Together') }}" required>
                        @error('contact_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_subtitle">Subtitle</label>
                        <textarea name="contact_subtitle" id="contact_subtitle" rows="3" class="form-control @error('contact_subtitle') is-invalid @enderror" 
                            required>{{ old('contact_subtitle', $homeSetting->contact_subtitle ?? 'Ready to bring your digital vision to life? Contact us today!') }}</textarea>
                        @error('contact_subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact_phone">Phone Number</label>
                                <input type="text" name="contact_phone" id="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" 
                                    value="{{ old('contact_phone', $homeSetting->contact_phone ?? '+880 1805 081012') }}" required>
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact_email">Email Address</label>
                                <input type="email" name="contact_email" id="contact_email" class="form-control @error('contact_email') is-invalid @enderror" 
                                    value="{{ old('contact_email', $homeSetting->contact_email ?? 'sales@wisedynamic.com.bd') }}" required>
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact_location">Location</label>
                                <input type="text" name="contact_location" id="contact_location" class="form-control @error('contact_location') is-invalid @enderror" 
                                    value="{{ old('contact_location', $homeSetting->contact_location ?? 'Bangladesh') }}" required>
                                @error('contact_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
        // About Items
        let aboutItemIndex = {{ count(old('about_items', $homeSetting->about_items ?? $defaultAboutItems)) }};
        
        $('#add-about-item').click(function() {
            const template = '<div class="item-row card mb-3">'+
                    '<div class="card-body">'+
                        '<div class="row">'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+
                                    '<label>Icon Class</label>'+
                                    '<input type="text" name="about_items['+aboutItemIndex+'][icon]" class="form-control" '+ 
                                        'placeholder="fas fa-certificate" required>'+
                                    '<small class="form-text text-muted">'+
                                        'Use FontAwesome classes. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>'+
                                    '</small>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+
                                    '<label>Title</label>'+
                                    '<input type="text" name="about_items['+aboutItemIndex+'][title]" class="form-control" required>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+
                                    '<label>Text</label>'+
                                    '<input type="text" name="about_items['+aboutItemIndex+'][text]" class="form-control" required>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<button type="button" class="btn btn-sm btn-danger remove-about-item float-right">'+
                            '<i class="fas fa-trash"></i> Remove'+
                        '</button>'+
                    '</div>'+
                '</div>';
            
            $('#about-items-container').append(template);
            aboutItemIndex++;
        });
        
        $(document).on('click', '.remove-about-item', function() {
            $(this).closest('.item-row').remove();
        });
        
        // Why Choose Us Items
        let whyChooseItemIndex = {{ count(old('why_choose_items', $homeSetting->why_choose_items ?? $defaultWhyChooseItems)) }};
        
        $('#add-why-choose-item').click(function() {
            const template = '<div class="item-row card mb-3">'+
                    '<div class="card-body">'+
                        '<div class="row">'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+
                                    '<label>Icon Class</label>'+
                                    '<input type="text" name="why_choose_items['+whyChooseItemIndex+'][icon]" class="form-control" '+ 
                                        'placeholder="fas fa-award" required>'+
                                    '<small class="form-text text-muted">'+
                                        'Use FontAwesome classes. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>'+
                                    '</small>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+
                                    '<label>Title</label>'+
                                    '<input type="text" name="why_choose_items['+whyChooseItemIndex+'][title]" class="form-control" required>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+
                                    '<label>Text</label>'+
                                    '<input type="text" name="why_choose_items['+whyChooseItemIndex+'][text]" class="form-control" required>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<button type="button" class="btn btn-sm btn-danger remove-why-choose-item float-right">'+
                            '<i class="fas fa-trash"></i> Remove'+
                        '</button>'+
                    '</div>'+
                '</div>';
            
            $('#why-choose-items-container').append(template);
            whyChooseItemIndex++;
        });
        
        $(document).on('click', '.remove-why-choose-item', function() {
            $(this).closest('.item-row').remove();
        });
    });
</script>
@endsection
