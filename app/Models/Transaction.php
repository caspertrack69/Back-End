<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $casts = [
        'user_id' => 'integer',
        'transaction_id' => 'string',
        'ref_trans_id' => 'string',
        'transaction_type' => 'string',
        'debit' => 'float:4',
        'credit' => 'float:4',
        'balance' => 'float:4',
        'from_user_id' => 'integer',
        'to_user_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

        'amount' => 'float:4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeNotAdmin($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('type', '!=', 0);
        });
    }

    public function scopeAgent($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('type', 1);
        });
    }

    public function scopeCustomer($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('type', 2);
        });
    }


}
