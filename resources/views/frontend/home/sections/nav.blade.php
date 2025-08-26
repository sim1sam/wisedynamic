<!-- Navigation -->
<nav class="bg-white shadow-lg fixed w-full z-50 top-0">
    <div class="container mx-auto px-6 py-3">
        <div class="flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center hover:opacity-90 transition">
                <i class="fas fa-code text-3xl gradient-text mr-3"></i>
                <span class="text-2xl font-bold gradient-text">Wise Dynamic</span>
            </a>
            <!-- Mobile toggle -->
            <button id="nav-toggle" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-expanded="false" aria-controls="nav-menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            
            <!-- Desktop menu -->
            <div id="nav-menu" class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}" class="font-medium {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">Home</a>
                <a href="{{ route('about') }}" class="font-medium {{ request()->routeIs('about') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">About</a>
                <a href="{{ route('services') }}" class="font-medium {{ request()->routeIs('services') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">Services</a>
                <a href="{{ route('packages') }}" class="text-gray-700 hover:text-blue-600 font-medium">Packages</a>
                <a href="{{ route('contact') }}" class="font-medium {{ request()->routeIs('contact') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">Contact</a>

                @guest
                <a href="{{ route('login') }}" class="hidden md:inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-5 py-2.5 rounded-full font-semibold shadow hover:shadow-lg transition">Login</a>
                @endguest

                @auth
                <a href="#" class="bg-gray-800 text-white px-4 py-2 rounded-full hover:bg-gray-900 transition">Account</a>
                @endauth
            </div>
        </div>
    </div>
    <!-- Mobile menu panel -->
    <div id="nav-panel" class="md:hidden hidden bg-white border-t shadow-inner">
        <div class="container mx-auto px-6 py-3 space-y-2">
            <a href="{{ route('home') }}" class="block py-2 font-medium {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">Home</a>
            <a href="{{ route('about') }}" class="block py-2 font-medium {{ request()->routeIs('about') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">About</a>
            <a href="{{ route('services') }}" class="block py-2 font-medium {{ request()->routeIs('services') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">Services</a>
            <a href="{{ route('packages') }}" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">Packages</a>
            <a href="{{ route('contact') }}" class="block py-2 font-medium {{ request()->routeIs('contact') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">Contact</a>
            @guest
            <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-5 py-2.5 rounded-full font-semibold shadow hover:shadow-lg transition">Login</a>
            @endguest
        </div>
    </div>
</nav>

@push('scripts')
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
</script>
@endpush
