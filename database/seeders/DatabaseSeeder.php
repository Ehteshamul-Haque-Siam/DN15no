<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'Super Admin',
            'email'     => 'admin@d15.test',
            'password'  => Hash::make('password'),
            'role'      => 'super_admin',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Moderator',
            'email'     => 'moderator@d15.test',
            'password'  => Hash::make('password'),
            'role'      => 'moderator',
            'is_active' => true,
        ]);
    }
}