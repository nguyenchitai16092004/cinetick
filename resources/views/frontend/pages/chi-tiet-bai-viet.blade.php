@extends('frontend.layouts.master')
@section('title', 'Chi tiết bài viết')
@section('main')
<link rel="stylesheet" href="{{ asset('frontend/Content/css/chi-tiet-bai-viet.css') }}">

    <nav class="breadcrumb">
        <a href="#">Trang chủ</a> /
        <a href="#">Bình luận phim</a> /
        <span>[Review] Hi Five: Hài Hước, Vô Tri Nhưng Cũng Rất Sâu Sắc</span>
    </nav>

    <main class="review-content">
        <h1>[Review] Hi Five: Hài Hước, Vô Tri Nhưng Cũng Rất Sâu Sắc</h1>
        <div class="review-actions">
            <button id="likeBtn" class="action-btn">
                <i id="likeIcon" class="fa-regular fa-thumbs-up"></i> Thích <span id="likeCount">0</span>
            </button>
            <button class="action-btn share-btn" id="shareBtn">
                <i class="fa-solid fa-share-nodes"></i> Chia sẻ
            </button>
            <div class="views">
                <i class="fa-regular fa-eye"></i> <span id="viewCount">352</span>
            </div>
        </div>
        <p class="intro">
            Dù không quá xuất sắc, những bộ phim hài của Hàn Quốc vẫn luôn chiếm được sự yêu quý của không ít khán giả
            Việt. Mới đây, một tác phẩm mới toanh với phong cách hài hước duyên dáng đang đổ bộ <a href="#">rạp
                chiếu phim</a> với tên <strong>Hi Five</strong> (tựa việt: <strong>Bộ Năm Siêu Đẳng Cấp</strong>).
        </p>
        <div class="review-image">
            <img src="https://cdn.galaxycine.vn/media/2025/5/16/until-dawn-2048_1747365952336.jpg" alt="Cảnh trong phim Hi Five" />
        </div>
    </main>
    <script src=" {{ asset('frontend/Content/js/chi-tiet-bai-viet.js') }}"></script>
@stop
