<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'about_title',
        'about_subtitle',
        'about_items',
        'why_choose_title',
        'why_choose_subtitle',
        'why_choose_items',
        'why_choose_clients_count',
        'why_choose_experience',
        'contact_title',
        'contact_subtitle',
        'contact_phone',
        'contact_email',
        'contact_location',
    ];

    protected $casts = [
        'about_items' => 'array',
        'why_choose_items' => 'array',
    ];
}
