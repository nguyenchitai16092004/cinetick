<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThongTinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('thong_tin')->insert([
            [
                'ID_ThongTin' => 1,
                'HoTen' => 'Admin',
                'GioiTinh' => 1,
                'NgaySinh' => '2004-10-15',
                'Email' => '0306221270@caothang.edu.vn',
                'SDT' => '0327216284',
                'created_at' => '2025-05-18 22:26:56',
                'updated_at' => '2025-05-18 22:26:56',
            ],
            [
                'ID_ThongTin' => 2,
                'HoTen' => 'Ca Zan Lo',
                'GioiTinh' => 1,
                'NgaySinh' => '2004-11-04',
                'Email' => 'noname2929aa@gmail.com',
                'SDT' => '0327216284',
                'created_at' => '2025-05-23 23:48:43',
                'updated_at' => '2025-06-04 21:22:00',
            ],
            [
                'ID_ThongTin' => 3,
                'HoTen' => 'Nguyễn Chí Tài',
                'GioiTinh' => 1,
                'NgaySinh' => '2004-09-16',
                'Email' => 'taizxc123ss@gmail.com',
                'SDT' => '0394378614',
                'created_at' => '2025-06-23 02:06:30',
                'updated_at' => '2025-06-23 02:06:30',
            ],
        ]);
    }
}