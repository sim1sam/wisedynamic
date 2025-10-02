@extends('adminlte::page')

@section('title', 'Package Categories')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Package Categories</h1>
        <a href="{{ route('admin.package-categories.create') }}" class="btn btn-primary">Add New Category</a>
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
                        <th>Icon</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Packages</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td><i class="{{ $category->icon }}"></i> {{ $category->icon }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                @if($category->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $category->packages->count() }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.package-categories.edit', ['package_category' => $category]) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.package-categories.destroy', ['package_category' => $category]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No package categories found.</td>
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
            });
        });
    </script>
@stop