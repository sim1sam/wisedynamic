<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'subtitle', 'price_text', 'link_url', 'image_source', 'image_url', 'image_path', 'position', 'active'
    ];
}
