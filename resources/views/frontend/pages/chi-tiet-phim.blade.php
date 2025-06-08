@extends('frontend.layouts.master')
@section('title', 'Chi tiết phim')
@section('main')

    <link rel="stylesheet" href="{{ asset('frontend/Content/css/chi-tiet-phim.css') }}">

    <div class="bg-gradient"></div>

    <section id="mainContent" class="movie-hero">
        <div class="hero-bg"
            style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.9)), url('{{ $phim->HinhAnh ? asset('storage/' . $phim->HinhAnh) : asset('images/no-image.jpg') }}') center/cover;">
        </div>
        <div class="hero-particles"></div>

        <div class="container">
            <div class="hero-content">
                <div class="poster-container fade-in-up stagger-1">
                    <div class="movie-poster">
                        <img src="{{ $phim->HinhAnh ? asset('storage/' . $phim->HinhAnh) : asset('images/no-image.jpg') }}">
                        <div class="poster-overlay"></div>
                        <a href="#" class="trailer-btn" id="trailerBtn">
                            <i class="fas fa-play"></i>
                        </a>
                        <span class="age-badge">{{ $phim->DoTuoi }}</span>
                    </div>
                </div>

                <div class="movie-info">
                    <h1 class="movie-title fade-in-up stagger-2">
                        {{ $phim->TenPhim }}
                    </h1>

                    <div class="movie-badges fade-in-up stagger-3">
                        <span class="badge badge-format">{{ $phim->DoHoa }}</span>
                    </div>

                    <div class="movie-meta fade-in-up stagger-4">
                        <div class="meta-item">
                            <div class="meta-label">
                                <i class="fas fa-user-tie"></i>
                                Đạo diễn
                            </div>
                            <div class="meta-value">
                                {{ $phim->DaoDien }}
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">
                                <i class="fas fa-calendar-alt"></i>
                                Ngày khởi chiếu
                            </div>
                            <div class="meta-value">{{ \Carbon\Carbon::parse($phim->NgayKhoiChieu)->format('d/m/Y') }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">
                                <i class="fas fa-users"></i>
                                Diễn viên
                            </div>
                            <div class="meta-value">
                                {{ $phim->DienVien }}
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">
                                <i class="fas fa-clock"></i>
                                Thời lượng
                            </div>
                            <div class="meta-value"> @php
                                $gio = floor($phim->ThoiLuong / 60);
                                $phut = $phim->ThoiLuong % 60;
                            @endphp
                                {{ $gio > 0 ? $gio . ' giờ ' : '' }}{{ $phut > 0 ? $phut . ' phút' : '' }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">
                                <i class="fas fa-tags"></i>
                                Thể loại
                            </div>
                            <div class="meta-value">
                                @foreach ($phim->theLoai as $key => $theLoai)
                                    {{ $theLoai->TenTheLoai }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="movie-description fade-in-up stagger-4">
                        <h4 class="description-title">
                            <i class="fas fa-align-left"></i>
                            Tóm tắt nội dung
                        </h4>
                        <p class="description-text">
                            {{ $phim->MoTaPhim }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>

    </div>

    <div class="trailer-modal" id="trailerModal">
        <div class="trailer-container">
            <button class="trailer-close" id="closeTrailer">
                <i class="fas fa-times"></i>
            </button>
            <iframe class="trailer-video" id="trailerVideo" src="" allowfullscreen></iframe>
            <div class="trailer-info">
                <p>Nhấn ESC hoặc click bên ngoài để đóng</p>
            </div>
        </div>
    </div>

    <section class="showtime-section">
        <div class="container">
            <h2 class="section-title">
                <i class="fas fa-calendar-days"></i> Lịch Chiếu
            </h2>
            @if ($suatChieu->isEmpty())
                <div class="showtime-empty text-center my-5">

                    <div class="showtime-empty-title">Ôi không!</div>
                    <div class="showtime-empty-desc">
                        Không tìm thấy suất chiếu nào cho phim này.
                    </div>
                </div>
            @else
                <div class="showtime-grid">
                    <!-- Showtime Card 1 -->
                    @foreach ($suatChieu as $groupKey => $group)
                        @php
                            [$ngayChieu, $diaChi] = explode('|', $groupKey);
                        @endphp
                        <div class="showtime-card fade-in-up stagger-1">
                            <div class="cinema-info">
                                <div class="cinema-details">
                                    <h3>
                                        <i class="fas fa-film"></i>
                                        {{ $group->first()->rap->TenRap ?? 'Thông tin rạp không khả dụng' }}
                                    </h3>
                                    <div class="cinema-address">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $diaChi }}
                                    </div>
                                    <div class="cinema-date">
                                        <i class="fas fa-calendar-day"></i>
                                        {{ ucfirst(\Carbon\Carbon::parse($ngayChieu)->translatedFormat('l, d/m/Y')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="showtime-buttons">
                                @foreach ($group as $suat)
                                    <a href="{{ route('dat-ve.show.slug', [
                                        'phimSlug' => $phim->Slug,
                                        'ngay' => $suat->NgayChieu,
                                        'gio' => $suat->GioChieu,
                                    ]) }}"
                                        class="showtime-btn">
                                        <i class="far fa-clock"></i>
                                        {{ substr($suat->GioChieu, 0, 5) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
    @php
        use Illuminate\Support\Str;
        $trailerEmbed = $phim->Trailer;
        if (Str::contains($trailerEmbed, 'watch?v=')) {
            $trailerEmbed = str_replace('watch?v=', 'embed/', $trailerEmbed);
        }
    @endphp
    <script src="{{ asset('frontend/Content/js/chi-tiet-phim.js') }}"></script>
    <script>
        const trailerUrl = "{{ $trailerEmbed }}";
    </script>
@stop
