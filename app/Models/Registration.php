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
        'registration_id',
        'name_en', 'name_bn', 'nickname',
        'father_en', 'father_bn', 'mother_en', 'mother_bn',
        'photo',
        'building_no', 'flat_no',
        'from_year', 'to_year', 'present_add',
        'contact_no', 'occupation', 'email', 'membership_fee',
        'payment_method', 'bank_id', 'bank_reference', 'bank_slip',
        'paid_to_bank', 'cash_receipt_no', 'cash_receipt',
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
                $reg->registration_id = static::generateNextId();
            }
        });
    }

    public static function generateNextId(): string
    {
        $year   = (int) date('Y');
        $prefix = 'D15-' . $year . '-';

        $attempt = 0;
        $next    = 1;

        do {
            $last = static::withTrashed()
                ->where('registration_id', 'like', $prefix . '%')
                ->orderByDesc('registration_id')
                ->value('registration_id');

            $next = 1;
            if ($last && preg_match('/^D15-' . $year . '-(\d+)$/', $last, $m)) {
                $next = ((int) $m[1]) + 1;
            }

            $id = $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);

            if (!static::withTrashed()->where('registration_id', $id)->exists()) {
                return $id;
            }
            $attempt++;
        } while ($attempt < 5);

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT) . '-' . substr((string) microtime(true), -4);
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

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function hasPhoto(): bool
    {
        return $this->photo && Storage::disk('public')->exists($this->photo);
    }

    public function hasBankSlip(): bool
    {
        return $this->bank_slip && Storage::disk('public')->exists($this->bank_slip);
    }

    public function hasCashReceipt(): bool
    {
        return $this->cash_receipt && Storage::disk('public')->exists($this->cash_receipt);
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->hasPhoto()
            ? asset('storage/' . $this->photo)
            : asset('images/avatar.png');
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'bkash' => 'bKash',
            'bank'  => 'Bank Transfer',
            'cash'  => 'Cash',
            default => 'Unknown',
        };
    }

    public function getTransactionReferenceAttribute(): string
    {
        return match ($this->payment_method) {
            'bkash' => $this->mfs_trn ?? '—',
            'bank'  => $this->bank_reference ?? '—',
            'cash'  => $this->cash_receipt_no ?? '—',
            default => '—',
        };
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when(!empty($filters['id']), fn ($q) =>
                $q->where('registration_id', 'like', '%' . $filters['id'] . '%')
            )
            ->when(!empty($filters['building']), fn ($q) =>
                $q->where('building_no', 'like', '%' . $filters['building'] . '%')
            )
            ->when(!empty($filters['flat']), fn ($q) =>
                $q->where('flat_no', 'like', '%' . $filters['flat'] . '%')
            )
            ->when(!empty($filters['contact']), function ($q) use ($filters) {
                $raw   = (string) $filters['contact'];
                $clean = preg_replace('/\D/', '', $raw);
                $q->where(function ($sub) use ($raw, $clean) {
                    $sub->where('contact_no', 'like', "%{$raw}%")
                        ->orWhere('contact_no', 'like', "%{$clean}%")
                        ->orWhere('mfs_no', 'like', "%{$raw}%");
                });
            })
            ->when(!empty($filters['payment']), fn ($q) =>
                $q->where('payment_status', $filters['payment'])
            )
            ->when(!empty($filters['approval']), fn ($q) =>
                $q->where('approval_status', $filters['approval'])
            )
            ->when(!empty($filters['method']), fn ($q) =>
                $q->where('payment_method', $filters['method'])
            );
    }
}