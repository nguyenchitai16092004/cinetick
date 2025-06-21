@extends('frontend.layouts.master')
@section('title', 'Trang chủ')
@section('main')
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/home.css') }}">
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

        <div class="slide-number">
            <span class="current" id="currentNumber">01</span>
            <span>/</span>
            <span id="totalNumber">05</span>
        </div>

        <div class="carousel-wrapper">
            <div class="carousel-track" id="carouselTrack">
                <!-- Slide 1 -->
                <div class="carousel-slide active">
                    <img src="https://cdn.galaxycine.vn/media/2025/5/16/until-dawn-2048_1747365952336.jpg" alt="Until Dawn">
                    <div class="slide-content">
                        <div class="slide-title">UNTIL DAWN</div>
                        <div class="slide-subtitle">ÁC MỘNG KINH HOÀNG TRỖI DẬY MỖI ĐÊM</div>
                        <div class="slide-description">Từ đạo diễn của LIGHTS OUT và ANNABELLE: CREATION. Một đêm định mệnh
                            sẽ thay đổi cuộc sống của nhóm bạn trẻ mãi mãi.</div>
                        <div class="cta-buttons">
                            <button class="primary-btn">XEM TRAILER</button>
                            <button class="secondary-btn">CHI TIẾT</button>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-slide">
                    <img src="https://cdn.galaxycine.vn/media/2025/5/16/glx-2048x682_1747389452013.png" alt="Lilo & Stitch">
                    <div class="slide-content">
                        <div class="slide-title">LILO & STITCH</div>
                        <div class="slide-subtitle">PHIÊU LƯU CÙNG NGƯỜI BẠN NGOÀI HÀNH TINH</div>
                        <div class="slide-description">Bộ phim hoạt hình Disney kinh điển trở lại với câu chuyện về tình bạn
                            và gia đình đầy cảm động.</div>
                        <div class="cta-buttons">
                            <button class="primary-btn">XEM TRAILER</button>
                            <button class="secondary-btn">CHI TIẾT</button>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-slide">
                    <img src="https://cdn.galaxycine.vn/media/2025/5/23/doraemon-movie-44-1_1748017461000.jpg"
                        alt="Doraemon Movie">
                    <div class="slide-content">
                        <div class="slide-title">DORAEMON</div>
                        <div class="slide-subtitle">NOBITA'S ART WORLD TALES</div>
                        <div class="slide-description">Cuộc phiêu lưu mới trong thế giới nghệ thuật đầy màu sắc và kỳ diệu
                            cùng Doraemon và những người bạn.</div>
                        <div class="cta-buttons">
                            <button class="primary-btn">XEM TRAILER</button>
                            <button class="secondary-btn">CHI TIẾT</button>
                        </div>
                    </div>
                </div>

                <!-- Slide 4 -->
                <div class="carousel-slide">
                    <img src="https://cdn.galaxycine.vn/media/2025/5/15/mua-lua-2048_1747295237842.jpg" alt="Mưa Lửa">
                    <div class="slide-content">
                        <div class="slide-title">MƯA LỬA</div>
                        <div class="slide-subtitle">ANH TRAI VƯỢT NGÀN CHÔNG GAI</div>
                        <div class="slide-description">Hành trình đầy cam go và thử thách của những người anh em trong cuộc
                            chiến sinh tồn khốc liệt.</div>
                        <div class="cta-buttons">
                            <button class="primary-btn">XEM TRAILER</button>
                            <button class="secondary-btn">CHI TIẾT</button>
                        </div>
                    </div>
                </div>

                <!-- Slide 5 -->
                <div class="carousel-slide">
                    <img src="https://cdn.galaxycine.vn/media/2025/5/9/mi8-2048_1746763282349.jpg"
                        alt="Mission Impossible 8">
                    <div class="slide-content">
                        <div class="slide-title">MISSION</div>
                        <div class="slide-subtitle">IMPOSSIBLE: THE FINAL RECKONING</div>
                        <div class="slide-description">Nhiệm vụ cuối cùng và nguy hiểm nhất của Ethan Hunt trong hành trình
                            đầy kịch tính và bất ngờ.</div>
                        <div class="cta-buttons">
                            <button class="primary-btn">XEM TRAILER</button>
                            <button class="secondary-btn">CHI TIẾT</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <button class="carousel-nav carousel-prev" onclick="previousSlide()">‹</button>
            <button class="carousel-nav carousel-next" onclick="nextSlide()">›</button>

            <!-- Dots -->
            <div class="carousel-dots" id="carouselDots">
                <span class="dot active" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
                <span class="dot" onclick="currentSlide(4)"></span>
                <span class="dot" onclick="currentSlide(5)"></span>
            </div>
        </div>
    </div>
    <!-- Slider Area End -->
    <div class="booking-container">
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
                                <div class="film-rating-crou"><span>9.5</span><span class="star">★</span></div>
                                <div class="age-rating">{{ $phim->DoTuoi }}</div>
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
    <script src=" {{ asset('frontend/Content/js/home.js') }}"></script>
@stop
