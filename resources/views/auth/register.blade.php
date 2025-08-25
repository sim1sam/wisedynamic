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
                    <h1 class="text-4xl font-extrabold leading-tight">Create your account</h1>
                    <p class="mt-3 text-white/90">Join our community to access your dashboard, manage projects, and collaborate faster.</p>

                    <ul class="mt-8 space-y-4">
                        <li class="flex items-start">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 mr-3"><i class="fas fa-user-shield"></i></span>
                            <div>
                                <p class="font-semibold">Privacy First</p>
                                <p class="text-sm text-white/80">Your data is safe and encrypted.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 mr-3"><i class="fas fa-rocket"></i></span>
                            <div>
                                <p class="font-semibold">Get Productive</p>
                                <p class="text-sm text-white/80">Tooling that speeds up your workflow.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 mr-3"><i class="fas fa-life-ring"></i></span>
                            <div>
                                <p class="font-semibold">Friendly Support</p>
                                <p class="text-sm text-white/80">We’re always here to help.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="mt-10">
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 rounded-full bg-white text-blue-700 font-semibold shadow hover:shadow-lg transition">
                            Already have an account? Sign in
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Register Form -->
            <div class="bg-white rounded-2xl shadow-xl p-10">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold mb-2">Create a new account</h2>
                    <p class="text-gray-600">Already registered? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Login</a></p>
                </div>

                <form method="POST" action="#" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="you@example.com">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="••••••••">
                        </div>
                    </div>
                    <label class="flex items-start gap-3">
                        <input type="checkbox" class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-600">I agree to the <a href="#" class="underline">Terms of Service</a> and <a href="#" class="underline">Privacy Policy</a>.</span>
                    </label>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">Create account</button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-500">We’ll never share your information with anyone.</p>
            </div>
        </div>
    </div>
</div>
@endsection
