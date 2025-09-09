<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'price',
        'price_unit',
        'image',
        'status',
        'featured',
        'package_category_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'featured' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(PackageCategory::class, 'package_category_id');
    }
}
