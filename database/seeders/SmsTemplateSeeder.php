<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'key'          => 'welcome',
                'name'         => 'Registration Welcome',
                'body'         => 'ধন্যবাদ {name}! Reg ID: {reg_id}। বার্ষিক চাঁদা {amount}৳ বিকাশ করুন: {payment_number}',
                'description'  => 'Sent when a new user submits the registration form.',
                'placeholders' => ['name', 'reg_id', 'amount', 'payment_number', 'mobile'],
            ],
            [
                'key'          => 'payment_received',
                'name'         => 'Payment Received',
                'body'         => 'প্রিয় {name}, আপনার পেমেন্ট ({amount}৳) গৃহীত হয়েছে। TRN: {trn}। অ্যাডমিন যাচাইয়ের পর সদস্যপদ নিশ্চিত হবে।',
                'description'  => 'Sent when payment is initially confirmed (before verification).',
                'placeholders' => ['name', 'amount', 'trn', 'reg_id'],
            ],
            [
                'key'          => 'payment_verified',
                'name'         => 'Payment Verified',
                'body'         => 'প্রিয় {name}, আপনার পেমেন্ট যাচাই হয়েছে। সদস্যপদ অনুমোদনের অপেক্ষায় আছেন।',
                'description'  => 'Sent when admin verifies the payment.',
                'placeholders' => ['name', 'reg_id', 'amount'],
            ],
            [
                'key'          => 'payment_rejected',
                'name'         => 'Payment Rejected',
                'body'         => 'দুঃখিত {name}, পেমেন্ট যাচাই ব্যর্থ হয়েছে। কারণ: {reason}',
                'description'  => 'Sent when admin rejects a payment.',
                'placeholders' => ['name', 'reg_id', 'reason'],
            ],
            [
                'key'          => 'approved',
                'name'         => 'Membership Approved',
                'body'         => 'অভিনন্দন {name}! সদস্যপদ অনুমোদিত। Reg ID: {reg_id}। ধন্যবাদ।',
                'description'  => 'Sent when admin approves membership.',
                'placeholders' => ['name', 'reg_id'],
            ],
            [
                'key'          => 'rejected',
                'name'         => 'Application Rejected',
                'body'         => 'দুঃখিত {name}, আবেদন প্রত্যাখ্যাত হয়েছে। কারণ: {reason}। যোগাযোগ: {contact}',
                'description'  => 'Sent when admin rejects the application.',
                'placeholders' => ['name', 'reg_id', 'reason', 'contact'],
            ],
            [
                'key'          => 'test',
                'name'         => 'Test Message',
                'body'         => 'টেস্ট SMS — D15 ফোরাম। সময়: {datetime}',
                'description'  => 'Used for admin test sends.',
                'placeholders' => ['datetime'],
            ],
        ];

        foreach ($templates as $t) {
            SmsTemplate::updateOrCreate(
                ['key' => $t['key']],
                $t
            );
        }

        $this->command->info('✓ ' . count($templates) . ' SMS templates seeded.');
    }
}