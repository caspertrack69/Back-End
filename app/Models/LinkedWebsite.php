<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkedWebsite extends Model
{
    use HasFactory;

    protected $casts = [
        'name' => 'string',
        'image' => 'string',
        'url' => 'string',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
