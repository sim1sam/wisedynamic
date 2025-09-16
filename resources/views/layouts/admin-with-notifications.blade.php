@extends('adminlte::page')

@section('adminlte_css_pre')
@stop

@section('adminlte_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        // Add notification dropdown to navbar
        $(document).ready(function() {
            // Find the navbar and add notification dropdown
            const navbar = $('.main-header .navbar .navbar-nav.ml-auto');
            if (navbar.length) {
                const notificationHtml = `
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#" id="notification-bell">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-danger navbar-badge" id="notification-count" style="display: none;">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification-dropdown">
                            <span class="dropdown-item dropdown-header" id="notification-header">0 Notifications</span>
                            <div class="dropdown-divider"></div>
                            <div id="notification-list">
                                <!-- Notifications will be loaded here -->
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('admin.notifications.index') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer" id="mark-all-read">Mark All as Read</a>
                        </div>
                    </li>
                `;
                
                // Insert before the fullscreen widget
                const fullscreenWidget = navbar.find('.nav-item').last();
                if (fullscreenWidget.length) {
                    fullscreenWidget.before(notificationHtml);
                } else {
                    navbar.append(notificationHtml);
                }
                
                // Initialize notification functionality
                initializeNotifications();
            }
        });
        
        function initializeNotifications() {
            // Load notifications on page load
            loadNotifications();
            
            // Refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);
            
            // Mark notification as read when clicked
            $(document).on('click', '.notification-item', function(e) {
                e.preventDefault();
                const notificationId = $(this).data('id');
                const url = $(this).attr('href');
                
                if (notificationId) {
                    markAsRead(notificationId, function() {
                        window.location.href = url;
                    });
                } else {
                    window.location.href = url;
                }
            });
            
            // Mark all as read
            $('#mark-all-read').click(function(e) {
                e.preventDefault();
                markAllAsRead();
            });
        }
        
        function loadNotifications() {
            $.get('{{ route("admin.notifications.api") }}', function(data) {
                updateNotificationUI(data);
            }).fail(function() {
                console.error('Failed to load notifications');
            });
        }
        
        function updateNotificationUI(data) {
            const count = data.unread_count;
            const notifications = data.notifications;
            
            // Update count badge
            if (count > 0) {
                $('#notification-count').text(count).show();
                $('#notification-header').text(count + ' Notification' + (count > 1 ? 's' : ''));
            } else {
                $('#notification-count').hide();
                $('#notification-header').text('No New Notifications');
            }
            
            // Update notification list
            const listContainer = $('#notification-list');
            listContainer.empty();
            
            if (notifications.length === 0) {
                listContainer.append('<div class="dropdown-item text-muted text-center">No new notifications</div>');
            } else {
                notifications.forEach(function(notification) {
                    const timeAgo = moment(notification.created_at).fromNow();
                    const item = `
                        <a href="${notification.url || '#'}" class="dropdown-item notification-item" data-id="${notification.id}">
                            <i class="fas fa-${getNotificationIcon(notification.type)} mr-2"></i>
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    ${notification.title}
                                    <span class="float-right text-sm text-muted">${timeAgo}</span>
                                </h3>
                                <p class="text-sm text-muted">${notification.message}</p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                    `;
                    listContainer.append(item);
                });
            }
        }
        
        function getNotificationIcon(type) {
            const icons = {
                'package_order': 'box',
                'service_order': 'cogs',
                'fund_request': 'wallet',
                'custom_service': 'tools',
                'message': 'envelope',
                'customer_request': 'clipboard-list'
            };
            return icons[type] || 'bell';
        }
        
        function markAsRead(notificationId, callback) {
            $.post('{{ route("admin.notifications.read", ":id") }}'.replace(':id', notificationId), {
                _token: '{{ csrf_token() }}'
            }, function() {
                loadNotifications();
                if (callback) callback();
            }).fail(function() {
                console.error('Failed to mark notification as read');
                if (callback) callback();
            });
        }
        
        function markAllAsRead() {
            $.post('{{ route("admin.notifications.read-all") }}', {
                _token: '{{ csrf_token() }}'
            }, function() {
                loadNotifications();
            }).fail(function() {
                console.error('Failed to mark all notifications as read');
            });
        }
    </script>
    
    <style>
        .notification-item {
            white-space: normal !important;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        .dropdown-item-title {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
    </style>
@stop