<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'content',
        'image',
        'show_in_footer',
        'show_in_header',
        'order',
        'is_active',
    ];
    
    protected $casts = [
        'show_in_footer' => 'boolean',
        'show_in_header' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
    
    /**
     * Generate a slug from the title if no slug is provided
     */
    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
        
        static::updating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    /**
     * Scope a query to only include active pages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to only include footer pages.
     */
    public function scopeFooter($query)
    {
        return $query->where('show_in_footer', true);
    }
    
    /**
     * Scope a query to only include header pages.
     */
    public function scopeHeader($query)
    {
        return $query->where('show_in_header', true);
    }
}
