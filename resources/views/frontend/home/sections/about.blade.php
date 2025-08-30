<!-- About Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">{{ $homeSetting->about_title ?? 'About Wise Dynamic' }}</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ $homeSetting->about_subtitle ?? 'BASIS certified IT firm since 2020, empowering young entrepreneurs with affordable, high-quality digital solutions' }}</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            @if(isset($homeSetting->about_items) && is_array($homeSetting->about_items))
                @foreach($homeSetting->about_items as $item)
                    <div class="text-center card-hover p-6 rounded-lg">
                        <div class="w-20 h-20 service-icon rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="{{ $item['icon'] ?? 'fas fa-check' }} text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ $item['title'] ?? 'Title' }}</h3>
                        <p class="text-gray-600">{{ $item['text'] ?? 'Description' }}</p>
                    </div>
                @endforeach
            @else
                <div class="text-center card-hover p-6 rounded-lg">
                    <div class="w-20 h-20 service-icon rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-certificate text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">BASIS Certified</h3>
                    <p class="text-gray-600">Official member since 2020</p>
                </div>
                
                <div class="text-center card-hover p-6 rounded-lg">
                    <div class="w-20 h-20 service-icon rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Young Team</h3>
                    <p class="text-gray-600">Innovative & energetic professionals</p>
                </div>
                
                <div class="text-center card-hover p-6 rounded-lg">
                    <div class="w-20 h-20 service-icon rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Affordable</h3>
                    <p class="text-gray-600">Budget-friendly solutions</p>
                </div>
            @endif
        </div>
    </div>
</section>
