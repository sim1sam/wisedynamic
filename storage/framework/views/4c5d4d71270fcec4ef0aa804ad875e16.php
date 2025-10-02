<!-- Digital Marketing Packages -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">Digital Marketing Packages</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600">Boost your online presence with our comprehensive marketing solutions</p>
        </div>
        
        <div class="grid lg:grid-cols-<?php echo e(count($marketingPackages) > 0 ? min(count($marketingPackages), 3) : 3); ?> gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $marketingPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    // Define different color schemes for packages
                    $colorSchemes = [
                        ['', ''],
                        ['border-2 border-purple-200', 'bg-purple-500'],
                        ['', '']
                    ];
                    
                    $currentScheme = $colorSchemes[$index % count($colorSchemes)];
                    $featured = $package->featured;
                    
                    // Parse description for features
                    $description = $package->description;
                    $features = explode("\n", $description);
                ?>
                
                <div class="bg-white p-8 rounded-lg shadow-lg card-hover <?php echo e($featured ? $currentScheme[0] : ''); ?>">
                    <?php if($featured || $index === 1): ?>
                        <div class="<?php echo e($currentScheme[1] ? $currentScheme[1] : 'bg-blue-500'); ?> text-white px-3 py-1 rounded-full text-sm mb-4 inline-block"><?php echo e($index === 1 ? 'Recommended' : 'Popular'); ?></div>
                    <?php endif; ?>
                    <h3 class="text-2xl font-bold mb-4 text-center"><?php echo e($package->title); ?></h3>
                    <div class="price-highlight text-3xl font-bold text-center mb-6">BDT <?php echo e(number_format($package->price)); ?>/- <span class="text-base font-normal text-gray-600"><?php echo e($package->price_unit); ?></span></div>
                    
                    <div class="prose space-y-3 mb-6">
                        <?php echo $package->description; ?>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <!-- Fallback if no packages are found -->
                <div class="col-span-3 text-center py-10">
                    <p class="text-xl text-gray-600">No digital marketing packages available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-8">
            <a href="<?php echo e(route('packages')); ?>" class="btn-primary px-8 py-3 rounded-full font-semibold">View All Packages</a>
        </div>
    </div>
</section>
<?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/home/sections/marketing.blade.php ENDPATH**/ ?>