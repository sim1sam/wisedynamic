<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomServiceRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'service_type',
        'total_amount',
        'payment_method',
        'status',
        'ssl_transaction_id',
        'ssl_response',
        'admin_notes',
        'started_at',
        'completed_at',
        'assigned_to',
    ];
    
    protected $casts = [
        'ssl_response' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    
    // Service type constants
    const TYPE_MARKETING = 'marketing';
    const TYPE_WEB_APP = 'web_app';
    
    // Payment method constants
    const PAYMENT_BALANCE = 'balance';
    const PAYMENT_SSL = 'ssl';
    
    /**
     * Get the user who made the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the admin assigned to this request.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    /**
     * Get the service items for this request.
     */
    public function items()
    {
        return $this->hasMany(CustomServiceItem::class);
    }
    
    /**
     * Get the transaction associated with this request.
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'custom_service_request_id');
    }
    
    /**
     * Scope for pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope for in progress requests.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }
    
    /**
     * Scope for completed requests.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
    
    /**
     * Scope for marketing services.
     */
    public function scopeMarketing($query)
    {
        return $query->where('service_type', self::TYPE_MARKETING);
    }
    
    /**
     * Scope for web/app services.
     */
    public function scopeWebApp($query)
    {
        return $query->where('service_type', self::TYPE_WEB_APP);
    }
    
    /**
     * Get the service type label.
     */
    public function getServiceTypeLabel()
    {
        return match($this->service_type) {
            self::TYPE_MARKETING => 'Marketing Services',
            self::TYPE_WEB_APP => 'Web/App Development',
            default => 'Unknown'
        };
    }
    
    /**
     * Get the status label.
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Unknown'
        };
    }
    
    /**
     * Get the status color class.
     */
    public function getStatusColorClass()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_IN_PROGRESS => 'info',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
            default => 'secondary'
        };
    }
}
