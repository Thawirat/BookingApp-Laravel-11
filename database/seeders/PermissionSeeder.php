<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // สร้าง permissions ที่เกี่ยวกับการจองห้อง
         $permissions = [
            'create booking',
            'edit booking',
            'delete booking',
            'view booking',
            'approve booking',   // permission สำหรับอนุมัติการจอง
            'manage rooms',      // permission สำหรับการจัดการห้อง
            'manage buildings',  // permission สำหรับการจัดการอาคาร
            'manage users',      // permission สำหรับการจัดการผู้ใช้
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
