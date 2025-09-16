<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get notifications for admin panel
     */
    public function getNotifications()
    {
        $notifications = Notification::getRecentUnread(10);
        $unreadCount = Notification::getUnreadCount();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::markAllAsRead();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Show all notifications page
     */
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.notifications.index', compact('notifications'));
    }
}
