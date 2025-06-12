<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            $quantity = rand(5, 50);
            $remaining = rand(0, $quantity); // อาจเหลือน้อยกว่าจำนวนทั้งหมด

            DB::table('equipment')->insert([
                'name' => 'อุปกรณ์หมายเลข ' . $i,
                'description' => 'รายละเอียดอุปกรณ์หมายเลข ' . $i . ' - ' . Str::random(20),
                'quantity' => $quantity,
                'remaining' => $remaining,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
