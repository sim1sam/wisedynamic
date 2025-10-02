<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'transaction_number',
        'package_order_id',
        'service_order_id',
        'fund_request_id',
        'custom_service_request_id',
        'amount',
        'payment_method',
        'status',
        'notes',
        'admin_notes',
        'updated_by_admin',
        'admin_updated_at',
        // SSL Payment specific fields
        'ssl_transaction_id',
        'ssl_session_id',
        'ssl_amount',
        'ssl_currency',
        'ssl_currency_amount',
        'ssl_card_type',
        'ssl_card_no',
        'ssl_bank_transaction_id',
        'ssl_status',
        'ssl_fail_reason',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'order_type',
        'order_details',
        'ssl_response_data',
    ];

    protected $casts = [
        'order_details' => 'array',
        'ssl_response_data' => 'array',
        'amount' => 'decimal:2',
        'ssl_amount' => 'decimal:2',
        'ssl_currency_amount' => 'decimal:2',
        'admin_updated_at' => 'datetime',
    ];

    /**
     * Get the package order associated with this transaction.
     */
    public function packageOrder()
    {
        return $this->belongsTo(PackageOrder::class);
    }
    
    /**
     * Get the service order associated with this transaction.
     */
    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }
    
    /**
     * Get the fund request associated with this transaction.
     */
    public function fundRequest()
    {
        return $this->belongsTo(FundRequest::class);
    }
    
    /**
     * Get the custom service request associated with this transaction.
     */
    public function customServiceRequest()
    {
        return $this->belongsTo(CustomServiceRequest::class);
    }

    /**
     * Relationship with admin user who updated the transaction
     */
    public function updatedByAdmin()
    {
        return $this->belongsTo(User::class, 'updated_by_admin');
    }

    /**
     * Generate a unique transaction number
     */
    public static function generateTransactionNumber()
    {
        do {
            $transactionNumber = 'TXN' . strtoupper(Str::random(10));
        } while (self::where('transaction_number', $transactionNumber)->exists());

        return $transactionNumber;
    }
    
    /**
     * Check if this is an SSL transaction
     */
    public function isSSLTransaction()
    {
        return $this->payment_method === 'SSL Payment' && !empty($this->ssl_transaction_id);
    }
    
    /**
     * Get customer information for SSL transactions
     */
    public function getCustomerInfo()
    {
        if (!$this->isSSLTransaction()) {
            return null;
        }

        return [
            'name' => $this->customer_name,
            'email' => $this->customer_email,
            'phone' => $this->customer_phone,
            'address' => $this->customer_address,
        ];
    }
    
    /**
     * Get SSL transaction details
     */
    public function getSSLDetails()
    {
        if (!$this->isSSLTransaction()) {
            return null;
        }

        return [
            'ssl_transaction_id' => $this->ssl_transaction_id,
            'ssl_session_id' => $this->ssl_session_id,
            'ssl_amount' => $this->ssl_amount,
            'ssl_currency' => $this->ssl_currency,
            'ssl_currency_amount' => $this->ssl_currency_amount,
            'ssl_card_type' => $this->ssl_card_type,
            'ssl_card_no' => $this->ssl_card_no,
            'ssl_bank_transaction_id' => $this->ssl_bank_transaction_id,
            'ssl_status' => $this->ssl_status,
            'ssl_fail_reason' => $this->ssl_fail_reason,
        ];
    }

    /**
     * Get all available status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /**
     * Check if transaction is successful
     */
    public function isSuccessful()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if transaction is failed
     */
    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Check if transaction is pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case self::STATUS_COMPLETED:
                return 'badge-success';
            case self::STATUS_FAILED:
                return 'badge-danger';
            case self::STATUS_PENDING:
                return 'badge-warning';
            case self::STATUS_CANCELLED:
                return 'badge-secondary';
            default:
                return 'badge-light';
        }
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayName()
    {
        $options = self::getStatusOptions();
        return $options[$this->status] ?? ucfirst($this->status);
    }
}
