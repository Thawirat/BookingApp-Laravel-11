<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Room permissions
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',
            
            // Building permissions
            'view buildings',
            'create buildings',
            'edit buildings',
            'delete buildings',
            
            // Booking permissions
            'view bookings',
            'create bookings',
            'approve bookings',
            'cancel bookings',
            
            // User management
            'manage users',
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $subAdminRole = Role::create(['name' => 'sub-admin']);
        $subAdminRole->givePermissionTo([
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',
            'view buildings',
            'view bookings',
            'approve bookings',
            'cancel bookings'
        ]);

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view rooms',
            'view buildings',
            'create bookings',
            'view bookings'
        ]);
    }
}