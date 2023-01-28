<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }
}