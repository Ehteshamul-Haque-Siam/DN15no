<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'registration_id', 'gateway', 'payment_id', 'trx_id',
        'amount', 'currency', 'payer_mobile', 'status', 'gateway_response',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'amount'           => 'decimal:2',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}