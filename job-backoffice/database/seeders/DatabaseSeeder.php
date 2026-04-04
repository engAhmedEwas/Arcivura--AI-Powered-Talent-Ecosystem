<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name'  => 'Ahmed Ewas',
            'email' => 'admin@arcivura.com',
            'password' => Hash::make('password'),
            'role'  => UserRole::SUPER_ADMIN,
            'is_verified' => true,
        ]);

        User::factory()->create([
            'name'  => 'Test Seeker',
            'email' => 'seeker@arcivura.com',
            'role'  => UserRole::SEEKER,
            'is_verified' => true,
        ]);

        User::factory(10)->create([
            'role' => UserRole::SEEKER,
        ]);
    }
}
