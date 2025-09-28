@extends('layouts.app')

@section('content')
<!-- Hero Banner -->
<header class="theme-gradient text-white pt-28 pb-16">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">Digital Marketing Packages</h1>
            <p class="mt-4 text-white/90 text-lg">Boost your online presence with our comprehensive marketing solutions</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#web-packages" class="btn-primary px-6 py-3 rounded-full font-semibold shadow">View Packages</a>
                <a href="{{ url('/#contact') }}" class="bg-white text-gray-900 px-6 py-3 rounded-full font-semibold hover:opacity-90">Get Custom Quote</a>
            </div>
        </div>
    </div>
</header>

<!-- Website Development Packages -->
<section id="web-packages" class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">Website Development Packages</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600">Choose the perfect package for your business needs</p>
        </div>
        
        <div class="grid lg:grid-cols-{{ count($webDevPackages) > 0 ? min(count($webDevPackages), 4) : 4 }} gap-6">
            @forelse($webDevPackages as $index => $package)
                @php
                    // Define different color schemes for packages
                    $colorSchemes = [
                        ['from-blue-50', 'hover:border-blue-200', ''],
                        ['from-purple-50', 'border-purple-200', 'bg-purple-500'],
                        ['from-green-50', 'hover:border-green-200', ''],
                        ['from-yellow-50', 'hover:border-yellow-200', 'bg-yellow-500']
                    ];
                    
                    $currentScheme = $colorSchemes[$index % count($colorSchemes)];
                    $featured = $package->featured;
                @endphp
                
                <div class="bg-gradient-to-b {{ $currentScheme[0] }} to-white p-8 rounded-lg shadow-lg card-hover border-2 border-transparent {{ $featured ? $currentScheme[1] : 'hover:'.$currentScheme[1] }}">
                    <div class="text-center mb-6">
                        @if($featured || $index === 1)
                            <div class="{{ $currentScheme[2] ? $currentScheme[2] : 'bg-blue-500' }} text-white px-3 py-1 rounded-full text-sm mb-2 inline-block">{{ $index === 3 ? 'Enterprise' : 'Popular' }}</div>
                        @endif
                        <h3 class="text-2xl font-bold mb-2">{{ $package->title }}</h3>
                        <div class="price-highlight text-{{ $index === 3 ? '2xl' : '3xl' }} font-bold mb-2">
                            @if($index === 3)
                                From BDT {{ number_format($package->price) }}/-
                            @else
                                BDT {{ number_format($package->price) }}/-
                            @endif
                        </div>
                        <p class="text-gray-600">{{ $package->short_description }}</p>
                    </div>
                    
                    <div class="prose space-y-3 mb-6">
                        {!! $package->description !!}
                    </div>
                    
                    <div class="text-sm text-gray-600 mb-4">
                        @if($index === 3)
                            <p class="font-semibold text-center">Contact for Custom Quote</p>
                        @else
                            @if($index === 0)
                                <p>Payment Gateway Setup: <span class="font-semibold">BDT 22,500/-</span></p>
                                <p>Integration: <span class="font-semibold">BDT 5,000/-</span></p>
                            @elseif($index === 1)
                                <p>Payment Gateway Setup: <span class="font-semibold">BDT 20,000/-</span></p>
                                <p>Integration: <span class="font-semibold">BDT 4,000/-</span></p>
                            @elseif($index === 2)
                                <p>Payment Gateway Setup: <span class="font-semibold">BDT 17,500/-</span></p>
                            @endif
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('packages.show', $package->slug) }}" class="btn-primary px-6 py-3 rounded-full font-semibold">View Details</a>
                        <a href="{{ route('cart.show', ['package' => $package->slug]) }}" class="btn-outline-primary px-6 py-3 rounded-full font-semibold">Get</a>
                        <a href="{{ url('/#contact') }}?package={{ $package->slug }}&consultation=free" class="text-primary hover:underline text-sm mt-2 block text-center">Free Consultation</a>
                    </div>
                </div>
            @empty
                <!-- Fallback if no packages are found -->
                <div class="col-span-4 text-center py-10">
                    <p class="text-xl text-gray-600">No website development packages available at the moment.</p>
                    <p class="mt-4">Please check back later or <a href="{{ url('/contact') }}" class="text-primary hover:underline">contact us</a> for custom solutions.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Digital Marketing Packages -->
<section id="marketing-packages" class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">Choose Your Package</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600">Select the perfect package that fits your business requirements and budget</p>
        </div>
        
        <div class="grid lg:grid-cols-{{ count($marketingPackages) > 0 ? min(count($marketingPackages), 3) : 3 }} gap-6">
            @forelse($marketingPackages as $index => $package)
                @php
                    // Define different color schemes for packages
                    $colorSchemes = [
                        ['from-blue-50', 'hover:border-blue-200', ''],
                        ['from-purple-50', 'border-purple-200', 'bg-purple-500'],
                        ['from-yellow-50', 'hover:border-yellow-200', '']
                    ];
                    
                    $currentScheme = $colorSchemes[$index % count($colorSchemes)];
                    $featured = $package->featured;
                @endphp
                
                <div class="bg-gradient-to-b {{ $currentScheme[0] }} to-white p-8 rounded-lg shadow-lg card-hover border-2 {{ $featured || $index === 1 ? $currentScheme[1] : 'border-transparent hover:'.$currentScheme[1] }}">
                    <div class="text-center mb-6">
                        @if($featured || $index === 1)
                            <div class="{{ $currentScheme[2] ? $currentScheme[2] : 'bg-blue-500' }} text-white px-3 py-1 rounded-full text-sm mb-2 inline-block">{{ $index === 1 ? 'Recommended' : 'Popular' }}</div>
                        @endif
                        <h3 class="text-2xl font-bold mb-2">{{ $package->title }}</h3>
                        <div class="price-highlight text-2xl font-bold mb-2">BDT {{ number_format($package->price) }}/- <span class="text-base font-semibold">{{ $package->price_unit }}</span></div>
                    </div>

                    <div class="prose space-y-3 mb-6">
                        {!! $package->description !!}
                    </div>

                    <div class="flex flex-wrap gap-3 mt-2">
                        <a href="{{ route('cart.show', ['package' => $package->slug]) }}" class="btn-primary px-6 py-3 rounded-full font-semibold">Get</a>
                        <a href="{{ url('/#contact') }}?package={{ $package->slug }}&consultation=free" class="btn-outline-primary px-6 py-3 rounded-full font-semibold">Free Consultation</a>
                    </div>
                </div>
            @empty
                <!-- Fallback if no packages are found -->
                <div class="col-span-3 text-center py-10">
                    <p class="text-xl text-gray-600">No digital marketing packages available at the moment.</p>
                    <p class="mt-4">Please check back later or <a href="{{ url('/contact') }}" class="text-primary hover:underline">contact us</a> for custom solutions.</p>
                </div>
            @endforelse
        </div>
        
        <!-- Additional Info Section -->
        <div class="mt-16 text-center">
            <h3 class="text-2xl font-bold mb-4 gradient-text">Need Something Custom?</h3>
            <p class="text-gray-600 mb-6">We can create a custom package tailored to your specific requirements</p>
            <a href="{{ url('/#contact') }}" class="btn-outline-primary px-8 py-3 rounded-full font-semibold">Contact Us</a>
        </div>
    </div>
</section>
@endsection

