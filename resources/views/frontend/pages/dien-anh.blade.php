@extends('frontend.layouts.master')
@section('title', 'Danh sách bài viết điện ảnh')
@section('main')
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/dien-anh.css') }}">

    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="container-blog">
        <header class="section-header">
            <h1 class="section-title">Góc điện ảnh</h1>
        </header>
        <div class="section-title-bar"></div>
        <div class="movie-list">
            @if ($dienAnhs->count())
                @foreach ($dienAnhs as $dienAnh)
                    <a href="{{ route('bai-viet.chiTiet.dien-anh', ['slug' => $dienAnh->Slug]) }}">
                        <div class="movie-item">
                            <img class="movie-thumb"
                                src="{{ $dienAnh->AnhDaiDien ? asset('storage/' . $dienAnh->AnhDaiDien) : asset('images/no-image.jpg') }}"
                                alt="{{ $dienAnh->TieuDe }}">
                            <div class="movie-detail">
                                <div class="post-movie-title">{{ $dienAnh->TieuDe }}</div>
                                <div class="movie-actions">
                                    <button class="btn-like" data-slug="{{ $dienAnh->Slug }}">
                                        <i class="fa-regular fa-thumbs-up"></i> Thích
                                        <span>{{ $dienAnh->LuotThich }}</span>
                                    </button>
                                    <div class="like-count">
                                        <i class="fa-regular fa-eye"></i>
                                        <span class="like-num">{{ $dienAnh->LuotXem }}</span>
                                    </div>
                                </div>
                                <div class="movie-desc">
                                    {!! Str::words(strip_tags($dienAnh->NoiDung), 60, '...') !!} </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <p class="update-showtime">
                    <span class="marquee-text">Ôi không! Các bài viết điện ảnh đâu rồi? Đừng lo lắng chúng tôi sẽ cập nhật chúng ngay thôi.</span>
                </p>
            @endif
        </div>
        <div class="pagination-wrapper">
            {{ $dienAnhs->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <script src="{{ asset('frontend/Content/js/dien-anh.js') }}"></script>
@stop
