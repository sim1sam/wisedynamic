@php $title = 'My Requests'; @endphp
@extends('layouts.customer')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    @if(session('success'))
        <div class="p-3 rounded bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif

    <!-- Header + CTA -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold">My Requests</h1>
            <p class="text-sm text-gray-600">Track and manage your service requests.</p>
        </div>
        <a href="{{ route('customer.requests.create') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
            <i class="fa-solid fa-plus mr-2"></i> New Request
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs text-gray-500">All</div>
            <div class="mt-1 text-2xl font-semibold">{{ $counts['all'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs text-gray-500">Pending</div>
            <div class="mt-1 text-2xl font-semibold">{{ $counts['pending'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs text-gray-500">In Progress</div>
            <div class="mt-1 text-2xl font-semibold">{{ $counts['in_progress'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs text-gray-500">Done</div>
            <div class="mt-1 text-2xl font-semibold">{{ $counts['done'] ?? 0 }}</div>
        </div>
    </div>

    <!-- Filters + Search -->
    <form method="GET" action="{{ route('customer.requests.index') }}" class="flex flex-col md:flex-row md:items-center gap-3">
        <div class="flex items-center gap-2 overflow-x-auto">
            @php $curr = $status; @endphp
            @php $allActive = empty($curr); @endphp
            @php $pendingActive = ($curr == 'pending'); @endphp
            @php $progressActive = ($curr == 'in_progress'); @endphp
            @php $doneActive = ($curr == 'done'); @endphp
            <a href="{{ route('customer.requests.index', ['status'=>null,'q'=>$q]) }}" class="px-3 py-2 text-sm rounded-full {{ $allActive ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">All ({{ $counts['all'] ?? 0 }})</a>
            <a href="{{ route('customer.requests.index', ['status'=>'pending','q'=>$q]) }}" class="px-3 py-2 text-sm rounded-full {{ $pendingActive ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Pending ({{ $counts['pending'] ?? 0 }})</a>
            <a href="{{ route('customer.requests.index', ['status'=>'in_progress','q'=>$q]) }}" class="px-3 py-2 text-sm rounded-full {{ $progressActive ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">In Progress ({{ $counts['in_progress'] ?? 0 }})</a>
            <a href="{{ route('customer.requests.index', ['status'=>'done','q'=>$q]) }}" class="px-3 py-2 text-sm rounded-full {{ $doneActive ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Done ({{ $counts['done'] ?? 0 }})</a>
        </div>
        <div class="flex items-center gap-2 md:ml-auto">
            <input type="hidden" name="status" value="{{ $status }}" />
            <input type="text" name="q" value="{{ $q }}" placeholder="Search title or description" class="w-64 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
            <button class="px-3 py-2 rounded-md bg-gray-800 text-white hover:bg-gray-900">Search</button>
            @if($q)
                <a href="{{ route('customer.requests.index', ['status'=>$status]) }}" class="px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200">Clear</a>
            @endif
        </div>
    </form>

    <!-- List / Table -->
    @if($requests->count() === 0)
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-600">
            <div class="text-lg font-medium mb-2">No matching requests</div>
            <p class="mb-4">Try adjusting filters or create a new request.</p>
            <a href="{{ route('customer.requests.create') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                <i class="fa-solid fa-plus mr-2"></i> New Request
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Updated</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($requests as $req)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-600">#{{ $req->id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $req->title }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700" title="{{ $req->description }}">
                                {{ $req->description ? \Illuminate\Support\Str::limit($req->description, 120) : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $badgeClass = 'bg-gray-100 text-gray-800';
                                    if ($req->status == 'pending') $badgeClass = 'bg-yellow-100 text-yellow-800';
                                    elseif ($req->status == 'in_progress') $badgeClass = 'bg-blue-100 text-blue-800';
                                    elseif ($req->status == 'done') $badgeClass = 'bg-green-100 text-green-800';
                                @endphp
                                <span class="px-2 py-1 text-xs rounded {{ $badgeClass }}">{{ \Illuminate\Support\Str::headline($req->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $req->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $req->updated_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('customer.requests.show', $req) }}" class="inline-flex items-center px-2 py-1 text-sm rounded bg-gray-100 hover:bg-gray-200" title="View">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customer.requests.edit', $req) }}" class="inline-flex items-center px-2 py-1 text-sm rounded bg-blue-100 text-blue-700 hover:bg-blue-200" title="Edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('customer.requests.destroy', $req) }}" onsubmit="return confirm('Delete this request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 text-sm rounded bg-red-100 text-red-700 hover:bg-red-200" title="Delete">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-600">No matching requests.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $requests->links() }}</div>
    @endif
</div>
@endsection
