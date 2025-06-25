@extends('user.layouts.master')
@section('title', 'CineTick - Trang chủ')
@section('main')
    <link rel="stylesheet" href="{{ asset('user/Content/css/home.css') }}">
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="filmoja-slider-area fix">
        <div class="floating-particles" id="particles"></div>
        <div class="progress-bar" id="progressBar"></div>

        <div style="display:none" class="slide-number">
            <span class="current" id="currentNumber">01</span>
            <span>/</span>
            <span id="totalNumber">{{ str_pad($banners->count(), 2, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div class="carousel-wrapper">

            @if ($banners->isEmpty())
                <div class="carousel-track" id="carouselTrack">
                    <div class="carousel-slide active">
                        <img src="{{ asset('images/no-image.jpg') }}" alt="No Banner">
                        <div class="slide-content">
                            <div class="slide-title">Chưa có banner</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="carousel-track" id="carouselTrack">
                    @foreach ($banners as $index => $banner)
                        <a href="{{ $banner->Link }}">
                            <div class="carousel-slide {{ $index == 0 ? ' active' : '' }}">
                                <img src="{{ asset('storage/' . $banner->HinhAnh) }}" alt="{{ $banner->TieuDe }}">
                                <div style="display: none;" class="slide-content">
                                    <a href="{{ $banner->Link }}" class="slide-link">
                                        <div class="slide-title">{{ $banner->TieuDeChinh }}</div>
                                    </a>
                                    <div class="slide-subtitle">{{ $banner->TieuDePhu }}</div>
                                    @if ($banner->MoTa)
                                        <div class="slide-description">{{ $banner->MoTa }}</div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
            <!-- Navigation -->
            <button class="carousel-nav carousel-prev" onclick="previousSlide()">‹</button>
            <button class="carousel-nav carousel-next" onclick="nextSlide()">›</button>

            <!-- Dots -->
            <div class="carousel-dots" id="carouselDots">
                @foreach ($banners as $index => $banner)
                    <span class="dot{{ $index == 0 ? ' active' : '' }}"
                        onclick="currentSlide({{ $index + 1 }})"></span>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Slider Area End -->
    <div class="booking-container" id="bookingSection">
        <header class="section-header">
            <h1 class="section-title">Đặt vé nhanh</h1>
        </header>


        <div class="steps-indicator">
            <div class="step active" id="step-1">
                <div class="step-number">1</div>
                <div class="step-label">Rạp</div>
            </div>
            <div class="step-separator" id="separator-1"></div>
            <div class="step" id="step-2">
                <div class="step-number">2</div>
                <div class="step-label">Phim</div>
            </div>
            <div class="step-separator" id="separator-2"></div>
            <div class="step" id="step-3">
                <div class="step-number">3</div>
                <div class="step-label">Ngày</div>
            </div>
            <div class="step-separator" id="separator-3"></div>
            <div class="step" id="step-4">
                <div class="step-number">4</div>
                <div class="step-label">Suất</div>
            </div>
        </div>

        <div class="booking-form">
            <div class="booking-dropdown" id="theater-dropdown">
                <button class="dropdown-btn" id="theater-btn">
                    <span>1. Chọn Rạp</span>
                    <span><i class="fas fa-chevron-down"></i></span>
                </button>
                <div class="dropdown-content" id="theater-content">
                    @foreach ($raps as $rap)
                        <div class="dropdown-item" data-value="{{ $rap->ID_Rap }}" data-name="{{ $rap->TenRap }}">
                            <span class="marquee-text">{{ $rap->TenRap }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="booking-dropdown" id="movie-dropdown">
                <button class="dropdown-btn disabled" id="movie-btn">
                    <span>2. Chọn Phim</span>
                    <span><i class="fas fa-chevron-down"></i></span>
                </button>
                <div class="dropdown-content" id="movie-content">
                    @foreach ($phims as $phim)
                        <div class="dropdown-item" data-value="{{ $phim->Slug }}" data-id="{{ $phim->ID_Phim }}">
                            <span class="marquee-text">{{ $phim->TenPhim }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="booking-dropdown" id="date-dropdown">
                <button class="dropdown-btn disabled" id="date-btn">
                    <span>3. Chọn Ngày</span>
                    <span><i class="fas fa-chevron-down"></i></span>
                </button>
                <div class="dropdown-content" id="date-content">
                </div>
            </div>

            <div class="booking-dropdown" id="time-dropdown">
                <button class="dropdown-btn disabled" id="time-btn">
                    <span>4. Chọn Suất</span>
                    <span><i class="fas fa-chevron-down"></i></span>
                </button>
                <div class="dropdown-content" id="time-content">
                </div>
            </div>

            <button class="book-btn disabled" id="book-btn">ĐẶT NGAY</button>
        </div>

        <div class="selected-info" id="theater-info"></div>

        <div class="booking-summary" id="booking-summary">
            <div class="movie-poster" id="movie-poster">
                <i class="fas fa-film"></i>
            </div>
            <div class="summary-details">
                <div class="movie-title" id="summary-movie-title"></div>
                <div class="movie-details" id="summary-movie-details"></div>
                <div class="summary-item">
                    <div class="summary-label">Rạp:</div>
                    <div class="summary-value" id="summary-theater"></div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Ngày:</div>
                    <div class="summary-value" id="summary-date"></div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Suất chiếu:</div>
                    <div class="summary-value" id="summary-time"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
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

        <div class="movies-carousel">
            <div class="carousel-container">
                <div class="movies-grid" id="moviesGrid">
                    @foreach ($dsPhimDangChieu as $phim)
                        <div class="movie-card">
                            <div class="movie-poster">
                                <img
                                    src="{{ $phim->HinhAnh ? asset('storage/' . $phim->HinhAnh) : asset('images/no-image.jpg') }}">
                                <div class="play-button" data-trailer="{{ $phim->Trailer }}">
                                    <div class="play-icon"></div>
                                </div>
                                <div class="film-rating-crou">
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
                                <div class="age-rating">{{ $phim->DoTuoi }}</div>
                            </div>
                            <div class="movie-info">
                                <a href="{{ route('phim.chiTiet', ['slug' => $phim->Slug]) }}">
                                    <h3 class="movie-title">{{ $phim->TenPhim }}</h3>
                                </a>
                                <p class="movie-genre">
                                    @foreach ($phim->theLoai as $index => $theLoai)
                                        {{ $theLoai->TenTheLoai }}{{ $index < count($phim->theLoai) - 1 ? ', ' : '' }}
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="nav-button prev" id="prevBtn" aria-label="Trước">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <circle cx="20" cy="20" r="20" fill="#fff" opacity="0.85" />
                        <path d="M24 12L16 20L24 28" stroke="#222" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
                <button class="nav-button next" id="nextBtn" aria-label="Sau">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <circle cx="20" cy="20" r="20" fill="#fff" opacity="0.85" />
                        <path d="M16 12L24 20L16 28" stroke="#222" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <div class="dots-indicator" id="dotsIndicator"></div>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Phim sắp chiếu</h1>
            <p>Trải nghiệm điện ảnh đỉnh cao tại rạp chiếu phim CineTick</p>
        </div>

        <div class="movies-carousel-2">
            <div class="carousel-container">
                <div class="movies-grid" id="moviesGridUpcoming">
                    @foreach ($dsPhimSapChieu as $phim)
                        <div class="movie-card">
                            <div class="movie-poster">
                                <img
                                    src="{{ $phim->HinhAnh ? asset('storage/' . $phim->HinhAnh) : asset('images/no-image.jpg') }}">
                                <div class="play-button" data-trailer="{{ $phim->Trailer }}">
                                    <div class="play-icon"></div>
                                </div>
                                <div class="film-rating-crou">
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

                                <div class="age-rating">{{ $phim->DoTuoi }}</div>
                            </div>
                            <div class="movie-info">
                                <a href="{{ route('phim.chiTiet', ['slug' => $phim->Slug]) }}">
                                    <h3 class="movie-title">{{ $phim->TenPhim }}</h3>
                                </a>
                                <p class="movie-genre">
                                    @foreach ($phim->theLoai as $index => $theLoai)
                                        {{ $theLoai->TenTheLoai }}{{ $index < count($phim->theLoai) - 1 ? ', ' : '' }}
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="nav-button prev" id="prevBtnUpcoming" aria-label="Trước">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <circle cx="20" cy="20" r="20" fill="#fff" opacity="0.85" />
                        <path d="M24 12L16 20L24 28" stroke="#222" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
                <button class="nav-button next" id="nextBtnUpcoming" aria-label="Sau">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <circle cx="20" cy="20" r="20" fill="#fff" opacity="0.85" />
                        <path d="M16 12L24 20L16 28" stroke="#222" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <div class="dots-indicator" id="dotsIndicatorUpcoming"></div>
        </div>
    </div>



    <div class="container">
        <header class="header">
            <h1 class="main-title">Góc điện ảnh</h1>
            <nav class="nav-tabs">
                {{-- <a href="#" class="nav-tab">Bình luận phim</a> --}}
                <a href="#" class="nav-tab active">Blog điện ảnh</a>
            </nav>
        </header>

        <main class="content-grid">
            @if (isset($mainArticle))
                <a href="{{ route('bai-viet.chiTiet.dien-anh', ['slug' => $mainArticle->Slug]) }}">
                    <article class="main-article">
                        <div class="main-article-image">
                            <img src="{{ $mainArticle->AnhDaiDien ? asset('storage/' . $mainArticle->AnhDaiDien) : asset('images/no-image.jpg') }}"
                                alt="{{ $mainArticle->TieuDe }}">
                            <div class="main-article-overlay">
                                <h2 class="main-article-title">{{ $mainArticle->TieuDe }}</h2>
                                <div class="article-meta">
                                    <button class="action-btn" data-slug="{{ $mainArticle->Slug }}">
                                        <i class="fa-regular fa-thumbs-up"></i> Thích
                                        <span>{{ $mainArticle->LuotThich }}</span>
                                    </button>
                                    <div class="article-views">
                                        <span><i class="fa-regular fa-eye"></i></span>
                                        <span>{{ $mainArticle->LuotXem }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </a>
            @endif
            <aside class="sidebar">
                @foreach ($sidebarArticles as $article)
                    <a href="{{ route('bai-viet.chiTiet.dien-anh', ['slug' => $article->Slug]) }}">
                        <article class="sidebar-article">
                            <div class="sidebar-image">
                                <img src="{{ $article->AnhDaiDien ? asset('storage/' . $article->AnhDaiDien) : asset('images/no-image.jpg') }}"
                                    alt="{{ $article->TieuDe }}">
                            </div>
                            <div class="sidebar-content">
                                <h3 class="sidebar-title">{{ $article->TieuDe }}</h3>
                                <div class="article-meta">
                                    <button class="action-btn" data-slug="{{ $article->Slug }}">
                                        <i class="fa-regular fa-thumbs-up"></i> Thích
                                        <span>{{ $article->LuotThich }}</span>
                                    </button>
                                    <div class="article-views">
                                        <span><i class="fa-regular fa-eye"></i></span>
                                        <span>{{ $article->LuotXem }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </a>
                @endforeach
            </aside>
        </main>

        <div class="view-more">
            <a href="{{ route('ds-bai-viet-dien-anh') }}" class="view-more-btn">Xem thêm</a>
        </div>
    </div>
    <div class="container">
        <header class="section-header">
            <h1 class="section-title">Tin Khuyến Mãi</h1>
        </header>

        <div class="promotions-grid">
            @foreach ($khuyenMais as $khuyenMai)
                <a href="{{ route('bai-viet.chiTiet.khuyen-mai', ['slug' => $khuyenMai->Slug]) }}">
                    <div class="promotion-card promotion-special">
                        <div class="promotion-image">
                            <img src="{{ $khuyenMai->AnhDaiDien ? asset('storage/' . $khuyenMai->AnhDaiDien) : asset('images/no-image.jpg') }}"
                                alt="{{ $khuyenMai->TieuDe }}">
                        </div>
                        <div class="promotion-content">
                            <h3 class="promotion-title">{{ $khuyenMai->TieuDe }}</h3>
                            <div class="promotion-meta">
                            </div>
                            <div class="article-meta">
                                <button class="action-btn" data-slug="{{ $khuyenMai->Slug }}">
                                    <i class="fa-regular fa-thumbs-up"></i> Thích
                                    <span>{{ $khuyenMai->LuotThich }}</span>
                                </button>
                                <div class="article-views">
                                    <span><i class="fa-regular fa-eye"></i></span>
                                    <span>{{ $khuyenMai->LuotXem }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="view-all-section">
            <a href="{{ route('ds-bai-viet-khuyen-mai') }}" class="view-all-btn">Xem Tất Cả Khuyến Mãi</a>
        </div>
    </div>
    <script src=" {{ asset('user/Content/js/home.js') }}"></script>
@stop
