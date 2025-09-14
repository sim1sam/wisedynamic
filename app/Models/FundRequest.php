<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'amount',
        'service_info',
        'payment_method',
        'bank_name',
        'account_number',
        'payment_screenshot',
        'ssl_transaction_id',
        'ssl_response',
        'status',
        'admin_notes',
        'approved_at',
        'approved_by',
    ];
    
    protected $casts = [
        'ssl_response' => 'array',
        'approved_at' => 'datetime',
    ];
    
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    
    // Payment method constants
    const PAYMENT_SSL = 'ssl';
    const PAYMENT_MANUAL = 'manual';
    
    /**
     * Get the user who made the fund request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the admin who approved the request.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    /**
     * Scope for pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope for approved requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
    
    /**
     * Get the transaction associated with this fund request.
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
