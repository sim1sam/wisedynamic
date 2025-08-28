@php $title = 'My Requests'; @endphp
@extends('layouts.customer')

@section('content')
<div class="w-full px-4 md:px-6 space-y-6">
    @if(session('success'))
        <div class="p-3 rounded bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif

    <!-- Page Header / Breadcrumbs -->
    <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">My Requests</h1>
                <p class="text-sm text-gray-600">Track and manage your service requests</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('customer.requests.create') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow">
                    <i class="fa-solid fa-plus mr-2"></i> New Request
                </a>
            </div>
        </div>
        
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

    <!-- Toolbar: Filters + Search -->
    <form method="GET" action="{{ route('customer.requests.index') }}" class="flex flex-col lg:flex-row lg:items-center gap-3 bg-white rounded-md shadow-sm p-3">
        <div class="flex items-center gap-2 overflow-x-auto">
            @php $curr = $status; @endphp
            @php $allActive = empty($curr); @endphp
            @php $pendingActive = ($curr == 'pending'); @endphp
            @php $progressActive = ($curr == 'in_progress'); @endphp
            @php $doneActive = ($curr == 'done'); @endphp
            <a href="{{ route('customer.requests.index', ['status'=>null,'q'=>$q]) }}" class="px-3 py-2 text-xs md:text-sm rounded-full border {{ $allActive ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200' }}">All ({{ $counts['all'] ?? 0 }})</a>
            <a href="{{ route('customer.requests.index', ['status'=>'pending','q'=>$q]) }}" class="px-3 py-2 text-xs md:text-sm rounded-full border {{ $pendingActive ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200' }}">Pending ({{ $counts['pending'] ?? 0 }})</a>
            <a href="{{ route('customer.requests.index', ['status'=>'in_progress','q'=>$q]) }}" class="px-3 py-2 text-xs md:text-sm rounded-full border {{ $progressActive ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200' }}">In Progress ({{ $counts['in_progress'] ?? 0 }})</a>
            <a href="{{ route('customer.requests.index', ['status'=>'done','q'=>$q]) }}" class="px-3 py-2 text-xs md:text-sm rounded-full border {{ $doneActive ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200' }}">Done ({{ $counts['done'] ?? 0 }})</a>
        </div>
        <div class="flex items-center gap-2 lg:ml-auto">
            <input type="hidden" name="status" value="{{ $status }}" />
            <input type="text" name="q" value="{{ $q }}" placeholder="Search page name, social media, or post link" class="w-64 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
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
        <div class="bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="sticky top-0 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50">ID</th>
                        <th class="sticky top-0 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50">Page Name</th>
                        <th class="sticky top-0 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50">Social Media</th>
                        <th class="sticky top-0 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50">Budget (BDT)</th>
                        <th class="sticky top-0 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50">Days</th>
                        <th class="sticky top-0 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50">Post Link</th>
                        <th class="sticky top-0 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50">Status</th>
                        <th class="sticky top-0 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50">Created</th>
                        <th class="sticky top-0 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50">Updated</th>
                        <th class="sticky top-0 px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $req)
                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
                            <td class="px-4 py-3 text-sm text-gray-600">#{{ $req->id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $req->page_name }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $req->social_media }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ number_format((float)$req->ads_budget_bdt, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $req->days }}</td>
                            <td class="px-4 py-3 text-sm text-blue-700">
                                @if($req->post_link)
                                    <a href="{{ $req->post_link }}" target="_blank" rel="noopener">Open</a>
                                @else
                                    -
                                @endif
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
                                    <a href="{{ route('customer.requests.show', $req) }}" class="inline-flex items-center px-2 py-1 text-xs md:text-sm rounded bg-gray-100 hover:bg-gray-200" title="View">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customer.requests.edit', $req) }}" class="inline-flex items-center px-2 py-1 text-xs md:text-sm rounded bg-blue-100 text-blue-700 hover:bg-blue-200" title="Edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('customer.requests.destroy', $req) }}" onsubmit="return confirm('Delete this request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 text-xs md:text-sm rounded bg-red-100 text-red-700 hover:bg-red-200" title="Delete">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-600">No matching requests.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
        <div class="mt-3">{{ $requests->links() }}</div>
    @endif
</div>
@endsection
