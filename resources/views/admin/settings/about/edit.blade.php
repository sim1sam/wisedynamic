@extends('adminlte::page')

@section('title', 'About Page Settings')
@section('page_title', 'About Page Settings')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">About Page Settings</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.settings.about.update') }}" method="POST" enctype="multipart/form-data">
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
                            value="{{ old('title', $aboutSetting->title ?? 'About Wise Dynamic') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="subtitle">Page Subtitle</label>
                        <textarea name="subtitle" id="subtitle" rows="3" class="form-control @error('subtitle') is-invalid @enderror">{{ old('subtitle', $aboutSetting->subtitle ?? 'We craft high-performing digital products and growth campaigns that help businesses move faster, scale smarter, and convert better.') }}</textarea>
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Who We Are Section</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="who_we_are_content">Content</label>
                        <textarea name="who_we_are_content" id="who_we_are_content" rows="4" class="form-control @error('who_we_are_content') is-invalid @enderror">{{ old('who_we_are_content', $aboutSetting->who_we_are_content ?? 'Wise Dynamic is a multidisciplinary team specializing in Website Development, UI/UX, and Digital Marketing. We blend strategy, design, and engineering to deliver solutions that don\'t just look great - they perform, scale, and drive measurable results.') }}</textarea>
                        @error('who_we_are_content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="who_we_are_image">Current Image</label>
                        @if($aboutSetting->who_we_are_image)
                            <div class="mb-2">
                                <img src="{{ $aboutSetting->who_we_are_image }}" alt="Who We Are" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        @endif
                        <input type="file" name="who_we_are_image_file" id="who_we_are_image_file" class="form-control-file @error('who_we_are_image_file') is-invalid @enderror">
                        <small class="form-text text-muted">Upload a new image (recommended size: 800x600px)</small>
                        @error('who_we_are_image_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">About Items</h4>
                </div>
                <div class="card-body">
                    <div id="about-items-container">
                        @php
                            $defaultItems = [
                                ['icon' => 'fas fa-check', 'title' => 'Customer-first mindset', 'text' => 'with transparent communication'],
                                ['icon' => 'fas fa-check', 'title' => 'Modern tech stack', 'text' => 'and data-informed decisions'],
                                ['icon' => 'fas fa-check', 'title' => 'On-time delivery', 'text' => 'with quality assurance'],
                            ];
                            $items = old('about_items', $aboutSetting->about_items ?? $defaultItems);
                        @endphp
                        
                        @foreach(is_array($items) ? $items : [] as $index => $item)
                            <div class="item-row card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Icon Class</label>
                                                <input type="text" name="about_items[{{ $index }}][icon]" class="form-control" 
                                                    value="{{ $item['icon'] ?? 'fas fa-check' }}" placeholder="fas fa-check">
                                                <small class="form-text text-muted">
                                                    Use FontAwesome classes. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" name="about_items[{{ $index }}][title]" class="form-control" 
                                                    value="{{ $item['title'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Text</label>
                                                <input type="text" name="about_items[{{ $index }}][text]" class="form-control" 
                                                    value="{{ $item['text'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @if($index > 0)
                                        <button type="button" class="btn btn-sm btn-danger remove-item float-right">
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

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Stats Section</h4>
                </div>
                <div class="card-body">
                    <div id="stats-container">
                        @php
                            $defaultStats = [
                                ['value' => '5+', 'label' => 'Years Experience'],
                                ['value' => '120+', 'label' => 'Projects Delivered'],
                                ['value' => '98%', 'label' => 'Client Satisfaction'],
                            ];
                            $stats = old('stats', $aboutSetting->stats ?? $defaultStats);
                        @endphp
                        
                        @foreach(is_array($stats) ? $stats : [] as $index => $stat)
                            <div class="stat-row card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Value</label>
                                                <input type="text" name="stats[{{ $index }}][value]" class="form-control" 
                                                    value="{{ $stat['value'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Label</label>
                                                <input type="text" name="stats[{{ $index }}][label]" class="form-control" 
                                                    value="{{ $stat['label'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @if($index > 0)
                                        <button type="button" class="btn btn-sm btn-danger remove-stat float-right">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <button type="button" id="add-stat" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Stat
                    </button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Values Section</h4>
                </div>
                <div class="card-body">
                    <div id="values-container">
                        @php
                            $defaultValues = [
                                ['title' => 'Integrity', 'description' => 'We do what\'s right, not what\'s easy.'],
                                ['title' => 'Excellence', 'description' => 'We sweat the details and focus on outcomes.'],
                                ['title' => 'Progress', 'description' => 'We learn, iterate, and improve continuously.'],
                                ['title' => 'Partnership', 'description' => 'We act as an extension of your team.'],
                            ];
                            $values = old('values', $aboutSetting->values ?? $defaultValues);
                        @endphp
                        
                        @foreach(is_array($values) ? $values : [] as $index => $value)
                            <div class="value-row card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" name="values[{{ $index }}][title]" class="form-control" 
                                                    value="{{ $value['title'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <input type="text" name="values[{{ $index }}][description]" class="form-control" 
                                                    value="{{ $value['description'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @if($index > 0)
                                        <button type="button" class="btn btn-sm btn-danger remove-value float-right">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <button type="button" id="add-value" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Value
                    </button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">Services Section</h4>
                </div>
                <div class="card-body">
                    <div id="services-container">
                        @php
                            $defaultServices = [
                                ['title' => 'Website Development', 'description' => 'Fast, secure, and scalable websites built to convert.'],
                                ['title' => 'UI/UX & Branding', 'description' => 'Human-centric design that elevates your brand.'],
                                ['title' => 'Digital Marketing', 'description' => 'SEO, Social, and Ads to drive qualified growth.'],
                                ['title' => 'eCommerce & Integrations', 'description' => 'Payments, analytics, and automations that scale.'],
                            ];
                            $services = old('services', $aboutSetting->services ?? $defaultServices);
                        @endphp
                        
                        @foreach(is_array($services) ? $services : [] as $index => $service)
                            <div class="service-row card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" name="services[{{ $index }}][title]" class="form-control" 
                                                    value="{{ $service['title'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <input type="text" name="services[{{ $index }}][description]" class="form-control" 
                                                    value="{{ $service['description'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @if($index > 0)
                                        <button type="button" class="btn btn-sm btn-danger remove-service float-right">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <button type="button" id="add-service" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Service
                    </button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">CTA Section</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="cta_title">CTA Title</label>
                        <input type="text" name="cta_title" id="cta_title" class="form-control @error('cta_title') is-invalid @enderror" 
                            value="{{ old('cta_title', $aboutSetting->cta_title ?? 'Ready to work with a results-driven team?') }}">
                        @error('cta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="cta_subtitle">CTA Subtitle</label>
                        <input type="text" name="cta_subtitle" id="cta_subtitle" class="form-control @error('cta_subtitle') is-invalid @enderror" 
                            value="{{ old('cta_subtitle', $aboutSetting->cta_subtitle ?? 'Get a free consultation and tailored plan for your business.') }}">
                        @error('cta_subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="cta_button_text">Button Text</label>
                        <input type="text" name="cta_button_text" id="cta_button_text" class="form-control @error('cta_button_text') is-invalid @enderror" 
                            value="{{ old('cta_button_text', $aboutSetting->cta_button_text ?? 'Contact Us') }}">
                        @error('cta_button_text')
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
        // About Items
        let aboutItemIndex = {{ count(is_array(old('about_items', $aboutSetting->about_items ?? $defaultItems)) ? old('about_items', $aboutSetting->about_items ?? $defaultItems) : []) }};
        
        $('#add-about-item').click(function() {
            const template = `
                <div class="item-row card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Icon Class</label>
                                    <input type="text" name="about_items[${aboutItemIndex}][icon]" class="form-control" 
                                        placeholder="fas fa-check">
                                    <small class="form-text text-muted">
                                        Use FontAwesome classes. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="about_items[${aboutItemIndex}][title]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Text</label>
                                    <input type="text" name="about_items[${aboutItemIndex}][text]" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-item float-right">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
            
            $('#about-items-container').append(template);
            aboutItemIndex++;
        });
        
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
        });

        // Stats
        let statIndex = {{ count(is_array(old('stats', $aboutSetting->stats ?? $defaultStats)) ? old('stats', $aboutSetting->stats ?? $defaultStats) : []) }};
        
        $('#add-stat').click(function() {
            const template = `
                <div class="stat-row card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Value</label>
                                    <input type="text" name="stats[${statIndex}][value]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Label</label>
                                    <input type="text" name="stats[${statIndex}][label]" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-stat float-right">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
            
            $('#stats-container').append(template);
            statIndex++;
        });
        
        $(document).on('click', '.remove-stat', function() {
            $(this).closest('.stat-row').remove();
        });

        // Values
        let valueIndex = {{ count(is_array(old('values', $aboutSetting->values ?? $defaultValues)) ? old('values', $aboutSetting->values ?? $defaultValues) : []) }};
        
        $('#add-value').click(function() {
            const template = `
                <div class="value-row card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="values[${valueIndex}][title]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="values[${valueIndex}][description]" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-value float-right">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
            
            $('#values-container').append(template);
            valueIndex++;
        });
        
        $(document).on('click', '.remove-value', function() {
            $(this).closest('.value-row').remove();
        });

        // Services
        let serviceIndex = {{ count(is_array(old('services', $aboutSetting->services ?? $defaultServices)) ? old('services', $aboutSetting->services ?? $defaultServices) : []) }};
        
        $('#add-service').click(function() {
            const template = `
                <div class="service-row card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="services[${serviceIndex}][title]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="services[${serviceIndex}][description]" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-service float-right">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
            
            $('#services-container').append(template);
            serviceIndex++;
        });
        
        $(document).on('click', '.remove-service', function() {
            $(this).closest('.service-row').remove();
        });
    });
</script>
@endsection
