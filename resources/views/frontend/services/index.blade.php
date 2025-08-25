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
            <h2 class="text-4xl font-bold mb-4 gradient-text">What we do</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600">End-to-end services for a seamless digital presence</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Website Development -->
            <div class="bg-white p-8 rounded-xl shadow-lg card-hover border border-gray-100">
                <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center">
                    <i class="fas fa-laptop-code text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">Website Development</h3>
                <p class="text-gray-600 mb-5">Fast, responsive, SEO-ready sites on WordPress, Laravel, or custom stacks.</p>
                <ul class="space-y-2 text-gray-700">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Corporate, Portfolio, eCommerce</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Performance & SEO best practices</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Hosting & maintenance options</li>
                </ul>
            </div>

            <!-- App Development -->
            <div class="bg-white p-8 rounded-xl shadow-lg card-hover border border-gray-100">
                <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center">
                    <i class="fas fa-mobile-screen-button text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">App Development</h3>
                <p class="text-gray-600 mb-5">Android, iOS, and cross‑platform apps with modern toolchains.</p>
                <ul class="space-y-2 text-gray-700">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Native & Flutter</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>API & backend integration</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Store deployment</li>
                </ul>
            </div>

            <!-- UI/UX & Branding -->
            <div class="bg-white p-8 rounded-xl shadow-lg card-hover border border-gray-100">
                <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center">
                    <i class="fas fa-pencil-ruler text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">UI/UX & Branding</h3>
                <p class="text-gray-600 mb-5">Pixel-perfect interfaces, brand identities, and design systems.</p>
                <ul class="space-y-2 text-gray-700">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Wireframes & prototypes</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Logos, guidelines, assets</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Accessibility & usability</li>
                </ul>
            </div>

            <!-- Digital Marketing -->
            <div class="bg-white p-8 rounded-xl shadow-lg card-hover border border-gray-100">
                <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center">
                    <i class="fas fa-bullhorn text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">Digital Marketing</h3>
                <p class="text-gray-600 mb-5">Attract and convert with social, SEO, and paid media campaigns.</p>
                <ul class="space-y-2 text-gray-700">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>SMM, SEO, PPC</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Content & creatives</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Analytics & reporting</li>
                </ul>
            </div>

            <!-- eCommerce Solutions -->
            <div class="bg-white p-8 rounded-xl shadow-lg card-hover border border-gray-100">
                <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center">
                    <i class="fas fa-store text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">eCommerce Solutions</h3>
                <p class="text-gray-600 mb-5">Sell online with WooCommerce, Shopify, or custom carts.</p>
                <ul class="space-y-2 text-gray-700">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Store setup & theming</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Payment & shipping</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Growth & retention</li>
                </ul>
            </div>

            <!-- Maintenance & Support -->
            <div class="bg-white p-8 rounded-xl shadow-lg card-hover border border-gray-100">
                <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center">
                    <i class="fas fa-life-ring text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">Maintenance & Support</h3>
                <p class="text-gray-600 mb-5">Keep your site secure, fast, and up-to-date.</p>
                <ul class="space-y-2 text-gray-700">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Backups & monitoring</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Updates & bug fixes</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Performance tuning</li>
                </ul>
            </div>

            <!-- Custom Development -->
            <div class="bg-white p-8 rounded-xl shadow-lg card-hover border border-gray-100">
                <div class="w-16 h-16 service-icon rounded-full mb-6 flex items-center justify-center">
                    <i class="fas fa-cubes text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">Custom Development</h3>
                <p class="text-gray-600 mb-5">APIs, dashboards, and integrations tailored to your business.</p>
                <ul class="space-y-2 text-gray-700">
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Laravel, Node, REST/GraphQL</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>3rd-party integrations</li>
                    <li><i class="fas fa-check text-blue-600 mr-2"></i>Scalable architecture</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Button-wise category lists (CSS only) -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-8">
            <h3 class="text-3xl font-bold mb-3 gradient-text">Browse detailed services</h3>
            <p class="text-gray-600">Use the buttons to switch categories</p>
        </div>

        <!-- Buttons -->
        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <a href="#tab-website" class="btn-outline-primary px-4 py-2 rounded-full">Website Development</a>
            <a href="#tab-app" class="btn-outline-primary px-4 py-2 rounded-full">App Development</a>
            <a href="#tab-uiux" class="btn-outline-primary px-4 py-2 rounded-full">UI/UX & Branding</a>
            <a href="#tab-marketing" class="btn-outline-primary px-4 py-2 rounded-full">Digital Marketing</a>
            <a href="#tab-ecom" class="btn-outline-primary px-4 py-2 rounded-full">eCommerce</a>
            <a href="#tab-maint" class="btn-outline-primary px-4 py-2 rounded-full">Maintenance</a>
            <a href="#tab-custom" class="btn-outline-primary px-4 py-2 rounded-full">Custom Development</a>
        </div>

        <style>
            .cat-panel { display: none; }
            .cat-panel:target { display: block; }
            /* Default open when no hash */
            #tab-website.cat-panel { display: block; }
        </style>

        <!-- Panels -->
        <div class="max-w-5xl mx-auto">
            <section id="tab-website" class="cat-panel">
                <h4 class="text-2xl font-bold mb-4">Website Development</h4>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Website Development</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>WordPress or custom PHP</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Responsive design</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>SEO-optimized</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Fast performance</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$499+</span>
                            <a href="{{ url('/#contact') }}?service=Website+Development" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Web App & E‑commerce</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Custom web apps</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>WooCommerce/Shopify</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Secure checkout</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Admin dashboards</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$1299+</span>
                            <a href="{{ url('/#contact') }}?service=Web+App+%26+E-commerce" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Payment Gateway</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>SSL partnership</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>PCI-DSS practices</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>IPN/webhooks</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Sandbox → live</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$199</span>
                            <a href="{{ url('/#contact') }}?service=Payment+Gateway" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Best SSL Deals</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>DV/OV/EV options</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Free installation</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Renewal reminder</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>1–2 year terms</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$15/yr+</span>
                            <a href="{{ url('/#contact') }}?service=Best+SSL+Deals" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Digital Marketing</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>SEO & on-page fixes</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Social media mgmt</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Google Ads</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Reports & insights</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$199/mo</span>
                            <a href="{{ url('/#contact') }}?service=Digital+Marketing" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Background Music</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Custom, copyright‑free</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Brand/theme matched</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Multiple lengths</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Commercial license</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$99</span>
                            <a href="{{ url('/#contact') }}?service=Background+Music" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Website Management</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Content updates</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Backups & security</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Performance checks</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Priority support</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$49/mo</span>
                            <a href="{{ url('/#contact') }}?service=Website+Management" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="tab-app" class="cat-panel">
                <h4 class="text-2xl font-bold mb-4">App Development</h4>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Android App</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Kotlin/Flutter</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Modern UI components</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>REST API integration</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Play Store support</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$1499</span>
                            <a href="{{ url('/#contact') }}?service=Android+App" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">iOS App</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Swift/Flutter</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Apple HIG compliant</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Secure auth & payments</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>App Store support</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$1699</span>
                            <a href="{{ url('/#contact') }}?service=iOS+App" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Cross-platform</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Flutter/React Native</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Single codebase</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Native performance</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>OTA updates</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$1999</span>
                            <a href="{{ url('/#contact') }}?service=Cross-platform+App" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">MVP Prototype</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Clickable prototype</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Core features only</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Fast iteration</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>User testing</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$899</span>
                            <a href="{{ url('/#contact') }}?service=MVP+Prototype" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="tab-uiux" class="cat-panel">
                <h4 class="text-2xl font-bold mb-4">UI/UX & Branding</h4>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">UI Design Package</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>2-3 key screens</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Responsive variants</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Design system tokens</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Handoff ready</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$299</span>
                            <a href="{{ url('/#contact') }}?service=UI+Design+Package" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">UX Wireframing</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>User flows</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Low/Hi-fidelity</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Interactive prototype</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Feedback rounds</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$349</span>
                            <a href="{{ url('/#contact') }}?service=UX+Wireframing" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Brand Identity</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Logo & color palette</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Typography</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Brand guidelines</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Assets pack</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$449</span>
                            <a href="{{ url('/#contact') }}?service=Brand+Identity" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Accessibility Review</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>WCAG checks</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Color contrast</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Keyboard nav</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Report & fixes</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$199</span>
                            <a href="{{ url('/#contact') }}?service=Accessibility+Review" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="tab-marketing" class="cat-panel">
                <h4 class="text-2xl font-bold mb-4">Digital Marketing</h4>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">SMM Package</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>FB/IG posting</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Basic creatives</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Community mgmt</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Monthly report</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$199/mo</span>
                            <a href="{{ url('/#contact') }}?service=SMM+Package" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">SEO Starter</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>On-page fixes</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Keyword research</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Meta & sitemap</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Speed audit</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$249</span>
                            <a href="{{ url('/#contact') }}?service=SEO+Starter" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">PPC Ads</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Google & FB Ads</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Conversion tracking</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>A/B ad testing</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Weekly report</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$299/mo</span>
                            <a href="{{ url('/#contact') }}?service=PPC+Ads" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Content Bundle</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>4 blogs/month</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>SEO optimized</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Social snippets</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Graphics included</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$179/mo</span>
                            <a href="{{ url('/#contact') }}?service=Content+Bundle" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="tab-ecom" class="cat-panel">
                <h4 class="text-2xl font-bold mb-4">eCommerce Solutions</h4>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">WooCommerce Setup</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Theme config</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Products import</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Payments & shipping</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Basic SEO</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$349</span>
                            <a href="{{ url('/#contact') }}?service=WooCommerce+Setup" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Shopify Launch</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Theme setup</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Apps selection</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Checkout config</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Launch checklist</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$449</span>
                            <a href="{{ url('/#contact') }}?service=Shopify+Launch" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Custom Checkout</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>UX optimized flow</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Coupons & upsells</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Payment gateways</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Abandon cart emails</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$599</span>
                            <a href="{{ url('/#contact') }}?service=Custom+Checkout" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Conversion Boost</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Speed & UX fixes</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Heatmaps</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>A/B testing</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Insights report</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$299</span>
                            <a href="{{ url('/#contact') }}?service=Conversion+Boost" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="tab-maint" class="cat-panel">
                <h4 class="text-2xl font-bold mb-4">Maintenance & Support</h4>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Basic Care</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Monthly updates</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Uptime checks</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Security scan</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Email support</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$49/mo</span>
                            <a href="{{ url('/#contact') }}?service=Basic+Care" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Pro Care</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Weekly updates</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Performance checks</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Priority support</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Monthly report</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$99/mo</span>
                            <a href="{{ url('/#contact') }}?service=Pro+Care" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Security Hardening</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Firewall rules</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Malware scan</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Backup strategy</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Hardening report</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$199</span>
                            <a href="{{ url('/#contact') }}?service=Security+Hardening" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Speed Boost</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Caching & CDN</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Image optimization</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Code minify</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Metrics report</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$159</span>
                            <a href="{{ url('/#contact') }}?service=Speed+Boost" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="tab-custom" class="cat-panel">
                <h4 class="text-2xl font-bold mb-4">Custom Development</h4>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">API Integration</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>REST/GraphQL</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>OAuth & keys</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Rate-limit safe</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Docs & tests</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$299</span>
                            <a href="{{ url('/#contact') }}?service=API+Integration" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Admin Dashboard</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Role permissions</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Charts & tables</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Audit logs</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Responsive UI</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$799</span>
                            <a href="{{ url('/#contact') }}?service=Admin+Dashboard" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Automation</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Cron & queues</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Webhook triggers</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Data pipelines</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Alerts & logs</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$499</span>
                            <a href="{{ url('/#contact') }}?service=Automation" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm card-hover flex flex-col">
                        <h5 class="font-semibold text-lg mb-2">Data Import/Export</h5>
                        <ul class="text-gray-700 space-y-1 text-sm mb-3">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>CSV/Excel/JSON</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Validation rules</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Error handling</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Progress reports</li>
                        </ul>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t">
                            <span class="font-bold text-gray-900">$259</span>
                            <a href="{{ url('/#contact') }}?service=Data+Import+Export" class="btn-primary px-4 py-1.5 rounded-full text-sm">Apply</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
@endsection
