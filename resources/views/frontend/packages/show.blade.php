@extends('layouts.app')

@section('content')
<!-- Hero Banner -->
<header class="theme-gradient text-white pt-28 pb-16">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">{{ $package->title }}</h1>
            <p class="mt-4 text-white/90 text-lg">{{ $package->short_description }}</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#features" class="btn-primary px-6 py-3 rounded-full font-semibold shadow">View Features</a>
                <a href="{{ url('/#contact') }}" class="bg-white text-gray-900 px-6 py-3 rounded-full font-semibold hover:opacity-90">Get Custom Quote</a>
            </div>
        </div>
    </div>
</header>

<!-- Package Details -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left Column - Package Details -->
            <div class="lg:w-2/3">
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6">Package Overview</h2>
                    <div class="prose prose-lg max-w-none">
                        {!! $package->description !!}
                    </div>
                </div>
                
                <div id="features" class="mb-12">
                    <h2 class="text-3xl font-bold mb-6">Key Features</h2>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="space-y-4">
                            @php
                                // Parse description for features
                                $features = explode("\n", $package->description);
                            @endphp
                            
                            @foreach($features as $feature)
                                @if(trim($feature) != '')
                                    <div class="flex items-start">
                                        <div class="mt-1 mr-3 flex-shrink-0">
                                            @if(strpos(strtolower($feature), 'delivery') !== false)
                                                <i class="fas fa-clock text-orange-500"></i>
                                            @else
                                                <i class="fas fa-check-circle text-green-500"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-gray-800">{{ trim($feature) }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6">Why Choose This Package</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="text-blue-500 mb-3">
                                <i class="fas fa-medal text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Premium Quality</h3>
                            <p class="text-gray-700">Get top-notch service with attention to detail and professional results.</p>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <div class="text-green-500 mb-3">
                                <i class="fas fa-hand-holding-usd text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Cost Effective</h3>
                            <p class="text-gray-700">Maximize your ROI with our carefully designed package pricing.</p>
                        </div>
                        
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="text-purple-500 mb-3">
                                <i class="fas fa-headset text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Dedicated Support</h3>
                            <p class="text-gray-700">Get personalized assistance throughout the entire process.</p>
                        </div>
                        
                        <div class="bg-yellow-50 p-6 rounded-lg">
                            <div class="text-yellow-500 mb-3">
                                <i class="fas fa-bolt text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Fast Delivery</h3>
                            <p class="text-gray-700">Quick turnaround times without compromising on quality.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Pricing Card & CTA -->
            <div class="lg:w-1/3">
                <div class="sticky top-24">
                    <div class="bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 py-6 px-6 text-white">
                            <h3 class="text-2xl font-bold mb-1">{{ $package->title }}</h3>
                            <p class="opacity-90">{{ $package->category->name ?? 'Package' }}</p>
                        </div>
                        
                        <div class="p-6">
                            <div class="mb-6 text-center">
                                <span class="text-4xl font-bold">BDT {{ number_format($package->price) }}</span>
                                @if($package->price_unit)
                                    <span class="text-gray-500">/{{ $package->price_unit }}</span>
                                @endif
                            </div>
                            
                            <div class="space-y-3 mb-6">
                                @php
                                    // Get first 5 features for sidebar
                                    $sidebarFeatures = array_slice(array_filter($features, function($item) {
                                        return trim($item) != '';
                                    }), 0, 5);
                                @endphp
                                
                                @foreach($sidebarFeatures as $feature)
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        <span class="text-sm">{{ trim($feature) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="space-y-3">
                                <a href="{{ url('/#contact') }}" class="block text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:opacity-90 transition">
                                    Get Started
                                </a>
                                <a href="tel:+8801234567890" class="block text-center border border-gray-300 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-50 transition">
                                    <i class="fas fa-phone-alt mr-2"></i> Call for Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Packages -->
@if($relatedPackages->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Related Packages</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600">Explore other options that might suit your needs</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($relatedPackages as $index => $relatedPackage)
                @php
                    $colorSchemes = [
                        ['from-blue-50', 'hover:border-blue-200'],
                        ['from-purple-50', 'hover:border-purple-200'],
                        ['from-green-50', 'hover:border-green-200']
                    ];
                    $currentScheme = $colorSchemes[$index % count($colorSchemes)];
                @endphp
                
                <div class="bg-gradient-to-b {{ $currentScheme[0] }} to-white p-6 rounded-lg shadow-lg card-hover border-2 border-transparent {{ $currentScheme[1] }}">
                    <div class="text-center mb-4">
                        <h3 class="text-xl font-bold mb-2">{{ $relatedPackage->title }}</h3>
                        <div class="price-highlight text-2xl font-bold mb-2">
                            BDT {{ number_format($relatedPackage->price) }}/-
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-6 text-center">{{ $relatedPackage->short_description }}</p>
                    
                    <div class="text-center">
                        <a href="{{ route('packages.show', $relatedPackage->slug) }}" class="inline-block bg-white border border-gray-300 hover:border-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Call to Action -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl opacity-90 mb-8 max-w-2xl mx-auto">Contact our team today to discuss how we can help you achieve your goals with our {{ $package->title }} package.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ url('/#contact') }}" class="bg-white text-gray-900 px-6 py-3 rounded-full font-semibold hover:opacity-90">Contact Us Now</a>
            <a href="{{ route('packages') }}" class="border-2 border-white text-white px-6 py-3 rounded-full font-semibold hover:bg-white/10">View All Packages</a>
        </div>
    </div>
</section>
@endsection
