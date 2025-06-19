<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RoomEquipmentSeeder extends Seeder
{
public function run()
    {
        $faker = Faker::create();

        // กำหนดอุปกรณ์ตัวอย่าง
        $equipments = ['โปรเจคเตอร์', 'ไมโครโฟน', 'ลำโพง', 'ไวท์บอร์ด', 'ทีวี', 'คอมพิวเตอร์', 'โต๊ะ', 'เก้าอี้'];

        // ดึงข้อมูลห้องที่มีอยู่
        $rooms = DB::table('rooms')->get();

        foreach ($rooms as $room) {
            // เพิ่มอุปกรณ์สุ่ม 2-4 ชิ้นต่อห้อง
            $selectedEquipments = $faker->randomElements($equipments, rand(2, 4));

            foreach ($selectedEquipments as $equipment) {
                DB::table('room_equipments')->insert([
                    'building_id' => $room->building_id,
                    'room_id' => $room->room_id,
                    'name' => $equipment,
                    'quantity' => $faker->numberBetween(1, 10),
                    'note' => $faker->boolean(20) ? $faker->sentence(3) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
