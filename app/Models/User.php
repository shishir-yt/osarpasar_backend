<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'email_verfied_at',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'service_provider_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'service_provider_id');
    }

    public function otherItems()
    {
        return $this->hasMany(OtherItem::class, 'service_provider_id');
    }

    public function userOtherItems()
    {
        return $this->hasMany(OtherItem::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'service_provider_id');
    }

    public function userOrders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'service_provider_id');
    }
}