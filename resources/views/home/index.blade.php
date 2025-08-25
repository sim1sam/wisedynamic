@extends('layouts.app')

@section('content')
    {{-- Legacy view refactored to use shared layout and sections. Navbar/footer come from the layout. --}}
    @include('frontend.home.sections.hero')
    @include('frontend.home.sections.about')
    @include('frontend.home.sections.services')
    @include('frontend.home.sections.packages')
    @include('frontend.home.sections.marketing')
    @include('frontend.home.sections.additional-services')
    @include('frontend.home.sections.why-choose')
    @include('frontend.home.sections.contact')
@endsection
                        <div class="price-highlight text-3xl font-bold mb-2">BDT 80,000/-</div>
                        <p class="text-gray-600">Advanced E-Commerce</p>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>PHP Technology</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Responsive Design</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Advanced SEO</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Free Domain (1st Year)</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Free 5GB Hosting</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Free Payment Integration</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-orange-500 mr-2"></i>
                            <span>25-30 Days Delivery</span>
                        </div>
                    </div>
                    
                    <div class="text-sm text-gray-600 mb-4">
                        <p>Payment Gateway Setup: <span class="font-semibold">BDT 17,500/-</span></p>
                    </div>
                </div>

                <!-- Stable Package -->
                <div class="bg-gradient-to-b from-yellow-50 to-white p-8 rounded-lg shadow-lg card-hover border-2 border-transparent hover:border-yellow-200">
                    <div class="text-center mb-6">
                        <div class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm mb-2 inline-block">Enterprise</div>
                        <h3 class="text-2xl font-bold mb-2">Stable</h3>
                        <div class="price-highlight text-2xl font-bold mb-2">From BDT 200,000/-</div>
                        <p class="text-gray-600">Custom Requirements</p>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Custom Technology</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Responsive Design</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Advanced SEO</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Custom Features</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Scalable Architecture</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Premium Support</span>
                        </div>
                    </div>
                    
                    <div class="text-sm text-gray-600 mb-4">
                        <p class="font-semibold text-center">Contact for Custom Quote</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Digital Marketing Packages -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4 gradient-text">Digital Marketing Packages</h2>
                <div class="w-20 h-1 gradient-bg mx-auto mb-6"></div>
                <p class="text-xl text-gray-600">Boost your online presence with our comprehensive marketing solutions</p>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg card-hover">
                    <h3 class="text-2xl font-bold mb-4 text-center">Social Media Marketing</h3>
                    <div class="price-highlight text-3xl font-bold text-center mb-6">BDT 12,000/- <span class="text-base font-normal text-gray-600">Per Month</span></div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>12 branded content designs</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>Facebook/Instagram Ads setup</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>Page setup & audience targeting</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>Weekly performance report</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-8 rounded-lg shadow-lg card-hover border-2 border-purple-200">
                    <div class="bg-purple-500 text-white px-3 py-1 rounded-full text-sm mb-4 inline-block">Recommended</div>
                    <h3 class="text-2xl font-bold mb-4 text-center">SEO Growth Plan</h3>
                    <div class="price-highlight text-3xl font-bold text-center mb-6">BDT 18,000/- <span class="text-base font-normal text-gray-600">Per Month</span></div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>Full website SEO audit</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>Keyword research + competitor analysis</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>On-page + Technical SEO</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>Google Console & Sitemap setup</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>Monthly rank tracking</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-8 rounded-lg shadow-lg card-hover">
                    <h3 class="text-2xl font-bold mb-4 text-center">Google Ads Campaign</h3>
                    <div class="price-highlight text-3xl font-bold text-center mb-6">BDT 15,000/- <span class="text-base font-normal text-gray-600">Per Month</span></div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>Google Ads account setup</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>Up to 3 campaign sets</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>Conversion & traffic targeting</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                            <span>A/B testing + ROI reporting</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4 gradient-text">Additional Services</h2>
                <div class="w-20 h-1 gradient-bg mx-auto mb-6"></div>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 p-8 rounded-lg shadow-lg card-hover">
                    <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center floating">
                        <i class="fas fa-music text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Background Music Creation</h3>
                    <p class="text-gray-600 mb-4">Custom-composed 20-second original background music, perfect for Reels, Shorts, Ads, and YouTube videos. 100% copyright-free & brand-matched sound.</p>
                    <div class="price-highlight text-xl font-bold">From BDT 5,000/-</div>
                </div>
                
                <div class="bg-gradient-to-br from-green-50 to-blue-50 p-8 rounded-lg shadow-lg card-hover">
                    <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center floating">
                        <i class="fas fa-credit-card text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">SSL Payment Gateway</h3>
                    <p class="text-gray-600 mb-4">Official SSL partnership with discounted signup prices, integration support, top-notch security, and full portal access after activation.</p>
                    <div class="price-highlight text-xl font-bold">Best SSL Deals</div>
                </div>
                
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-lg shadow-lg card-hover">
                    <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center floating">
                        <i class="fas fa-cogs text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Website Management</h3>
                    <p class="text-gray-600 mb-4">Complete website management including product uploads, content creation, and ongoing maintenance for businesses without dedicated teams.</p>
                    <div class="price-highlight text-xl font-bold">From BDT 5,000/- per month</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-16 gradient-bg tech-pattern">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4 text-white">Why Choose Wise Dynamic?</h2>
                <div class="w-20 h-1 bg-white mx-auto mb-6"></div>
                <p class="text-xl text-white opacity-90">We blend creativity, technology, and personalized support</p>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-award text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">BASIS Certified Excellence</h3>
                            <p class="text-white opacity-90">Official BASIS member since 2020, ensuring professional standards and reliability</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-rocket text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Startup-Friendly Pricing</h3>
                            <p class="text-white opacity-90">Affordable solutions designed for young entrepreneurs and growing businesses</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-tools text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Full-Spectrum Solutions</h3>
                            <p class="text-white opacity-90">From websites to mobile apps, marketing to music — everything under one roof</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-heart text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Dedicated Small Team</h3>
                            <p class="text-white opacity-90">Personal attention and care - we treat your success like our own</p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="bg-white rounded-lg p-8 shadow-2xl animate-pulse-custom">
                        <div class="text-6xl font-bold gradient-text mb-4">100+</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Happy Clients</h3>
                        <p class="text-gray-600">Successful projects delivered</p>
                        
                        <div class="mt-8 grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold gradient-text">4+ Years</div>
                                <p class="text-sm text-gray-600">Experience</p>
                            </div>
                            <div>
                                <div class="text-2xl font-bold gradient-text">24/7</div>
                                <p class="text-sm text-gray-600">Support</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4 gradient-text">Let's Build Something Amazing Together</h2>
                <div class="w-20 h-1 gradient-bg mx-auto mb-6"></div>
                <p class="text-xl text-gray-600">Ready to bring your digital vision to life? Contact us today!</p>
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
                                    <p class="text-gray-600">+880 1805 081012</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-12 h-12 service-icon rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-envelope text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Email Us</h4>
                                    <p class="text-gray-600">sales@wisedynamic.com.bd</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-12 h-12 service-icon rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-map-marker-alt text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Location</h4>
                                    <p class="text-gray-600">Bangladesh</p>
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
                            
                            <div class="text-center">
                                <a href="tel:+8801805081012" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-full font-bold text-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-phone mr-2"></i>Call Now for Free Consultation
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="gradient-bg text-white py-12">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <i class="fas fa-code text-3xl mr-3"></i>
                    <span class="text-3xl font-bold">Wise Dynamic</span>
                </div>
                <p class="text-lg mb-6 opacity-90">Your Technology Partner for Innovation, Affordability & Results</p>
                
                <div class="flex justify-center space-x-8 mb-8">
                    <a href="tel:+8801805081012" class="hover:text-yellow-300 transition">
                        <i class="fas fa-phone text-2xl"></i>
                    </a>
                    <a href="mailto:sales@wisedynamic.com.bd" class="hover:text-yellow-300 transition">
                        <i class="fas fa-envelope text-2xl"></i>
                    </a>
                </div>
                
                <div class="border-t border-white border-opacity-20 pt-6">
                    <p class="opacity-75">&copy; 2024 Wise Dynamic. BASIS Member Since 2020. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        let slideIndex = 1;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            
            if (n > totalSlides) { slideIndex = 1; }
            if (n < 1) { slideIndex = totalSlides; }
            
            slides[slideIndex - 1].classList.add('active');
            
            // Update dot indicators
            const dots = document.querySelectorAll('.absolute.bottom-4 button');
            dots.forEach((dot, index) => {
                dot.style.opacity = index === slideIndex - 1 ? '1' : '0.5';
            });
        }

        function currentSlide(n) {
            slideIndex = n;
            showSlide(slideIndex);
        }

        function nextSlide() {
            slideIndex++;
            showSlide(slideIndex);
        }

        // Auto-slide functionality
        setInterval(nextSlide, 5000);

        // Initialize
        showSlide(slideIndex);

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all cards and sections for animation
        document.querySelectorAll('.card-hover, section').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>

@endsection
