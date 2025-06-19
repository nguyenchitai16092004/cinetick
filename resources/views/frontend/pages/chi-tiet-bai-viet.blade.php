@extends('frontend.layouts.master')
@section('title', 'Chi tiết bài viết')
@section('main')
<link rel="stylesheet" href="{{ asset('frontend/Content/css/chi-tiet-bai-viet.css') }}">

    <nav class="breadcrumb">
        <a href="#">Trang chủ</a> /
        <a href="#">Góc điện ảnh</a> /
        <span>{{ $tinTuc->TieuDe }}</span>
    </nav>

    <main class="review-content">
        <h1>{{ $tinTuc->TieuDe }}</h1>
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
            {{ $tinTuc->NoiDung }}
        </p>
        <div class="review-image">
            <img src="https://cdn.galaxycine.vn/media/2025/5/16/until-dawn-2048_1747365952336.jpg" alt="Cảnh trong phim Hi Five" />
        </div>
    </main>
    <script src=" {{ asset('frontend/Content/js/chi-tiet-bai-viet.js') }}"></script>
@stop
