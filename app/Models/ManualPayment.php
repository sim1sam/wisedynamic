<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualPayment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'payable_type',
        'payable_id',
        'user_id',
        'amount',
        'bank_name',
        'account_number',
        'transaction_id',
        'payment_screenshot',
        'status',
        'admin_notes',
        'verified_by',
        'verified_at',
    ];
    
    protected $casts = [
        'verified_at' => 'datetime',
    ];
    
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    
    /**
     * Get the owning payable model (PackageOrder or ServiceOrder).
     */
    public function payable()
    {
        return $this->morphTo();
    }
    
    /**
     * Get the user who made the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the admin who verified the payment.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
    
    /**
     * Scope for pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope for approved payments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
    
    /**
     * Scope for rejected payments.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }
    
    /**
     * Get the status label.
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending Verification',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
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
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            default => 'secondary'
        };
    }
    
    /**
     * Get the payment screenshot URL.
     */
    public function getScreenshotUrl()
    {
        return asset('storage/' . $this->payment_screenshot);
    }
}
