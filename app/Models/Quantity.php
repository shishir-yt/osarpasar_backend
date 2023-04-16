<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'user_id', 'item_name', 'service_provider_id', 'quantity', 'order_id'];

    public function userProvider(){
        return $this->belongsToMany(User::class);

    }
    public function serviceProvider()
    {
        return $this->belongsToMany(User::class);
    }

    public function order(){
        return $this->hasMany(Order::class, 'order_id');
    }
}
