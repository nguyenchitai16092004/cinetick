<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhongChieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('phong_chieu')->insert([
            [
                'ID_PhongChieu'   => 1,
                'ID_Rap'          => 2,
                'TenPhongChieu'   => 'Phòng Chiếu A',
                'LoaiPhong'       => 1,
                'TrangThai'       => 1,
                'SoLuongGhe'      => 35,
                'HangLoiDi'       => '["4"]',
                'CotLoiDi'        => '["2"]',
                'created_at'      => '2025-05-10 13:32:00',
                'updated_at'      => '2025-06-03 05:58:25',
            ],
            [
                'ID_PhongChieu'   => 2,
                'ID_Rap'          => 2,
                'TenPhongChieu'   => 'Phòng chiếu B',
                'LoaiPhong'       => 0,
                'TrangThai'       => 1,
                'SoLuongGhe'      => 100,
                'HangLoiDi'       => '["8"]',
                'CotLoiDi'        => '["1"]',
                'created_at'      => '2025-05-11 23:51:47',
                'updated_at'      => '2025-05-26 10:52:05',
            ],
            [
                'ID_PhongChieu'   => 3,
                'ID_Rap'          => 1,
                'TenPhongChieu'   => 'Phòng Chiếu C',
                'LoaiPhong'       => 0,
                'TrangThai'       => 1,
                'SoLuongGhe'      => 63,
                'HangLoiDi'       => '[]',
                'CotLoiDi'        => '["6"]',
                'created_at'      => '2025-05-12 11:02:07',
                'updated_at'      => '2025-06-05 23:04:35',
            ],
            [
                'ID_PhongChieu'   => 4,
                'ID_Rap'          => 1,
                'TenPhongChieu'   => 'Phòng Chiếu D',
                'LoaiPhong'       => 0,
                'TrangThai'       => 1,
                'SoLuongGhe'      => 36,
                'HangLoiDi'       => '["3"]',
                'CotLoiDi'        => '["2"]',
                'created_at'      => '2025-05-23 10:29:25',
                'updated_at'      => '2025-06-05 22:54:58',
            ],
            [
                'ID_PhongChieu'   => 5,
                'ID_Rap'          => 3,
                'TenPhongChieu'   => 'Phòng Chiếu 1',
                'LoaiPhong'       => 0,
                'TrangThai'       => 1,
                'SoLuongGhe'      => 99,
                'HangLoiDi'       => '[]',
                'CotLoiDi'        => '["3"]',
                'created_at'      => '2025-05-23 10:30:05',
                'updated_at'      => '2025-06-09 06:17:56',
            ],
            [
                'ID_PhongChieu'   => 6,
                'ID_Rap'          => 3,
                'TenPhongChieu'   => 'Phòng chiếu số 2',
                'LoaiPhong'       => 0,
                'TrangThai'       => 1,
                'SoLuongGhe'      => 60,
                'HangLoiDi'       => '["4"]',
                'CotLoiDi'        => '["2"]',
                'created_at'      => '2025-05-23 10:30:26',
                'updated_at'      => '2025-05-23 10:30:26',
            ],
            [
                'ID_PhongChieu'   => 7,
                'ID_Rap'          => 4,
                'TenPhongChieu'   => 'Phòng Chiếu 3',
                'LoaiPhong'       => 0,
                'TrangThai'       => 1,
                'SoLuongGhe'      => 84,
                'HangLoiDi'       => '["5"]',
                'CotLoiDi'        => '["2"]',
                'created_at'      => '2025-05-23 10:30:45',
                'updated_at'      => '2025-05-23 10:30:45',
            ],
            [
                'ID_PhongChieu'   => 8,
                'ID_Rap'          => 4,
                'TenPhongChieu'   => 'Phòng Chiếu 4',
                'LoaiPhong'       => 0,
                'TrangThai'       => 1,
                'SoLuongGhe'      => 84,
                'HangLoiDi'       => '["5"]',
                'CotLoiDi'        => '["2"]',
                'created_at'      => '2025-05-23 10:30:45',
                'updated_at'      => '2025-05-23 10:30:45',
            ],
        ]);
    }
}