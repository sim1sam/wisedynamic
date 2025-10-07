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
        'payment_status',
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
        switch($this->service_type) {
            case self::TYPE_MARKETING:
                return 'Marketing Services';
            case self::TYPE_WEB_APP:
                return 'Web/App Development';
            default:
                return 'Unknown';
        }
    }
    
    /**
     * Get the status label.
     */
    public function getStatusLabel()
    {
        switch($this->status) {
            case self::STATUS_PENDING:
                return 'Pending';
            case self::STATUS_IN_PROGRESS:
                return 'In Progress';
            case self::STATUS_COMPLETED:
                return 'Completed';
            case self::STATUS_CANCELLED:
                return 'Cancelled';
            default:
                return 'Unknown';
        }
    }
    
    /**
     * Get the status color class.
     */
    public function getStatusColorClass()
    {
        switch($this->status) {
            case self::STATUS_PENDING:
                return 'warning';
            case self::STATUS_IN_PROGRESS:
                return 'info';
            case self::STATUS_COMPLETED:
                return 'success';
            case self::STATUS_CANCELLED:
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
