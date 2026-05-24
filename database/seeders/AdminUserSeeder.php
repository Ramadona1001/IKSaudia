<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@iksaudi.com'],
            [
                'name' => 'IK Admin',
                'password' => Hash::make('ChangeMe!2026'),
                'is_active' => true,
                'locale' => 'ar',
                'email_verified_at' => now(),
            ],
        );
    }
}
