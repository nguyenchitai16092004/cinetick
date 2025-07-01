@extends('user.layouts.master')
@section('title', 'CineTick - Chi tiết phim')
@section('main')
    <link rel="stylesheet" href="{{ asset('user/Content/css/chi-tiet-phim.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

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
                        <button href="#" class="trailer-btn" id="trailerBtn">
                            <i class="fas fa-play"></i>
                        </button>
                        <span class="age-badge">{{ $phim->DoTuoi }}</span>
                        <span class="rating">
                            <span class="star"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                    data-icon="star" class="svg-inline--fa fa-star text-yellow-400 mr-3 ml-4 text-[12px]"
                                    role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="15px">
                                    <path fill="#FFD700"
                                        d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z">
                                    </path>
                                </svg></span>
                            <span class="avgRatingInfo">--</span>
                        </span>
                    </div>
                </div>

                <div class="movie-info">
                    <h1 class="movie-title fade-in-up stagger-2">
                        {{ $phim->TenPhim }}
                    </h1>

                    <div class="movie-badges fade-in-up stagger-3">

                        <div class="evaluate" id="openRatingModal">
                            <span class="star"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                    data-icon="star" class="svg-inline--fa fa-star text-yellow-400  text-[12px]"
                                    role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="15px">
                                    <path fill="#FFD700"
                                        d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z">
                                    </path>
                                </svg></span>
                            <span id="avgRatingInfo">--</span>
                            (<span id="countRatingInfo">0</span> lượt đánh giá)
                        </div>

                        <div class="badge badge-format" style="{{ empty($phim->DoHoa) ? 'visibility:hidden' : '' }}">
                            {{ $phim->DoHoa ?? '---' }}
                        </div>
                    </div>

                    <div class="movie-meta fade-in-up stagger-4">
                        <div class="meta-item">
                            <div class="meta-label">
                                <i class="fa-solid fa-user-tie"></i>
                                Nhà sản xuất
                            </div>
                            <div class="meta-value">
                                {{ $phim->NhaSanXuat }}
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">
                                <i class="fa-solid fa-user-tie"></i>
                                Đạo diễn
                            </div>
                            <div class="meta-value">
                                {{ $phim->DaoDien }}
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">
                                <i class="fa-solid fa-earth-asia"></i>
                                Quốc gia
                            </div>
                            <div class="meta-value">
                                {{ $phim->QuocGia }}
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">
                                <i class="fa-solid fa-user-tie"></i>
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
                            <div class="meta-value">{{ \Carbon\Carbon::parse($phim->NgayKhoiChieu)->format('d/m/Y') }}
                            </div>
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
    <!-- Popup đánh giá (ẩn sẵn, chỉ khi click mới hiện) -->
    <div class="rating-modal-overlay" id="ratingModal" style="display:none;">
        <div class="rating-modal-box">
            <div style="position:relative;">
                <img class="rating-modal-image"
                    src="{{ $phim->HinhAnh ? asset('storage/' . $phim->HinhAnh) : asset('images/no-image.jpg') }}"
                    alt="Poster">
                <button class="rating-modal-close" id="closeRatingModal" title="Đóng">&times;</button>
            </div>
            <div class="rating-modal-content">
                <div class="rating-movie-title">{{ $phim->TenPhim }}</div>
                <div class="rating-modal-score">
                    <div class="total-rating">
                        <span class="star-icon"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                data-icon="star" class="svg-inline--fa fa-star text-yellow-400  text-[12px]"
                                role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20px">
                                <path fill="#FFD700"
                                    d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z">
                                </path>
                            </svg></span>
                        <span class="score-value" id="avgRatingModal">--</span>
                        <span class="score-divider">/10</span>
                    </div>
                    <div class="score-count">
                        (<span id="countRatingModal">0</span> đánh giá)
                    </div>
                </div>
                <div class="rating-stars" id="starContainer" title="Đánh giá của bạn">
                    @for ($i = 1; $i <= 10; $i++)
                        <span class="rating-star" data-value="{{ $i }}">★</span>
                    @endfor
                </div>
                <div class="custom-rating-input">
                    <label class="customRatingInput-title" for="customRatingInput">Hoặc nhập điểm bạn muốn (0 -
                        10):</label>
                    <input type="number" id="customRatingInput" min="0" max="10" step="0.1"
                        value="" placeholder="">
                </div>
                <div id="ratingMsg"></div>

            </div>
            <div class="rating-modal-actions">
                <button class="btn-cancel" id="cancelRatingModal">Đóng</button>
                <button class="btn-confirm" id="sendRatingBtn">Xác Nhận</button>
            </div>
        </div>
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
                <i class="fa-solid fa-tachograph-digital"></i> Lịch Chiếu
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
                                        {{ ucwords(\Carbon\Carbon::parse($ngayChieu)->translatedFormat('l, d/m/Y')) }}
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
        } elseif (Str::contains($trailerEmbed, 'youtu.be/')) {
            $videoId = Str::after($trailerEmbed, 'youtu.be/');
            $trailerEmbed = 'https://www.youtube.com/embed/' . $videoId;
        }
    @endphp
    <script src="{{ asset('user/Content/js/chi-tiet-phim.js') }}"></script>
    <script>
        const trailerUrl = "{{ $trailerEmbed }}";
        window.initRatingModal({
            idPhim: "{{ $phim->ID_Phim }}",
            canRateUrl: "{{ route('ajax.can-rate') }}",
            sendRatingUrl: "{{ route('ajax.send-rating') }}",
            getRatingUrl: "{{ route('ajax.get-rating') }}",
            csrf: "{{ csrf_token() }}"
        });
        window.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.loader-overlay').classList.add('hidden');
        });
        window.addEventListener('pageshow', function() {
            document.querySelector('.loader-overlay').classList.add('hidden');
        });
    </script>

@stop
