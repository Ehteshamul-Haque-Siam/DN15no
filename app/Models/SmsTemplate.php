<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable = [
        'key', 'name', 'body', 'description',
        'placeholders', 'is_active',
    ];

    protected $casts = [
        'placeholders' => 'array',
        'is_active'    => 'boolean',
    ];

    public static function bodyFor(string $key, string $fallback = ''): string
    {
        $template = static::where('key', $key)->where('is_active', true)->first();
        return $template?->body ?? $fallback;
    }
}