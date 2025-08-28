@php($title = 'New Request')
@extends('layouts.customer')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-xl font-semibold mb-1">Create New Request</h1>
        <p class="text-sm text-gray-600">Describe what you need. Our team will review and update the status.</p>
    </div>

    @if ($errors->any())
        <div class="p-3 rounded bg-red-100 text-red-700">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="create-request-form" method="POST" action="{{ route('customer.requests.store') }}" class="bg-white rounded shadow-sm p-5 space-y-4">
        @csrf
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. Need a new landing page" />
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Add details to help us understand your request">{{ old('description') }}</textarea>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                <i class="fa-solid fa-paper-plane mr-2"></i> Submit Request
            </button>
            <a href="{{ route('customer.requests.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
        </div>
    </form>
</div>
@endsection
