@extends('layouts.app')

@section('content')
<div class="min-h-[60vh] bg-gray-50 py-24">
  <div class="container mx-auto px-6 max-w-5xl">
    <div class="bg-white rounded-2xl shadow p-8">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl md:text-3xl font-bold">My Account</h1>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="px-4 py-2 rounded-full bg-gray-800 text-white hover:bg-gray-900">Logout</button>
        </form>
      </div>

      <div class="grid md:grid-cols-2 gap-6">
        <div class="border rounded-xl p-6">
          <h2 class="text-lg font-semibold mb-4">Profile</h2>
          <div class="space-y-2 text-gray-700">
            <p><span class="font-medium">Name:</span> {{ auth()->user()->name }}</p>
            <p><span class="font-medium">Email:</span> {{ auth()->user()->email }}</p>
          </div>
        </div>
        <div class="border rounded-xl p-6">
          <h2 class="text-lg font-semibold mb-4">Quick Links</h2>
          <ul class="space-y-2 list-disc list-inside text-blue-700">
            <li><a href="{{ route('home') }}" class="hover:underline">Back to Home</a></li>
            <li><a href="{{ route('packages') }}" class="hover:underline">Browse Packages</a></li>
            <li><a href="{{ route('contact') }}" class="hover:underline">Contact Support</a></li>
          </ul>
        </div>
      </div>

      <div class="mt-8 border rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-4">Security</h2>
        <p class="text-gray-600">Password reset coming soon.</p>
      </div>
    </div>
  </div>
</div>
@endsection
