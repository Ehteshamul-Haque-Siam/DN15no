<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'registration_id', 'name_en', 'name_bn', 'nickname',
        'father_en', 'father_bn', 'mother_en', 'mother_bn',
        'photo', 'flat', 'from_year', 'to_year', 'present_add',
        'contact_no', 'occupation', 'email', 'membership_fee',
        'mfs_no', 'mfs_trn', 'payment_ref', 'payment_status',
        'paid_at', 'verified_by', 'verified_at', 'admin_remarks',
        'approval_status', 'approved_at', 'approved_by', 'tracking_token',
    ];

    protected $casts = [
        'paid_at'        => 'datetime',
        'verified_at'    => 'datetime',
        'approved_at'    => 'datetime',
        'membership_fee' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function ($reg) {
            if (empty($reg->tracking_token)) {
                $reg->tracking_token = Str::random(64);
            }
            if (empty($reg->registration_id)) {
                $year = date('Y');
                $last = static::whereYear('created_at', $year)->count() + 1;
                $reg->registration_id = 'D15-' . $year . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function smsLogs()
    {
        return $this->hasMany(SmsLog::class)->latest();
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function hasPhoto(): bool
    {
        return $this->photo
            && Storage::disk('public')->exists($this->photo);
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->hasPhoto()
            ? asset('storage/' . $this->photo)
            : asset('images/avatar.png');
    }
}