<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Models\SmsTemplate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS using a template key.
     */
    public function sendTemplate(
        string $mobile,
        string $templateKey,
        array $data,
        string $type = 'registration',
        ?int $regId = null,
        string $fallback = ''
    ): void {
        $body = SmsTemplate::bodyFor($templateKey, $fallback);
        $message = $this->render($body, $data);

        $this->sendRaw($mobile, $message, $type, $regId);
    }

    /**
     * Send an SMS with a raw (already-composed) message.
     */
    public function sendRaw(string $mobile, string $message, string $type = 'registration', ?int $regId = null): void
    {
        $log = SmsLog::create([
            'registration_id' => $regId,
            'mobile'          => $mobile,
            'message'         => $message,
            'type'            => $type,
            'status'          => 'queued',
        ]);

        if (!config('services.sms.enabled')) {
            $log->update([
                'status'   => 'sent',
                'response' => 'SMS disabled via SMS_ENABLED=false (dry run).',
            ]);
            Log::info('[SMS-DRY] ' . $mobile . ' | ' . $message);
            return;
        }

        $clean = preg_replace('/\D/', '', $mobile);
        if (!preg_match('/^(01[3-9]\d{8}|8801[3-9]\d{8})$/', $clean)) {
            $log->update([
                'status'   => 'failed',
                'response' => 'Invalid BD mobile: ' . $mobile,
            ]);
            return;
        }

        try {
            $response = $this->sendViaRouteMobile($mobile, $message);

            $log->update([
                'status'   => ($response['success'] ?? false) ? 'sent' : 'failed',
                'response' => json_encode($response, JSON_UNESCAPED_UNICODE),
            ]);

            if (!($response['success'] ?? false)) {
                Log::warning('[SMS] Gateway failure', $response);
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
     * Backward-compatible send() — sends a raw message.
     */
    public function send(string $mobile, string $message, string $type = 'registration', ?int $regId = null): void
    {
        $this->sendRaw($mobile, $message, $type, $regId);
    }

    /**
     * Replace {placeholder} tokens with values.
     */
    protected function render(string $body, array $data): string
    {
        $replacements = [];
        foreach ($data as $key => $value) {
            $replacements['{' . $key . '}'] = (string) $value;
        }
        return strtr($body, $replacements);
    }

    /**
     * Route Mobile Bulk HTTP API.
     */
    protected function sendViaRouteMobile(string $phone, string $message): array
    {
        $user   = config('services.sms.username');
        $pass   = config('services.sms.password');
        $sender = config('services.sms.sender');
        $server = config('services.sms.server') ?: 'apibd.rmlconnect.net';
        $port   = config('services.sms.port')   ?: '80';

        if (empty($user) || empty($pass) || empty($sender)) {
            return ['success' => false, 'error' => 'SMS config incomplete.'];
        }

        $clean  = preg_replace('/\D/', '', $phone);
        $mobile = '88' . ltrim($clean, '0');

        $isUnicode = preg_match('/[\x{0980}-\x{09FF}]/u', $message) === 1;
        $type      = $isUnicode ? '2' : '0';
        $dlr       = '1';

        $messageEncoded = $isUnicode ? $this->toUnicodeHex($message) : $message;

        $url = "http://{$server}:{$port}/bulksms/bulksms";

        try {
            $response = Http::timeout(25)
                ->retry(2, 500, throw: false)
                ->get($url, [
                    'username'    => $user,
                    'password'    => $pass,
                    'type'        => $type,
                    'dlr'         => $dlr,
                    'destination' => $mobile,
                    'source'      => $sender,
                    'message'     => $messageEncoded,
                ]);

            $body  = trim($response->body());
            $parts = explode('|', $body);
            $code  = $parts[0] ?? '';

            return [
                'success'    => $code === '1701',
                'error_code' => $code,
                'message_id' => $parts[2] ?? null,
                'raw'        => $body,
                'type_sent'  => $type,
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => 'HTTP error: ' . $e->getMessage()];
        }
    }

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
}