<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Package;

class PackageOrder extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'package_id',
        'package_name',
        'amount',
        'full_name',
        'email',
        'phone',
        'company',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'website_name',
        'website_type',
        'page_count',
        'page_url',
        'notes',
        'status',
        'paid_amount',
        'due_amount',
        'total_installments',
        'current_installment',
        'payment_history',
    ];
    
    protected $casts = [
        'payment_history' => 'array',
    ];
    
    /**
     * Get the package associated with this order.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    
    /**
     * Get the transactions for this package order.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
