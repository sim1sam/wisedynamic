<?php $__env->startSection('content'); ?>
<!-- Hero -->
<header class="theme-gradient text-white pt-28 pb-16">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">Services that grow your business</h1>
            <p class="mt-4 text-white/90 text-lg">Full-stack web, app, design, and marketing solutions powered by modern tech.</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#services" class="btn-primary px-6 py-3 rounded-full font-semibold shadow">Explore Services</a>
                <a href="<?php echo e(url('/#contact')); ?>" class="bg-white text-gray-900 px-6 py-3 rounded-full font-semibold hover:opacity-90">Apply Now</a>
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
            <?php if($activeCategory): ?>
                <p class="mt-2 text-sm text-gray-500">Category: <span class="font-semibold text-gray-700"><?php echo e($activeCategory->name); ?></span></p>
            <?php endif; ?>
        </div>
        
        <!-- Category Filter -->
        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <a href="<?php echo e(route('services.index')); ?>" class="px-4 py-2 rounded-full text-sm <?php echo e(!$activeCategory ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">All Categories</a>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('services.category', $category->slug)); ?>" class="px-4 py-2 rounded-full text-sm <?php echo e($activeCategory && $activeCategory->id == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>"><?php echo e($category->name); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 card-hover hover:shadow-2xl hover:-translate-y-1 transition-all">
                <div class="w-16 h-16 service-icon rounded-full mb-5 flex items-center justify-center">
                    <i class="<?php echo e($service->category->icon ?? 'fas fa-cogs'); ?> text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2"><?php echo e($service->title); ?></h3>
                <p class="text-gray-600 mb-4"><?php echo e($service->short_description); ?></p>
                <div class="flex items-center justify-between pt-4 border-t">
                    <span class="font-bold text-gray-900">
                        <?php if($service->price): ?>
                            BDT <?php echo e(number_format($service->price)); ?><?php echo e($service->price_unit ? '/'.$service->price_unit : ''); ?>

                        <?php else: ?>
                            Contact for pricing
                        <?php endif; ?>
                    </span>
                    <a href="<?php echo e(route('services.show', $service->slug)); ?>" class="btn-primary px-5 py-2 rounded-full">Apply</a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">No services found</h3>
                <p class="text-gray-600">We couldn't find any services matching your criteria.</p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <div class="mt-12">
            <?php echo e($services->links()); ?>

        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/services/index.blade.php ENDPATH**/ ?>