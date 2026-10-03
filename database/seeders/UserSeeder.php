<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ],
            [
                'name' => 'Super Admin',
                'email' => 'admin@shopping.com',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ],
            [
                'name' => 'Normal User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
