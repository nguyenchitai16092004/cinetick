@extends('frontend.layouts.master')
@section('title', $rap->TenRap ?? 'Thông tin rạp')
@section('main')
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/rap.css') }}">
    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <section class="film-section">
        <div class="film-box">
            <div class="header-content">
                <div class="cinema-info">
                    <h2>{{ $rap->TenRap }}</h2>
                    <div class="cinema-address">
                        Địa chỉ: {{ $rap->DiaChi }}<br>
                        Hotline: <a href="tel:{{ $rap->Hotline }}" class="hotline">{{ $rap->Hotline }}</a>
                    </div>
                </div>
            </div>

            <header class="section-header">
                <h1 class="section-title">PHIM</h1>
            </header>
            <div class="date-tabs">
                @foreach ($days as $idx => $date)
                    <div class="tab {{ $idx == 0 ? 'active' : '' }}" data-date="{{ $date }}">
                        {{ ucwords(\Carbon\Carbon::parse($date)->translatedFormat('l')) }}<br>
                        <span>{{ \Carbon\Carbon::parse($date)->format('d/m') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="films-lists-wrapper">
                <div class="header-2">
                    <h1 class="twinkle-title-pro">
                        <span class="star-pro star-pro-1"></span>
                        <span class="star-pro star-pro-2"></span>
                        <span class="star-pro star-pro-3"></span>
                        Suất chiếu hiện đang có tại rạp {{ $rap->TenRap }}
                        <span class="star-pro star-pro-4"></span>
                        <span class="star-pro star-pro-5"></span>
                        <span class="star-pro star-pro-6"></span>
                    </h1>
                    <p>Trải nghiệm điện ảnh đỉnh cao tại rạp chiếu phim CineTick</p>
                </div>
                @foreach ($days as $idx => $date)
                    <div class="films-by-date" data-date="{{ $date }}" style="{{ $idx == 0 ? '' : 'display:none' }}">
                        <div class="films-list">
                            @forelse($phimsByDay[$date] as $phim)
                                <a href="{{ route('phim.chiTiet', ['slug' => $phim->Slug]) }}" class="film-card-link">
                                    <div class="film-card">
                                        <div class="film-poster">
                                            <img
                                                src="{{ $phim->HinhAnh ? asset('storage/' . $phim->HinhAnh) : asset('images/no-image.jpg') }}"
                                                alt="{{ $phim->TenPhim }}">
                                            <div class="film-rating"><span>{{ $phim->DanhGia ?? 'N/A' }}</span><span class="star">★</span></div>
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
                            @empty
                                <p class="update-showtime">Suất chiếu tại rạp {{ $rap->TenRap }} đang được cập nhật...</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>        </div>

        <div class="info-container">
            <div class="cinema-detail">
                <header class="section-header">
                    <h1 class="section-title">Thông tin chi tiết</h1>
                </header>
                <div class="detail-address">
                    <strong>Địa chỉ:</strong> {{ $rap->DiaChi }}<br>
                    <strong>Số điện thoại:</strong> <a href="tel:{{ $rap->Hotline }}" class="hotline">{{ $rap->Hotline }}</a>
                </div>

                <div class="detail-desc">
                    {!! $rap->MoTa ?? 'Đang cập nhật...' !!}
                </div>
            </div>
        </div>
    </section>


    <script src=" {{ asset('frontend/Content/js/home.js') }}"></script>
    <script src="{{ asset('frontend/Content/js/rap.js') }}"></script>

@stop
