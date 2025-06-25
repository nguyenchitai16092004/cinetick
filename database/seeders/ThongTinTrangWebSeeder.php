<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThongTinTrangWebSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('thong_tin_trang_web')->insert([
            [
                'Id' => 1,
                'Logo' => 'logos/dbQVBQWbUWBuroe9nBWXaPrNRZs0ZFnsfW2ucpM4.png',
                'Icon' => 'icons/zU2QrD1K1kks52bodGpmwWVinmMwComnju0ViEGN.png',
                'TenDonVi' => 'ĐỒ ÁN TỐT NGHIỆP CỦA SINH VIÊN 0306221270 - NGUYỄN CHÍ TÀI | 0306221271 - TRƯƠNG THÀNH TÀI',
                'TenWebsite' => 'CineTick',
                'Zalo' => 'https://chat.zalo.me/',
                'Facebook' => 'https://www.facebook.com/',
                'Instagram' => 'https://www.instagram.com/',
                'Youtube' => 'https://www.youtube.com/',
                'Email' => 'taikhoan.cinetick@gmail.com',
                'DiaChi' => '3/9 Võ Văn Tần, Phường Võ Thị Sáu, Quận 3, Tp. Hồ Chí Minh, Việt Nam',
                'Hotline' => '190022244',
                'created_at' => '2025-06-20 10:16:32',
                'updated_at' => '2025-06-23 08:24:48',
            ],
        ]);
    }
}