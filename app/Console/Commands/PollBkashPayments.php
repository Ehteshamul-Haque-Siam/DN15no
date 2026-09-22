<?php

namespace App\Console\Commands;

use App\Models\Registration;
use App\Services\BkashService;
use App\Services\SmsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PollBkashPayments extends Command
{
    protected $signature   = 'bkash:poll {--hours=48}';
    protected $description = 'Auto-verify pending bKash payments by querying the bKash API';

    public function handle(BkashService $bkash, SmsService $sms): int
    {
        $hours = (int) $this->option('hours');

        $pending = Registration::where('payment_status', 'pending')
            ->whereNotNull('mfs_trn')
            ->where('created_at', '>', now()->subHours($hours))
            ->get();

        $this->info("Checking {$pending->count()} pending payments...");

        $verified = 0;

        foreach ($pending as $reg) {
            try {
                $result = $bkash->searchTransaction($reg->mfs_trn);

                if (!$result['success']) {
                    $this->warn("  ✗ {$reg->registration_id}: " . ($result['statusMessage'] ?? 'unknown'));
                    continue;
                }

                if ($result['transactionStatus'] !== 'Completed') {
                    $this->line("  · {$reg->registration_id}: {$result['transactionStatus']}");
                    continue;
                }

                $expected = (float) $reg->membership_fee;
                $actual   = (float) $result['amount'];

                if (abs($expected - $actual) >= 0.01) {
                    $this->warn("  ! {$reg->registration_id}: amount mismatch ({$actual} vs {$expected})");
                    continue;
                }

                $reg->update([
                    'payment_status' => 'verified',
                    'paid_at'        => $result['paymentExecuteTime'] ?? now(),
                    'verified_at'    => now(),
                    'admin_remarks'  => 'Auto-verified via bKash poll',
                ]);

                $sms->send(
                    $reg->contact_no,
                    "প্রিয় {$reg->name_bn}, আপনার পেমেন্ট যাচাই হয়েছে। সদস্যপদ অনুমোদনের অপেক্ষায় আছেন।",
                    'payment',
                    $reg->id
                );

                $this->info("  ✓ {$reg->registration_id} auto-verified (৳{$actual})");
                $verified++;

                sleep(1);
            } catch (\Throwable $e) {
                Log::warning("bKash poll failed for {$reg->mfs_trn}: " . $e->getMessage());
                $this->error("  ✗ {$reg->registration_id}: " . $e->getMessage());
            }
        }

        $this->info("Done. Verified: {$verified}");
        return self::SUCCESS;
    }
}