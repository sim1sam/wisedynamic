@extends('adminlte::page')

@section('title', 'Package Details')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>{{ $package->title }}</h1>
        <div>
            <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                <i class="fas fa-list"></i> Back to List
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Package Details</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        @if($package->image)
                            <div class="col-md-4">
                                <img src="{{ asset('storage/' . $package->image) }}" class="img-fluid rounded" alt="{{ $package->title }}">
                            </div>
                        @endif
                        <div class="col-md-{{ $package->image ? '8' : '12' }}">
                            <h4>{{ $package->title }}</h4>
                            <p class="text-muted">{{ $package->short_description }}</p>
                            
                            @if($package->price)
                                <div class="mb-3">
                                    <strong>Price:</strong> {{ $package->price_unit ?? '$' }} {{ number_format($package->price, 2) }}
                                    @if($package->price_unit)
                                        <span class="text-muted">per {{ $package->price_unit }}</span>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <strong>Category:</strong> {{ $package->category->name ?? 'Uncategorized' }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Slug:</strong> {{ $package->slug }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Status:</strong>
                                @if($package->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <strong>Featured:</strong>
                                @if($package->featured)
                                    <span class="badge badge-info">Yes</span>
                                @else
                                    <span class="badge badge-light">No</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Full Description</h5>
                        <div class="p-3 bg-light rounded">
                            {!! $package->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Meta Information</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Created</dt>
                        <dd class="col-sm-8">{{ $package->created_at->format('Y-m-d H:i') }}</dd>
                        
                        <dt class="col-sm-4">Updated</dt>
                        <dd class="col-sm-8">{{ $package->updated_at->format('Y-m-d H:i') }}</dd>
                        
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8">{{ $package->id }}</dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
