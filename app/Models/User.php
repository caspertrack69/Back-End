<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'last_active_at',
    ];

    protected $casts = [
        'f_name' => 'string',
        'l_name' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'image' => 'string',
        'type' => 'integer',
        'role' => 'integer',
        'password' => 'string',
        'is_phone_verified' => 'integer',
        'is_email_verified' => 'integer',
        'last_active_at' => 'datetime',
        'unique_id' => 'string',
        'referral_id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'identity_images' => 'array',
    ];

    public function AauthAcessToken(){
        return $this->hasMany(OauthAccessToken::class);
    }

    public function scopeAgent($query)
    {
        return $query->where('type', '=', 1);
    }

    public function scopeCustomer($query)
    {
        return $query->where('type', '=', 2);
    }

    public function emoney()
    {
        return $this->hasOne(EMoney::class, 'user_id', 'id');
    }
}
