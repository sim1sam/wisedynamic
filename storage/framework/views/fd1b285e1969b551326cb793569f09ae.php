<!-- Footer -->
<footer class="gradient-bg text-white py-12">
    <div class="container mx-auto px-6">
        <div class="relative">
            <!-- SSL Logo positioned on the right -->
            <div class="absolute top-0 right-0 hidden md:block">
                <?php if(!empty($footerSettings->ssl_logo)): ?>
                    <img src="<?php echo e(asset($footerSettings->ssl_logo)); ?>" alt="SSL Payment Gateway" class="w-64 h-auto opacity-90 hover:opacity-100 transition-opacity">
                <?php else: ?>
                    <img src="<?php echo e(asset('images/ssl-logo.svg')); ?>" alt="SSL Payment Gateway" class="w-64 h-auto opacity-90 hover:opacity-100 transition-opacity">
                <?php endif; ?>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <?php if(!empty($websiteSettings->site_logo)): ?>
                        <img src="<?php echo e(asset('storage/' . $websiteSettings->site_logo)); ?>" alt="<?php echo e($websiteSettings->logo_alt_text ?? 'Company Logo'); ?>" class="h-12 w-auto">
                    <?php endif; ?>
                </div>
                <?php $tagline = $footerSettings->tagline ?? 'Your Technology Partner for Innovation, Affordability & Results'; ?>
                <p class="text-lg mb-6 opacity-90"><?php echo e($tagline); ?></p>

                <div class="flex justify-center space-x-8 mb-8">
                    <?php $phone = $footerSettings->phone ?? '+8801805081012'; ?>
                    <a href="tel:<?php echo e($phone); ?>" class="hover:text-yellow-300 transition" title="Call">
                        <i class="fas fa-phone text-2xl"></i>
                    </a>
                    <?php $email = $footerSettings->email ?? 'sales@wisedynamic.com.bd'; ?>
                    <a href="mailto:<?php echo e($email); ?>" class="hover:text-yellow-300 transition" title="Email">
                        <i class="fas fa-envelope text-2xl"></i>
                    </a>
                    <?php if(!empty($footerSettings?->facebook_url)): ?>
                        <a href="<?php echo e($footerSettings->facebook_url); ?>" target="_blank" rel="noopener" class="hover:text-yellow-300 transition" title="Facebook">
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($footerSettings?->twitter_url)): ?>
                        <a href="<?php echo e($footerSettings->twitter_url); ?>" target="_blank" rel="noopener" class="hover:text-yellow-300 transition" title="Twitter">
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($footerSettings?->linkedin_url)): ?>
                        <a href="<?php echo e($footerSettings->linkedin_url); ?>" target="_blank" rel="noopener" class="hover:text-yellow-300 transition" title="LinkedIn">
                            <i class="fab fa-linkedin text-2xl"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($footerSettings?->instagram_url)): ?>
                        <a href="<?php echo e($footerSettings->instagram_url); ?>" target="_blank" rel="noopener" class="hover:text-yellow-300 transition" title="Instagram">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Footer Pages Links -->
                <div class="mb-6">
                    <div class="flex flex-wrap justify-center gap-4">
                        <?php
                            $footerPages = \App\Models\Page::where('show_in_footer', true)
                                ->where('is_active', true)
                                ->orderBy('order')
                                ->get();
                        ?>
                        
                        <?php $__currentLoopData = $footerPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('page.show', $page->slug)); ?>" class="text-white hover:text-yellow-300 transition">
                                <?php echo e($page->title); ?>

                            </a>
                            <?php if(!$loop->last): ?>
                                <span class="text-white text-opacity-50">|</span>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                
                <div class="border-t border-white border-opacity-20 pt-6">
                    <?php $copy = $footerSettings->copyright_text ?? ('© '.date('Y').' Wise Dynamic. All rights reserved.'); ?>
                    <p class="opacity-75"><?php echo e($copy); ?></p>
                    
                    <!-- SSL Logo for mobile - centered below copyright -->
                    <div class="md:hidden mt-4 flex justify-center">
                        <?php if(!empty($footerSettings->ssl_logo)): ?>
                            <img src="<?php echo e(asset($footerSettings->ssl_logo)); ?>" alt="SSL Payment Gateway" class="w-48 h-auto opacity-90">
                        <?php else: ?>
                            <img src="<?php echo e(asset('images/ssl-logo.svg')); ?>" alt="SSL Payment Gateway" class="w-48 h-auto opacity-90">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/home/sections/footer.blade.php ENDPATH**/ ?>