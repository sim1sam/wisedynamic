<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebsiteSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'site_name',
        'meta_title',
        'meta_description',
        'site_logo',
        'site_favicon',
        'logo_alt_text',
        'show_site_name_with_logo'
    ];
    
    protected $casts = [
        'show_site_name_with_logo' => 'boolean',
    ];
}
