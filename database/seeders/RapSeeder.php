<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rap')->insert([
            [
                'ID_Rap'          => 1,
                'TenRap'    => 'CineTick Nguyễn Du',
                'HinhAnh'   => 'rap/cinetick-nguyen-du.jpg',
                'Slug'      => 'cinetick-nguyen-du',
                'DiaChi'    => '116 Nguyễn Du, Quận 1, Tp.HCM',
                'TrangThai' => true,
                'MoTa'      =>
                'Là rạp chiếu đầu tiên và đông khách nhất trong hệ thống, Galaxy Nguyễn Du chính thức đi vào hoạt động từ ngày 20/5/2005 và được xem là một trong những cụm rạp mang tiêu chuẩn quốc tế hiện đại bậc nhất đầu tiên xuất hiện tại Việt Nam. Galaxy Nguyễn Du là một trong những rạp chiếu phim tiên phong mang đến cho khán giả những trải nghiệm phim chiếu rạp tốt nhất.    Galaxy Nguyễn Du gồm 5 phòng chiếu với hơn 1000 chỗ ngồi, trong đó có 1 phòng chiếu phim 3D và 4 phòng chiếu phim 2D, với hơn 1000 chỗ ngồi được thiết kế tinh tế giúp khách hàng có thể xem những bộ phim hay một cách thoải mái và thuận tiện nhất. Chất lượng hình ảnh rõ nét, âm thanh Dolby 7.1 cùng màn hình chiếu kỹ thuật 3D và Digital vô cùng sắc mịn, mang đến một không gian giải trí vô cùng sống động.   Bên cạnh đó, với lợi thế gần khu vực sầm uất bậc nhất ở trung tâm thành phố, bãi để xe rộng rãi, có tiệm cafe ngoài trời – đây là nơi cực thu hút bạn trẻ đến xem phim và check-in.',
                'Hotline'   => '19002224',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ID_Rap'          => 2,
                'TenRap'    => 'CineTick Tân Bình',
                'HinhAnh'   => 'rap/cinetick-tan-binh.jpg',
                'Slug'      => 'cinetick-tan-binh',
                'DiaChi'    => '246 Nguyễn Hồng Đào, Q.TB, Tp.HCM',
                'TrangThai' => true,
                'MoTa'      =>
                'CineTick Tân Bình, hay còn được các Stars quen gọi là “CineTick Nguyễn Hồng Đào” do tọa lạc tại số 246 Nguyễn Hồng Đào, Q.TB, Tp.HCM.   Với diện tích hơn 3000 m2 gồm 5 phòng chiếu , CineTick Tân Bình được đánh giá như một thế giới Hollywood thu nhỏ của TP.HCM. Cùng sự hỗ trợ tư vấn thiết kế và lắp đặt bởi các chuyên gia của Tập đoàn Warner Bros đến từ Hollywood, các phòng chiếu 2D và 3D với màn hình chiếu sắc nét và dàn âm thanh Dolby 7.1 bậc nhất tại Việt Nam. Đây là cụm rạp chiếu phim đầu tiên của CineTick được trang bị hệ thống âm thanh Dolby 7.1 và hệ thống chiếu phim Digital khiến chất lượng hình ảnh và âm thanh của những bộ phim hay càng sống động, tuyệt vời bậc nhất. CineTick Tân Bình là một trong những cụm rạp đông khách nhất của CineTick Cinema và luôn cập nhật nhanh chóng nhất những bộ phim mới để phục vụ cho nhu cầu của khán giả. ',
                'Hotline'   => '19002224',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ID_Rap'          => 3,
                'TenRap'    => 'CineTick Huỳnh Tấn Phát',
                'HinhAnh'   => 'rap/cinetick-huynh-tan-phat.jpg',
                'Slug'      => 'cinetick-huynh-tan-phat',
                'DiaChi'    => 'Lầu 2, TTTM Coopmart, số 1362 Huỳnh Tấn Phát, khu phố 1, Phường Phú Mỹ, Quận 7, Tp.Hồ Chí Minh, Việt Nam.',
                'TrangThai' => true,
                'MoTa'      =>
                'Tọa lạc tại lầu 2 siêu thị Coopmart - 1362 Đường Huỳnh Tấn Phát, Khu phố 1, Phường Phú Mỹ, Quận 7, TPHCM, rạp chiếu phim CineTick Huỳnh Tấn Phát được xây dựng theo tiêu chuẩn quốc tế với hệ thống phòng chiếu định dạng 2D & 3D đáp ứng tốt nhất nhu cầu xem phim của khán giả. Màn hình sắc nét và hệ thống âm thanh vòm Dolby 7.1 hiện đại mang đến những trải nghiệm sống động như thật. Mục tiêu của CineTick Huỳnh Tấn Phát là giúp dân cư ở khu vực quận 7, thị trấn Nhà Bè và khu vực lân cận có nhiều lựa chọn vui chơi giải trí hơn. Với giá vé hợp túi tiền, phim hay phim mới được cập nhật liên tục, không gian trẻ trung và nhân viên thân thiện, chắc chắn đây sẽ là một trải nghiệm mới lạ và tuyệt vời.',
                'Hotline'   => '19002224',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ID_Rap'          => 4,
                'TenRap'    => 'CineTick Bến Tre',
                'HinhAnh'   => 'rap/cinetick-ben-tre.jpg',
                'Slug'      => 'cinetick-ben-tre',
                'DiaChi'    => 'Lầu 1, TTTM Sense City 26A Trần Quốc Tuấn, Phường An Hội, TP. Bến Tre',
                'TrangThai' => true,
                'MoTa'      =>
                'Nằm trong khu phức hợp mua sắm và giải trí Co.opmart-Sense City, tọa lạc trên cung đường nhộn nhịp và tấp nập nhất thành phố, CineTick Bến Tre là cụm rạp hiện đại đầu tiên xuất hiện trong khu vực. Với 5 phòng chiếu tối tân đều sẽ được trang bị hệ thống hình ảnh Digital đạt chuẩn, âm thanh Dolby 7.1, khả năng trình chiếu phim 3D chất lượng, sẽ đảm bảo những trải nghiệm tuyệt vời nhất, “đã tai đã mắt” nhất trong rạp chiếu bóng. Với chất lượng “mang Hollywood đến gần bạn”, nhưng giá vé tại CineTic Bến Tre vẫn rất “Việt Nam”, vừa túi tiền với đa số người dân thành phố. Cùng với đội ngũ nhân viên chuyên nghiệp, phục vụ tận tình, thế giới điện ảnh tuyệt diệu ngày càng trở nên gần gũi và thân thuộc với mọi đối tượng khán giả ở mọi lứa tuổi.   Đến ngay CineTic Bến Tre - Lầu 1, TTTM Sense City 26A Trần Quốc Tuấn, Phường An Hội, TP. Bến Tre thưởng thức loạt phim hay phim mới xuất sắc nào!',
                'Hotline'   => '19002224',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}