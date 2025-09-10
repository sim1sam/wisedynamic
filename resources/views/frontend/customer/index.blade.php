@extends('layouts.customer')

@section('content')
<div class="grid gap-6 md:grid-cols-2">
  <div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Profile</h2>
    <div class="space-y-2 text-gray-700">
      <p><span class="font-medium">Name:</span> {{ auth()->user()->name }}</p>
      <p><span class="font-medium">Email:</span> {{ auth()->user()->email }}</p>
    </div>
  </div>
  <div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Quick Links</h2>
    <ul class="space-y-2 list-disc list-inside text-blue-700">
      <li><a href="{{ route('home') }}" class="hover:underline">Back to Home</a></li>
      <li><a href="{{ route('packages') }}" class="hover:underline">Browse Packages</a></li>
      <li><a href="{{ route('customer.orders.index') }}" class="hover:underline">My Orders</a></li>
      <li><a href="{{ route('contact') }}" class="hover:underline">Contact Support</a></li>
    </ul>
  </div>
  <div class="bg-white rounded-xl shadow p-6 md:col-span-2">
    <h2 class="text-lg font-semibold mb-4">Security</h2>
    <p class="text-gray-600">Password reset coming soon.</p>
  </div>
</div>
@endsection
