<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BinhLuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('binh_luan')->insert([
            [
                'ID_BinhLuan' => 1,
                'DiemDanhGia' => 8.5,
                'ID_TaiKhoan' => 9,
                'ID_Phim' => 4,
                'created_at' => '2025-06-20 06:55:40',
                'updated_at' => '2025-06-20 06:55:40',
            ],
            [
                'ID_BinhLuan' => 6,
                'DiemDanhGia' => 8.75,
                'ID_TaiKhoan' => 9,
                'ID_Phim' => 7,
                'created_at' => '2025-06-20 06:55:40',
                'updated_at' => '2025-06-20 06:55:40',
            ],
        ]);
    }
}