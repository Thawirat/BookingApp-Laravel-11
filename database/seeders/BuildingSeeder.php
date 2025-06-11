<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); // Create a Faker instance

        $buildings = [
            ['id' => 1, 'building_name' => 'อาคาร 1 สถาบันภาษา ศิลปะและวัฒนธรรม', 'citizen_save' => 'Admin'],
            ['id' => 2, 'building_name' => 'อาคาร 2 คณะครุศาสตร์', 'citizen_save' => 'Admin'],
            ['id' => 3, 'building_name' => 'อาคาร 3 คณะมนุษยศาสตร์และสังคมศาสตร์', 'citizen_save' => 'Admin'],
            ['id' => 4, 'building_name' => 'อาคาร 4 คณะวิทยาการจัดการ', 'citizen_save' => 'Admin'],
            ['id' => 5, 'building_name' => 'อาคาร 5 สำนักงานบัณฑิตวิทยาลัย', 'citizen_save' => 'Admin'],
            ['id' => 6, 'building_name' => 'อาคาร 6 คณะวิทยาศาสตร์และเทคโนโลยี', 'citizen_save' => 'Admin'],
            ['id' => 7, 'building_name' => 'อาคาร 7 คณะวิทยาศาสตร์และเทคโนโลยี', 'citizen_save' => 'Admin'],
            ['id' => 8, 'building_name' => 'อาคาร 8 กาญจนาภิเษก', 'citizen_save' => 'Admin'],
            ['id' => 9, 'building_name' => 'อาคาร 9 ศูนย์วิทยาศาสตร์', 'citizen_save' => 'Admin'],
            ['id' => 10, 'building_name' => 'อาคาร 10 สำนักงานอธิการบดี', 'citizen_save' => 'Admin'],
            ['id' => 13, 'building_name' => 'อาคาร 13 ปฏิบัติการอเนกประสงค์', 'citizen_save' => 'Admin'],
            ['id' => 19, 'building_name' => 'อาคาร 19 อาคารเรียนรวม', 'citizen_save' => 'Admin'],
            ['id' => 31, 'building_name' => 'หอประชุมจามจุรี 1', 'citizen_save' => 'Admin'],
            ['id' => 32, 'building_name' => 'หอประชุมจามจุรี 2', 'citizen_save' => 'Admin'],
            ['id' => 33, 'building_name' => 'หอประชุมกลาง', 'citizen_save' => 'Admin'],
            ['id' => 40, 'building_name' => 'หอประชุมใหญ่', 'citizen_save' => 'Admin'],
        ];

        foreach ($buildings as $building) {
            DB::table('buildings')->insert([
                'id' => $building['id'], // กำหนด ID เอง
                'building_name' => $building['building_name'],
                'citizen_save' => $building['citizen_save'],
                'image' => null, // สร้าง URL รูปภาพตัวอย่าง
                'created_at' => now(),
                'updated_at' => now(),
                'date_save' => now(),
            ]);
        }
    }
}
