<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'registration_id', 'mobile', 'message', 'type', 'status', 'response',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}