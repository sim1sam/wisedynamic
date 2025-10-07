<!-- Website Development Packages -->
<section id="packages" class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text">Website Development Packages</h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-600">Choose the perfect package for your business needs</p>
        </div>
        
        <div class="grid lg:grid-cols-<?php echo e(count($webDevPackages) > 0 ? min(count($webDevPackages), 4) : 4); ?> gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $webDevPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    // Define different color schemes for packages
                    $colorSchemes = [
                        ['from-blue-50', 'hover:border-blue-200', ''],
                        ['from-purple-50', 'border-purple-200', 'bg-purple-500'],
                        ['from-green-50', 'hover:border-green-200', ''],
                        ['from-yellow-50', 'hover:border-yellow-200', 'bg-yellow-500']
                    ];
                    
                    $currentScheme = $colorSchemes[$index % count($colorSchemes)];
                    $featured = $package->featured;
                ?>
                
                <div class="bg-gradient-to-b <?php echo e($currentScheme[0]); ?> to-white p-8 rounded-lg shadow-lg card-hover border-2 border-transparent <?php echo e($featured ? $currentScheme[1] : 'hover:'.$currentScheme[1]); ?>">
                    <div class="text-center mb-6">
                        <?php if($featured || $index === 1): ?>
                            <div class="<?php echo e($currentScheme[2] ? $currentScheme[2] : 'bg-blue-500'); ?> text-white px-3 py-1 rounded-full text-sm mb-2 inline-block"><?php echo e($index === 3 ? 'Enterprise' : 'Popular'); ?></div>
                        <?php endif; ?>
                        <h3 class="text-2xl font-bold mb-2"><?php echo e($package->title); ?></h3>
                        <div class="price-highlight text-<?php echo e($index === 3 ? '2xl' : '3xl'); ?> font-bold mb-2">
                            <?php if($index === 3): ?>
                                From BDT <?php echo e(number_format($package->price)); ?>/-
                            <?php else: ?>
                                BDT <?php echo e(number_format($package->price)); ?>/-
                            <?php endif; ?>
                        </div>
                        <p class="text-gray-600"><?php echo e($package->short_description); ?></p>
                    </div>
                    
                    <div class="prose space-y-3 mb-6">
                        <?php echo $package->description; ?>

                    </div>
                    
                    <div class="text-sm text-gray-600 mb-4">
                        <?php if($index === 3): ?>
                            <p class="font-semibold text-center">Contact for Custom Quote</p>
                        <?php else: ?>
                            <?php if($index === 0): ?>
                                <p>Payment Gateway Setup: <span class="font-semibold">BDT 22,500/-</span></p>
                                <p>Integration: <span class="font-semibold">BDT 5,000/-</span></p>
                            <?php elseif($index === 1): ?>
                                <p>Payment Gateway Setup: <span class="font-semibold">BDT 20,000/-</span></p>
                                <p>Integration: <span class="font-semibold">BDT 4,000/-</span></p>
                            <?php elseif($index === 2): ?>
                                <p>Payment Gateway Setup: <span class="font-semibold">BDT 17,500/-</span></p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <!-- Fallback if no packages are found -->
                <div class="col-span-4 text-center py-10">
                    <p class="text-xl text-gray-600">No website development packages available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-8">
            <a href="<?php echo e(route('packages')); ?>#web-packages" class="btn-primary px-8 py-3 rounded-full font-semibold">View All Packages</a>
        </div>
    </div>
</section>
<?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/home/sections/packages.blade.php ENDPATH**/ ?>