@extends('layouts.app')

@section('content')
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <div class="mb-4">
                <i class="fas fa-shield-alt text-yellow-600 text-4xl"></i>
            </div>
            <h1 class="text-2xl font-bold mb-2">Page Expired</h1>
            <p class="text-gray-600 mb-6">Your session may have expired or the request was invalid. Please try again.</p>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow">
                    <i class="fas fa-undo mr-2"></i> Go Back
                </a>
                <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-700 text-white hover:bg-gray-800 shadow">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
            </div>
        </div>
    </div>
</section>
@endsection