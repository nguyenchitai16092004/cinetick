@extends('frontend.layouts.master')
@section('title', 'Bài viết điện ảnh')
@section('main')
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/dien-anh.css') }}">
    <div class="container-blog">
        <div class="section-title">PHIM HAY THÁNG</div>
        <div class="section-title-bar"></div>
        <div class="movie-list">
            <!-- Movie 1 -->
            <div class="movie-item">
                <img class="movie-thumb" src="https://static2.galaxycine.vn/media/2024/5/30/banner-web_1717059037596.jpg"
                    alt="Tết Thiếu Nhi">
                <div class="movie-detail">
                    <div class="movie-title">Phim Hay Tháng 06.2025: Tết Thiếu Nhi</div>
                    <div class="movie-actions">
                        <button class="btn-like" onclick="likeMovie(this)">
                            <i class="fa-regular fa-thumbs-up"></i> Thích
                        </button>
                        <div class="like-count">
                            <i class="fa-regular fa-user"></i>
                            <span class="like-num">0</span>
                        </div>
                    </div>
                    <p class="movie-desc">
                        Để các khối còn lại có thể bình tâm thư giãn, đôi khi là “thao túng” được khối nghỉ hè, hãy đến với
                        <strong>Galaxy Cinema</strong>, nơi có vô vàn những bộ phim cực kì thú vị dành cho mọi người, mọi
                        nhà.
                    </p>
                </div>
            </div>
            <!-- Movie 2 -->
            <div class="movie-item">
                <img class="movie-thumb"
                    src="https://static2.galaxycine.vn/media/2024/4/29/cum-phim-meo-web_1714365680058.jpg" alt="Cún pk Mèo">
                <div class="movie-detail">
                    <div class="movie-title">Phim Hay Tháng 05.2025: Mèo pk. Chó</div>
                    <div class="movie-actions">
                        <button class="btn-like" onclick="likeMovie(this)">
                            <i class="fa-regular fa-thumbs-up"></i> Thích
                        </button>
                        <div class="like-count">
                            <i class="fa-regular fa-user"></i>
                            <span class="like-num">0</span>
                        </div>
                    </div>
                    <p class="movie-desc">
                        <strong>Galaxy Cinema</strong> quyết định mở một cuộc chiến giữa “chó” Stitch và mèo Doraemon tại <a
                            href="#">rạp chiếu phim</a> để các Stars tha hồ giải trí.
                    </p>
                </div>
            </div>
            <!-- Movie 3 -->
            <div class="movie-item">
                <img class="movie-thumb" src="https://static2.galaxycine.vn/media/2024/3/28/web_1711618746352.jpg"
                    alt="Mặt Trời Trong Bóng Tối">
                <div class="movie-detail">
                    <div class="movie-title">Phim Hay Tháng 04.2025: Mặt Trời Trong Bóng Tối</div>
                    <div class="movie-actions">
                        <button class="btn-like" onclick="likeMovie(this)">
                            <i class="fa-regular fa-thumbs-up"></i> Thích
                        </button>
                        <div class="like-count">
                            <i class="fa-regular fa-user"></i>
                            <span class="like-num">0</span>
                        </div>
                    </div>
                    <p class="movie-desc">
                        Ngoài những phim hay như sự trở lại của thám tử Kiên trong <strong>Người Vợ Cuối Cùng</strong>, phần
                        thứ 8 của chuỗi phim <strong>Lật Mặt</strong> do đạo diễn Lý Hải sản xuất, khán giả còn được thưởng
                        thức tác phẩm mang đậm dấu ấn lịch sử là <b>Địa Đạo: Mặt Trời Trong Bóng Tối</b> – hoàn toàn phù
                        h...
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script src=" {{ asset('frontend/Content/js/dien-anh.js') }}"></script>
@stop
