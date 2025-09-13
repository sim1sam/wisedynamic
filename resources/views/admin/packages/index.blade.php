@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Packages</h1>
        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">Add New Package</a>
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
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td>{{ $package->id }}</td>
                            <td>{{ $package->title }}</td>
                            <td>{{ $package->category->name ?? 'N/A' }}</td>
                            <td>
                                @if($package->price)
                                    BDT {{ number_format($package->price) }}{{ $package->price_unit ? '/'.$package->price_unit : '' }}
                                @else
                                    Contact for pricing
                                @endif
                            </td>
                            <td>
                                @if($package->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($package->featured)
                                    <span class="badge badge-info">Featured</span>
                                @else
                                    <span class="badge badge-light">No</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.packages.slug', $package->slug) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.packages.edit', ['package' => $package]) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.packages.destroy', ['package' => $package]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');" class="d-inline">
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
                            <td colspan="7" class="text-center">No packages found.</td>
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
