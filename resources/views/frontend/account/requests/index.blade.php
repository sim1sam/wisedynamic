@php($title = 'My Requests')
@extends('layouts.customer')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="max-w-5xl mx-auto space-y-4">
    @if(session('success'))
        <div class="p-3 rounded bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">My Requests</h1>
        <a href="{{ route('account.requests.create') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
            <i class="fa-solid fa-plus mr-2"></i> New Request
        </a>
    </div>

    @if($requests->count() === 0)
        <div class="p-6 bg-white rounded shadow-sm text-gray-600">No requests yet. Create your first one.</div>
    @else
        <div class="bg-white rounded shadow-sm overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Title</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($requests as $req)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">
                                <div class="font-medium text-gray-900">{{ $req->title }}</div>
                                @if($req->description)
                                    <div class="text-sm text-gray-600">{{ Str::limit($req->description, 120) }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @php
                                    $map = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'done' => 'bg-green-100 text-green-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded {{ $map[$req->status] ?? 'bg-gray-100 text-gray-800' }}">{{ Str::headline($req->status) }}</span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">{{ $req->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $requests->links() }}</div>
    @endif
</div>

<!-- Create Request Modal -->
<div id="create-request-modal" class="fixed inset-0 z-50 items-center justify-center px-4 {{ isset($showCreateModal) && $showCreateModal ? 'flex' : 'hidden' }}">
  <div class="absolute inset-0 bg-black/40" data-close-modal></div>
  <div class="relative w-full max-w-xl bg-white rounded shadow-lg" role="dialog" aria-modal="true">
    <div class="flex items-center justify-between px-4 py-3 border-b">
      <h2 class="font-semibold">New Request</h2>
      <button class="w-9 h-9 inline-flex items-center justify-center rounded hover:bg-gray-100" aria-label="Close" data-close-modal>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="create-request-form" method="POST" action="{{ route('account.requests.store') }}" class="p-4 space-y-4">
      @csrf
      <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. Need a new landing page" />
      </div>
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Add details to help us understand your request">{{ old('description') }}</textarea>
      </div>
      <div class="flex items-center justify-end gap-3 pt-2">
        <button type="button" class="text-gray-600 hover:text-gray-800" data-close-modal>Cancel</button>
        <button id="create-request-submit" type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Submit
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
(function(){
  const modal = document.getElementById('create-request-modal');
  if(!modal) return;
  const closeEls = modal.querySelectorAll('[data-close-modal]');
  const close = ()=> modal.classList.add('hidden');
  closeEls.forEach(el=> el.addEventListener('click', close));
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') close(); });

  // Ensure submit triggers even if something intercepts clicks
  const form = document.getElementById('create-request-form');
  const submitBtn = document.getElementById('create-request-submit');
  if(form && submitBtn){
    submitBtn.addEventListener('click', function(){
      try {
        console.debug('[Requests] Submitting form');
        form.submit();
      } catch (e) {
        console.error('[Requests] Submit error', e);
      }
    });
  }
})();
</script>
@endpush
@endsection
