@extends('layouts.app')

@section('content')
<header class="theme-gradient text-white pt-28 pb-14">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight">About Wise Dynamic</h1>
        <p class="mt-3 text-white/90 max-w-3xl">We craft high-performing digital products and growth campaigns that help businesses move faster, scale smarter, and convert better.</p>
    </div>
</header>

<section class="py-14 bg-white">
    <div class="container mx-auto px-6 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold gradient-text">Who We Are</h2>
            <p class="mt-4 text-gray-700 leading-relaxed">Wise Dynamic is a multidisciplinary team specializing in Website Development, UI/UX, and Digital Marketing. We blend strategy, design, and engineering to deliver solutions that don't just look great—they perform, scale, and drive measurable results.</p>
            <ul class="mt-6 space-y-3">
                <li class="flex items-start gap-3"><span class="w-6 h-6 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-sm">✓</span><span>Customer-first mindset with transparent communication</span></li>
                <li class="flex items-start gap-3"><span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm">✓</span><span>Modern tech stack and data-informed decisions</span></li>
                <li class="flex items-start gap-3"><span class="w-6 h-6 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-sm">✓</span><span>On-time delivery with quality assurance</span></li>
            </ul>
        </div>
        <div>
            <div class="rounded-2xl overflow-hidden shadow-xl">
                <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?q=80&w=1600&auto=format&fit=crop" alt="Team at work" class="w-full h-80 object-cover">
            </div>
        </div>
    </div>
</section>

<section class="py-10 bg-gradient-to-br from-blue-50 to-purple-50">
    <div class="container mx-auto px-6 grid md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow text-center">
            <div class="text-3xl font-extrabold gradient-text">5+</div>
            <div class="text-gray-700 mt-1">Years Experience</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow text-center">
            <div class="text-3xl font-extrabold gradient-text">120+</div>
            <div class="text-gray-700 mt-1">Projects Delivered</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow text-center">
            <div class="text-3xl font-extrabold gradient-text">98%</div>
            <div class="text-gray-700 mt-1">Client Satisfaction</div>
        </div>
    </div>
</section>

<section class="py-14 bg-white">
    <div class="container mx-auto px-6 grid lg:grid-cols-2 gap-10 items-start">
        <div class="bg-white rounded-2xl shadow p-8">
            <h3 class="text-xl font-bold gradient-text">Our Values</h3>
            <ul class="mt-5 space-y-4">
                <li><span class="font-semibold text-gray-900">Integrity.</span> We do what's right, not what's easy.</li>
                <li><span class="font-semibold text-gray-900">Excellence.</span> We sweat the details and focus on outcomes.</li>
                <li><span class="font-semibold text-gray-900">Progress.</span> We learn, iterate, and improve continuously.</li>
                <li><span class="font-semibold text-gray-900">Partnership.</span> We act as an extension of your team.</li>
            </ul>
        </div>
        <div class="bg-white rounded-2xl shadow p-8">
            <h3 class="text-xl font-bold gradient-text">What We Do</h3>
            <div class="mt-5 grid sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50 to-purple-50">
                    <div class="font-semibold text-gray-900">Website Development</div>
                    <p class="text-gray-700 text-sm mt-1">Fast, secure, and scalable websites built to convert.</p>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50 to-purple-50">
                    <div class="font-semibold text-gray-900">UI/UX & Branding</div>
                    <p class="text-gray-700 text-sm mt-1">Human-centric design that elevates your brand.</p>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50 to-purple-50">
                    <div class="font-semibold text-gray-900">Digital Marketing</div>
                    <p class="text-gray-700 text-sm mt-1">SEO, Social, and Ads to drive qualified growth.</p>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50 to-purple-50">
                    <div class="font-semibold text-gray-900">eCommerce & Integrations</div>
                    <p class="text-gray-700 text-sm mt-1">Payments, analytics, and automations that scale.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-gradient-to-r from-blue-600 to-purple-600">
    <div class="container mx-auto px-6 text-center text-white">
        <h3 class="text-2xl md:text-3xl font-extrabold">Ready to work with a results-driven team?</h3>
        <p class="mt-2 text-white/90">Get a free consultation and tailored plan for your business.</p>
        <div class="mt-6">
            <a href="{{ route('contact') }}" class="inline-block bg-white text-gray-900 px-8 py-3 rounded-full font-semibold shadow hover:shadow-lg transform hover:-translate-y-0.5 transition">Contact Us</a>
        </div>
    </div>
</section>
@endsection
