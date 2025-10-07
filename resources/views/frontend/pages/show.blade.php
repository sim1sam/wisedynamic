@extends('layouts.app')

@section('title', $page->title)

@section('meta_description', $page->short_description)



@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="container text-center">
        <h1 class="page-title">{{ $page->title }}</h1>
        @if($page->short_description)
            <p class="page-description mx-auto">{{ $page->short_description }}</p>
        @endif
    </div>
</div>

<div class="container page-content-wrapper pb-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="bg-white rounded-lg shadow-sm p-4 p-md-5 mb-5">
                @if($page->image)
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $page->image) }}" alt="{{ $page->title }}" class="page-image">
                    </div>
                @endif
                
                <div class="page-content">
                    {!! $page->content !!}
                </div>
                
                <div class="mt-4 pt-3 border-top">
                    <p class="page-meta">
                        <i class="far fa-calendar-alt"></i> Last updated: {{ $page->updated_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="page-card quick-links-card p-4 mb-4">
                <h3 class="h5 mb-3">Quick Links</h3>
                <ul class="list-unstyled">
                    @php
                        $footerPages = \App\Models\Page::where('show_in_footer', true)
                            ->where('is_active', true)
                            ->where('id', '!=', $page->id)
                            ->orderBy('order')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @foreach($footerPages as $footerPage)
                        <li class="mb-2">
                            <a href="{{ route('page.show', $footerPage->slug) }}" class="d-flex align-items-center">
                                <i class="fas fa-angle-right mr-2"></i>
                                <span>{{ $footerPage->title }}</span>
                            </a>
                        </li>
                    @endforeach
                    
                    <li class="mb-2">
                        <a href="{{ route('contact') }}" class="d-flex align-items-center">
                            <i class="fas fa-angle-right mr-2"></i>
                            <span>Contact Us</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="page-card help-card p-4 text-center">
                <h3 class="h5 mb-3">Need Assistance?</h3>
                <p class="mb-3">Have questions about our services? Our team is here to help!</p>
                <a href="{{ route('contact') }}" class="btn-contact">
                    <i class="fas fa-envelope mr-2"></i> Contact Our Team
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
