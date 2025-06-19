<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $rooms = [
            ['building_id' => 10, 'room_id' => 26, 'room_type' => 3, 'room_name' => 'หอประชุม 694', 'class' => '4', 'room_details' => 'หอประชุม ความจุประมาณ 125 ที่นั่ง พร้อมไมโครโฟน เครื่องเสียง และโปรเจ็กเตอร์', 'capacity' => 80, 'service_rates' => 0, 'status_id' => 1],
            ['building_id' => 10, 'room_id' => 27, 'room_type' => 2, 'room_name' => 'ห้องประชุม 523', 'class' => '4', 'room_details' => 'ห้องประชุม ความจุประมาณ 158 ที่นั่ง มีทีวีขนาดใหญ่ ไมค์ประชุม และไวท์บอร์ด', 'capacity' => 100, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 10, 'room_id' => 28, 'room_type' => 7, 'room_name' => 'ห้องกิจกรรม 708', 'class' => '2', 'room_details' => 'ห้องกิจกรรม ความจุประมาณ 109 ที่นั่ง มีเวทีขนาดเล็ก ลำโพง และพื้นที่อเนกประสงค์', 'capacity' => 120, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 9, 'room_id' => 29, 'room_type' => 5, 'room_name' => 'ห้องคอม 818', 'class' => '4', 'room_details' => 'ห้องคอม ความจุประมาณ 260 ที่นั่ง พร้อมคอมพิวเตอร์ 80 เครื่อง และอินเทอร์เน็ตความเร็วสูง', 'capacity' => 80, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 9, 'room_id' => 30, 'room_type' => 3, 'room_name' => 'หอประชุม 813', 'class' => '5', 'room_details' => 'หอประชุม ความจุประมาณ 136 ที่นั่ง มีระบบเสียง ครบชุด พร้อมจอโปรเจ็กเตอร์', 'capacity' => 60, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 3, 'room_id' => 31, 'room_type' => 6, 'room_name' => 'ห้องปฏิบัติการ 917', 'class' => '3', 'room_details' => 'ห้องปฏิบัติการ ความจุประมาณ 223 ที่นั่ง พร้อมอุปกรณ์ทดลอง และโต๊ะแลป', 'capacity' => 100, 'service_rates' => 0, 'status_id' => 1],
            ['building_id' => 1, 'room_id' => 32, 'room_type' => 7, 'room_name' => 'ห้องกิจกรรม 561', 'class' => '4', 'room_details' => 'ห้องกิจกรรม ความจุประมาณ 225 ที่นั่ง พร้อมฉากพับ โต๊ะเคลื่อนย้าย และอุปกรณ์กีฬาเบื้องต้น', 'capacity' => 120, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 5, 'room_id' => 33, 'room_type' => 2, 'room_name' => 'ห้องประชุม 738', 'class' => '4', 'room_details' => 'ห้องประชุม ความจุประมาณ 30 ที่นั่ง พร้อมทีวี LCD และไมค์ตั้งโต๊ะ', 'capacity' => 50, 'service_rates' => 0, 'status_id' => 1],
            ['building_id' => 1, 'room_id' => 34, 'room_type' => 6, 'room_name' => 'ห้องปฏิบัติการ 856', 'class' => '4', 'room_details' => 'ห้องปฏิบัติการ ความจุประมาณ 125 ที่นั่ง พร้อมอุปกรณ์ทดลองขั้นพื้นฐาน และเครื่องพิมพ์ 3D', 'capacity' => 250, 'service_rates' => 0, 'status_id' => 1],
            ['building_id' => 9, 'room_id' => 35, 'room_type' => 5, 'room_name' => 'ห้องคอม 219', 'class' => '1', 'room_details' => 'ห้องคอม ความจุประมาณ 291 ที่นั่ง พร้อมคอมพิวเตอร์ 50 เครื่อง และเครื่องพิมพ์', 'capacity' => 50, 'service_rates' => 0, 'status_id' => 1],
            ['building_id' => 8, 'room_id' => 36, 'room_type' => 2, 'room_name' => 'ห้องประชุม 262', 'class' => '2', 'room_details' => 'ห้องประชุม ความจุประมาณ 244 ที่นั่ง พร้อมกล้องบันทึกวิดีโอ และโปรเจ็กเตอร์', 'capacity' => 40, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 13, 'room_id' => 37, 'room_type' => 6, 'room_name' => 'ห้องปฏิบัติการ 335', 'class' => '3', 'room_details' => 'ห้องปฏิบัติการ ความจุประมาณ 251 ที่นั่ง มีโต๊ะวิจัย และเครื่องมือวัด', 'capacity' => 120, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 3, 'room_id' => 38, 'room_type' => 5, 'room_name' => 'ห้องคอม 901', 'class' => '3', 'room_details' => 'ห้องคอม ความจุประมาณ 263 ที่นั่ง พร้อมเครื่องคอม 100 เครื่อง และระบบ LAN ครบวงจร', 'capacity' => 150, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 10, 'room_id' => 39, 'room_type' => 1, 'room_name' => 'ห้องเรียน 325', 'class' => '1', 'room_details' => 'ห้องเรียน ความจุประมาณ 105 ที่นั่ง พร้อมกระดานไวท์บอร์ด และโปรเจ็กเตอร์', 'capacity' => 250, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 1, 'room_id' => 40, 'room_type' => 3, 'room_name' => 'หอประชุม 591', 'class' => '5', 'room_details' => 'หอประชุม ความจุประมาณ 227 ที่นั่ง พร้อมระบบแสง สี เสียง และเวที', 'capacity' => 40, 'service_rates' => 0, 'status_id' => 1],
            ['building_id' => 5, 'room_id' => 41, 'room_type' => 4, 'room_name' => 'ห้องสัมมนา 443', 'class' => '1', 'room_details' => 'ห้องสัมมนา ความจุประมาณ 81 ที่นั่ง พร้อมทีวีจอใหญ่ และโต๊ะประชุม', 'capacity' => 250, 'service_rates' => 0, 'status_id' => 1],
            ['building_id' => 1, 'room_id' => 42, 'room_type' => 2, 'room_name' => 'ห้องประชุม 147', 'class' => '2', 'room_details' => 'ห้องประชุม ความจุประมาณ 150 ที่นั่ง พร้อมไวท์บอร์ด และกล้องถ่ายทอดสด', 'capacity' => 60, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 8, 'room_id' => 43, 'room_type' => 3, 'room_name' => 'หอประชุม 143', 'class' => '1', 'room_details' => 'หอประชุม ความจุประมาณ 257 ที่นั่ง มีระบบเสียงครบชุด พร้อมเวทีย่อย', 'capacity' => 250, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 5, 'room_id' => 44, 'room_type' => 7, 'room_name' => 'ห้องกิจกรรม 106', 'class' => '4', 'room_details' => 'ห้องกิจกรรม ความจุประมาณ 131 ที่นั่ง พร้อมโต๊ะพับและลำโพงพกพา', 'capacity' => 200, 'service_rates' => 0, 'status_id' => 2],
            ['building_id' => 10, 'room_id' => 45, 'room_type' => 6, 'room_name' => 'ห้องปฏิบัติการ 888', 'class' => '2', 'room_details' => 'ห้องปฏิบัติการ ความจุประมาณ 80 ที่นั่ง พร้อมเครื่องมือทดลองพื้นฐาน และระบบดูดควัน', 'capacity' => 60, 'service_rates' => 0, 'status_id' => 2],
        ];

        foreach ($rooms as $room) {
            DB::table('rooms')->insert([
                'building_id' => $room['building_id'],
                'room_id' => $room['room_id'],
                'room_type' => $room['room_type'],
                'room_name' => $room['room_name'],
                'class' => $room['class'],
                'room_details' => $room['room_details'],
                'image' => 0,
                'capacity' => $room['capacity'],
                'service_rates' => $room['service_rates'],
                'status_id' => $room['status_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
