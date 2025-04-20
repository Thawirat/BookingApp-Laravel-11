<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        if (!User::where('email', 'admin@example.com')->exists()) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]);
            $admin->assignRole('admin');
        }

        // Create regular users
        User::factory()
            ->count(100)
            ->create([
                'role' => 'user'
            ])
            ->each(function ($user) {
                $user->assignRole('user');
            });
    }

}
