<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create an Admin account for your presentation
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create a standard test user
        User::create([
            'name' => 'Test Voter',
            'email' => 'voter@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
        
        // Add any other specific users you need here
    }
}