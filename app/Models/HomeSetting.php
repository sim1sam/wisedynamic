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
    ];

    protected $casts = [
        'about_items' => 'array',
    ];
}
