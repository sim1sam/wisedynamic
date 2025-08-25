@extends('layouts.app')

@section('content')
<!-- Hero Banner -->
<header class="theme-gradient text-white pt-28 pb-16">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">Digital Marketing Packages</h1>
            <p class="mt-4 text-white/90 text-lg">Boost your online presence with our comprehensive marketing solutions</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#web-packages" class="btn-primary px-6 py-3 rounded-full font-semibold shadow">View Packages</a>
                <a href="{{ url('/#contact') }}" class="bg-white text-gray-900 px-6 py-3 rounded-full font-semibold hover:opacity-90">Get Custom Quote</a>
            </div>
        </div>
    </div>
</header>

<!-- Website Development Packages -->
<section id="web-packages" class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">Website Development Packages</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600">Choose the perfect package for your business needs</p>
        </div>
        
        <div class="grid lg:grid-cols-4 gap-6">
            <!-- Startup Package -->
            <div class="bg-gradient-to-b from-blue-50 to-white p-8 rounded-lg shadow-lg card-hover border-2 border-transparent hover:border-blue-200">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold mb-2">Startup</h3>
                    <div class="price-highlight text-3xl font-bold mb-2">BDT 20,000/-</div>
                    <p class="text-gray-600">Perfect for Portfolio</p>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>WordPress Technology</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Responsive Design</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Basic SEO</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Free Domain (1st Year)</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Free 1GB Hosting</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock text-orange-500 mr-2"></i>
                        <span>3-7 Days Delivery</span>
                    </div>
                </div>
                
                <div class="text-sm text-gray-600 mb-4">
                    <p>Payment Gateway Setup: <span class="font-semibold">BDT 22,500/-</span></p>
                    <p>Integration: <span class="font-semibold">BDT 5,000/-</span></p>
                </div>
            </div>

            <!-- Streamline Package -->
            <div class="bg-gradient-to-b from-purple-50 to-white p-8 rounded-lg shadow-lg card-hover border-2 border-purple-200">
                <div class="text-center mb-6">
                    <div class="bg-purple-500 text-white px-3 py-1 rounded-full text-sm mb-2 inline-block">Popular</div>
                    <h3 class="text-2xl font-bold mb-2">Streamline</h3>
                    <div class="price-highlight text-3xl font-bold mb-2">BDT 50,000/-</div>
                    <p class="text-gray-600">Best for E-Commerce</p>
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
                        <span>Basic SEO</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Free Domain (1st Year)</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Free 3GB Hosting</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock text-orange-500 mr-2"></i>
                        <span>15-20 Days Delivery</span>
                    </div>
                </div>
                
                <div class="text-sm text-gray-600 mb-4">
                    <p>Payment Gateway Setup: <span class="font-semibold">BDT 20,000/-</span></p>
                    <p>Integration: <span class="font-semibold">BDT 4,000/-</span></p>
                </div>
            </div>

            <!-- Scale Package -->
            <div class="bg-gradient-to-b from-green-50 to-white p-8 rounded-lg shadow-lg card-hover border-2 border-transparent hover:border-green-200">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold mb-2">Scale</h3>
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
<section id="marketing-packages" class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">Choose Your Package</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600">Select the perfect package that fits your business requirements and budget</p>
        </div>
        
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Social Media Marketing -->
            <div class="bg-gradient-to-b from-blue-50 to-white p-8 rounded-lg shadow-lg card-hover border-2 border-transparent hover:border-blue-200">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold mb-2">Social Media Marketing</h3>
                    <div class="price-highlight text-2xl font-bold mb-2">BDT 12,000/- <span class="text-base font-semibold">Per Month</span></div>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>12 branded content designs</span></div>
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>Facebook/Instagram Ads setup</span></div>
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>Page setup & audience targeting</span></div>
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>Weekly performance report</span></div>
                </div>

                <div class="text-center">
                    <a href="{{ url('/#contact') }}?package=social" class="btn-primary px-6 py-3 rounded-full font-semibold w-full">Get Started</a>
                </div>
            </div>

            <!-- SEO Growth Plan (Recommended) -->
            <div class="bg-gradient-to-b from-purple-50 to-white p-8 rounded-lg shadow-lg card-hover border-2 border-purple-200">
                <div class="text-center mb-6">
                    <div class="bg-purple-500 text-white px-3 py-1 rounded-full text-sm mb-2 inline-block">Recommended</div>
                    <h3 class="text-2xl font-bold mb-2">SEO Growth Plan</h3>
                    <div class="price-highlight text-2xl font-bold mb-2">BDT 18,000/- <span class="text-base font-semibold">Per Month</span></div>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>Full website SEO audit</span></div>
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>Keyword research + competitor analysis</span></div>
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>On-page + Technical SEO</span></div>
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>Google Console & Sitemap setup</span></div>
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>Monthly rank tracking</span></div>
                </div>

                <div class="text-center">
                    <a href="{{ url('/#contact') }}?package=seo" class="btn-primary px-6 py-3 rounded-full font-semibold w-full">Get Started</a>
                </div>
            </div>

            <!-- Google Ads Campaign -->
            <div class="bg-gradient-to-b from-yellow-50 to-white p-8 rounded-lg shadow-lg card-hover border-2 border-transparent hover:border-yellow-200">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold mb-2">Google Ads Campaign</h3>
                    <div class="price-highlight text-2xl font-bold mb-2">BDT 15,000/- <span class="text-base font-semibold">Per Month</span></div>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>Google Ads account setup</span></div>
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>Up to 3 campaign sets</span></div>
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>Conversion & traffic targeting</span></div>
                    <div class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i><span>A/B testing + ROI reporting</span></div>
                </div>

                <div class="text-center">
                    <a href="{{ url('/#contact') }}?package=ads" class="btn-primary px-6 py-3 rounded-full font-semibold w-full">Get Started</a>
                </div>
            </div>
        </div>
        
        <!-- Additional Info Section -->
        <div class="mt-16 text-center">
            <h3 class="text-2xl font-bold mb-4 gradient-text">Need Something Custom?</h3>
            <p class="text-gray-600 mb-6">We can create a custom package tailored to your specific requirements</p>
            <a href="{{ url('/#contact') }}" class="btn-outline-primary px-8 py-3 rounded-full font-semibold">Contact Us</a>
        </div>
    </div>
</section>
@endsection
