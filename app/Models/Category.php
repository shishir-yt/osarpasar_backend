<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Category extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }
}