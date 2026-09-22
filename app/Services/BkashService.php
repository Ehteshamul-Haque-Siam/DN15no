<?php

namespace App\Services;

use App\Models\BkashCredential;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

            $token = $res['id_token'] ?? null;
            if (!$token) {
                Log::error('bKash token grant failed', $res);
                throw new \Exception('bKash token grant failed: ' . json_encode($res));
            }

            return $token;
        });
    }

    public function createPayment(array $data): array
    {
        return Http::withHeaders([
            'Authorization' => $this->grantToken(),
            'X-APP-Key'     => $this->cred->app_key,
        ])->post("{$this->baseUrl}/tokenized/checkout/create", $data)->json();
    }

    public function executePayment(string $paymentId): array
    {
        return Http::withHeaders([
            'Authorization' => $this->grantToken(),
            'X-APP-Key'     => $this->cred->app_key,
        ])->post("{$this->baseUrl}/tokenized/checkout/execute", [
            'paymentID' => $paymentId,
        ])->json();
    }

    /**
     * Search a bKash transaction by TrxID.
     * Used to verify a TRN a user submitted.
     */
    public function searchTransaction(string $trxId): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->grantToken(),
            'X-APP-Key'     => $this->cred->app_key,
        ])->post("{$this->baseUrl}/tokenized/checkout/general/search-transaction", [
            'trxID' => $trxId,
        ]);

        $body = $response->json() ?? [];

        return [
            'success'            => $response->successful()
                                    && !empty($body['transactionStatus']),
            'raw'                => $body,
            'transactionStatus'  => $body['transactionStatus'] ?? null,
            'amount'             => $body['amount'] ?? null,
            'currency'           => $body['currency'] ?? 'BDT',
            'customerMsisdn'     => $body['customerMsisdn'] ?? null,
            'trxID'              => $body['trxID'] ?? $trxId,
            'paymentExecuteTime' => $body['paymentExecuteTime'] ?? null,
            'statusMessage'      => $body['statusMessage'] ?? null,
        ];
    }
}