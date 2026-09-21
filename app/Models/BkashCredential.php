<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkashCredential extends Model
{
    protected $fillable = [
        'app_key', 'app_secret', 'username', 'password',
        'merchant_number', 'environment', 'is_active',
    ];

    protected $casts = [
        'app_key'    => 'encrypted',
        'app_secret' => 'encrypted',
        'username'   => 'encrypted',
        'password'   => 'encrypted',
        'is_active'  => 'boolean',
    ];

    public static function active(): ?self
    {
        return static::where('is_active', true)->latest()->first();
    }
}