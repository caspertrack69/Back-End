<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    use HasFactory;

    protected $casts = [
        'key' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
