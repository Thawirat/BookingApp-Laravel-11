<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // สร้าง roles
        $adminRole = Role::create(['name' => 'admin']);
        $subAdminRole = Role::create(['name' => 'sub-admin']);
        $userRole = Role::create(['name' => 'user']);

        // กำหนด permissions ให้กับ roles
        $adminRole->givePermissionTo(Permission::all());  // admin จะได้ทุกสิทธิ์
        $subAdminRole->givePermissionTo([
            'view booking',       // ดูการจอง
            'create booking',     // สร้างการจอง
            'edit booking',       // แก้ไขการจอง
            'delete booking',     // ลบการจอง
            'approve booking',    // อนุมัติการจอง
            'manage rooms',       // จัดการห้อง
        ]);
        $userRole->givePermissionTo([
            'view booking',       // ดูการจอง
            'create booking',     // สร้างการจอง
        ]);
    }
}
