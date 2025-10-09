<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Customer Dashboard' }} - {{ config('app.name') }}</title>
    <!-- Tailwind CSS via CDN (no npm required) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#0976bc',
                        'dark': '#0e0f3e'
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/customer-dashboard.css') }}" />
    @stack('styles')
</head>
<body class="dashboard-container text-gray-900">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside id="customer-sidebar" class="w-64 text-gray-100 flex-shrink-0 md:flex md:flex-col md:relative fixed inset-y-0 left-0 z-40 transform transition-transform duration-200 ease-in-out -translate-x-full md:translate-x-0 shadow-lg">
        <div class="h-16 flex items-center px-5 brand">
            <i class="fas fa-user-circle text-2xl mr-2"></i>
            <span class="text-lg font-semibold">My Dashboard</span>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="px-3 space-y-1">
                <li>
                    <a href="{{ route('customer.dashboard') }}" class="link-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-gauge-high w-5 mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.requests.index') }}" class="link-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('customer.requests.*') ? 'active' : '' }}">
                        <i class="fa-regular fa-paper-plane w-5 mr-3"></i>
                        <span>Quick Ad Request</span>
                    </a>
                </li>
                <li>
                    <div class="px-3 py-1">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Orders</span>
                    </div>
                </li>
                <li>
                    <a href="{{ route('customer.orders.index') }}" class="link-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                        <i class="fas fa-box w-5 mr-3"></i>
                        <span>Package Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.service-orders.index') }}" class="link-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('customer.service-orders.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs w-5 mr-3"></i>
                        <span>Service Orders</span>
                    </a>
                </li>
                <li>
                    <div class="px-3 py-1 mt-4">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Account</span>
                    </div>
                </li>
                <li>
                    <a href="{{ route('customer.profile.show') }}" class="link-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user-edit w-5 mr-3"></i>
                        <span>My Profile</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.fund.index') }}" class="link-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('customer.fund.*') ? 'active' : '' }}">
                        <i class="fas fa-wallet w-5 mr-3"></i>
                        <span>Fund Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.custom-service.index') }}" class="link-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('customer.custom-service.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs w-5 mr-3"></i>
                        <span>Custom Marketing Services</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}" class="link-item flex items-center px-3 py-2 rounded-md">
                        <i class="fas fa-home w-5 mr-3"></i>
                        <span>Back to Website</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact') }}" class="link-item flex items-center px-3 py-2 rounded-md">
                        <i class="fas fa-headset w-5 mr-3"></i>
                        <span>Support</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-3" style="border-top:1px solid var(--lte-border)">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-md bg-red-600 hover:bg-red-700">
                    <i class="fas fa-right-from-bracket mr-2"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile backdrop (hidden by default) -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/40 z-30 md:hidden hidden"></div>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b flex items-center justify-between px-4 md:px-6">
            <div class="flex items-center gap-2">
                <button id="sidebar-toggle" class="inline-flex items-center justify-center w-11 h-11 rounded-full text-blue-600 md:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Toggle sidebar" aria-expanded="false" title="Open menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a id="open-sidebar-link" href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-semibold">← Back to site</a>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:block text-sm text-gray-600">{{ auth()->user()->email ?? '' }}</span>
                @if(auth()->user()->profile_image)
                <img src="{{ asset(auth()->user()->profile_image) }}" alt="Profile Image" class="w-9 h-9 rounded-full object-cover border border-gray-300">
                @else
                    <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                        <i class="far fa-user"></i>
                    </div>
                @endif
            </div>
        </header>

        <!-- Content -->
        <main class="p-4 md:p-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('modals')
@stack('scripts')
<script src="{{ asset('assets/js/customer-dashboard.js') }}"></script>
</body>
</html>
