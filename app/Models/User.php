<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Exceptions\InvalidManipulation;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

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
    protected $appends = ['profile_image'];

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
        if($this->profile_image) {
            return $this->profile_image;
        } else {
        return asset('logo/opLogo.png');

        }
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

    public function payments()
    {
        return $this->hasMany(Payment::class, 'service_provider_id');
    }

    public function userOrders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'service_provider_id');
    }

    public function quantities()
    {
        return $this->hasMany(Quantity::class, 'item_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        try {
            $this->addMediaConversion('thumb')
                ->width(160)
                ->height(300);

            $this->addMediaConversion('medium')
                ->width(320)
                ->height(320);

        } catch (InvalidManipulation $e) {

        }
    }

    function getImage($collectionName = null)
    {
        if ($collectionName) {
            if (count($this->getMedia($collectionName)) == 0) return null;
            return $this->getFirstMediaUrl($collectionName ?? null);
        } else {
            if (count($this->getMedia()) == 0) return null;
            return $this->getFirstMediaUrl() ?? null;
        }
    }

    function getImageByIndex($index = 0)
    {
        if (count($this->getMedia()) == 0) return null;
//        if ($this->getMedia()->first()->getUrl('large') != null)
        try {
            return $this->getMedia()[$index]->getUrl() ?? null;
        } catch (\Exception $e) {
            return null;
        }
        return $this->getFirstMediaUrl() ?? null;
    }

    function getThumbnail($collectionName = null)
    {
        if ($collectionName) {
            if (count($this->getMedia($collectionName)) == 0) return null;
            return $this->getMedia($collectionName)->first()->getUrl('medium') ?? null;
        } else {
            if (count($this->getMedia()) == 0) return null;
            return $this->getMedia()->first()->getUrl('medium') ?? null;
        }
    }

    function getProfileImageAttribute()
    {
        return $this->getFirstMediaUrl();
    }
}