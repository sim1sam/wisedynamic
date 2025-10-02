@extends('layouts.admin-with-notifications')

@section('title', 'Notifications')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Notifications</h1>
        <div>
            <button class="btn btn-primary" id="mark-all-read-btn">
                <i class="fas fa-check"></i> Mark All as Read
            </button>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <div class="list-group-item {{ $notification->is_read ? '' : 'list-group-item-warning' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-{{ getNotificationIcon($notification->type) }} mr-3 text-{{ getNotificationColor($notification->type) }}"></i>
                                    <div>
                                        <h5 class="mb-1">{{ $notification->title }}</h5>
                                        <p class="mb-1">{{ $notification->message }}</p>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    @if($notification->url)
                                        <a href="{{ $notification->url }}" class="btn btn-sm btn-outline-primary mr-2">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    @endif
                                    @if(!$notification->is_read)
                                        <button class="btn btn-sm btn-success mark-read-btn" data-id="{{ $notification->id }}">
                                            <i class="fas fa-check"></i> Mark Read
                                        </button>
                                    @else
                                        <span class="badge badge-success">Read</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Notifications</h4>
                    <p class="text-muted">You don't have any notifications yet.</p>
                </div>
            @endif
        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Mark individual notification as read
    $('.mark-read-btn').click(function() {
        const notificationId = $(this).data('id');
        const button = $(this);
        
        $.post('{{ route("admin.notifications.read", ":id") }}'.replace(':id', notificationId), {
            _token: '{{ csrf_token() }}'
        }, function() {
            button.closest('.list-group-item').removeClass('list-group-item-warning');
            button.replaceWith('<span class="badge badge-success">Read</span>');
        }).fail(function() {
            alert('Failed to mark notification as read');
        });
    });
    
    // Mark all notifications as read
    $('#mark-all-read-btn').click(function() {
        $.post('{{ route("admin.notifications.read-all") }}', {
            _token: '{{ csrf_token() }}'
        }, function() {
            location.reload();
        }).fail(function() {
            alert('Failed to mark all notifications as read');
        });
    });
});
 </script>
 @stop

@php
function getNotificationIcon($type) {
    $icons = [
        'package_order' => 'box',
        'service_order' => 'cogs',
        'fund_request' => 'wallet',
        'custom_service' => 'tools',
        'message' => 'envelope',
        'customer_request' => 'clipboard-list'
    ];
    return $icons[$type] ?? 'bell';
}

function getNotificationColor($type) {
    $colors = [
        'package_order' => 'primary',
        'service_order' => 'info',
        'fund_request' => 'success',
        'custom_service' => 'warning',
        'message' => 'secondary',
        'customer_request' => 'dark'
    ];
    return $colors[$type] ?? 'primary';
}
@endphp