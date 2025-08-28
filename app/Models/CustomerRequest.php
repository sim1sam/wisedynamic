<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        // legacy fields
        'title',
        'description',
        // marketing fields
        'page_name',
        'social_media',
        'ads_budget_bdt',
        'days',
        'post_link',
        'status',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_DONE = 'done';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
