<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'price',
        'price_unit',
        'image',
        'service_category_id',
        'status',
        'featured',
    ];
    
    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate slug from title when creating or updating
        static::saving(function ($service) {
            if (!$service->slug || $service->isDirty('title')) {
                $service->slug = Str::slug($service->title);
                
                // Ensure slug is unique
                $count = 1;
                $originalSlug = $service->slug;
                
                while (static::where('slug', $service->slug)
                    ->where('id', '!=', $service->id ?? 0)
                    ->exists()) {
                    $service->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }
}
