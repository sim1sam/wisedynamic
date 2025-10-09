@extends('layouts.app')

@section('title', $page->title)

@section('meta_description', $page->short_description)



@section('content')
<!-- About-style Header -->
<header class="theme-gradient text-white pt-28 pb-12">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold">{{ $page->title }}</h1>
        @if($page->short_description)
            <p class="mt-2 text-white/90">{{ $page->short_description }}</p>
        @endif
    </div>
</header>

<!-- Content -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-3 gap-8 items-start">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6 md:p-8">
                    @if($page->image)
                        <div class="text-center mb-6">
        <img src="{{ asset($page->image) }}" alt="{{ $page->title }}" class="mx-auto rounded-lg shadow max-h-96">
                        </div>
                    @endif
                    <div class="prose max-w-none page-content">
                        {!! $page->content !!}
                    </div>
                    <div class="mt-6 pt-4 border-t text-gray-600 text-sm">
                        <i class="far fa-calendar-alt mr-2"></i> Last updated: {{ $page->updated_at->format('M d, Y') }}
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <div class="p-6 rounded-lg shadow bg-gradient-to-br from-blue-50 to-purple-50 mb-6">
                    <h3 class="text-lg font-bold mb-3 gradient-text">Quick Links</h3>
                    <ul class="space-y-2">
                    @php
                        $footerPages = \App\Models\Page::where('show_in_footer', true)
                            ->where('is_active', true)
                            ->where('id', '!=', $page->id)
                            ->orderBy('order')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @foreach($footerPages as $footerPage)
                        <li>
                            <a href="{{ route('page.show', $footerPage->slug) }}" class="flex items-center text-gray-800 hover:text-indigo-700">
                                <i class="fas fa-angle-right mr-2"></i>
                                <span>{{ $footerPage->title }}</span>
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('contact') }}" class="flex items-center text-gray-800 hover:text-indigo-700">
                            <i class="fas fa-envelope mr-2"></i>
                            <span>Contact Us</span>
                        </a>
                    </li>
                    </ul>
                </div>

                <div class="p-6 rounded-lg shadow text-center bg-white">
                    <h3 class="text-lg font-bold mb-2">Need Assistance?</h3>
                    <p class="text-gray-700 mb-4">Have questions about our services? Our team is here to help!</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow">
                        <i class="fas fa-envelope mr-2"></i> Contact Our Team
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
