<!-- Footer -->
<footer class="gradient-bg text-white py-12">
    <div class="container mx-auto px-6">
        <div class="relative">
            <!-- SSL Logo positioned on the right -->
            <div class="absolute top-0 right-0 hidden md:block">
                @if(!empty($footerSettings->ssl_logo))
                    <img src="{{ asset($footerSettings->ssl_logo) }}" alt="SSL Payment Gateway" class="w-64 h-auto opacity-90 hover:opacity-100 transition-opacity">
                @else
                    <img src="{{ asset('images/ssl-logo.svg') }}" alt="SSL Payment Gateway" class="w-64 h-auto opacity-90 hover:opacity-100 transition-opacity">
                @endif
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    @if(!empty($websiteSettings->site_logo))
                        <img src="{{ asset('storage/' . $websiteSettings->site_logo) }}" alt="{{ $websiteSettings->logo_alt_text ?? 'Company Logo' }}" class="h-12 w-auto">
                    @endif
                </div>
                @php $tagline = $footerSettings->tagline ?? 'Your Technology Partner for Innovation, Affordability & Results'; @endphp
                <p class="text-lg mb-6 opacity-90">{{ $tagline }}</p>

                <div class="flex justify-center space-x-8 mb-8">
                    @php $phone = $footerSettings->phone ?? '+8801805081012'; @endphp
                    <a href="tel:{{ $phone }}" class="hover:text-yellow-300 transition" title="Call">
                        <i class="fas fa-phone text-2xl"></i>
                    </a>
                    @php $email = $footerSettings->email ?? 'sales@wisedynamic.com.bd'; @endphp
                    <a href="mailto:{{ $email }}" class="hover:text-yellow-300 transition" title="Email">
                        <i class="fas fa-envelope text-2xl"></i>
                    </a>
                    @if(!empty($footerSettings?->facebook_url))
                        <a href="{{ $footerSettings->facebook_url }}" target="_blank" rel="noopener" class="hover:text-yellow-300 transition" title="Facebook">
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                    @endif
                    @if(!empty($footerSettings?->twitter_url))
                        <a href="{{ $footerSettings->twitter_url }}" target="_blank" rel="noopener" class="hover:text-yellow-300 transition" title="Twitter">
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                    @endif
                    @if(!empty($footerSettings?->linkedin_url))
                        <a href="{{ $footerSettings->linkedin_url }}" target="_blank" rel="noopener" class="hover:text-yellow-300 transition" title="LinkedIn">
                            <i class="fab fa-linkedin text-2xl"></i>
                        </a>
                    @endif
                    @if(!empty($footerSettings?->instagram_url))
                        <a href="{{ $footerSettings->instagram_url }}" target="_blank" rel="noopener" class="hover:text-yellow-300 transition" title="Instagram">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                    @endif
                </div>

                <!-- Footer Pages Links -->
                <div class="mb-6">
                    <div class="flex flex-wrap justify-center gap-4">
                        @php
                            $footerPages = \App\Models\Page::where('show_in_footer', true)
                                ->where('is_active', true)
                                ->orderBy('order')
                                ->get();
                        @endphp
                        
                        @foreach($footerPages as $page)
                            <a href="{{ route('page.show', $page->slug) }}" class="text-white hover:text-yellow-300 transition">
                                {{ $page->title }}
                            </a>
                            @if(!$loop->last)
                                <span class="text-white text-opacity-50">|</span>
                            @endif
                        @endforeach
                    </div>
                </div>
                
                <div class="border-t border-white border-opacity-20 pt-6">
                    @php $copy = $footerSettings->copyright_text ?? ('© '.date('Y').' Wise Dynamic. All rights reserved.'); @endphp
                    <p class="opacity-75">{{ $copy }}</p>
                    
                    <!-- SSL Logo for mobile - centered below copyright -->
                    <div class="md:hidden mt-4 flex justify-center">
                        @if(!empty($footerSettings->ssl_logo))
                            <img src="{{ asset($footerSettings->ssl_logo) }}" alt="SSL Payment Gateway" class="w-48 h-auto opacity-90">
                        @else
                            <img src="{{ asset('images/ssl-logo.svg') }}" alt="SSL Payment Gateway" class="w-48 h-auto opacity-90">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
