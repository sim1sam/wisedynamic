<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('frontend.home.sections.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('frontend.home.sections.quick-request', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('frontend.home.sections.about', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('frontend.home.sections.services', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('frontend.home.sections.packages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('frontend.home.sections.marketing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('frontend.home.sections.additional-services', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('frontend.home.sections.why-choose', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('frontend.home.sections.contact', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        // Auto-open Quick Request modal if qr=1 is present
        try {
            const url = new URL(window.location.href);
            if (url.searchParams.get('qr') === '1') {
                const openBtn = document.getElementById('qr-open');
                if (openBtn) { openBtn.click(); }
            }
        } catch (e) {}

        let slideIndex = 1;
        const getSlides = () => document.querySelectorAll('.slider-container .slide');
        const getDots = () => document.querySelectorAll('.absolute.bottom-4 button');

        function showSlide(n) {
            const slides = getSlides();
            const total = slides.length;
            slides.forEach(slide => slide.classList.remove('active'));
            if (n > total) { slideIndex = 1; }
            if (n < 1) { slideIndex = total; }
            if (total > 0) {
                slides[slideIndex - 1].classList.add('active');
            }
            const dots = getDots();
            dots.forEach((dot, index) => { dot.style.opacity = index === slideIndex - 1 ? '1' : '0.5'; });
        }

        window.currentSlide = function(n) { slideIndex = n; showSlide(slideIndex); }
        function nextSlide() { slideIndex++; showSlide(slideIndex); }

        showSlide(slideIndex);
        setInterval(nextSlide, 5000);

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
    });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/home/index.blade.php ENDPATH**/ ?>