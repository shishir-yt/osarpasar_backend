<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Payment extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id'];

    public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
