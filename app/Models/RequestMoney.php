<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestMoney extends Model
{
    use HasFactory;

    protected $casts = [
        'from_user_id' => 'integer',
        'to_user_id' => 'integer',
        'type' => 'string',
        'amount' => 'float:4',
        //'note' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


}
