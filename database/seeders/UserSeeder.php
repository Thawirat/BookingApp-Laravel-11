<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // สร้าง user ที่มี role admin
        if (! User::where('email', 'admin@example.com')->exists()) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]);
            // กำหนด role admin ให้กับ user
            $admin->assignRole('admin');
        }

        // สร้าง user ที่มี role sub-admin
        if (! User::where('email', 'subadmin@example.com')->exists()) {
            $subAdmin = User::create([
                'name' => 'Sub Admin',
                'email' => 'subadmin@example.com',
                'password' => Hash::make('12345678'),
                'role' => 'sub-admin',
            ]);
            // กำหนด role sub-admin ให้กับ user
            $subAdmin->assignRole('sub-admin');
        }

        // สร้าง regular users โดยใช้ factory
        User::factory()
            ->count(100)
            ->create()
            ->each(function ($user) {
                // กำหนด role user ให้กับ user ทุกคน
                $user->assignRole('user');
            });
    }
}
