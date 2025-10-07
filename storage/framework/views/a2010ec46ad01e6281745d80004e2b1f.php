<?php $__env->startSection('content'); ?>
<header class="theme-gradient text-white pt-28 pb-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl md:text-4xl font-extrabold"><?php echo e($contactSetting->title); ?></h1>
        <p class="mt-2 text-white/90"><?php echo e($contactSetting->subtitle); ?></p>
    </div>
</header>

<section class="py-12 bg-white">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            <!-- Contact Information Column -->
            <div>
                <!-- Contact Info Card -->
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 p-8 rounded-lg shadow-lg mb-8">
                    <h3 class="text-2xl font-bold mb-6 gradient-text">Get In Touch</h3>
                    
                    <div class="space-y-6">
                        <!-- Address -->
                        <div class="flex items-center">
                            <div class="w-12 h-12 service-icon rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Address</h4>
                                <p class="text-gray-600"><?php echo e($contactSetting->address); ?></p>
                            </div>
                        </div>
                        
                        <!-- Phone -->
                        <div class="flex items-center">
                            <div class="w-12 h-12 service-icon rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Call Us</h4>
                                <p class="text-gray-600"><?php echo e($contactSetting->phone); ?></p>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="flex items-center">
                            <div class="w-12 h-12 service-icon rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Email Us</h4>
                                <p class="text-gray-600"><?php echo e($contactSetting->email); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Office Hours -->
                <div class="bg-white p-8 rounded-lg shadow-lg mb-8">
                    <h3 class="text-xl font-bold mb-4 gradient-text">Office Hours</h3>
                    <div class="space-y-3">
                        <?php
                            $officeHours = is_array($contactSetting->office_hours) ? $contactSetting->office_hours : json_decode($contactSetting->office_hours, true);
                            $officeHours = $officeHours ?: [];
                        ?>
                        <?php $__currentLoopData = $officeHours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hours): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <span class="font-medium text-gray-700"><?php echo e($hours['day']); ?></span>
                            <span class="text-gray-600"><?php echo e($hours['hours']); ?></span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-4 gradient-text">Connect With Us</h3>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <?php
                            $socialLinks = is_array($contactSetting->social_links) ? $contactSetting->social_links : json_decode($contactSetting->social_links, true);
                            $socialLinks = $socialLinks ?: [];
                        ?>
                        <?php $__currentLoopData = $socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($social['url']); ?>" target="_blank" class="w-12 h-12 service-icon rounded-full flex items-center justify-center hover:scale-110 transition-transform">
                            <i class="<?php echo e($social['icon']); ?> text-white"></i>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- Contact Form Column -->
            <div>
                <!-- Map -->
                <div class="mb-8 rounded-lg overflow-hidden shadow-lg">
                    <?php echo $contactSetting->map_embed; ?>

                </div>

                <!-- Contact Form -->
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mb-4 gradient-text"><?php echo e($contactSetting->form_title); ?></h3>
                    <p class="text-gray-600 mb-6"><?php echo e($contactSetting->form_subtitle); ?></p>
                    
                    <?php if(session('success')): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('contact.submit')); ?>" method="POST" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                                <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" class="w-full px-4 py-2 border <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your Email</label>
                                <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" class="w-full px-4 py-2 border <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <input type="text" name="subject" id="subject" value="<?php echo e(old('subject')); ?>" class="w-full px-4 py-2 border <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea name="message" id="message" rows="5" class="w-full px-4 py-2 border <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-md focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('message')); ?></textarea>
                            <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-md font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/contact/index.blade.php ENDPATH**/ ?>