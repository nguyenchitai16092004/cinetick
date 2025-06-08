<!-- Footer Start -->
<footer class="filmoja-footer-area">
    <div class="footer-top-area section_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-6">
                    <div class="single-footer-widget">
                        <a href="#"><img width="300px" src="Content/img/logoCineTick.png" alt="footer logo" /></a>

                        <div class="footer-contact">
                            <p>Support: <a href="#">support@cinetick.vn</a></p>
                            <p>Hotline: <a href="tell:1900 1722">1900 1722</a></p>
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="single-footer-widget">
                        <h3>Chính sách</h3>
                        <ul>
                            <li><a href="{{ route('chinh-sach.dieu-khoan-chung') }}"><i
                                        class="fa fa-angle-double-right"></i>Điều khoản chung</a></li>
                            <li><a href="{{ route('chinh-sach.thanh-toan') }}"><i
                                        class="fa fa-angle-double-right"></i>Chính sách thanh toán</a></li>
                            <li><a href="{{ route('chinh-sach.giao-nhan') }}"><i
                                        class="fa fa-angle-double-right"></i>Chính sách giao nhận</a>
                            </li>
                            <li><a href="{{ route('chinh-sach.bao-mat-thong-tin') }}"><i
                                        class="fa fa-angle-double-right"></i>Bảo mật thông tin</a></li>
                            <li><a href="{{ route('chinh-sach.kiem-hang-doi-tra-hoan-tien') }}"><i
                                        class="fa fa-angle-double-right"></i>Kiểm hàng, đổi trả hoàn tiền</a></li>
                            <li><a href="{{ route('cau-hoi-thuong-gap') }}"><i
                                        class="fa fa-angle-double-right"></i>Câu hỏi thường gặp</a></li>

                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="single-footer-widget">
                        <h3>Download App</h3>
                        <div class="footer-app-box">
                            <p>Tải ngay ứng dụng đặt vé online cho dế yêu của bạn !</p>
                            <ul class="apps-list">
                                <li><a href="#"><img src="Content/img/app-1.jpg" alt="app image" /></a></li>
                                <li><a href="#"><img src="Content/img/app-2.jpg" alt="app image" /></a></li>
                            </ul>
                            <ul class="apps-list">
                                <li><a href="#" target="_blank"><img alt="" title=""
                                            src="Content/img/congthuong.png"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            {{-- <h3 style="color:#fff">CÔNG TY CỔ PHẦN ENTERTAINMENT 2020</h3>
            <p>
                Giấy chứng nhận đăng kí doanh nghiệp công ty cổ phần: 0402021264 đăng ký lần đầu ngày 03/01/2020
            </p>
            <p>
                Cơ quan cấp : Phòng Đăng ký kinh doanh - Sở kế hoạch và đầu tư Thành phố Đà Nẵng
            </p>
            <p>
                Địa chỉ: Tầng 4, Tòa nhà Nguyễn Kim, 46 Điện Biên Phủ, TP. Đà Nẵng, Việt Nam
            </p>
            <p style="color:#fff">
                Hotline (Đường dây nóng) : <a href="tel:19001722">19001722</a>
            </p> --}}
        </div>
    </div>
    <div class="copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright-box">
                        <p>2025 © CineTick. All rights reserved - Design by T2Cinema
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<section>
    <div id="mobile-menu">
        <ul>
            <li><a class="" href="index.html" style="background-color:#2b2b31;"><i
                        class="fa fa-home"></i><br />Trang Chủ</a></li>
            <li>
                <p onclick="toggleMBooking()" style="background-color: #2b2b31;"><i class="fa fa-ticket"></i><br /> Đặt
                    V&#233;</p>
            </li>
            <li><a class="" href="lich-chieu.html" style="background-color: #2b2b31;"><i
                        class="fa fa-film"></i><br /> Lịch Chiếu</a></li>
            <li><a href="uu-dai.html" style="background-color: #2b2b31;"><i class="fa fa-gift"></i><br /> Ưu
                   Đãi</a></li>
        </ul>
    </div>

</section>
<section>
    <div class="mobile-book">
        <select class="slMTheater" onchange="selectMServer(this)">
            <option value="0">Rạp</option>
        </select>
        <select class="slMFilm" onchange="selectMFilm(this)">
            <option value="0">Phim</option>
        </select>
        <select class="slMDate" onchange="selectMDate(this)">
            <option value="0">Ng&#224;y chiếu</option>
        </select>
        <select class="slMTime" onchange="selectMTime(this)">
            <option value="0">Suất chiếu</option>
        </select>
        <a id="btnMBooking" onclick="btnMBooking()">Mua V&#233; Ngay</a>
    </div>
</section>


<!-- Load Facebook SDK for JavaScript -->

<div class="modal fade" id="adsImageModal" role="dialog" style="background: rgba(51, 51, 51, 0.46);">
    <div class="modal-dialog" style="width:95%;max-width:95%;margin:100px auto;">
        <!-- Modal content-->
        <div class="modal-content" style="    max-width: 640px;margin: 0 auto;">
            <div class="modal-body" style="padding:0">
                <img id="adsImage" style="    border: 3px solid #000;border-radius: 3px;width:100%"
                    src="#" />
            </div>
        </div>
    </div>
</div>
