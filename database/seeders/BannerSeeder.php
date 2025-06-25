<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('banners')->insert([
            [
                'id' => 1,
                'TieuDeChinh' => 'Elio',
                'TieuDePhu' => 'Elio Cậu Bé Đến Từ Trái Đất',
                'MoTa' => 'Cậu bé Elio vô tình bị cuốn vào một liên minh liên thiên hà và bị nhầm là lãnh đạo Trái Đất, buộc phải kết nối với sinh vật ngoài hành tinh để cứu lấy cả vũ trụ và khám phá bản thân.',
                'HinhAnh' => 'banners/3y5TyOrbYvi05HYLM4hdqHfcBmkr6JgfOCVKb3GP.jpg',
                'Link' => '/phim/chi-tiet-phim/elio-cau-be-den-tu-trai-dat',
                'created_at' => '2025-06-22 03:40:25',
                'updated_at' => '2025-06-22 05:32:03',
            ],
            [
                'id' => 2,
                'TieuDeChinh' => 'Mùa hè đã đến',
                'TieuDePhu' => 'Xem Phim Real Chất – Siêu Siêu Sảng Khoái – Khám Phá Vinwonders',
                'MoTa' => 'Mùa hè bom tấn đã cập bến CineTick nhưng có chút khác biệt so với thường lệ.',
                'HinhAnh' => 'banners/WEYwUYQdUPAehG7U3unGRM7KPA2UfdhHhfWbVvAG.jpg',
                'Link' => '/goc-dien-anh/xem-phim-real-chat-sieu-sieu-sang-khoai-kham-pha-vinwonders',
                'created_at' => '2025-06-22 05:38:57',
                'updated_at' => '2025-06-22 05:42:33',
            ],
            [
                'id' => 3,
                'TieuDeChinh' => 'Cơn ác mộng chưa kết thúc',
                'TieuDePhu' => '28 Năm sau tận thế',
                'MoTa' => 'Liệu niềm hy vọng cuối cùng có đủ để cứu họ khỏi vực thẳm tuyệt vọng?',
                'HinhAnh' => 'banners/bannerData_1.jpg',
                'Link' => '/phim/chi-tiet-phim/1750581769_28-year-later-500_1750407074215.jpg',
                'created_at' => '2025-06-22 05:38:57',
                'updated_at' => '2025-06-22 05:42:33',
            ],
            [
                'id' => 4,
                'TieuDeChinh' => 'Huyền thoại chưa từng được gọi tên',
                'TieuDePhu' => 'F1®',
                'MoTa' => 'F1® kể về Sonny Hayes (Brad Pitt) được mệnh danh là "Huyền thoại chưa từng được gọi tên" là ngôi sao sáng giá nhất của FORMULA 1 trong những năm 1990 cho đến khi một vụ tai nạn trên đường đua suýt nữa đã kết thúc sự nghiệp của anh.',
                'HinhAnh' => 'banners/bannerData_3.jpg',
                'Link' => '/phim/chi-tiet-phim/f1',
                'created_at' => '2025-06-22 05:38:57',
                'updated_at' => '2025-06-22 05:42:33',
            ],
            [
                'id' => 5,
                'TieuDeChinh' => 'Ma không đầu',
                'TieuDePhu' => 'Liệu họ có hoàn thành nhiệm vụ "khó nhằn" này hay sẽ gặp phải những "biến cố" nào?',
                'MoTa' => 'Bộ đôi Tiến Luật và Ngô Kiến Huy, với nghề nghiệp "độc lạ" hốt xác và lái xe cứu thương, hứa hẹn mang đến những tràng cười không ngớt cho khán giả qua hành trình tìm xác có một không hai trên màn ảnh Việt.',
                'HinhAnh' => 'banners/bannerData_4.jpg',
                'Link' => '/phim/chi-tiet-phim/ma-khong-dau',
                'created_at' => '2025-06-22 05:38:57',
                'updated_at' => '2025-06-22 05:42:33',
            ],
            [
                'id' => 7,
                'TieuDeChinh' => 'Halabala: Rừng Ma Tế Xác',
                'TieuDePhu' => 'Thanh tra Dan – kẻ mang biệt danh rùng rợn “Dan Trăm Xác”',
                'MoTa' => 'Thanh tra Dan – kẻ mang biệt danh rùng rợn “Dan Trăm Xác” – là một cảnh sát liều mạng, nổi tiếng với quá khứ đẫm máu và những phi vụ bất chấp luật lệ.',
                'HinhAnh' => 'banners/bannerData_5.jpg',
                'Link' => '/phim/chi-tiet-phim/halabala-rung-ma-te-xac',
                'created_at' => '2025-06-22 05:38:57',
                'updated_at' => '2025-06-22 05:42:33',
            ],
        ]);
    }
}