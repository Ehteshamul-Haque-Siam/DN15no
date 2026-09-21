<?php

namespace App\Services;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS and log the attempt.
     *
     * @param  string  $mobile  BD format: 01XXXXXXXXX
     * @param  string  $message  UTF-8 — Bengali supported
     * @param  string  $type  registration | payment | approval | rejection | otp
     * @param  int|null  $regId  Optional registration ID for linking
     */
    public function send(string $mobile, string $message, string $type = 'registration', ?int $regId = null): void
    {
        // 1. Log first
        $log = SmsLog::create([
            'registration_id' => $regId,
            'mobile' => $mobile,
            'message' => $message,
            'type' => $type,
            'status' => 'queued',
        ]);

        // 2. Dry run
        if (! config('services.sms.enabled')) {
            $log->update([
                'status' => 'sent',
                'response' => 'SMS disabled via SMS_ENABLED=false (dry run).',
            ]);
            Log::info('[SMS-DRY] '.$mobile.' | '.$message);

            return;
        }

        // 3. Validate BD mobile
        $clean = preg_replace('/\D/', '', $mobile);
        if (! preg_match('/^01[3-9]\d{8}$/', $clean)) {
            $log->update([
                'status' => 'failed',
                'response' => 'Invalid BD mobile: '.$mobile,
            ]);

            return;
        }

        // 4. Send via gateway
        try {
            $gateway = config('services.sms.gateway', 'routemobile');

            $response = match ($gateway) {
                'routemobile' => $this->sendViaRouteMobile($clean, $message),
                'bulksmsbd' => $this->sendViaBulkSmsBd($mobile, $message),
                default => throw new \Exception("Unknown SMS gateway: {$gateway}"),
            };

            $log->update([
                'status' => ($response['success'] ?? false) ? 'sent' : 'failed',
                'response' => json_encode($response, JSON_UNESCAPED_UNICODE),
            ]);

            if (! ($response['success'] ?? false)) {
                Log::warning('[SMS] Gateway returned failure', $response);
            }
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'response' => $e->getMessage(),
            ]);
            Log::error('[SMS] Exception: '.$e->getMessage());
        }
    }

    /**
     * Route Mobile — Personalized Bulk SMS. Returns "1701|<mobile>|<message_id>" on success.
     */
    protected function sendViaRouteMobile(string $phone, string $message): array
    {
        // Bengali is sent as plain UTF-8 — the gateway delivers hex-encoded text literally.
        $response = Http::get('http://apibd.rmlconnect.net/bulksms/personalizedbulksms', [
            'username' => config('services.sms.username'),
            'password' => config('services.sms.password'),
            'source' => config('services.sms.sender'),
            'destination' => '88'.$phone,
            'message' => $message,
        ]);

        return [
            'success' => str_starts_with($response->body(), '1701'),
            'raw' => $response->body(),
        ];
    }

    /**
     * Bulk SMS BD fallback.
     */
    protected function sendViaBulkSmsBd(string $mobile, string $message): array
    {
        $type = preg_match('/[\x{0980}-\x{09FF}]/u', $message) ? 'unicode' : 'text';

        $res = Http::timeout(20)->asForm()->post(
            config('services.sms.endpoint'),
            [
                'api_key' => config('services.sms.key'),
                'type' => $type,
                'number' => $mobile,
                'senderid' => config('services.sms.sender'),
                'message' => $message,
            ]
        );

        $body = $res->json() ?? [];

        return [
            'success' => ($body['response_code'] ?? 0) == 202,
            'response_code' => $body['response_code'] ?? null,
            'raw' => $body,
        ];
    }
}
