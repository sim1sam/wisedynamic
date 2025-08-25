@extends('layouts.app')

@section('content')
<!-- Hero Banner -->
<header class="theme-gradient text-white pt-28 pb-16">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">{{ $title }}</h1>
            <p class="mt-4 text-white/90 text-lg">{{ $subtitle }}</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#services" class="btn-primary px-6 py-3 rounded-full font-semibold shadow">Learn More</a>
                <a href="{{ url('/#contact') }}?service={{ urlencode($title) }}" class="bg-white text-gray-900 px-6 py-3 rounded-full font-semibold hover:opacity-90">Apply Now</a>
            </div>
        </div>
    </div>
</header>

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <!-- Main layout with sidebar aligned and with proper gaps -->
        <div class="lg:grid lg:grid-cols-[1fr,24rem] lg:gap-6">
            <!-- Left: image and content -->
            <div class="space-y-8">
                <!-- Visual -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-4 lg:mr-3">
                    @if(!empty($image))
                        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-64 md:h-80 object-cover">
                    @else
                        <div class="w-full h-64 md:h-80 theme-gradient"></div>
                    @endif
                </div>

                <!-- Expertise -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-blue-700 mb-3">Our Expertise</h2>
                    <div class="h-0.5 w-14 bg-blue-600 mb-4"></div>
                    <p class="text-gray-700 leading-relaxed">{{ $expertise }}</p>
                </div>

                <!-- Core Features -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-blue-700 mb-3">Core Features</h3>
                    <div class="h-0.5 w-14 bg-blue-600 mb-4"></div>
                    <ul class="text-gray-700 space-y-2">
                        @foreach($features as $item)
                            <li class="flex items-start"><i class="fas fa-check mt-1 mr-3 text-blue-600"></i><span>{{ $item }}</span></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Client Benefits -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-blue-700 mb-3">Client Benefits</h3>
                    <div class="h-0.5 w-14 bg-blue-600 mb-4"></div>
                    <ul class="text-gray-700 space-y-2">
                        @foreach($benefits as $item)
                            <li class="flex items-start"><i class="fas fa-check mt-1 mr-3 text-blue-600"></i><span>{{ $item }}</span></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Technologies -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-blue-700 mb-3">Technologies We Use</h3>
                    <div class="h-0.5 w-14 bg-blue-600 mb-4"></div>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($technologies as $tech)
                            <div class="bg-gray-50 border border-gray-200 rounded-md px-3 py-2 text-sm">{{ $tech }}</div>
                        @endforeach
                    </div>
                </div>

                <!-- CTA strip -->
                <div class="text-center py-10">
                    <h4 class="text-xl font-semibold mb-2">Ready to start your project?</h4>
                    <p class="text-gray-600 mb-5">Contact us today to discuss your development needs.</p>
                    <a href="{{ url('/#contact') }}" class="btn-primary px-6 py-3 rounded-full">Contact Us</a>
                </div>
            </div>

            <!-- Right: Sidebar CTA -->
            <aside class="mt-8 lg:mt-0 lg:sticky lg:top-24 lg:self-start">
                <div class="theme-gradient text-white rounded-xl shadow-lg p-6 min-h-80 flex flex-col justify-between lg:w-full">
                    <h3 class="text-lg font-bold mb-1">Start Your Project</h3>
                    <p class="text-white/90 mb-4">Let's turn your vision into a real, high‑performing solution tailored for growth.</p>

                    <h4 class="text-sm font-semibold text-white mb-2">Why Clients Love Us</h4>
                    <ul class="space-y-2 text-sm text-white/90 mb-5">
                        <li class="flex"><i class="fas fa-check text-white mr-3 mt-1"></i><span>Transparent & upfront pricing</span></li>
                        <li class="flex"><i class="fas fa-check text-white mr-3 mt-1"></i><span>Lightning‑fast delivery timelines</span></li>
                        <li class="flex"><i class="fas fa-check text-white mr-3 mt-1"></i><span>24/7 technical & customer support</span></li>
                        <li class="flex"><i class="fas fa-check text-white mr-3 mt-1"></i><span>Long‑term maintenance options</span></li>
                        <li class="flex"><i class="fas fa-check text-white mr-3 mt-1"></i><span>Solutions that scale with you</span></li>
                        <li class="flex"><i class="fas fa-check text-white mr-3 mt-1"></i><span>100% transparency and ownership</span></li>
                    </ul>

                    <a href="{{ url('/#contact') }}?service={{ urlencode($title) }}" class="block w-full text-center px-5 py-3 rounded-full mt-2 mb-3 bg-white text-gray-900 font-semibold hover:opacity-90">Apply Now</a>
                    <a href="#" class="block text-center w-full border border-white/60 rounded-full py-3 text-white hover:bg-white/10">View Portfolio</a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
