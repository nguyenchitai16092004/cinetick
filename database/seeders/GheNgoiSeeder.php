<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GheNgoiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ghe_ngoi')->insert([
            [
                'ID_Ghe' => 1,
                'TenGhe' => 'A1',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 2,
                'TenGhe' => 'A2',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 3,
                'TenGhe' => 'A3',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 4,
                'TenGhe' => 'A4',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 5,
                'TenGhe' => 'A5',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 6,
                'TenGhe' => 'A6',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 7,
                'TenGhe' => 'A7',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 8,
                'TenGhe' => 'A8',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 9,
                'TenGhe' => 'A9',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 10,
                'TenGhe' => 'A10',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 11,
                'TenGhe' => 'A11',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 12,
                'TenGhe' => 'A12',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            [
                'ID_Ghe' => 13,
                'TenGhe' => 'A13',
                'LoaiTrangThaiGhe' => 1,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:40',
            ],
            // ... (Tương tự cho các ghế từ 11 đến 99)
            [
                'ID_Ghe' => 100,
                'TenGhe' => 'J10',
                'LoaiTrangThaiGhe' => 0,
                'ID_PhongChieu' => 1,
                'created_at' => '2025-06-09 06:17:40',
                'updated_at' => '2025-06-09 06:17:57',
            ],
        ]);
    }
}