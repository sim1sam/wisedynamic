<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'wisedynamic')); ?></title>
    <?php
        $websiteSetting = \App\Models\WebsiteSetting::first();
    ?>
    <?php if($websiteSetting && $websiteSetting->site_favicon): ?>
        <link rel="icon" href="<?php echo e(asset($websiteSetting->site_favicon)); ?>" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo e(asset($websiteSetting->site_favicon)); ?>" type="image/x-icon">
    <?php endif; ?>

    
    <?php if(file_exists(public_path('build/manifest.json'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <!-- Fallback: Tailwind CSS and Font Awesome via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Use Font Awesome 5 for 'fas' compatibility used across views -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <style>
            /* Minimal shared utilities used across pages */
            .gradient-bg { background: linear-gradient(90deg, #0976bc, #0e0f3e); }
            .gradient-text { background: linear-gradient(90deg, #0976bc, #0e0f3e); -webkit-background-clip: text; background-clip: text; color: transparent; }
            .theme-gradient { background: linear-gradient(135deg, #0976bc 0%, #0e0f3e 100%); }
            /* Avoid overriding Tailwind defaults to prevent design regressions */

            /* Brand helpers */
            .service-icon { background-color: #0976bc; box-shadow: 0 10px 20px rgba(9,118,188,0.25); }
            .btn-primary { background-color: #0976bc; color: #fff; }
            .btn-primary:hover { background-color: #0e0f3e; }
            .btn-outline-primary { border: 2px solid #0976bc; color: #0976bc; background: transparent; }
            .btn-outline-primary:hover { background-color: #0976bc; color: #fff; }
            .price-highlight { color: #0e0f3e; }
            .card-hover { transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
            .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(14,15,62,0.12); border-color: #0976bc; }
            /* Minimal animations used in sections */
            .service-icon { transition: all .3s ease; }
            .service-icon:hover { transform: rotate(360deg); }
            @keyframes floating { 0%,100%{ transform: translateY(0);} 50%{ transform: translateY(-10px);} }
            .floating { animation: floating 3s ease-in-out infinite; }
            @keyframes slideUp { from { transform: translateY(24px); opacity: 0;} to { transform: translateY(0); opacity: 1;} }
            .animate-slideUp { animation: slideUp .6s ease-out; }
            @keyframes pulse { 0%,100%{ transform: scale(1);} 50%{ transform: scale(1.03);} }
            .animate-pulse-custom { animation: pulse 2s infinite; }
            .section-divider { width: 5rem; height: 4px; background: linear-gradient(90deg, #0976bc, #0e0f3e); margin: 0 auto; border-radius: 9999px; }
            
            /* Custom prose styles for rich text content */
            .prose ul {
                list-style-type: disc;
                margin-left: 1.5rem;
                margin-bottom: 1rem;
                padding-left: 0.5rem;
            }
            .prose ol {
                list-style-type: decimal;
                margin-left: 1.5rem;
                margin-bottom: 1rem;
                padding-left: 0.5rem;
            }
            .prose li {
                margin-bottom: 0.5rem;
                line-height: 1.6;
            }
            .prose p {
                margin-bottom: 1rem;
                line-height: 1.6;
            }
            .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
                margin-top: 1.5rem;
                margin-bottom: 1rem;
                font-weight: 600;
            }
            .prose strong {
                font-weight: 600;
            }
            .prose em {
                font-style: italic;
            }
        </style>
    <?php endif; ?>
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
 <body>
    
    <?php echo $__env->make('frontend.home.sections.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if(session('success')): ?>
        <div class="container mx-auto px-6 mt-4">
            <div class="p-3 rounded bg-green-100 text-green-700"><?php echo e(session('success')); ?></div>
        </div>
        <div id="global-flash-success" class="fixed bottom-5 right-5 z-[10000] px-4 py-3 rounded-md bg-green-600 text-white shadow-lg">
            <?php echo e(session('success')); ?>

        </div>
        <script>
            setTimeout(function(){
                var el = document.getElementById('global-flash-success');
                if(el){ el.style.transition='opacity .4s ease'; el.style.opacity='0'; setTimeout(function(){ el.remove(); }, 400); }
            }, 3000);
        </script>
    <?php endif; ?>
    <?php echo $__env->yieldContent('content'); ?>
    
    <?php echo $__env->make('frontend.home.sections.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
   </body>
 </html>
<?php /**PATH F:\laragon\www\wisedynamic\resources\views/layouts/app.blade.php ENDPATH**/ ?>