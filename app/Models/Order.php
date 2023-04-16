<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quantity()
    {
        return $this->belongsTo(Quantity::class);
    }

    public function otherItem()
    {
        return $this->belongsTo(OtherItem::class, 'other_item_id');
    }

    public function orderAddress()
    {
        return $this->belongsTo(OrderAddress::class, 'order_address_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}