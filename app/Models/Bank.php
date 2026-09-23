<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'bank_name', 'account_name', 'account_number', 'branch',
        'routing_number', 'swift_code', 'is_active', 'display_order',
        'instructions',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function activeBanks()
    {
        return static::where('is_active', true)
            ->orderBy('display_order')
            ->limit(2)
            ->get();
    }

    public static function activeCount(): int
    {
        return static::where('is_active', true)->count();
    }
}