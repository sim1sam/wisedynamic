@extends('layouts.app')

@section('content')
<!-- Hero -->
<header class="theme-gradient text-white pt-28 pb-16">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">Services that grow your business</h1>
            <p class="mt-4 text-white/90 text-lg">Full-stack web, app, design, and marketing solutions powered by modern tech.</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#services" class="btn-primary px-6 py-3 rounded-full font-semibold shadow">Explore Services</a>
                <a href="{{ url('/#contact') }}" class="bg-white text-gray-900 px-6 py-3 rounded-full font-semibold hover:opacity-90">Apply Now</a>
            </div>
        </div>
    </div>
</header>

<!-- Services Grid (cards) -->
<section id="services" class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">Our Services</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600">Clear features, upfront pricing, and fast apply.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            <!-- 1. Website Development -->
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 card-hover hover:shadow-2xl hover:-translate-y-1 transition-all">
                <div class="w-16 h-16 service-icon rounded-full mb-5 flex items-center justify-center">
                    <i class="fas fa-laptop-code text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Website Development</h3>
                <p class="text-gray-600 mb-4">Responsive, SEO‑ready websites on WordPress, Laravel, or custom stacks.</p>
                <ul class="text-gray-700 space-y-1.5 mb-6 text-sm">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Corporate, Portfolio, eCommerce</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Fast performance</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Hosting & maintenance</li>
                </ul>
                <div class="flex items-center justify-between pt-4 border-t">
                    <span class="font-bold text-gray-900">$499+</span>
                    <a href="{{ url('/#contact') }}?service=Website+Development" class="btn-primary px-5 py-2 rounded-full">Apply</a>
                </div>
            </div>

            <!-- 2. App Development -->
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 card-hover hover:shadow-2xl hover:-translate-y-1 transition-all">
                <div class="w-16 h-16 service-icon rounded-full mb-5 flex items-center justify-center">
                    <i class="fas fa-mobile-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">App Development</h3>
                <p class="text-gray-600 mb-4">Android, iOS, and cross‑platform apps with secure APIs.</p>
                <ul class="text-gray-700 space-y-1.5 mb-6 text-sm">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Native & Flutter</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>API integration</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Store deployment</li>
                </ul>
                <div class="flex items-center justify-between pt-4 border-t">
                    <span class="font-bold text-gray-900">$1499</span>
                    <a href="{{ url('/#contact') }}?service=App+Development" class="btn-primary px-5 py-2 rounded-full">Apply</a>
                </div>
            </div>

            <!-- 3. UI/UX & Branding -->
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 card-hover hover:shadow-2xl hover:-translate-y-1 transition-all">
                <div class="w-16 h-16 service-icon rounded-full mb-5 flex items-center justify-center">
                    <i class="fas fa-pencil-ruler text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">UI/UX & Branding</h3>
                <p class="text-gray-600 mb-4">Design systems, wireframes, logos, and brand identity.</p>
                <ul class="text-gray-700 space-y-1.5 mb-6 text-sm">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Wireframes & prototypes</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Logos & guidelines</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Accessibility focus</li>
                </ul>
                <div class="flex items-center justify-between pt-4 border-t">
                    <span class="font-bold text-gray-900">$299</span>
                    <a href="{{ url('/#contact') }}?service=UI%2FUX+%26+Branding" class="btn-primary px-5 py-2 rounded-full">Apply</a>
                </div>
            </div>

            <!-- 4. Digital Marketing -->
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 card-hover hover:shadow-2xl hover:-translate-y-1 transition-all">
                <div class="w-16 h-16 service-icon rounded-full mb-5 flex items-center justify-center">
                    <i class="fas fa-bullhorn text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Digital Marketing</h3>
                <p class="text-gray-600 mb-4">SEO, social media, and PPC campaigns to grow conversions.</p>
                <ul class="text-gray-700 space-y-1.5 mb-6 text-sm">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>SMM, SEO, PPC</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Content & creatives</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Analytics reporting</li>
                </ul>
                <div class="flex items-center justify-between pt-4 border-t">
                    <span class="font-bold text-gray-900">$199/mo</span>
                    <a href="{{ url('/#contact') }}?service=Digital+Marketing" class="btn-primary px-5 py-2 rounded-full">Apply</a>
                </div>
            </div>

            <!-- 5. eCommerce Solutions -->
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 card-hover hover:shadow-2xl hover:-translate-y-1 transition-all">
                <div class="w-16 h-16 service-icon rounded-full mb-5 flex items-center justify-center">
                    <i class="fas fa-store text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">eCommerce Solutions</h3>
                <p class="text-gray-600 mb-4">WooCommerce, Shopify setups, and custom carts.</p>
                <ul class="text-gray-700 space-y-1.5 mb-6 text-sm">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Store setup & theming</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Payments & shipping</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Growth & retention</li>
                </ul>
                <div class="flex items-center justify-between pt-4 border-t">
                    <span class="font-bold text-gray-900">$349+</span>
                    <a href="{{ url('/#contact') }}?service=eCommerce+Solutions" class="btn-primary px-5 py-2 rounded-full">Apply</a>
                </div>
            </div>

            <!-- 6. Maintenance & Support -->
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 card-hover hover:shadow-2xl hover:-translate-y-1 transition-all">
                <div class="w-16 h-16 service-icon rounded-full mb-5 flex items-center justify-center">
                    <i class="fas fa-life-ring text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Maintenance & Support</h3>
                <p class="text-gray-600 mb-4">Backups, updates, security, and performance tuning.</p>
                <ul class="text-gray-700 space-y-1.5 mb-6 text-sm">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Backups & monitoring</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Updates & fixes</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Performance tweaks</li>
                </ul>
                <div class="flex items-center justify-between pt-4 border-t">
                    <span class="font-bold text-gray-900">$49/mo</span>
                    <a href="{{ url('/#contact') }}?service=Maintenance+%26+Support" class="btn-primary px-5 py-2 rounded-full">Apply</a>
                </div>
            </div>

            <!-- 7. Custom Development -->
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 card-hover hover:shadow-2xl hover:-translate-y-1 transition-all">
                <div class="w-16 h-16 service-icon rounded-full mb-5 flex items-center justify-center">
                    <i class="fas fa-cubes text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Custom Development</h3>
                <p class="text-gray-600 mb-4">APIs, dashboards, integrations, and automation.</p>
                <ul class="text-gray-700 space-y-1.5 mb-6 text-sm">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>REST/GraphQL APIs</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Dashboards</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>3rd‑party integrations</li>
                </ul>
                <div class="flex items-center justify-between pt-4 border-t">
                    <span class="font-bold text-gray-900">$299+</span>
                    <a href="{{ url('/#contact') }}?service=Custom+Development" class="btn-primary px-5 py-2 rounded-full">Apply</a>
                </div>
            </div>

            <!-- 8. Security Hardening -->
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 card-hover hover:shadow-2xl hover:-translate-y-1 transition-all">
                <div class="w-16 h-16 service-icon rounded-full mb-5 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Security Hardening</h3>
                <p class="text-gray-600 mb-4">Firewall rules, malware scans, and best‑practice hardening.</p>
                <ul class="text-gray-700 space-y-1.5 mb-6 text-sm">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Firewall & WAF config</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Malware scans</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Hardening checklist</li>
                </ul>
                <div class="flex items-center justify-between pt-4 border-t">
                    <span class="font-bold text-gray-900">$199</span>
                    <a href="{{ url('/#contact') }}?service=Security+Hardening" class="btn-primary px-5 py-2 rounded-full">Apply</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
