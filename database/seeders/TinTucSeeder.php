<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TinTucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tin_tuc')->insert([
            [
                'ID_TinTuc' => 1,
                'TieuDe' => 'Bùi Thạc Chuyên Và 11 Năm Tâm Huyết Với Địa Đạo: Mặt Trời Trong Bóng Tối',
                'Slug' => 'bui-thac-chuyen-va-11-nam-tam-huyet-voi-dia-dao-mat-troi-trong-bong-toi',
                'NoiDung' => 'Nội dung chi tiết cho ưu đãi đặc biệt tháng 6...',
                'LoaiBaiViet' => 4, // 1: Khuyến mãi
                'LuotThich' => 0,
                'LuotXem' => 1,
                'AnhDaiDien' => 'tin-tuc/1750324767_1135-1_1741937896730.jpg',
                'ID_TaiKhoan' => 9,
                'TrangThai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}