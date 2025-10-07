<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'address',
        'phone',
        'whatsapp',
        'email',
        'map_embed',
        'office_hours',
        'social_links',
        'form_title',
        'form_subtitle',
    ];

    protected $casts = [
        'office_hours' => 'array',
        'social_links' => 'array',
    ];
}
