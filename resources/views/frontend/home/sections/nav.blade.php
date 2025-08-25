<!-- Navigation -->
<nav class="bg-white shadow-lg fixed w-full z-50 top-0">
    <div class="container mx-auto px-6 py-3">
        <div class="flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center hover:opacity-90 transition">
                <i class="fas fa-code text-3xl gradient-text mr-3"></i>
                <span class="text-2xl font-bold gradient-text">Wise Dynamic</span>
            </a>
            <div class="flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium">Home</a>
                <a href="#services" class="text-gray-700 hover:text-blue-600 font-medium">Services</a>
                <a href="#packages" class="text-gray-700 hover:text-blue-600 font-medium">Packages</a>
                <a href="#contact" class="text-gray-700 hover:text-blue-600 font-medium">Contact</a>

                @guest
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition">Register</a>
                @endguest

                @auth
                <a href="#" class="bg-gray-800 text-white px-4 py-2 rounded-full hover:bg-gray-900 transition">Account</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
