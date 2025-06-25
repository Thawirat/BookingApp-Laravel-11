<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['ห้องเรียน', 'ห้องประชุม', 'หอประชุม', 'ห้องสัมมนา', 'ห้องคอม', 'ห้องปฏิบัติการ', 'ห้องกิจกรรม','ห้องปฏิบัติการสื่อมัลติมีเดีย'];

        foreach ($types as $type) {
            RoomType::firstOrCreate(['name' => $type]);
        }
    }
}
