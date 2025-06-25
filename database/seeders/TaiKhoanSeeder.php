<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaiKhoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tai_khoan')->insert([
            [
                'ID_TaiKhoan' => 9,
                'TenDN' => 'ad_cinetick',
                'MatKhau' => '$2y$12$v1mQAJJrRDrmt3JY.SxlaObg.PFDdutHl.h6BYU9aDk02QwNRIDi6',
                'VaiTro' => 2,
                'TrangThai' => 1,
                'ID_ThongTin' => 1,
                'created_at' => '2025-05-18 22:26:56',
                'updated_at' => '2025-06-24 04:19:31',
                'token_xac_nhan' => null,
            ],
            [
                'ID_TaiKhoan' => 11,
                'TenDN' => 'CaZanLo',
                'MatKhau' => '$2y$12$oIINU0V8od/5GjHW5/Jtn.t6wbQrdCObixtGLDvxhhFKlahUIjXGu',
                'VaiTro' => 0,
                'TrangThai' => 1,
                'ID_ThongTin' => 2,
                'created_at' => '2025-05-23 23:48:43',
                'updated_at' => '2025-06-04 21:09:56',
                'token_xac_nhan' => null,
            ],
            [
                'ID_TaiKhoan' => 15,
                'TenDN' => 'taifhoiwdz',
                'MatKhau' => '$2y$12$Me97eBjPBFA7fJ/njm0H/uzcDtIfqySjfT/fkLbAnAaGRyg.oUQvS',
                'VaiTro' => 0,
                'TrangThai' => 1,
                'ID_ThongTin' => 3,
                'created_at' => '2025-06-23 02:06:30',
                'updated_at' => '2025-06-23 07:36:02',
                'token_xac_nhan' => null,
            ],
        ]);
    }
}