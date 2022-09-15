<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EMoney extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected $casts = [
        'user_id' => 'integer',
        'current_balance' => 'float:4',
        'charge_earned' => 'float:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
