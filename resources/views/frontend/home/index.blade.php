@extends('layouts.app')



@section('content')
    @include('frontend.home.sections.hero')
    @include('frontend.home.sections.quick-request')
    @include('frontend.home.sections.about')
    @include('frontend.home.sections.services')
    @include('frontend.home.sections.packages')
    @include('frontend.home.sections.marketing')
    @include('frontend.home.sections.additional-services')
    @include('frontend.home.sections.why-choose')
    @include('frontend.home.sections.contact')
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
