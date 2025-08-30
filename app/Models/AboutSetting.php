<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'who_we_are_content',
        'who_we_are_image',
        'about_items',
        'stats',
        'values',
        'services',
        'cta_title',
        'cta_subtitle',
        'cta_button_text',
    ];

    protected $casts = [
        'about_items' => 'array',
        'stats' => 'array',
        'values' => 'array',
        'services' => 'array',
    ];
}
