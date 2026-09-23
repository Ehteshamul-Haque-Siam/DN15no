<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        // Only create if no banks exist
        if (Bank::count() > 0) {
            $this->command->info('Banks already exist — skipping seed.');
            return;
        }

        Bank::create([
            'bank_name'      => 'Sonali Bank Ltd.',
            'account_name'   => 'Dhanmondi 15 No Govt Staff Quarters Ex-Residents Forum',
            'account_number' => '4402173000123',
            'branch'         => 'Dhanmondi Corporate Branch',
            'routing_number' => '200262419',
            'swift_code'     => 'BSONBDDHXXX',
            'is_active'      => true,
            'display_order'  => 1,
            'instructions'   => 'ট্রান্সফার করার পর অবশ্যই রেফারেন্স নাম্বার লিখুন।',
        ]);

        Bank::create([
            'bank_name'      => 'Dutch-Bangla Bank Ltd.',
            'account_name'   => 'Dhanmondi 15 No Govt Staff Quarters Ex-Residents Forum',
            'account_number' => '1021234567890',
            'branch'         => 'Dhanmondi Branch',
            'routing_number' => '090262419',
            'swift_code'     => 'DBBLBDDH',
            'is_active'      => true,
            'display_order'  => 2,
            'instructions'   => 'নোটে "D15 Forum Fee" লিখুন।',
        ]);

        $this->command->info('✓ 2 banks seeded successfully.');
    }
}