<?php

namespace App\Services;

use App\Models\BkashCredential;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BkashService
{
    protected string $baseUrl;
    protected BkashCredential $cred;

    public function __construct()
    {
        $this->cred = BkashCredential::active()
            ?? throw new \RuntimeException('No active bKash credentials configured.');

        $this->baseUrl = $this->cred->environment === 'live'
            ? 'https://tokenized.pay.bka.sh/v1.2.0-beta'
            : 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';
    }

    public function grantToken(): string
    {
        return Cache::remember('bkash_token_' . $this->cred->id, 3000, function () {
            $res = Http::withHeaders([
                'username' => $this->cred->username,
                'password' => $this->cred->password,
            ])->post("{$this->baseUrl}/tokenized/checkout/token/grant", [
                'app_key'    => $this->cred->app_key,
                'app_secret' => $this->cred->app_secret,
            ])->json();

            return $res['id_token'] ?? throw new \Exception('bKash token grant failed: ' . json_encode($res));
        });
    }

    public function createPayment(array $data): array
    {
        return Http::withToken($this->grantToken())
            ->post("{$this->baseUrl}/tokenized/checkout/create", $data)
            ->json();
    }

    public function executePayment(string $paymentId): array
    {
        return Http::withToken($this->grantToken())
            ->post("{$this->baseUrl}/tokenized/checkout/execute", ['paymentID' => $paymentId])
            ->json();
    }

    public function queryPayment(string $paymentId): array
    {
        return Http::withToken($this->grantToken())
            ->post("{$this->baseUrl}/tokenized/checkout/payment/status", ['paymentID' => $paymentId])
            ->json();
    }
}