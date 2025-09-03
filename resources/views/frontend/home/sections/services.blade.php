<!-- Services Overview -->
<section id="services" class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">Our Services</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600">Complete digital solutions under one roof</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Service Categories Section -->
            @forelse($categories as $category)
            <a href="{{ route('services.category', $category->slug) }}" class="block">
                <div class="bg-white p-8 rounded-lg shadow-lg card-hover">
                    <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center">
                        <i class="{{ $category->icon ?? 'fas fa-folder' }} text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">{{ $category->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($category->description, 100) }}</p>
                    <div class="mt-4 text-blue-600 font-semibold flex items-center">
                        <span>View Services</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </div>
                </div>
            </a>
            @empty
            <!-- Fallback content if no services are found -->
            <div class="col-span-3 text-center py-8">
                <p class="text-gray-500">No services available at the moment. Please check back later.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
