@extends('adminlte::page')

@section('title', 'Home Settings')
@section('page_title', 'Home Settings')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">About Section</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.settings.home.update') }}" method="POST">
            @csrf
            @method('PUT')
            
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
                    required>{{ old('about_subtitle', $homeSetting->about_subtitle ?? 'BASIS certified IT firm since 2020, empowering young entrepreneurs with affordable, high-quality digital solutions') }}</textarea>
                @error('about_subtitle')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Feature Items</h4>
                </div>
                <div class="card-body">
                    <div id="items-container">
                        @php
                            $defaultItems = [
                                ['icon' => 'fas fa-certificate', 'title' => 'BASIS Certified', 'text' => 'Official member since 2020'],
                                ['icon' => 'fas fa-users', 'title' => 'Young Team', 'text' => 'Innovative & energetic professionals'],
                                ['icon' => 'fas fa-dollar-sign', 'title' => 'Affordable', 'text' => 'Budget-friendly solutions'],
                            ];
                            $items = old('about_items', $homeSetting->about_items ?? $defaultItems);
                        @endphp
                        
                        @foreach($items as $index => $item)
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
                                        <button type="button" class="btn btn-sm btn-danger remove-item float-right">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <button type="button" id="add-item" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
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
        let itemIndex = {{ count(old('about_items', $homeSetting->about_items ?? $defaultItems)) }};
        
        $('#add-item').click(function() {
            const template = '<div class="item-row card mb-3">'+
                    '<div class="card-body">'+
                        '<div class="row">'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+
                                    '<label>Icon Class</label>'+
                                    '<input type="text" name="about_items['+itemIndex+'][icon]" class="form-control" '+ 
                                        'placeholder="fas fa-certificate" required>'+
                                    '<small class="form-text text-muted">'+
                                        'Use FontAwesome classes. <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>'+
                                    '</small>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+
                                    '<label>Title</label>'+
                                    '<input type="text" name="about_items['+itemIndex+'][title]" class="form-control" required>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+
                                    '<label>Text</label>'+
                                    '<input type="text" name="about_items['+itemIndex+'][text]" class="form-control" required>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<button type="button" class="btn btn-sm btn-danger remove-item float-right">'+
                            '<i class="fas fa-trash"></i> Remove'+
                        '</button>'+
                    '</div>'+
                '</div>';
            
            $('#items-container').append(template);
            itemIndex++;
        });
        
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
        });
    });
</script>
@endsection
