<!-- Why Choose Us -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4 gradient-text"><?php echo e($homeSetting->why_choose_title ?? 'Why Choose Wise Dynamic?'); ?></h2>
            <div class="section-divider mb-6"></div>
            <p class="text-xl text-gray-700"><?php echo e($homeSetting->why_choose_subtitle ?? 'We blend creativity, technology, and personalized support'); ?></p>
        </div>
        
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <?php if(isset($homeSetting->why_choose_items) && is_array($homeSetting->why_choose_items)): ?>
                    <?php $__currentLoopData = $homeSetting->why_choose_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="<?php echo e($item['icon'] ?? 'fas fa-check'); ?> text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo e($item['title'] ?? 'Feature'); ?></h3>
                                <p class="text-gray-600"><?php echo e($item['text'] ?? 'Description'); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-award text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">BASIS Certified Excellence</h3>
                            <p class="text-gray-600">Official BASIS member since 2020, ensuring professional standards and reliability</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-rocket text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Startup-Friendly Pricing</h3>
                            <p class="text-gray-600">Affordable solutions designed for young entrepreneurs and growing businesses</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-tools text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Full-Spectrum Solutions</h3>
                            <p class="text-gray-600">From websites to mobile apps, marketing to music — everything under one roof</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-heart text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Dedicated Small Team</h3>
                            <p class="text-gray-600">Personal attention and care - we treat your success like our own</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center">
                <div class="bg-white rounded-lg p-8 shadow-2xl animate-pulse-custom">
                    <div class="text-6xl font-bold gradient-text mb-4"><?php echo e($homeSetting->why_choose_clients_count ?? '100'); ?>+</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Happy Clients</h3>
                    <p class="text-gray-600">Successful projects delivered</p>
                    
                    <div class="mt-8 grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold gradient-text"><?php echo e($homeSetting->why_choose_experience ?? '4+ Years'); ?></div>
                            <p class="text-sm text-gray-600">Experience</p>
                        </div>
                        <div>
                            <div class="text-2xl font-bold gradient-text">24/7</div>
                            <p class="text-sm text-gray-600">Support</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/home/sections/why-choose.blade.php ENDPATH**/ ?>