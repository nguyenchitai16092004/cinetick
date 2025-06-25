<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TheLoaiCuaPhimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('the_loai_cua_phim')->insert([
            [
                'id' => 1,
                'ID_Phim' => 1,
                'ID_TheLoaiPhim' => 10,
            ],
            [
                'id' => 2,
                'ID_Phim' => 2,
                'ID_TheLoaiPhim' => 10,
            ],
            [
                'id' => 3,
                'ID_Phim' => 3,
                'ID_TheLoaiPhim' => 5,
            ],
            [
                'id' => 4,
                'ID_Phim' => 4,
                'ID_TheLoaiPhim' => 7,
            ],
            [
                'id' => 5,
                'ID_Phim' => 4,
                'ID_TheLoaiPhim' => 13,
            ],
            [
                'id' => 6,
                'ID_Phim' => 5,
                'ID_TheLoaiPhim' => 10,
            ],
            [
                'id' => 7,
                'ID_Phim' => 6,
                'ID_TheLoaiPhim' => 3,
            ],
            [
                'id' => 8,
                'ID_Phim' => 6,
                'ID_TheLoaiPhim' => 7,
            ],
            [
                'id' => 9,
                'ID_Phim' => 6,
                'ID_TheLoaiPhim' => 8,
            ],
            [
                'id' => 10,
                'ID_Phim' => 6,
                'ID_TheLoaiPhim' => 11,
            ],
            [
                'id' => 11,
                'ID_Phim' => 7,
                'ID_TheLoaiPhim' => 13,
            ],
            [
                'id' => 12,
                'ID_Phim' => 7,
                'ID_TheLoaiPhim' => 14,
            ],
        ]);
    }
}