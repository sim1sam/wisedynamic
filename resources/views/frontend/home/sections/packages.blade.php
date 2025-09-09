<!-- Website Development Packages -->
<section id="packages" class="py-16 bg-white">
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
                    
                    // Parse description for features
                    $description = $package->description;
                    $features = explode("\n", $description);
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
                    
                    <div class="space-y-3 mb-6">
                        @foreach($features as $feature)
                            @if(trim($feature) != '')
                                <div class="flex items-center">
                                    @if(strpos(strtolower($feature), 'delivery') !== false)
                                        <i class="fas fa-clock text-orange-500 mr-2"></i>
                                    @else
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                    @endif
                                    <span>{{ trim($feature) }}</span>
                                </div>
                            @endif
                        @endforeach
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
                </div>
            @empty
                <!-- Fallback if no packages are found -->
                <div class="col-span-4 text-center py-10">
                    <p class="text-xl text-gray-600">No website development packages available at the moment.</p>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('packages') }}#web-packages" class="btn-primary px-8 py-3 rounded-full font-semibold">View All Packages</a>
        </div>
    </div>
</section>
