<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'url',
        'is_read',
        'related_id',
        'related_type'
    ];
    
    protected $casts = [
        'is_read' => 'boolean',
    ];
    
    /**
     * Create a new notification
     */
    public static function createNotification($type, $title, $message, $url = null, $relatedId = null, $relatedType = null)
    {
        return self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
        ]);
    }
    
    /**
     * Get unread notifications count
     */
    public static function getUnreadCount()
    {
        return self::where('is_read', false)->count();
    }
    
    /**
     * Get recent unread notifications
     */
    public static function getRecentUnread($limit = 10)
    {
        return self::where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
    
    /**
     * Mark all notifications as read
     */
    public static function markAllAsRead()
    {
        self::where('is_read', false)->update(['is_read' => true]);
    }
}
