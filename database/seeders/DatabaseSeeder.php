<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'demo@tarukoda.ee'],
            [
                'name' => 'Demo Kasutaja',
                'password' => Hash::make(env('DEMO_USER_PASSWORD', 'tarukoda123')),
                'is_admin' => false,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'test@test.ee'],
            [
                'name' => 'Admin',
                'password' => Hash::make(env('ADMIN_SEED_PASSWORD', 'test')),
                'is_admin' => true,
            ]
        );
    }
}
