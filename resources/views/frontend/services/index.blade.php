@extends('layouts.app')

@section('content')
<!-- Hero -->
<header class="theme-gradient text-white pt-28 pb-16">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">Services that grow your business</h1>
            <p class="mt-4 text-white/90 text-lg">Full-stack web, app, design, and marketing solutions powered by modern tech.</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#services" class="btn-primary px-6 py-3 rounded-full font-semibold shadow">Explore Services</a>
                <a href="{{ url('/#contact') }}" class="bg-white text-gray-900 px-6 py-3 rounded-full font-semibold hover:opacity-90">Apply Now</a>
            </div>
        </div>
    </div>
</header>

<!-- Services Grid (cards) -->
<section id="services" class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">Our Services</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600">Clear features, upfront pricing, and fast apply.</p>
            @if($activeCategory)
                <p class="mt-2 text-sm text-gray-500">Category: <span class="font-semibold text-gray-700">{{ $activeCategory->name }}</span></p>
            @endif
        </div>
        
        <!-- Category Filter -->
        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <a href="{{ route('services.index') }}" class="px-4 py-2 rounded-full text-sm {{ !$activeCategory ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">All Categories</a>
            @foreach($categories as $category)
                <a href="{{ route('services.category', $category->slug) }}" class="px-4 py-2 rounded-full text-sm {{ $activeCategory && $activeCategory->id == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ $category->name }}</a>
            @endforeach
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            @forelse($services as $service)
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 card-hover hover:shadow-2xl hover:-translate-y-1 transition-all">
                <div class="w-16 h-16 service-icon rounded-full mb-5 flex items-center justify-center">
                    <i class="{{ $service->category->icon ?? 'fas fa-cogs' }} text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">{{ $service->title }}</h3>
                <p class="text-gray-600 mb-4">{{ $service->short_description }}</p>
                <div class="flex items-center justify-between pt-4 border-t">
                    <span class="font-bold text-gray-900">
                        @if($service->price)
                            BDT {{ number_format($service->price) }}{{ $service->price_unit ? '/'.$service->price_unit : '' }}
                        @else
                            Contact for pricing
                        @endif
                    </span>
                    <a href="{{ route('services.show', $service->slug) }}" class="btn-primary px-5 py-2 rounded-full">Apply</a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">No services found</h3>
                <p class="text-gray-600">We couldn't find any services matching your criteria.</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-12">
            {{ $services->links() }}
        </div>
    </div>
</section>

@endsection
