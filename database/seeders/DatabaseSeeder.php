<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        User::factory(10)->create();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
         */
        $this->call(ThongTinTrangWebSeeder::class);
        $this->call(RapSeeder::class);
        $this->call(TheLoaiPhimSeeder::class);
        $this->call(PhongChieuSeeder::class);
        $this->call(GheNgoiSeeder::class);
        $this->call(PhimSeeder::class);
        $this->call(TheLoaiCuaPhimSeeder::class);
        $this->call(SuatChieuSeeder::class);
        $this->call(ThongTinSeeder::class);
        $this->call(TaiKhoanSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(BinhLuanSeeder::class);
        $this->call(HoaDonSeeder::class);
        $this->call(VeXemPhimSeeder::class);
        $this->call(TinTucSeeder::class);
    }
}