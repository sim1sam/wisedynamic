<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Packages page
Route::get('/packages', function () {
    return view('frontend.packages.index');
})->name('packages');

// Cart & Checkout
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.show');
Route::post('/checkout', [CartController::class, 'place'])->name('checkout.place');

// Auth pages (simple static views for now)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Services page
Route::get('/services', function () {
    return view('frontend.services.index');
})->name('services');

// Service detail pages
Route::get('/services/{slug}', function (string $slug) {
    $map = [
        'website-development' => [
            'title' => 'Website Development',
            'subtitle' => 'We build high‑performing, user‑friendly websites that convert.',
            'image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1600&auto=format&fit=crop',
            'expertise' => 'Custom and CMS websites (WordPress, Laravel) optimized for speed, SEO, and conversions.',
            'features' => [
                'Responsive UI/UX', 'SEO‑ready architecture', 'Clean, scalable code', 'Optimized performance', 'Security best practices', 'Content & media management'
            ],
            'benefits' => [
                'Better engagement and conversions', 'Fast go‑live timelines', 'Easy to manage', 'Future‑proof stack'
            ],
            'technologies' => ['Laravel / PHP', 'WordPress', 'Tailwind CSS', 'Alpine.js', 'MySQL', 'REST API'],
        ],
        'app-development' => [
            'title' => 'Mobile App Development',
            'subtitle' => 'Native and cross‑platform mobile apps for Android and iOS.',
            'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=1600&auto=format&fit=crop',
            'expertise' => 'Proficient in building native and cross‑platform mobile apps tailored to diverse business needs.',
            'features' => [
                'Intuitive UI/UX', 'Fast & scalable performance', 'Cross‑platform compatibility', 'Real‑time data sync', 'Secure authentication', 'Push notifications', 'App store deployment'
            ],
            'benefits' => [
                'Customized mobile solutions', 'Faster go‑to‑market', 'Enhanced engagement', 'Seamless integrations'
            ],
            'technologies' => ['Flutter / Dart', 'React Native / JavaScript', 'Swift (iOS)', 'Kotlin (Android)', 'Firebase', 'REST API'],
        ],
        'ui-ux-branding' => [
            'title' => 'UI/UX & Branding',
            'subtitle' => 'Design systems, wireframes, logos, and brand identity.',
            'image' => 'https://images.unsplash.com/photo-1587613840803-722d9d4c42e4?q=80&w=1600&auto=format&fit=crop',
            'expertise' => 'User‑centered design that reflects your brand and improves usability.',
            'features' => ['Wireframes & prototypes', 'Design systems', 'Logo & guidelines', 'Accessibility'],
            'benefits' => ['Consistent brand experience', 'Higher conversions', 'Reduced dev rework'],
            'technologies' => ['Figma', 'Adobe XD', 'Illustrator', 'Design Tokens'],
        ],
        'digital-marketing' => [
            'title' => 'Digital Marketing',
            'subtitle' => 'SEO, Social Media, and PPC campaigns that drive growth.',
            'image' => 'https://images.unsplash.com/photo-1454165205744-3b78555e5572?q=80&w=1600&auto=format&fit=crop',
            'expertise' => 'Full‑funnel strategy from awareness to conversion with transparent reporting.',
            'features' => ['SEO', 'Social media', ' Google Ads / PPC', 'Content & creatives', 'Analytics'],
            'benefits' => ['More qualified leads', 'Improved ROI', 'Scalable growth'],
            'technologies' => ['GA4', 'GSC', 'Meta Ads', 'Google Ads', 'SEMrush'],
        ],
        'ecommerce-solutions' => [
            'title' => 'eCommerce Solutions',
            'subtitle' => 'WooCommerce, Shopify, and custom carts built for sales.',
            'image' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=1600&auto=format&fit=crop',
            'expertise' => 'Conversion‑focused storefronts, payments, shipping, and analytics.',
            'features' => ['Store setup & theming', 'Payment & shipping', 'Inventory & orders', 'Promotions & coupons'],
            'benefits' => ['Higher AOV & retention', 'Secure & reliable', 'Easy management'],
            'technologies' => ['WooCommerce', 'Shopify', 'Laravel', 'MySQL', 'Stripe / SSLCOMMERZ'],
        ],
        'maintenance-support' => [
            'title' => 'Maintenance & Support',
            'subtitle' => 'Backups, updates, security, and performance tuning.',
            'image' => 'https://images.unsplash.com/photo-1556157382-97eda2d62296?q=80&w=1600&auto=format&fit=crop',
            'expertise' => 'Keep your product running smoothly with proactive care.',
            'features' => ['Backups & monitoring', 'Security patches', 'Bug fixes', 'Performance tweaks'],
            'benefits' => ['Less downtime', 'Peace of mind', 'Predictable cost'],
            'technologies' => ['Uptime tools', 'Firewall', 'Cache / CDN', 'CI/CD'],
        ],
        'custom-development' => [
            'title' => 'Custom Development',
            'subtitle' => 'APIs, dashboards, integrations, and automation.',
            'image' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?q=80&w=1600&auto=format&fit=crop',
            'expertise' => 'Build exactly what your business needs—secure, scalable, robust.',
            'features' => ['REST/GraphQL APIs', 'Admin dashboards', '3rd‑party integrations', 'Automation'],
            'benefits' => ['Tailored solutions', 'Faster ops', 'Competitive edge'],
            'technologies' => ['Laravel', 'Node.js', 'MySQL', 'Redis', 'Docker'],
        ],
        'security-hardening' => [
            'title' => 'Security Hardening',
            'subtitle' => 'Firewall rules, malware scans, and best‑practice hardening.',
            'image' => 'https://images.unsplash.com/photo-1556157381-97f7f95fcd79?q=80&w=1600&auto=format&fit=crop',
            'expertise' => 'Protect your app and data with layered security.',
            'features' => ['Firewall & WAF config', 'Malware scans', 'Hardening checklist', 'Audit & logging'],
            'benefits' => ['Reduced risk', 'Compliance support', 'Trust & safety'],
            'technologies' => ['Fail2ban', 'CrowdSec', 'ClamAV', 'OWASP ASVS'],
        ],
    ];

    if (!isset($map[$slug])) {
        abort(404);
    }

    $data = $map[$slug];
    return view('frontend.services.show', $data);
})->name('services.show');
