@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    @if(session('success'))
        <div class="mb-3 p-3 rounded bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif
    <h1 class="text-2xl font-semibold mb-4">Customer Requests</h1>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Customer</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Title</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Description</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Created</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($requests as $req)
                    <tr class="align-top">
                        <td class="px-4 py-2 text-sm text-gray-800">
                            <div class="font-medium">{{ $req->user->name ?? 'N/A' }}</div>
                            <div class="text-gray-600">{{ $req->user->email ?? '' }}</div>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-800">{{ $req->title }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $req->description }}</td>
                        <td class="px-4 py-2 text-sm">
                            <span class="px-2 py-1 text-xs rounded bg-gray-100">{{ \Illuminate\Support\Str::headline($req->status) }}</span>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-600">{{ $req->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2 text-sm">
                            <form method="POST" action="{{ route('admin.requests.status', $req) }}" class="inline-flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="pending" @selected($req->status==='pending')>Pending</option>
                                    <option value="in_progress" @selected($req->status==='in_progress')>In Progress</option>
                                    <option value="done" @selected($req->status==='done')>Done</option>
                                </select>
                                <button class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700">Update</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-gray-600" colspan="6">No requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $requests->links() }}</div>
</div>
@endsection
