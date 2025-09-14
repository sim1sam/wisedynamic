<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\PackageOrder;
use App\Models\ServiceOrder;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'profile_image',
        'balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
    
    /**
     * Get the package orders for the user.
     */
    public function packageOrders()
    {
        return $this->hasMany(PackageOrder::class, 'user_id');
    }
    
    /**
     * Get the service orders for the user.
     */
    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class, 'user_id');
    }
    
    /**
     * Get the fund requests for the user.
     */
    public function fundRequests()
    {
        return $this->hasMany(FundRequest::class);
    }
    
    /**
     * Add funds to user balance.
     */
    public function addBalance($amount)
    {
        $this->increment('balance', $amount);
    }
    
    /**
     * Deduct funds from user balance.
     */
    public function deductBalance($amount)
    {
        if ($this->balance >= $amount) {
            $this->decrement('balance', $amount);
            return true;
        }
        return false;
    }
}
