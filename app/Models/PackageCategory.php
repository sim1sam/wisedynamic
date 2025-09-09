<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
