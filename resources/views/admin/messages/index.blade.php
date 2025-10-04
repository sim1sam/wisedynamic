@extends('adminlte::page')

@section('title', 'Customer Messages')

@section('content_header')
    <h1>Customer Messages</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Messages</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table id="messagesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr>
                            <td>{{ $message->id }}</td>
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                            <td>{{ $message->subject }}</td>
                            <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                @if($message->is_read)
                                    <span class="badge badge-success">Read</span>
                                @else
                                    <span class="badge badge-warning">Unread</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No messages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            
        </div>
    </div>
@stop

@section('css')
    <style>
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#messagesTable').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true,
                order: [[0, 'desc']]
            });
        });
    </script>
@stop