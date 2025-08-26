<!-- Footer -->
<footer class="gradient-bg text-white py-12">
    <div class="container mx-auto px-6">
        <div class="text-center">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-code text-3xl mr-3"></i>
                <span class="text-3xl font-bold">{{ $footerSettings->company_name ?? 'Wise Dynamic' }}</span>
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

            <div class="border-t border-white border-opacity-20 pt-6">
                @php $copy = $footerSettings->copyright_text ?? ('© '.date('Y').' Wise Dynamic. All rights reserved.'); @endphp
                <p class="opacity-75">{{ $copy }}</p>
            </div>
        </div>
    </div>
</footer>
