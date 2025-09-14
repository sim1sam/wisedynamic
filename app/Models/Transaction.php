<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PackageOrder;

class Transaction extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_number',
        'package_order_id',
        'service_order_id',
        'fund_request_id',
        'amount',
        'payment_method',
        'status',
        'notes',
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
     * Generate a unique transaction number.
     *
     * @return string
     */
    public static function generateTransactionNumber()
    {
        $prefix = 'TRX';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        
        return $prefix . $date . $random;
    }
}
