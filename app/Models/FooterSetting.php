<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name', 'tagline', 'phone', 'email', 'facebook_url', 'twitter_url', 'linkedin_url', 'instagram_url', 'copyright_text'
    ];
}
