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
                'password' => Hash::make('tarukoda123'),
                'is_admin' => false,
            ]
        );

        User::query()->firstOrCreate(
            ['email' => 'test@test.ee'],
            [
                'name' => 'Admin',
                'password' => Hash::make('test'),
                'is_admin' => true,
            ]
        );
    }
}
