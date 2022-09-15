<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function scopeAgentAndAll($query)
    {
        return $query->where('receiver', 'agents')->orWhere('receiver', 'all');
    }

    public function scopeCustomerAndAll($query)
    {
        return $query->where('receiver', 'customers')->orWhere('receiver', 'all');
    }
}
