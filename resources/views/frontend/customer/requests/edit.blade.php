@php($title = 'Edit Request')
@extends('layouts.customer')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
  <div class="flex items-start justify-between gap-3">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Edit Request</h1>
      <p class="text-sm text-gray-600 mt-1">Update the details of your request. Our team will review changes.</p>
    </div>
    <a href="{{ route('customer.requests.index') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200">
      <i class="fa-solid fa-arrow-left mr-2"></i> Back to list
    </a>
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

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <form method="POST" action="{{ route('customer.requests.update', $customerRequest) }}" class="md:col-span-2 bg-white rounded-xl shadow p-6 space-y-5">
      @csrf
      @method('PUT')

      <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
        <input type="text" id="title" name="title" value="{{ old('title', $customerRequest->title) }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600" placeholder="e.g., Boost campaign for product launch" />
      </div>

      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea id="description" name="description" rows="7" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600" placeholder="Add any context, links, platforms, dates, desired outcomes...">{{ old('description', $customerRequest->description) }}</textarea>
      </div>

      <div class="flex items-center gap-3">
        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
          <i class="fa-regular fa-floppy-disk mr-2"></i> Save Changes
        </button>
        <a href="{{ route('customer.requests.show', $customerRequest) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-100 text-gray-800 hover:bg-gray-200">
          View Details
        </a>
      </div>
    </form>

    <aside class="bg-white rounded-xl shadow p-6 space-y-4">
      <div>
        <div class="text-sm text-gray-500">Status</div>
        <div class="mt-1 inline-block px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">{{ \Illuminate\Support\Str::headline($customerRequest->status) }}</div>
      </div>
      <div>
        <div class="text-sm text-gray-500">Created</div>
        <div class="mt-1 text-gray-700">{{ $customerRequest->created_at->format('Y-m-d H:i') }}</div>
      </div>
      <div>
        <div class="text-sm text-gray-500">Last Updated</div>
        <div class="mt-1 text-gray-700">{{ $customerRequest->updated_at->format('Y-m-d H:i') }}</div>
      </div>
    </aside>
  </div>
</div>
@endsection
