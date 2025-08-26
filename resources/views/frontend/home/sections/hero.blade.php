<!-- Hero Section with Image Slider -->
<section class="relative overflow-hidden mt-16">
    <div class="slider-container h-96 relative">
        @php $hasSlides = isset($slides) && $slides->count() > 0; @endphp
        @if($hasSlides)
            @foreach($slides as $i => $s)
                @php
                    $bg = $s->image_source === 'upload' && $s->image_path
                        ? asset('storage/'.$s->image_path)
                        : ($s->image_url ?: '');
                @endphp
                <div class="slide {{ $i === 0 ? 'active' : '' }} h-full">
                    <div class="h-full bg-cover bg-center relative" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ $bg }}');">
                        <div class="absolute inset-0 flex items-center justify-center text-center text-white">
                            <div class="animate-slideUp">
                                <h1 class="text-3xl md:text-5xl font-bold mb-3 md:mb-4">{{ $s->title }}</h1>
                                @if($s->subtitle)
                                    <p class="text-base md:text-xl mb-4 md:mb-6">{{ $s->subtitle }}</p>
                                @endif
                                @if(!empty($s->link_url))
                                    <div class="mt-4">
                                        <a href="{{ $s->link_url }}" class="inline-block bg-white text-gray-900 font-semibold px-4 py-2 rounded-md shadow hover:shadow-lg transition">
                                            Learn More
                                        </a>
                                    </div>
                                @endif
                                @if($s->price_text)
                                    <div class="price-highlight text-xl md:text-3xl font-bold inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-3 md:px-4 py-1.5 md:py-2 rounded-full shadow-lg">{{ $s->price_text }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="slide active h-full">
                <div class="h-full bg-cover bg-center relative" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=2072&q=80');">
                    <div class="absolute inset-0 flex items-center justify-center text-center text-white">
                        <div class="animate-slideUp">
                            <h1 class="text-3xl md:text-5xl font-bold mb-3 md:mb-4">Premium Web Development</h1>
                            <p class="text-base md:text-xl mb-4 md:mb-6">At Unbeatable Prices</p>
                            <div class="price-highlight text-xl md:text-3xl font-bold inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-3 md:px-4 py-1.5 md:py-2 rounded-full shadow-lg">Starting from BDT 20,000/-</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide h-full">
                <div class="h-full bg-cover bg-center relative" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=2015&q=80');">
                    <div class="absolute inset-0 flex items-center justify-center text-center text-white">
                        <div class="animate-slideUp">
                            <h1 class="text-3xl md:text-5xl font-bold mb-3 md:mb-4">Digital Marketing Excellence</h1>
                            <p class="text-base md:text-xl mb-4 md:mb-6">Boost Your Online Presence</p>
                            <div class="price-highlight text-xl md:text-3xl font-bold inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-3 md:px-4 py-1.5 md:py-2 rounded-full shadow-lg">From BDT 12,000/- per month</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide h-full">
                <div class="h-full bg-cover bg-center relative" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1551650975-87deedd944c3?auto=format&fit=crop&w=2074&q=80');">
                    <div class="absolute inset-0 flex items-center justify-center text-center text-white">
                        <div class="animate-slideUp">
                            <h1 class="text-3xl md:text-5xl font-bold mb-3 md:mb-4">Complete IT Solutions</h1>
                            <p class="text-base md:text-xl mb-4 md:mb-6">From Startups to Enterprises</p>
                            <div class="price-highlight text-xl md:text-3xl font-bold inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-3 md:px-4 py-1.5 md:py-2 rounded-full shadow-lg">BASIS Certified Since 2020</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Slider Controls -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
        @if($hasSlides)
            @for($d = 1; $d <= $slides->count(); $d++)
                <button onclick="currentSlide({{ $d }})" class="w-3 h-3 bg-white rounded-full opacity-50 hover:opacity-100 transition"></button>
            @endfor
        @else
            <button onclick="currentSlide(1)" class="w-3 h-3 bg-white rounded-full opacity-50 hover:opacity-100 transition"></button>
            <button onclick="currentSlide(2)" class="w-3 h-3 bg-white rounded-full opacity-50 hover:opacity-100 transition"></button>
            <button onclick="currentSlide(3)" class="w-3 h-3 bg-white rounded-full opacity-50 hover:opacity-100 transition"></button>
        @endif
    </div>
</section>
