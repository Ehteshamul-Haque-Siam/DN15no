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
     * @param  string   $mobile  BD format: 01XXXXXXXXX (or 8801XXXXXXXXX)
     * @param  string   $message UTF-8 — Bengali supported
     * @param  string   $type    registration | payment | approval | rejection | otp
     * @param  int|null $regId   Optional registration ID for linking
     */
    public function send(string $mobile, string $message, string $type = 'registration', ?int $regId = null): void
    {
        // 1. Log first
        $log = SmsLog::create([
            'registration_id' => $regId,
            'mobile'          => $mobile,
            'message'         => $message,
            'type'            => $type,
            'status'          => 'queued',
        ]);

        // 2. Dry run
        if (!config('services.sms.enabled')) {
            $log->update([
                'status'   => 'sent',
                'response' => 'SMS disabled via SMS_ENABLED=false (dry run).',
            ]);
            Log::info('[SMS-DRY] ' . $mobile . ' | ' . $message);
            return;
        }

        // 3. Validate BD mobile
        $clean = preg_replace('/\D/', '', $mobile);
        if (!preg_match('/^(01[3-9]\d{8}|8801[3-9]\d{8})$/', $clean)) {
            $log->update([
                'status'   => 'failed',
                'response' => 'Invalid BD mobile: ' . $mobile,
            ]);
            return;
        }

        // 4. Send via gateway
        try {
            $gateway = config('services.sms.gateway', 'routemobile');

            $response = match ($gateway) {
                'routemobile' => $this->sendViaRouteMobile($mobile, $message),
                'bulksmsbd'   => $this->sendViaBulkSmsBd($mobile, $message),
                default       => throw new \Exception("Unknown SMS gateway: {$gateway}"),
            };

            $log->update([
                'status'   => ($response['success'] ?? false) ? 'sent' : 'failed',
                'response' => json_encode($response, JSON_UNESCAPED_UNICODE),
            ]);

            if (!($response['success'] ?? false)) {
                Log::warning('[SMS] Gateway returned failure', $response);
            }
        } catch (\Throwable $e) {
            $log->update([
                'status'   => 'failed',
                'response' => $e->getMessage(),
            ]);
            Log::error('[SMS] Exception: ' . $e->getMessage());
        }
    }

    /**
     * Route Mobile — Personalized Bulk SMS endpoint.
     *
     * Endpoint: http://apibd.rmlconnect.net:80/bulksms/personalizedbulksms
     * Method:   POST (form-encoded)
     * Response: 1701|<mobile>|<message_id>
     */
    protected function sendViaRouteMobile(string $phone, string $message): array
    {
        $user   = config('services.sms.username');
        $pass   = config('services.sms.password');
        $sender = config('services.sms.sender');
        $server = config('services.sms.server') ?: 'apibd.rmlconnect.net';
        $port   = config('services.sms.port')   ?: '80';

        // Guard
        if (empty($user) || empty($pass) || empty($sender)) {
            return [
                'success' => false,
                'error'   => 'SMS config incomplete. Set SMS_USERNAME, SMS_PASSWORD, SMS_SENDER in .env.',
            ];
        }

        // Normalize mobile → 88XXXXXXXXXX
        $clean  = preg_replace('/\D/', '', $phone);
        $mobile = '88' . ltrim($clean, '0');

        // Detect Bengali → Unicode
        $isUnicode = preg_match('/[\x{0980}-\x{09FF}]/u', $message) === 1;
        $type      = $isUnicode ? '2' : '0';

        // Encode Bengali as UTF-16BE hex
        $messageEncoded = $isUnicode ? $this->toUnicodeHex($message) : $message;

        $url = "http://{$server}:{$port}/bulksms/personalizedbulksms";

        try {
            $response = Http::timeout(25)
                ->retry(2, 500, throw: false)
                ->asForm()
                ->post($url, [
                    'username'    => $user,
                    'password'    => $pass,
                    'type'        => $type,
                    'dlr'         => '1',
                    'destination' => $mobile,
                    'source'      => $sender,
                    'message'     => $messageEncoded,
                ]);

            $body  = trim($response->body());
            $parts = explode('|', $body);
            $code  = $parts[0] ?? '';
            $msgId = $parts[2] ?? null;

            return [
                'success'    => $code === '1701',
                'error_code' => $code,
                'message_id' => $msgId,
                'raw'        => $body,
                'type_sent'  => $type,
                'endpoint'   => 'personalizedbulksms',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error'   => 'HTTP error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Convert UTF-8 → UTF-16BE (UCS-2BE) hex, uppercase.
     * Required for Route Mobile type=2 (Bengali/Unicode).
     */
    protected function toUnicodeHex(string $message): string
    {
        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'UCS-2BE', $message);
            if ($converted !== false && $converted !== '') {
                return strtoupper(bin2hex($converted));
            }
        }

        if (function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($message, 'UTF-16BE', 'UTF-8');
            if ($converted !== false && $converted !== '') {
                return strtoupper(bin2hex($converted));
            }
        }

        return '';
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
                'api_key'  => config('services.sms.key'),
                'type'     => $type,
                'number'   => $mobile,
                'senderid' => config('services.sms.sender'),
                'message'  => $message,
            ]
        );

        $body = $res->json() ?? [];

        return [
            'success'       => ($body['response_code'] ?? 0) == 202,
            'response_code' => $body['response_code'] ?? null,
            'raw'           => $body,
        ];
    }
}