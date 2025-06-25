<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SuatChieuSeeder extends Seeder
{
    public function run(): void
    {
        $suatChieus = [];
        $id = 1;

        $rapCount = 4;
        $phongCount = 8;

        $gioBatDau = 9;
        $gioKetThuc = 21;
        $maxSuatMotNgay = 6;

        $today = now()->startOfDay();
        $ngayChieuList = [];

        for ($i = 0; $i <= 4; $i++) {
            $ngayChieuList[] = $today->copy()->addDays($i);
        }

        // Lấy danh sách phim và NgayKhoiChieu
        $phimList = DB::table('phim')->select('ID_Phim', 'NgayKhoiChieu')->get();

        foreach ($phimList as $phim) {
            $phimId = $phim->ID_Phim;
            $ngayKhoiChieu = Carbon::parse($phim->NgayKhoiChieu)->startOfDay();

            if ($ngayKhoiChieu->gt($today)) {
                // Nếu NgayKhoiChieu > hôm nay => chỉ tạo 1 suất chiếu duy nhất
                $ngayChieu = $ngayKhoiChieu->copy()->addDays(rand(0, 1));
                $gio = sprintf('%02d:00:00', rand($gioBatDau, $gioKetThuc));
                $rapId = rand(1, $rapCount);
                $phongId = rand(1, $phongCount);

                $suatChieus[] = [
                    'ID_SuatChieu' => $id++,
                    'GioChieu' => $gio,
                    'NgayChieu' => $ngayChieu,
                    'GiaVe' => 90000.00,
                    'ID_PhongChieu' => $phongId,
                    'ID_Phim' => $phimId,
                    'ID_Rap' => $rapId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                // Phim đang chiếu → tạo suất chiếu trong 5 ngày
                foreach ($ngayChieuList as $ngayChieu) {
                    $gioChieuUsed = [];
                    $soSuat = rand(3, $maxSuatMotNgay);

                    for ($i = 0; $i < $soSuat; $i++) {
                        // Random giờ không trùng trong 1 ngày
                        do {
                            $gio = sprintf('%02d:00:00', rand($gioBatDau, $gioKetThuc));
                        } while (in_array($gio, $gioChieuUsed));
                        $gioChieuUsed[] = $gio;

                        // Tạo suất chiếu ở nhiều rạp khác nhau
                        foreach (range(1, rand(1, $rapCount)) as $rapId) {
                            $phongId = rand(1, $phongCount);

                            $suatChieus[] = [
                                'ID_SuatChieu' => $id++,
                                'GioChieu' => $gio,
                                'NgayChieu' => $ngayChieu,
                                'GiaVe' => 90000.00,
                                'ID_PhongChieu' => $phongId,
                                'ID_Phim' => $phimId,
                                'ID_Rap' => $rapId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
            }
        }

        DB::table('suat_chieu')->insert($suatChieus);
    }
}