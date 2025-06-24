@extends('frontend.layouts.master')
@section('title', 'CineTick - Chi tiết bài viết')
@section('main')
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/chi-tiet-bai-viet.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/home.css') }}">

    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    
    <nav class="breadcrumb">
        <a href="{{ route('home') }}">Trang chủ</a> /
        @php
            $prefix = request()->route()->getPrefix();
        @endphp
        @if($prefix === '/goc-dien-anh')
            <a href="{{ route('ds-bai-viet-dien-anh') }}">Góc điện ảnh</a> /
        @elseif($prefix === '/tin-khuyen-mai')
            <a href="{{ route('ds-bai-viet-khuyen-mai') }}">Tin khuyến mãi</a> /
        @endif
        <span>{{ $tinTuc->TieuDe }}</span>
    </nav>

    <main class="review-content">
        <h1>{{ $tinTuc->TieuDe }}</h1>
        <div class="review-actions">
            <button id="likeBtn" class="action-btn">
                <i id="likeIcon" class="fa-regular fa-thumbs-up"></i> Thích <span
                    id="likeCount">{{ $tinTuc->LuotThich }}</span>
            </button>
            <button class="action-btn share-btn" id="shareBtn">
                <i class="fa-solid fa-share-nodes"></i> Chia sẻ
            </button>
            <div class="views">
                <i class="fa-regular fa-eye"></i> <span id="viewCount">{{ $tinTuc->LuotXem }}</span>
            </div>
        </div>
        <div class="intro">
            {!! $tinTuc->NoiDung !!}
        </div>
    </main>
    <script src=" {{ asset('frontend/Content/js/chi-tiet-bai-viet.js') }}"></script>
    <script>
        const likeBtn = document.getElementById("likeBtn");
        const likeCount = document.getElementById("likeCount");
        const likeIcon = document.getElementById("likeIcon");
        const postSlug = "{{ $tinTuc->Slug }}";
        let liked = {{ $userLikedThis ? 'true' : 'false' }};
    </script>

@stop
