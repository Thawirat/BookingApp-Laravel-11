<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            StatusSeeder::class,
            UserSeeder::class,
            RoomTypeSeeder::class,
            BuildingSeeder::class,
            RoomSeeder::class,
            EquipmentSeeder::class,
            RoomEquipmentSeeder::class,
        ]);
    }
}
