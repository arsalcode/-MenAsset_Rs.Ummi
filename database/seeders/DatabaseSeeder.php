<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User for RS Ummi Asset Management
        User::updateOrCreate(
            ['email' => 'admin@rsummi.com'],
            [
                'name' => 'Admin Manajemen Aset RS Ummi',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            HospitalAssetSeeder::class,
        ]);
    }
}
