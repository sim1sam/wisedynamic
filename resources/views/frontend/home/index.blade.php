@extends('layouts.app')

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .gradient-text { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .slide { display: none; animation: fadeIn 0.5s ease-in-out; }
        .slide.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes pulse { 0%, 100% { transform: scale(1);} 50% { transform: scale(1.05);} }
        .animate-slideUp { animation: slideUp 0.6s ease-out; }
        .animate-pulse-custom { animation: pulse 2s infinite; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .floating { animation: floating 3s ease-in-out infinite; }
        @keyframes floating { 0%, 100% { transform: translateY(0px);} 50% { transform: translateY(-20px);} }
        .tech-pattern { background-image: radial-gradient(circle at 25px 25px, rgba(255,255,255,.2) 2%, transparent 50%), radial-gradient(circle at 75px 75px, rgba(255,255,255,.2) 2%, transparent 50%); background-size: 100px 100px; }
        .price-highlight { background: linear-gradient(45deg, #FF6B6B, #4ECDC4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 800; }
        .service-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease; }
        .service-icon:hover { background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); transform: rotate(360deg); }
    </style>
@endpush

@section('content')
    @include('frontend.home.sections.nav')
    @include('frontend.home.sections.hero')
    @include('frontend.home.sections.about')
    @include('frontend.home.sections.services')
    @include('frontend.home.sections.packages')
    @include('frontend.home.sections.marketing')
    @include('frontend.home.sections.additional-services')
    @include('frontend.home.sections.why-choose')
    @include('frontend.home.sections.contact')
    @include('frontend.home.sections.footer')
@endsection

@push('scripts')
    <script>
        let slideIndex = 1;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;
        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            if (n > totalSlides) { slideIndex = 1; }
            if (n < 1) { slideIndex = totalSlides; }
            slides[slideIndex - 1].classList.add('active');
            const dots = document.querySelectorAll('.absolute.bottom-4 button');
            dots.forEach((dot, index) => { dot.style.opacity = index === slideIndex - 1 ? '1' : '0.5'; });
        }
        function currentSlide(n) { slideIndex = n; showSlide(slideIndex); }
        function nextSlide() { slideIndex++; showSlide(slideIndex); }
        setInterval(nextSlide, 5000);
        showSlide(slideIndex);
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) { target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
            });
        });
        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) { entry.target.style.opacity = '1'; entry.target.style.transform = 'translateY(0)'; } });
        }, observerOptions);
        document.querySelectorAll('.card-hover, section').forEach(el => {
            el.style.opacity = '0'; el.style.transform = 'translateY(30px)'; el.style.transition = 'opacity 0.6s ease, transform 0.6s ease'; observer.observe(el);
        });
    </script>
@endpush
