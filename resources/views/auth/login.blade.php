@extends('layouts.app')

@section('content')
<section class="min-h-screen pt-24 pb-16 theme-gradient flex items-center">
  <div class="container mx-auto px-6">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-8 items-stretch">
      <!-- Brand / Intro -->
      <div class="hidden md:flex flex-col justify-between bg-white/10 backdrop-blur rounded-2xl p-8 text-white shadow-2xl border border-white/20">
        <div>
          <div class="flex items-center gap-3 mb-6">
            <i class="fas fa-user-circle text-2xl"></i>
            <h1 class="text-2xl font-extrabold tracking-tight">Customer Portal</h1>
          </div>
          <p class="text-white/90">Sign in to access your account, manage orders, and track your projects. Welcome back!</p>
        </div>
        <ul class="mt-8 space-y-3 text-sm">
          <li class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">✓</span> Track your orders and projects</li>
          <li class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">✓</span> Manage your account details</li>
          <li class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">✓</span> Request new services easily</li>
        </ul>
        <div class="mt-8 text-xs text-white/80">Need help? Contact support at <span class="font-semibold">support@wisedynamic.com</span></div>
      </div>

      <!-- Login Card -->
      <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10">
        <div class="mb-6">
          <h2 class="text-2xl font-extrabold gradient-text">Customer Login</h2>
          <p class="text-gray-600 mt-1">Sign in to your account to continue.</p>
        </div>

        @if (session('error'))
          <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ session('error') }}</div>
        @endif

        <form method="post" action="{{ route('login.post') }}" class="space-y-5">
          @csrf
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
            @error('email')
              <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <div class="flex items-center justify-between mb-1">
              <label class="block text-sm font-medium text-gray-700">Password</label>
              <a href="#" class="text-sm text-blue-600 hover:underline">Forgot?</a>
            </div>
            <input type="password" name="password" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
            @error('password')
              <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
            @enderror
          </div>
          <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
              <input type="checkbox" name="remember" class="rounded border-gray-300"> Remember me
            </label>
          </div>
          <button type="submit" class="w-full inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-5 py-2.5 rounded-lg font-semibold shadow hover:shadow-lg transition">
            <i class="fas fa-sign-in-alt mr-2"></i> Sign In
          </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
          Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
          <div class="mt-2">
            <a href="{{ route('admin.login') }}" class="text-gray-500 hover:underline">Admin Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection