<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        // สร้าง user ที่มี role admin
        if (! User::where('email', 'admin@snru.ac.th')->exists()) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@snru.ac.th',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'user_type' => 'internal',
                'phone_number' => '0800000000',
                'position' => 'System Administrator',
                'department' => 'IT Department',
                'address' => 'มหาวิทยาลัยราชภัฏสกลนคร',
                'status' => 'active',
            ]);
            $admin->assignRole('admin');
        }

        // สร้าง user ที่มี role sub-admin
        if (! User::where('email', 'subadmin@snru.ac.th')->exists()) {
            $subAdmin = User::create([
                'name' => 'Sub Admin',
                'email' => 'subadmin@snru.ac.th',
                'password' => Hash::make('12345678'),
                'role' => 'sub-admin',
                'user_type' => 'internal',
                'phone_number' => '0800000001',
                'position' => 'Building Manager',
                'department' => 'Facilities',
                'address' => 'อาคารบริการส่วนกลาง',
            ]);
            $subAdmin->assignRole('sub-admin');
        }

        // สร้าง regular users โดยใช้ factory พร้อมฟิลด์เพิ่มเติม
        User::factory()
            ->count(100)
            ->create()
            ->each(function ($user, $index) use ($faker) {
                $user->update([
                    'name' => $faker->name,
                    'email' => strtolower($faker->unique()->firstName) . '@snru.ac.th',
                    'user_type' => 'internal',
                    'phone_number' => '08000001' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                    'position' => 'Staff',
                    'department' => 'General Affairs',
                    'address' => 'มหาวิทยาลัยราชภัฏสกลนคร',
                    'role' => 'user',
                ]);
                $user->assignRole('user');
            });
    }
}
