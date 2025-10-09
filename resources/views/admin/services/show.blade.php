@extends('adminlte::page')

@section('title', 'Service Details')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Service Details</h1>
        <div>
            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-info">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
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

            <div class="row">
                <div class="col-md-4">
                    @if($service->image)
        <img src="{{ asset($service->image) }}" alt="{{ $service->title }}" class="img-fluid rounded mb-3">
                    @else
                        <div class="bg-light text-center py-5 mb-3 rounded">
                            <i class="fas fa-image fa-4x text-secondary"></i>
                            <p class="mt-2">No image available</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Title</th>
                            <td>{{ $service->title }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $service->category->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td>
                                @if($service->price)
                                    BDT {{ number_format($service->price) }}{{ $service->price_unit ? '/'.$service->price_unit : '' }}
                                @else
                                    Contact for pricing
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($service->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Featured</th>
                            <td>
                                @if($service->featured)
                                    <span class="badge badge-info">Featured</span>
                                @else
                                    <span class="badge badge-light">No</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Slug</th>
                            <td>{{ $service->slug }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $service->created_at->format('F d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $service->updated_at->format('F d, Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <h4>Short Description</h4>
                <div class="p-3 bg-light rounded">
                    {{ $service->short_description }}
                </div>
            </div>

            <div class="mt-4">
                <h4>Full Description</h4>
                <div class="p-3 bg-light rounded">
                    {!! $service->description !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th {
            background-color: #f8f9fa;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Any JavaScript you need can go here
        });
    </script>
@stop
