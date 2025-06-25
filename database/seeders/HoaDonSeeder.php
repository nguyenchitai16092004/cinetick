<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HoaDonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hoa_don')->insert([
            [
                'ID_HoaDon' => '69X8PCW',
                'TongTien' => 2000.00,
                'SoTienGiam' => 0.00,
                'PTTT' => 'PayOS',
                'ID_TaiKhoan' => 9,
                'SoLuongVe' => 1,
                'TrangThaiXacNhanHoaDon' => 1,
                'TrangThaiXacNhanThanhToan' => 1,
                'order_code' => '208227037',
                'SoTaiKhoan' => '0394378614',
                'TenTaiKhoan' => 'VND-TGTT-PHAM HUYNH DANG KHOA',
                'TenNganHang' => 'Techcombank',
                'created_at' => '2025-06-20 15:41:48',
                'updated_at' => '2025-06-20 15:41:48',
            ],
            [
                'ID_HoaDon' => 'GNPSAQ7',
                'TongTien' => 2000.00,
                'SoTienGiam' => 0.00,
                'PTTT' => 'PayOS',
                'ID_TaiKhoan' => 9,
                'SoLuongVe' => 1,
                'TrangThaiXacNhanHoaDon' => 1,
                'TrangThaiXacNhanThanhToan' => 1,
                'order_code' => '934013384',
                'SoTaiKhoan' => '0394378614',
                'TenTaiKhoan' => 'NGUYEN CHI TAI',
                'TenNganHang' => 'MB Bank (Ngân hàng TMCP Quân đội)',
                'created_at' => '2025-06-21 10:25:20',
                'updated_at' => '2025-06-21 10:25:20',
            ],
            [
                'ID_HoaDon' => 'P9270FT',
                'TongTien' => 2000.00,
                'SoTienGiam' => 0.00,
                'PTTT' => 'PayOS',
                'ID_TaiKhoan' => 9,
                'SoLuongVe' => 1,
                'TrangThaiXacNhanHoaDon' => 1,
                'TrangThaiXacNhanThanhToan' => 1,
                'order_code' => '737027883',
                'SoTaiKhoan' => '394378614',
                'TenTaiKhoan' => 'NGUYEN CHI TAI',
                'TenNganHang' => null,
                'created_at' => '2025-06-20 06:09:55',
                'updated_at' => '2025-06-20 06:09:55',
            ],
            [
                'ID_HoaDon' => 'YGY4BCD',
                'TongTien' => 2000.00,
                'SoTienGiam' => 0.00,
                'PTTT' => 'PayOS',
                'ID_TaiKhoan' => 9,
                'SoLuongVe' => 1,
                'TrangThaiXacNhanHoaDon' => 1,
                'TrangThaiXacNhanThanhToan' => 1,
                'order_code' => '575024955',
                'SoTaiKhoan' => '0394378614',
                'TenTaiKhoan' => 'NGUYEN CHI TAI',
                'TenNganHang' => 'MB Bank (Ngân hàng TMCP Quân đội)',
                'created_at' => '2025-06-20 06:16:52',
                'updated_at' => '2025-06-20 06:16:52',
            ],
        ]);
    }
}