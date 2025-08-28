@php($title = 'View Request')
@extends('layouts.customer')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <h1 class="text-xl font-semibold">Request Details</h1>
    <div class="flex items-center gap-2">
      <a href="{{ route('customer.requests.index') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back
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

  <div class="bg-white rounded shadow-sm p-5 space-y-4">
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
    <div class="grid grid-cols-2 gap-4">
      <div>
        <div class="text-sm text-gray-500">Status</div>
        <div class="inline-block mt-1 px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">{{ \Illuminate\Support\Str::headline($customerRequest->status) }}</div>
      </div>
      <div>
        <div class="text-sm text-gray-500">Created</div>
        <div class="mt-1 text-gray-700">{{ $customerRequest->created_at->format('Y-m-d H:i') }}</div>
      </div>
    </div>
  </div>
</div>
@endsection
