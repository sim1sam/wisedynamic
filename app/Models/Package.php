<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    
    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate slug from title when creating or updating
        static::saving(function ($package) {
            if (!$package->slug || $package->isDirty('title')) {
                $package->slug = Str::slug($package->title);
                
                // Ensure slug is unique
                $count = 1;
                $originalSlug = $package->slug;
                
                while (static::where('slug', $package->slug)
                    ->where('id', '!=', $package->id ?? 0)
                    ->exists()) {
                    $package->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }
}
