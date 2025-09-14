@php $title = 'Dashboard'; @endphp
@extends('layouts.customer')

@section('content')
<div class="w-full px-4 md:px-6 space-y-6">
    <!-- Welcome Section -->
    <div class="gradient-welcome rounded-lg shadow p-6 text-white">
        <div class="flex items-center justify-between">
             <div>
                 <h1 class="text-3xl font-bold mb-2 welcome-text">Welcome back, {{ auth()->user()->name }}!</h1>
                 <p class="welcome-text text-lg">Here's an overview of your account activity</p>
                 <div class="mt-2 flex items-center">
                     <span class="welcome-text text-sm mr-4">Account Balance:</span>
                     <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-semibold">
                         BDT {{ number_format(auth()->user()->balance, 2) }}
                     </span>
                 </div>
             </div>
            <div class="hidden md:block">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile Image" class="w-16 h-16 rounded-full object-cover border-2 border-blue-200">
                @else
                    <i class="fas fa-user-circle text-6xl text-blue-200"></i>
                @endif
            </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="rounded-lg shadow p-4 stat-card dashboard-card stat-card-blue">
            <div class="flex items-center">
                <div class="p-2 rounded-lg stat-icon">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm opacity-90">Package Orders</p>
                    <p class="text-2xl font-bold">{{ $stats['total_package_orders'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="rounded-lg shadow p-4 stat-card dashboard-card stat-card-green">
            <div class="flex items-center">
                <div class="p-2 rounded-lg stat-icon">
                    <i class="fas fa-cogs text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm opacity-90">Service Orders</p>
                    <p class="text-2xl font-bold">{{ $stats['total_service_orders'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="rounded-lg shadow p-4 stat-card dashboard-card stat-card-purple">
            <div class="flex items-center">
                <div class="p-2 rounded-lg stat-icon">
                    <i class="fas fa-paper-plane text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm opacity-90">Requests</p>
                    <p class="text-2xl font-bold">{{ $stats['total_requests'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="rounded-lg shadow p-4 stat-card dashboard-card stat-card-orange">
            <div class="flex items-center">
                <div class="p-2 rounded-lg stat-icon">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm opacity-90">Total Spent</p>
                    <p class="text-2xl font-bold">৳{{ number_format($stats['total_spent']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
      <div class="rounded-lg shadow dashboard-card card-blue">
           <div class="card-header-themed p-4 rounded-t-lg">
               <h2 class="text-xl font-bold section-header mb-0">Quick Actions</h2>
           </div>
          <div class="p-6">
         <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
             <a href="{{ route('customer.requests.create') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 quick-action action-blue">
                 <i class="fas fa-plus-circle text-3xl text-blue-600 mb-3"></i>
                 <span class="text-sm font-semibold text-gray-700">New Request</span>
             </a>
             <a href="{{ route('packages') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 quick-action action-green">
                 <i class="fas fa-box text-3xl text-green-600 mb-3"></i>
                 <span class="text-sm font-semibold text-gray-700">Browse Packages</span>
             </a>
             <a href="{{ route('services.index') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 quick-action action-purple">
                 <i class="fas fa-cogs text-3xl text-purple-600 mb-3"></i>
                 <span class="text-sm font-semibold text-gray-700">Browse Services</span>
             </a>
             <a href="{{ route('contact') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 quick-action action-orange">
                 <i class="fas fa-headset text-3xl text-orange-600 mb-3"></i>
                 <span class="text-sm font-semibold text-gray-700">Contact Support</span>
             </a>
          </div>
          </div>
      </div>

    <!-- Recent Activity -->
    <div class="grid md:grid-cols-2 gap-6">
        <!-- Recent Orders -->
          <div class="rounded-lg shadow dashboard-card card-green">
              <div class="card-header-themed p-4 rounded-t-lg">
                  <h3 class="text-xl font-bold section-header mb-0">Recent Orders</h3>
              </div>
            <div class="p-4">
                @if($recentPackageOrders->count() > 0 || $recentServiceOrders->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentPackageOrders as $order)
                             <div class="flex items-center justify-between p-4 rounded-lg activity-card transition-all duration-200">
                                 <div>
                                     <p class="font-semibold text-gray-800">{{ $order->package_name }}</p>
                                     <p class="text-sm text-gray-600">Package Order #{{ $order->id }}</p>
                                 </div>
                                 <div class="text-right">
                                     <p class="font-bold text-lg text-gray-800">৳{{ number_format($order->amount) }}</p>
                                     <span class="text-xs px-3 py-1 rounded-full font-medium
                                         @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                         @elseif($order->status == 'completed') bg-green-100 text-green-800
                                         @else bg-gray-100 text-gray-800 @endif">
                                         {{ ucfirst($order->status) }}
                                     </span>
                                 </div>
                             </div>
                         @endforeach
                         @foreach($recentServiceOrders as $order)
                             <div class="flex items-center justify-between p-4 rounded-lg activity-card transition-all duration-200">
                                 <div>
                                     <p class="font-semibold text-gray-800">{{ $order->service_name }}</p>
                                     <p class="text-sm text-gray-600">Service Order #{{ $order->id }}</p>
                                 </div>
                                 <div class="text-right">
                                     <p class="font-bold text-lg text-gray-800">৳{{ number_format($order->amount) }}</p>
                                     <span class="text-xs px-3 py-1 rounded-full font-medium
                                         @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                         @elseif($order->status == 'completed') bg-green-100 text-green-800
                                         @else bg-gray-100 text-gray-800 @endif">
                                         {{ ucfirst($order->status) }}
                                     </span>
                                 </div>
                             </div>
                         @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('customer.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All Orders →</a>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box text-3xl mb-3"></i>
                        <p>No orders yet</p>
                        <a href="{{ route('packages') }}" class="text-blue-600 hover:text-blue-800 text-sm">Browse packages to get started</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Requests -->
          <div class="rounded-lg shadow dashboard-card card-purple">
              <div class="card-header-themed p-4 rounded-t-lg">
                  <h3 class="text-xl font-bold section-header mb-0">Recent Requests</h3>
              </div>
            <div class="p-4">
                @if($recentRequests->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentRequests as $request)
                             <div class="flex items-center justify-between p-4 rounded-lg activity-card transition-all duration-200">
                                 <div>
                                     <p class="font-semibold text-gray-800">{{ $request->page_name }}</p>
                                     <p class="text-sm text-gray-600">{{ $request->social_media }}</p>
                                 </div>
                                 <div class="text-right">
                                     <p class="text-sm font-medium text-gray-700">{{ $request->created_at->format('M d') }}</p>
                                     <span class="text-xs px-3 py-1 rounded-full font-medium
                                         @if($request->status == 'pending') bg-yellow-100 text-yellow-800
                                         @elseif($request->status == 'done') bg-green-100 text-green-800
                                         @elseif($request->status == 'in_progress') bg-blue-100 text-blue-800
                                         @else bg-gray-100 text-gray-800 @endif">
                                         {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                     </span>
                                 </div>
                             </div>
                         @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('customer.requests.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All Requests →</a>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-paper-plane text-3xl mb-3"></i>
                        <p>No requests yet</p>
                        <a href="{{ route('customer.requests.create') }}" class="text-blue-600 hover:text-blue-800 text-sm">Create your first request</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Account Info -->
      <div class="rounded-lg shadow dashboard-card card-orange">
          <div class="card-header-themed p-4 rounded-t-lg">
              <h2 class="text-xl font-bold section-header mb-0">Account Information</h2>
          </div>
         <div class="p-6">
             <div class="grid md:grid-cols-2 gap-6">
                 <div class="account-card p-4 rounded-lg transition-all duration-200">
                     <h3 class="font-bold mb-3 text-gray-800">Profile Details</h3>
                     <div class="space-y-3 text-gray-700">
                         <p class="flex items-center"><i class="fas fa-user w-5 mr-3 text-blue-600"></i><span class="font-semibold">Name:</span> <span class="ml-2">{{ auth()->user()->name }}</span></p>
                         <p class="flex items-center"><i class="fas fa-envelope w-5 mr-3 text-blue-600"></i><span class="font-semibold">Email:</span> <span class="ml-2">{{ auth()->user()->email }}</span></p>
                         @if(auth()->user()->phone)
                             <p class="flex items-center"><i class="fas fa-phone w-5 mr-3 text-blue-600"></i><span class="font-semibold">Phone:</span> <span class="ml-2">{{ auth()->user()->phone }}</span></p>
                         @endif
                     </div>
                 </div>
                 <div class="account-card p-4 rounded-lg transition-all duration-200">
                     <h3 class="font-bold mb-3 text-gray-800">Account Status</h3>
                     <div class="space-y-3">
                         <div class="flex items-center">
                             <span class="w-4 h-4 bg-green-500 rounded-full mr-3 flex-shrink-0"></span>
                             <span class="text-sm font-medium text-gray-700">Account Active</span>
                         </div>
                         <div class="flex items-center">
                             <span class="w-4 h-4 bg-blue-500 rounded-full mr-3 flex-shrink-0"></span>
                             <span class="text-sm font-medium text-gray-700">Member since {{ auth()->user()->created_at->format('M Y') }}</span>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
    </div>
</div>
@endsection
