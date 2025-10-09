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
        switch($this->status) {
            case self::STATUS_PENDING:
                return 'Pending Verification';
            case self::STATUS_APPROVED:
                return 'Approved';
            case self::STATUS_REJECTED:
                return 'Rejected';
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
            case self::STATUS_APPROVED:
                return 'success';
            case self::STATUS_REJECTED:
                return 'danger';
            default:
                return 'secondary';
        }
    }
    
    /**
     * Get the payment screenshot URL.
     */
    public function getScreenshotUrl()
    {
        return asset($this->payment_screenshot);
    }
}
