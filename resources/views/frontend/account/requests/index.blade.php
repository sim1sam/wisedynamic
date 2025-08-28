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
@endsection
