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
        'ad_budget',
        'notes',
        'status',
    ];
    
    /**
     * Get the package associated with this order.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
