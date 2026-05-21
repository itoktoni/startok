<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
         User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'developer',
            'password' => Hash::make('password'), // Sets a fixed secure password
        ]);
    }
}
