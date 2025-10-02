<!-- Direct notification implementation -->
<style>
.notification-icon {
    position: fixed;
    top: 15px;
    right: 180px;
    z-index: 1050;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 8px 12px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.notification-icon .nav-link {
    color: #495057;
    padding: 0;
}
.notification-icon .nav-link:hover {
    color: #007bff;
}
</style>

<div class="notification-icon">
    <a class="nav-link" href="#" id="notification-bell" data-toggle="dropdown">
        <i class="far fa-bell"></i>
        <span class="badge badge-danger" id="notification-count" style="display: none;">0</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right" id="notification-dropdown">
        <h6 class="dropdown-header" id="notification-header">0 Notifications</h6>
        <div class="dropdown-divider"></div>
        <div id="notification-list">
            <div class="dropdown-item text-muted text-center">Loading...</div>
        </div>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item dropdown-footer" href="/admin/notifications">See All Notifications</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item dropdown-footer" href="#" id="mark-all-read">Mark All as Read</a>
    </div>
</div>

<script>
$(document).ready(function() {
    console.log('Direct notification implementation loading...');
    
    // Initialize dropdown functionality
    $('#notification-bell').dropdown();
    
    // Load notifications
    loadNotifications();
    
    // Refresh every 30 seconds
    setInterval(loadNotifications, 30000);
    
    // Event handlers
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
    
    $('#mark-all-read').click(function(e) {
        e.preventDefault();
        markAllAsRead();
    });
});

function loadNotifications() {
    $.get('/admin/notifications/api', function(data) {
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
            const timeAgo = moment ? moment(notification.created_at).fromNow() : 'Recently';
            const item = `
                <a href="${notification.url || '#'}" class="dropdown-item notification-item" data-id="${notification.id}">
                    <i class="fas fa-${getNotificationIcon(notification.type)} mr-2"></i>
                    <div>
                        <strong>${notification.title}</strong>
                        <small class="float-right text-muted">${timeAgo}</small>
                        <br>
                        <small class="text-muted">${notification.message}</small>
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
    $.post('/admin/notifications/' + notificationId + '/read', {
        _token: $('meta[name="csrf-token"]').attr('content')
    }, function() {
        loadNotifications();
        if (callback) callback();
    }).fail(function() {
        console.error('Failed to mark notification as read');
        if (callback) callback();
    });
}

function markAllAsRead() {
    $.post('/admin/notifications/read-all', {
        _token: $('meta[name="csrf-token"]').attr('content')
    }, function() {
        loadNotifications();
    }).fail(function() {
        console.error('Failed to mark all notifications as read');
    });
}
</script><?php /**PATH F:\laragon\www\wisedynamic\resources\views/admin/partials/notification-test.blade.php ENDPATH**/ ?>