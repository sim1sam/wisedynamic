@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 py-20">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-8 items-stretch">
            <!-- Left: Brand / Benefits -->
            <div class="relative overflow-hidden rounded-2xl shadow-xl theme-gradient p-10 text-white">
                <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-12 -left-12 w-72 h-72 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-code text-3xl mr-3"></i>
                        <span class="text-3xl font-extrabold tracking-tight">Wise Dynamic</span>
                    </div>
                    <h1 class="text-4xl font-extrabold leading-tight">Welcome back</h1>
                    <p class="mt-3 text-white/90">Access your dashboard, manage projects, and continue building amazing digital experiences.</p>

                    <ul class="mt-8 space-y-4">
                        <li class="flex items-start">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 mr-3"><i class="fas fa-shield-alt"></i></span>
                            <div>
                                <p class="font-semibold">Secure & Private</p>
                                <p class="text-sm text-white/80">Protected authentication with best practices.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 mr-3"><i class="fas fa-bolt"></i></span>
                            <div>
                                <p class="font-semibold">Fast Access</p>
                                <p class="text-sm text-white/80">Optimized experience for quick workflows.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 mr-3"><i class="fas fa-headset"></i></span>
                            <div>
                                <p class="font-semibold">24/7 Support</p>
                                <p class="text-sm text-white/80">We’re here whenever you need us.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="mt-10">
                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 rounded-full bg-white text-blue-700 font-semibold shadow hover:shadow-lg transition">
                            Create a free account
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Login Form -->
            <div class="bg-white rounded-2xl shadow-xl p-10">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold mb-2">Sign in to your account</h2>
                    <p class="text-gray-600">Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Register</a></p>
                </div>

                <!-- Social buttons (non-functional placeholders) -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <button type="button" class="flex items-center justify-center gap-2 border rounded-lg px-4 py-2 hover:bg-gray-50">
                        <i class="fab fa-google text-red-500"></i> <span>Google</span>
                    </button>
                    <button type="button" class="flex items-center justify-center gap-2 border rounded-lg px-4 py-2 hover:bg-gray-50">
                        <i class="fab fa-facebook text-blue-600"></i> <span>Facebook</span>
                    </button>
                </div>
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t"></div></div>
                    <div class="relative flex justify-center"><span class="bg-white px-3 text-gray-500 text-sm">or continue with email</span></div>
                </div>

                @if (session('error'))
                    <div class="mb-4 text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="you@example.com" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="••••••••" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('remember') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">Sign in</button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-500">By continuing you agree to our <a href="#" class="underline">Terms</a> and <a href="#" class="underline">Privacy Policy</a>.</p>
            </div>
        </div>
    </div>
</div>
@endsection
