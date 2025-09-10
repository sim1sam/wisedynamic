<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',
        'service_name',
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
        'project_name',
        'project_type',
        'requirements',
        'notes',
        'status',
        'paid_amount',
        'due_amount',
        'total_installments',
        'current_installment',
        'payment_history',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_history' => 'array',
    ];
    
    /**
     * Get the service associated with this order.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    /**
     * Get the transactions for this service order.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'service_order_id');
    }
}
