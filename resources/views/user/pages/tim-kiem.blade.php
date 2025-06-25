@extends('user.layouts.master')
@section('title', 'CineTick - Tìm kiếm')
@section('main')
    <link rel="stylesheet" href="{{ asset('user/Content/css/tim-kiem.css') }}">
    <link rel="stylesheet" href="{{ asset('user/Content/css/rap.css') }}">
    <link rel="stylesheet" href="{{ asset('user/Content/css/home.css') }}">

    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="container search-page">
        <h3 class="result-search">Kết quả tìm kiếm cho: <u class="search-keyword">{{ $keyword }}</u></h3>

        @php
            $hasPhim = isset($phims) && !$phims->isEmpty();
            $hasRap = isset($raps) && $raps && $raps->count();
            $hasRapsByPhim = isset($rapsByPhim) && $rapsByPhim && $rapsByPhim->count();
        @endphp

        @if (!$hasPhim && !$hasRap && !$hasRapsByPhim)
            <p class="not-found">Không tìm thấy kết quả cho "<u class="search-keyword">{{ $keyword }}</u>"</p>
        @else
            {{-- PHIM --}}
            @if ($hasPhim)
                <div class="header-2">
                    <h1 class="twinkle-title-pro">
                        <span class="star-pro star-pro-1"></span>
                        <span class="star-pro star-pro-2"></span>
                        <span class="star-pro star-pro-3"></span>
                        Phim
                        <span class="star-pro star-pro-4"></span>
                        <span class="star-pro star-pro-5"></span>
                        <span class="star-pro star-pro-6"></span>
                    </h1>
                    <p>Trải nghiệm điện ảnh đỉnh cao tại rạp chiếu phim CineTick</p>
                </div>
                <div class="films-list">
                    @foreach ($phims as $phim)
                        <a href="{{ route('phim.chiTiet', ['slug' => $phim->Slug]) }}" class="film-card-link">
                            <div class="film-card">
                                <div class="film-poster">
                                    <img src="{{ $phim->HinhAnh ? asset('storage/' . $phim->HinhAnh) : asset('images/no-image.jpg') }}"
                                        alt="{{ $phim->TenPhim }}">
                                    <div class="film-rating">
                                        <span class="star">
                                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="star"
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
                                    <div class="film-age">{{ $phim->DoTuoi }}</div>
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
                <div class="pagination-wrapper">
                    {{ $phims->links('pagination::bootstrap-4') }}
                </div>
            @endif

            {{-- RẠP LIÊN QUAN TỪ KHÓA --}}
            @if ($hasRap)
                <div class="header-2 mt-5">
                    <h1 class="twinkle-title-pro">
                        <span class="star-pro star-pro-1"></span>
                        <span class="star-pro star-pro-2"></span>
                        <span class="star-pro star-pro-3"></span>
                        Rạp
                        <span class="star-pro star-pro-4"></span>
                        <span class="star-pro star-pro-5"></span>
                        <span class="star-pro star-pro-6"></span>
                    </h1>
                    <p>Khám phá hệ thống rạp CineTick trên toàn quốc</p>
                </div>
                <div class="cinema-list-wrapper">
                    @foreach ($rapsSearch as $rap)
                        <div class="cinema-card">
                            <div class="cinema-thumb">
                                <img src="{{ $rap->HinhAnh ? asset('storage/' . $rap->HinhAnh) : asset('images/no-image.jpg') }}"
                                    alt="{{ $rap->TenRap }}">

                            </div>
                            <div class="cinema-body">
                                <h3 class="cinema-title">{{ $rap->TenRap }}</h3>
                                <div class="cinema-address">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span>{{ $rap->DiaChi }}</span>
                                </div>
                                <div class="cinema-hotline">
                                    <i class="fa-solid fa-phone-volume"></i>
                                    <span>{{ $rap->Hotline ?: 'Đang cập nhật' }}</span>
                                </div>
                                <a href="{{ route('rap.chiTiet', ['slug' => $rap->Slug]) }}"
                                    class="cinema-ticket-btn submit-btn">Đặt
                                    vé ngay</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
@stop
