<!-- Contact Section -->
<section id="contact" class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">{{ $homeSetting->contact_title ?? 'Let\'s Build Something Amazing Together' }}</h2>
            <div class="w-20 h-1 gradient-bg mx-auto mb-6"></div>
            <p class="text-xl text-gray-600">{{ $homeSetting->contact_subtitle ?? 'Ready to bring your digital vision to life? Contact us today!' }}</p>
        </div>
        
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mb-6 gradient-text">Get In Touch</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 service-icon rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Call Us</h4>
                                <p class="text-gray-600">{{ $homeSetting->contact_phone ?? '+880 1805 081012' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 service-icon rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Email Us</h4>
                                <p class="text-gray-600">{{ $homeSetting->contact_email ?? 'sales@wisedynamic.com.bd' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 service-icon rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Location</h4>
                                <p class="text-gray-600">{{ $homeSetting->contact_location ?? 'Bangladesh' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 p-6 bg-white rounded-lg shadow-inner">
                        <h4 class="font-bold text-gray-800 mb-3">Quick Response Promise</h4>
                        <p class="text-gray-600 text-sm">We respond to all inquiries within 2 hours during business hours. Your success is our priority!</p>
                    </div>
                </div>
            </div>
            
            <div>
                <div class="bg-white p-8 rounded-lg shadow-2xl">
                    <h3 class="text-2xl font-bold mb-6 gradient-text text-center">Ready to Start Your Project?</h3>
                    
                    <div class="space-y-6">
                        <div class="text-center p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg">
                            <div class="text-4xl font-bold price-highlight mb-2">FREE</div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Consultation & Quote</h4>
                            <p class="text-gray-600">Get expert advice and detailed project estimate at no cost</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="p-4 bg-green-50 rounded-lg">
                                <i class="fas fa-clock text-green-600 text-2xl mb-2"></i>
                                <h5 class="font-bold text-gray-800">Fast Delivery</h5>
                                <p class="text-sm text-gray-600">Quick turnaround times</p>
                            </div>
                            
                            <div class="p-4 bg-blue-50 rounded-lg">
                                <i class="fas fa-shield-alt text-blue-600 text-2xl mb-2"></i>
                                <h5 class="font-bold text-gray-800">Guaranteed Quality</h5>
                                <p class="text-sm text-gray-600">100% satisfaction promise</p>
                            </div>
                        </div>
                        
                        <div class="text-center space-y-4">
                            <a href="tel:{{ str_replace(' ', '', $homeSetting->contact_phone ?? '+8801805081012') }}" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-full font-bold text-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                <i class="fas fa-phone mr-2"></i>Call Now for Free Consultation
                            </a>
                            
                            @if($homeSetting->contact_whatsapp ?? null)
                            <div class="mt-3">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $homeSetting->contact_whatsapp) }}?text=Hi%20Wise%20Dynamic,%20I'm%20interested%20in%20your%20services" target="_blank" class="inline-block bg-gradient-to-r from-green-500 to-green-700 text-white px-8 py-3 rounded-full font-bold text-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                    <i class="fab fa-whatsapp mr-2"></i>Chat on WhatsApp
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
