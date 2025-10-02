@extends('adminlte::page')

@section('title', 'Customers')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Customers</h1>
        <div>
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Customer
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? '—' }}</td>
                            <td>
                                @if($customer->status === 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($customer->status === 'blocked')
                                    <span class="badge badge-danger">Blocked</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($customer->status ?? 'Unknown') }}</span>
                                @endif
                            </td>
                            <td>{{ $customer->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @if($customer->status === 'active')
                                        <form action="{{ route('admin.customers.update-status', $customer) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="blocked">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to block this customer?')">
                                                <i class="fas fa-ban"></i> Block
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.customers.update-status', $customer) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to activate this customer?')">
                                                <i class="fas fa-check"></i> Activate
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <style>
        .btn-group .btn {
            margin-right: 5px;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "order": [[0, "desc"]]
            });
        });
    </script>
@stop