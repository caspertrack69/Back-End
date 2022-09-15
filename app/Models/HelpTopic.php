<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTopic extends Model
{
    use HasFactory;

    protected $casts = [

        'ranking'    => 'integer',
        'status'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $fillable = [
        'question',
        'answer',
        'status',
        'ranking',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
