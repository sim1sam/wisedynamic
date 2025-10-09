<!-- Navigation -->
<nav class="bg-white shadow-lg fixed w-full z-50 top-0">
    <div class="container mx-auto px-6 py-3">
        <div class="flex justify-between items-center">
            <a href="<?php echo e(route('home')); ?>" class="flex items-center hover:opacity-90 transition">
                <?php
                    $websiteSetting = \App\Models\WebsiteSetting::first();
                ?>
                <?php if($websiteSetting && $websiteSetting->site_logo): ?>
                    <img src="<?php echo e(asset($websiteSetting->site_logo)); ?>" alt="<?php echo e($websiteSetting->logo_alt_text ?? 'Wise Dynamic Logo'); ?>" class="h-10 mr-3">
                <?php else: ?>
                    <i class="fas fa-code text-3xl gradient-text mr-3"></i>
                <?php endif; ?>
                <?php if(!$websiteSetting || $websiteSetting->show_site_name_with_logo): ?>
                    <span class="text-2xl font-bold gradient-text"><?php echo e($websiteSetting->site_name ?? 'Wise Dynamic'); ?></span>
                <?php endif; ?>
            </a>
            <!-- Mobile toggle -->
            <button id="nav-toggle" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-expanded="false" aria-controls="nav-menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            
            <!-- Desktop menu -->
            <div id="nav-menu" class="hidden md:flex items-center space-x-6">
                <a href="<?php echo e(route('home')); ?>" class="font-medium <?php echo e(request()->routeIs('home') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'); ?>">Home</a>
                <a href="<?php echo e(route('about')); ?>" class="font-medium <?php echo e(request()->routeIs('about') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'); ?>">About</a>
                <a href="<?php echo e(route('services.index')); ?>" class="font-medium <?php echo e(request()->routeIs('services.index') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'); ?>">Services</a>
                <a href="<?php echo e(route('packages')); ?>" class="text-gray-700 hover:text-blue-600 font-medium">Packages</a>
                <a href="<?php echo e(route('contact')); ?>" class="font-medium <?php echo e(request()->routeIs('contact') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'); ?>">Contact</a>

                <?php if(auth()->guard()->guest()): ?>
                  <a href="<?php echo e(route('login')); ?>" class="hidden md:inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-5 py-2.5 rounded-full font-semibold shadow hover:shadow-lg transition">Login</a>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                  <div class="relative">
                    <button id="user-menu-btn" aria-expanded="false" class="bg-gray-800 text-white px-4 py-2 rounded-full hover:bg-gray-900 transition inline-flex items-center">
                      <i class="far fa-user mr-2"></i>
                      <span><?php echo e(auth()->user()->name); ?></span>
                      <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>
                    <div id="user-menu-panel" class="hidden absolute right-0 mt-2 w-40 bg-white text-gray-700 rounded-md shadow-lg overflow-hidden z-20">
                      <a href="<?php echo e(route('customer.dashboard')); ?>" class="block px-4 py-2 hover:bg-gray-50">Dashboard</a>
                      <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-50">Logout</button>
                      </form>
                    </div>
                  </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Mobile menu panel -->
    <div id="nav-panel" class="md:hidden hidden bg-white border-t shadow-inner">
        <div class="container mx-auto px-6 py-3 space-y-2">
            <a href="<?php echo e(route('home')); ?>" class="block py-2 font-medium <?php echo e(request()->routeIs('home') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'); ?>">Home</a>
            <a href="<?php echo e(route('about')); ?>" class="block py-2 font-medium <?php echo e(request()->routeIs('about') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'); ?>">About</a>
            <a href="<?php echo e(route('services.index')); ?>" class="block py-2 font-medium <?php echo e(request()->routeIs('services.index') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'); ?>">Services</a>
            <a href="<?php echo e(route('packages')); ?>" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">Packages</a>
            <a href="<?php echo e(route('contact')); ?>" class="block py-2 font-medium <?php echo e(request()->routeIs('contact') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600'); ?>">Contact</a>
            <?php if(auth()->guard()->guest()): ?>
              <a href="<?php echo e(route('login')); ?>" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-5 py-2.5 rounded-full font-semibold shadow hover:shadow-lg transition">Login</a>
            <?php endif; ?>
            <?php if(auth()->guard()->check()): ?>
              <div class="border-t pt-2 mt-2">
                <div class="flex items-center mb-3">
                  <i class="far fa-user mr-2 text-gray-600"></i>
                  <span class="font-medium text-gray-800"><?php echo e(auth()->user()->name); ?></span>
                </div>
                <a href="<?php echo e(route('customer.dashboard')); ?>" class="block py-2 font-medium text-blue-600 hover:text-blue-700">
                  <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-2">
                  <?php echo csrf_field(); ?>
                  <button type="submit" class="w-full text-left py-2 font-medium text-red-600 hover:text-red-700">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                  </button>
                </form>
              </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const toggle = document.getElementById('nav-toggle');
  const panel = document.getElementById('nav-panel');
  if(!toggle || !panel) return;
  toggle.addEventListener('click', function(){
    const isOpen = !panel.classList.contains('hidden');
    panel.classList.toggle('hidden');
    this.setAttribute('aria-expanded', String(!isOpen));
  });
})();

(function(){
  const btn = document.getElementById('user-menu-btn');
  const menu = document.getElementById('user-menu-panel');
  if(!btn || !menu) return;
  function openMenu(){ menu.classList.remove('hidden'); btn.setAttribute('aria-expanded','true'); }
  function closeMenu(){ menu.classList.add('hidden'); btn.setAttribute('aria-expanded','false'); }
  btn.addEventListener('click', function(e){
    e.stopPropagation();
    const isOpen = !menu.classList.contains('hidden');
    if(isOpen) closeMenu(); else openMenu();
  });
  document.addEventListener('click', function(e){
    if(!menu.classList.contains('hidden')){
      if(!menu.contains(e.target) && e.target !== btn) closeMenu();
    }
  });
  document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeMenu(); });
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH F:\laragon\www\wisedynamic\resources\views/frontend/home/sections/nav.blade.php ENDPATH**/ ?>