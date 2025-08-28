@php($title = 'View Request')
@extends('layouts.customer')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
  <div class="flex items-start justify-between gap-3">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Request Details</h1>
      <p class="text-sm text-gray-600 mt-1">Full information for your request.</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('customer.requests.index') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back to list
      </a>
      <a href="{{ route('customer.requests.edit', $customerRequest) }}" class="inline-flex items-center px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
        <i class="fa-regular fa-pen-to-square mr-2"></i> Edit
      </a>
      <form method="POST" action="{{ route('customer.requests.destroy', $customerRequest) }}" onsubmit="return confirm('Delete this request?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center px-3 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
          <i class="fa-regular fa-trash-can mr-2"></i> Delete
        </button>
      </form>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white rounded-xl shadow p-6 space-y-4">
      <div>
        <div class="text-sm text-gray-500">Title</div>
        <div class="text-lg font-medium text-gray-900">{{ $customerRequest->title }}</div>
      </div>
      @if($customerRequest->description)
      <div>
        <div class="text-sm text-gray-500">Description</div>
        <div class="whitespace-pre-line">{{ $customerRequest->description }}</div>
      </div>
      @endif
    </div>

    <aside class="bg-white rounded-xl shadow p-6 space-y-4">
      <div>
        <div class="text-sm text-gray-500">Status</div>
        <div class="inline-block mt-1 px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">{{ \Illuminate\Support\Str::headline($customerRequest->status) }}</div>
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
