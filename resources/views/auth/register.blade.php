@extends('layouts.app')

@section('content')
<section class="min-h-screen pt-24 pb-16 theme-gradient flex items-center">
  <div class="container mx-auto px-6">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-8 items-stretch">
      <!-- Brand / Intro -->
      <div class="hidden md:flex flex-col justify-between bg-white/10 backdrop-blur rounded-2xl p-8 text-white shadow-2xl border border-white/20">
        <div>
          <div class="flex items-center gap-3 mb-6">
            <i class="fas fa-user-plus text-2xl"></i>
            <h1 class="text-2xl font-extrabold tracking-tight">Join Our Community</h1>
          </div>
          <p class="text-white/90">Create an account to access exclusive features, track your orders, and get personalized service recommendations.</p>
        </div>
        <ul class="mt-8 space-y-3 text-sm">
          <li class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">✓</span> Easy order management</li>
          <li class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">✓</span> Faster checkout process</li>
          <li class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">✓</span> Order history and tracking</li>
        </ul>
        <div class="mt-8 text-xs text-white/80">By registering, you agree to our <a href="#" class="underline">Terms of Service</a> and <a href="#" class="underline">Privacy Policy</a>.</div>
      </div>

      <!-- Registration Card -->
      <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10">
        <div class="mb-6">
          <h2 class="text-2xl font-extrabold gradient-text">Create Account</h2>
          <p class="text-gray-600 mt-1">Fill in your details to get started.</p>
        </div>

        @if (session('error'))
          <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ session('error') }}</div>
        @endif

        <form method="post" action="{{ route('register.post') }}" class="space-y-4">
          @csrf
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
            @error('name')
              <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
            @enderror
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
            @error('email')
              <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
              <input type="password" name="password" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
              @error('password')
                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
              <input type="password" name="password_confirmation" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
            </div>
          </div>
          
          <div class="flex items-center mt-2">
            <input type="checkbox" name="terms" id="terms" class="rounded border-gray-300" required>
            <label for="terms" class="ml-2 text-sm text-gray-700">I agree to the <a href="#" class="text-blue-600 hover:underline">Terms of Service</a></label>
          </div>
          
          <button type="submit" class="w-full inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-5 py-2.5 rounded-lg font-semibold shadow hover:shadow-lg transition mt-2">
            <i class="fas fa-user-plus mr-2"></i> Register Account
          </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
          Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Sign In</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection