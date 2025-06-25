<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TheLoaiPhimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('the_loai_phim')->insert([
            [
                'ID_TheLoaiPhim' => 1,
                'TenTheLoai' => 'Tình Cảm',
                'Slug' => 'tinh-cam',
                'created_at' => '2024-11-11 11:36:28',
                'updated_at' => '2024-11-11 11:36:28',
            ],
            [
                'ID_TheLoaiPhim' => 2,
                'TenTheLoai' => 'Chiến Tranh',
                'Slug' => 'chien-tranh',
                'created_at' => '2024-11-11 11:36:37',
                'updated_at' => '2024-11-11 11:36:37',
            ],
            [
                'ID_TheLoaiPhim' => 3,
                'TenTheLoai' => 'Hoạt Hình',
                'Slug' => 'hoat-hinh',
                'created_at' => '2024-11-11 11:36:49',
                'updated_at' => '2024-11-11 11:36:49',
            ],
            [
                'ID_TheLoaiPhim' => 4,
                'TenTheLoai' => '3D',
                'Slug' => '3d',
                'created_at' => '2024-11-11 11:37:04',
                'updated_at' => '2024-11-11 11:37:04',
            ],
            [
                'ID_TheLoaiPhim' => 5,
                'TenTheLoai' => 'Thuyết Minh',
                'Slug' => 'thuyet-minh',
                'created_at' => '2024-11-11 11:37:18',
                'updated_at' => '2024-11-11 11:37:18',
            ],
            [
                'ID_TheLoaiPhim' => 6,
                'TenTheLoai' => 'Vietsub',
                'Slug' => 'vietsub',
                'created_at' => '2024-11-11 11:37:35',
                'updated_at' => '2024-11-11 11:37:35',
            ],
            [
                'ID_TheLoaiPhim' => 7,
                'TenTheLoai' => 'Hài',
                'Slug' => 'hai',
                'created_at' => '2024-11-21 20:20:05',
                'updated_at' => '2024-11-21 20:20:05',
            ],
            [
                'ID_TheLoaiPhim' => 8,
                'TenTheLoai' => 'Phiêu Lưu',
                'Slug' => 'phieu-luu',
                'created_at' => '2024-11-21 20:34:03',
                'updated_at' => '2024-11-21 20:34:03',
            ],
            [
                'ID_TheLoaiPhim' => 9,
                'TenTheLoai' => 'Tâm Lý',
                'Slug' => 'tam-ly',
                'created_at' => '2024-11-21 20:34:11',
                'updated_at' => '2024-11-21 20:34:11',
            ],
            [
                'ID_TheLoaiPhim' => 10,
                'TenTheLoai' => 'Hành Động',
                'Slug' => 'hanh-dong',
                'created_at' => '2024-11-21 20:39:31',
                'updated_at' => '2024-12-17 06:10:34',
            ],
            [
                'ID_TheLoaiPhim' => 11,
                'TenTheLoai' => 'Giả tưởng',
                'Slug' => 'gia-tuong',
                'created_at' => '2024-11-21 20:43:37',
                'updated_at' => '2025-06-22 02:46:33',
            ],
            [
                'ID_TheLoaiPhim' => 12,
                'TenTheLoai' => 'Lồng Tiếng',
                'Slug' => 'long-tieng',
                'created_at' => '2024-11-21 20:44:23',
                'updated_at' => '2024-12-01 14:13:46',
            ],
            [
                'ID_TheLoaiPhim' => 13,
                'TenTheLoai' => 'Hành động',
                'Slug' => 'hanh-dong',
                'created_at' => '2024-12-17 06:10:45',
                'updated_at' => '2024-12-17 06:10:45',
            ],
            [
                'ID_TheLoaiPhim' => 14,
                'TenTheLoai' => 'Kinh dị',
                'Slug' => 'kinh-di',
                'created_at' => '2025-05-12 01:38:54',
                'updated_at' => '2025-05-12 01:40:40',
            ],
        ]);
    }
}