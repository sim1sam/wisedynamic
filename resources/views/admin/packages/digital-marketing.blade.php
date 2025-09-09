@extends('adminlte::page')

@section('title', 'Digital Marketing Packages')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Digital Marketing Packages</h1>
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

            <div class="mb-3">
                <h4>Category: {{ $category->name }}</h4>
                <p>{{ $category->description }}</p>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary mr-2">
                            <i class="fas fa-arrow-left"></i> All Packages
                        </a>
                        <a href="{{ url('/packages') }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i> View on Frontend
                        </a>
                    </div>
                    <div>
                        <button id="toggleView" class="btn btn-outline-info mb-2">
                            <i class="fas fa-th"></i> Toggle View
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table View -->
            <div id="tableView">
                <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
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
                            <td colspan="6" class="text-center">No digital marketing packages found.</td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </div>

            <!-- Card View -->
            <div id="cardView" class="row" style="display: none;">
                @forelse($packages as $package)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 {{ $package->featured ? 'border-primary' : '' }}">
                            @if($package->featured)
                                <div class="ribbon ribbon-top-right"><span>Featured</span></div>
                            @endif
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $package->title }}</h5>
                                @if($package->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="price-tag mb-3">
                                    @if($package->price)
                                        <h4>BDT {{ number_format($package->price) }}{{ $package->price_unit ? '/'.$package->price_unit : '' }}</h4>
                                    @else
                                        <h4>Contact for pricing</h4>
                                    @endif
                                </div>
                                <p class="card-text">{{ $package->short_description }}</p>
                                <div class="features-list">
                                    <strong>Features:</strong>
                                    <ul class="pl-3 mt-2">
                                        @foreach(explode("\n", $package->description) as $feature)
                                            @if(trim($feature) != '')
                                                <li>{{ trim($feature) }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="btn-group w-100">
                                    <a href="{{ route('admin.packages.edit', ['package' => $package]) }}" class="btn btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ url('/packages') }}" target="_blank" class="btn btn-success">
                                        <i class="fas fa-eye"></i> Preview
                                    </a>
                                    <form action="{{ route('admin.packages.destroy', ['package' => $package]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');" class="d-inline w-100">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No digital marketing packages found.</p>
                        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> Create Your First Package
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .btn-group .btn {
            margin-right: 5px;
        }
        
        /* Ribbon style for featured packages */
        .ribbon {
            width: 150px;
            height: 150px;
            overflow: hidden;
            position: absolute;
        }
        
        .ribbon-top-right {
            top: -10px;
            right: -10px;
        }
        
        .ribbon-top-right::before,
        .ribbon-top-right::after {
            border-top-color: transparent;
            border-right-color: transparent;
        }
        
        .ribbon-top-right::before {
            top: 0;
            left: 0;
        }
        
        .ribbon-top-right::after {
            bottom: 0;
            right: 0;
        }
        
        .ribbon-top-right span {
            position: absolute;
            top: 30px;
            right: -25px;
            transform: rotate(45deg);
            width: 150px;
            padding: 5px 0;
            background-color: #007bff;
            color: white;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
        }
        
        /* Card styles */
        .card-hover:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease-in-out;
        }
        
        .price-tag h4 {
            color: #007bff;
            font-weight: bold;
        }
        
        .features-list ul {
            list-style-type: none;
        }
        
        .features-list ul li:before {
            content: "✓";
            color: #28a745;
            margin-right: 8px;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            
            // Toggle between table and card view
            $('#toggleView').click(function() {
                if ($('#tableView').is(':visible')) {
                    $('#tableView').hide();
                    $('#cardView').show();
                    $(this).html('<i class="fas fa-table"></i> Table View');
                } else {
                    $('#tableView').show();
                    $('#cardView').hide();
                    $(this).html('<i class="fas fa-th"></i> Card View');
                }
            });
        });
    </script>
@stop
