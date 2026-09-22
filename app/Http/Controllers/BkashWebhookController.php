<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BkashWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Verify shared secret
        $secret   = (string) config('services.bkash.webhook_secret');
        $received = (string) $request->header('X-BKASH-WEBHOOK-SECRET');

        if ($secret && !hash_equals($secret, $received)) {
            Log::warning('bKash webhook: invalid signature', [
                'ip'       => $request->ip(),
                'received' => $received,
            ]);
            abort(401);
        }

        $payload = $request->all();
        $trxId   = $payload['trxID'] ?? null;

        if (!$trxId) {
            return response('missing trxID', 400);
        }

        // 2. Idempotency — never process same trxID twice
        if (Payment::where('trx_id', $trxId)->exists()) {
            Log::info("bKash webhook: duplicate trxID {$trxId}, ignored");
            return response('ok', 200);
        }

        // 3. Find matching registration by TRN
        $registration = Registration::where('mfs_trn', $trxId)->first();

        if (!$registration) {
            Log::warning("bKash webhook: no matching registration for {$trxId}");
            return response('ok', 200);
        }

        DB::transaction(function () use ($registration, $payload, $trxId) {
            Payment::create([
                'registration_id'  => $registration->id,
                'gateway'          => 'bkash',
                'trx_id'           => $trxId,
                'amount'           => $payload['amount'] ?? $registration->membership_fee,
                'currency'         => $payload['currency'] ?? 'BDT',
                'payer_mobile'     => $payload['debitMSISDN'] ?? null,
                'status'           => 'success',
                'gateway_response' => $payload,
            ]);

            if ($registration->payment_status !== 'verified') {
                $registration->update([
                    'payment_status' => 'verified',
                    'paid_at'        => now(),
                    'verified_at'    => now(),
                    'admin_remarks'  => 'Auto-verified via bKash webhook',
                ]);

                app(SmsService::class)->send(
                    $registration->contact_no,
                    "প্রিয় {$registration->name_bn}, আপনার পেমেন্ট যাচাই হয়েছে। সদস্যপদ অনুমোদনের অপেক্ষায় আছেন।",
                    'payment',
                    $registration->id
                );
            }

            Log::info("bKash webhook: {$trxId} verified for {$registration->registration_id}");
        });

        return response('ok', 200);
    }
}