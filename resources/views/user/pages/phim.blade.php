@extends('user.layouts.master')
@section('title', 'CineTick - ' . $title)
@section('main')

    <link rel="stylesheet" href="{{ asset('user/Content/css/phim.css') }}">
    <link rel="stylesheet" href="{{ asset('user/Content/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('user/Content/css/rap.css') }}">
    <link rel="stylesheet" href="{{ asset('user/Content/css/header.css') }}">

    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="films-lists-wrapper">
        @if (Route::currentRouteName() === 'phim.dangChieu')
            <div class="header-2">
                <h1 class="twinkle-title-pro">
                    <span class="star-pro star-pro-1"></span>
                    <span class="star-pro star-pro-2"></span>
                    <span class="star-pro star-pro-3"></span>
                    Phim đang chiếu
                    <span class="star-pro star-pro-4"></span>
                    <span class="star-pro star-pro-5"></span>
                    <span class="star-pro star-pro-6"></span>
                </h1>
                <p>Trải nghiệm điện ảnh đỉnh cao tại rạp chiếu phim CineTick</p>
            </div>
        @elseif (Route::currentRouteName() === 'phim.sapChieu')
            <div class="header-2">
                <h1 class="twinkle-title-pro">
                    Phim sắp chiếu
                </h1>
                <p>Trải nghiệm điện ảnh đỉnh cao tại rạp chiếu phim CineTick</p>
            </div>
        @endif

        @if ($danhSachPhim->isEmpty())
            <p class="update-showtime">
                <span class="marquee-text">Ôi không! Phim đâu rồi? Đừng lo lắng chúng tôi sẽ cập nhật chúng ngay
                    thôi.</span>
            </p>
        @else
            <div class="films-list">
            @foreach ($danhSachPhim as $index => $phim)
                <a href="{{ route('phim.chiTiet', ['slug' => $phim->Slug]) }}" class="film-card-link">
                    <div class="film-card">
                        <div class="film-poster">
                            <img src="{{ $phim->HinhAnh ? asset('storage/' . $phim->HinhAnh) : asset('images/no-image.jpg') }}"
                                alt="{{ $phim->TenPhim }}">
                            <div class="film-rating-crou film-rating-crou-3">
                                <span class="star"><svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="star"
                                        class="svg-inline--fa fa-star text-yellow-400 mr-3 ml-4 text-[12px]"
                                        role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
                                        width="15px">
                                        <path fill="#FFD700"
                                            d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z">
                                        </path>
                                    </svg>
                                </span>
                                <span>{{ $phim->avg_rating }}</span>
                            </div>
                            <div class="age-rating">{{ $phim->DoTuoi }}</div>
                        </div>
                        <div class="film-title">{{ $phim->TenPhim }}</div>
                        <p class="movie-genre">
                            @foreach ($phim->theLoai as $index => $theLoai)
                                {{ $theLoai->TenTheLoai }}{{ $index < count($phim->theLoai) - 1 ? ', ' : '' }}
                            @endforeach
                        </p>
                    </div>
                </a>
            @endforeach
            </div>
        @endif
        <div class="pagination-wrapper">
            {{ $danhSachPhim->links('pagination::bootstrap-4') }}
        </div>
    </div>
@stop