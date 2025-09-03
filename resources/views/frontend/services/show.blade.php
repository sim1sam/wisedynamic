@extends('layouts.app')

@section('content')
<!-- Hero Banner -->
<header class="theme-gradient text-white pt-28 pb-16">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">{{ $service->title }}</h1>
            <p class="mt-4 text-white/90 text-lg">{{ $service->short_description }}</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#details" class="btn-primary px-6 py-3 rounded-full font-semibold shadow">Learn More</a>
                <a href="{{ url('/#contact') }}?service={{ urlencode($service->title) }}" class="bg-white text-gray-900 px-6 py-3 rounded-full font-semibold hover:opacity-90">Apply Now</a>
            </div>
        </div>
    </div>
</header>

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <!-- Main layout with sidebar aligned and with proper gaps -->
        <div class="lg:grid lg:grid-cols-[1fr,24rem] lg:gap-6">
            <!-- Left: image and content -->
            <div class="space-y-8" id="details">
                <!-- Visual -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-4 lg:mr-3">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="w-full h-64 md:h-80 object-cover">
                    @else
                        <div class="w-full h-64 md:h-80 theme-gradient"></div>
                    @endif
                </div>

                <!-- Service Description -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-blue-700 mb-3">About This Service</h2>
                    <div class="h-0.5 w-14 bg-blue-600 mb-4"></div>
                    <div class="text-gray-700 leading-relaxed">
                        {!! $service->description !!}
                    </div>
                </div>
                
                <!-- Category -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-blue-700 mb-3">Service Category</h3>
                    <div class="h-0.5 w-14 bg-blue-600 mb-4"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full service-icon flex items-center justify-center">
                            <i class="{{ $service->category->icon ?? 'fas fa-folder' }} text-white"></i>
                        </div>
                        <span class="text-gray-700">{{ $service->category->name }}</span>
                    </div>
                </div>
                
                <!-- Pricing -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-blue-700 mb-3">Pricing</h3>
                    <div class="h-0.5 w-14 bg-blue-600 mb-4"></div>
                    <div class="text-gray-700">
                        @if($service->price)
                            <div class="text-2xl font-bold mb-2">BDT {{ number_format($service->price) }}{{ $service->price_unit ? '/'.$service->price_unit : '' }}</div>
                        @else
                            <div class="text-2xl font-bold mb-2">Contact for pricing</div>
                        @endif
                        <p>Please contact us for a detailed quote tailored to your specific requirements.</p>
                    </div>
                </div>

                <!-- CTA strip -->
                <div class="text-center py-10">
                    <h4 class="text-xl font-semibold mb-2">Ready to start your project?</h4>
                    <p class="text-gray-600 mb-5">Contact us today to discuss your development needs.</p>
                    <a href="{{ url('/#contact') }}" class="btn-primary px-6 py-3 rounded-full">Contact Us</a>
                </div>
            </div>

            <!-- Right: Sidebar CTA and Related Services -->
            <aside class="mt-8 lg:mt-0 lg:sticky lg:top-24 lg:self-start space-y-6">
                <!-- CTA Box -->
                <div class="theme-gradient text-white rounded-xl shadow-lg p-6 min-h-80 flex flex-col justify-between lg:w-full">
                    <h3 class="text-lg font-bold mb-1">Start Your Project</h3>
                    <p class="text-white/90 mb-4">Let's turn your vision into a real, high‑performing solution tailored for growth.</p>

                    <h4 class="text-sm font-semibold text-white mb-2">Why Clients Love Us</h4>
                    <ul class="space-y-2 text-sm text-white/90 mb-5">
                        <li class="flex"><i class="fas fa-check text-white mr-3 mt-1"></i><span>Transparent & upfront pricing</span></li>
                        <li class="flex"><i class="fas fa-check text-white mr-3 mt-1"></i><span>Lightning‑fast delivery timelines</span></li>
                        <li class="flex"><i class="fas fa-check text-white mr-3 mt-1"></i><span>24/7 technical & customer support</span></li>
                        <li class="flex"><i class="fas fa-check text-white mr-3 mt-1"></i><span>Long‑term maintenance options</span></li>
                    </ul>

                    <a href="{{ url('/#contact') }}?service={{ urlencode($service->title) }}" class="block w-full text-center px-5 py-3 rounded-full mt-2 mb-3 bg-white text-gray-900 font-semibold hover:opacity-90">Apply Now</a>
                    <a href="{{ route('services.index') }}" class="block text-center w-full border border-white/60 rounded-full py-3 text-white hover:bg-white/10">View All Services</a>
                </div>
                
                <!-- Related Services -->
                @if($relatedServices->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-blue-700 mb-3">Related Services</h3>
                    <div class="h-0.5 w-14 bg-blue-600 mb-4"></div>
                    
                    <div class="space-y-4">
                        @foreach($relatedServices as $relatedService)
                        <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <h4 class="font-semibold mb-1">{{ $relatedService->title }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($relatedService->short_description, 80) }}</p>
                            <a href="{{ route('services.show', $relatedService->slug) }}" class="text-blue-600 text-sm font-medium hover:underline">Learn more →</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
        </div>
    </div>
</section>
@endsection
