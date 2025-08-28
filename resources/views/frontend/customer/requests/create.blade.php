@php($title = 'New Request')
@extends('layouts.customer')

@section('content')
<div class="-m-4 md:-m-6">

    @if ($errors->any())
        <div class="p-3 rounded bg-red-100 text-red-700">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="create-request-form" method="POST" action="{{ route('customer.requests.store') }}" class="bg-white p-5 md:p-6 space-y-4 min-h-[calc(100vh-4rem)]">
        @csrf
        <div>
            <label for="page_name" class="block text-sm font-medium text-gray-700">Page Name <span class="text-red-500">*</span></label>
            <input type="text" id="page_name" name="page_name" value="{{ old('page_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., WiseDynamic FB Page" />
        </div>
        <div>
            <label for="social_media" class="block text-sm font-medium text-gray-700">Social Media <span class="text-red-500">*</span></label>
            <select id="social_media" name="social_media" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Select one</option>
                <option value="facebook" @selected(old('social_media')==='facebook')>Facebook</option>
                <option value="instagram" @selected(old('social_media')==='instagram')>Instagram</option>
                <option value="tiktok" @selected(old('social_media')==='tiktok')>TikTok</option>
                <option value="twitter" @selected(old('social_media')==='twitter')>Twitter</option>
                <option value="linkedin" @selected(old('social_media')==='linkedin')>LinkedIn</option>
                <option value="youtube" @selected(old('social_media')==='youtube')>YouTube</option>
            </select>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="ads_budget_bdt" class="block text-sm font-medium text-gray-700">Ads Budget (BDT) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" min="0" id="ads_budget_bdt" name="ads_budget_bdt" value="{{ old('ads_budget_bdt') }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., 5000" />
            </div>
            <div>
                <label for="days" class="block text-sm font-medium text-gray-700">Days <span class="text-red-500">*</span></label>
                <input type="number" min="1" id="days" name="days" value="{{ old('days') }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., 7" />
            </div>
            <div>
                <label for="post_link" class="block text-sm font-medium text-gray-700">Post Link</label>
                <input type="url" id="post_link" name="post_link" value="{{ old('post_link') }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="https://..." />
            </div>
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
