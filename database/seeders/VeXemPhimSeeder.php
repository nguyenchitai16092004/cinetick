<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VeXemPhimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ve_xem_phim')->insert([
            [
                'ID_Ve' => 1,
                'TenGhe' => 'A4',
                'TenPhim' => 'Lilo & Stitch',
                'NgayXem' => '2025-06-20',
                'DiaChi' => 'Tầng 5, Vincom Center Landmark 81, 772 Điện Biên Phủ, Quận Bình Thạnh, TP.HCM',
                'GiaVe' => 2000.00,
                'TrangThai' => 0,
                'ID_SuatChieu' => 1,
                'ID_HoaDon' => 'P9270FT',
                'ID_Ghe' => 4,
                'created_at' => '2025-06-20 06:09:55',
                'updated_at' => '2025-06-20 06:09:55',
            ],
            [
                'ID_Ve' => 2,
                'TenGhe' => 'A5',
                'TenPhim' => 'Lilo & Stitch',
                'NgayXem' => '2025-06-20',
                'DiaChi' => 'Tầng 5, Vincom Center Landmark 81, 772 Điện Biên Phủ, Quận Bình Thạnh, TP.HCM',
                'GiaVe' => 2000.00,
                'TrangThai' => 0,
                'ID_SuatChieu' => 1,
                'ID_HoaDon' => 'YGY4BCD',
                'ID_Ghe' => 5,
                'created_at' => '2025-06-20 06:16:52',
                'updated_at' => '2025-06-20 06:16:52',
            ],
            [
                'ID_Ve' => 3,
                'TenGhe' => 'A7',
                'TenPhim' => 'Lilo & Stitch',
                'NgayXem' => '2025-06-21',
                'DiaChi' => 'Tầng 5, Vincom Center Landmark 81, 772 Điện Biên Phủ, Quận Bình Thạnh, TP.HCM',
                'GiaVe' => 2000.00,
                'TrangThai' => 0,
                'ID_SuatChieu' => 1,
                'ID_HoaDon' => '69X8PCW',
                'ID_Ghe' => 7,
                'created_at' => '2025-06-20 15:41:48',
                'updated_at' => '2025-06-20 15:41:48',
            ],
            [
                'ID_Ve' => 4,
                'TenGhe' => 'A6',
                'TenPhim' => 'Lilo & Stitch',
                'NgayXem' => '2025-06-22',
                'DiaChi' => 'Tầng 5, Vincom Center Landmark 81, 772 Điện Biên Phủ, Quận Bình Thạnh, TP.HCM',
                'GiaVe' => 2000.00,
                'TrangThai' => 0,
                'ID_SuatChieu' => 1,
                'ID_HoaDon' => 'GNPSAQ7',
                'ID_Ghe' => 6,
                'created_at' => '2025-06-21 10:25:20',
                'updated_at' => '2025-06-21 10:25:20',
            ],
        ]);
    }
}