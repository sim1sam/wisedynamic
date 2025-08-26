@extends('layouts.app')

@section('content')
<header class="theme-gradient text-white pt-28 pb-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl md:text-4xl font-extrabold">Admin Dashboard</h1>
        <p class="mt-1 text-white/90">Welcome, {{ auth()->user()->name ?? 'Admin' }}</p>
    </div>
</header>

<section class="py-10 bg-white">
    <div class="container mx-auto px-6 grid md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="text-sm text-gray-500">Total Users</div>
            <div class="mt-2 text-3xl font-extrabold gradient-text">{{ \App\Models\User::count() }}</div>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="text-sm text-gray-500">Admins</div>
            <div class="mt-2 text-3xl font-extrabold gradient-text">{{ \App\Models\User::where('is_admin', true)->count() }}</div>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="text-sm text-gray-500">Environment</div>
            <div class="mt-2 text-3xl font-extrabold gradient-text">{{ app()->environment() }}</div>
        </div>
    </div>
</section>
@endsection
