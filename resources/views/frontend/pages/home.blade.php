@extends('frontend.layouts.master')
@section('title', 'Trang chủ')
@section('main')
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/home.css') }}">

    <!-- Slider Area Start -->
    {{-- <section class="filmoja-slider-area fix">
        <div class="filmoja-slide owl-carousel">

            <a href="film/nha-gia-tien-t18/b046f0d2-c828-408e-9c89-a4cdb8db0daa.html">
                <div class="filmoja-main-slide">
                    <img style="height: 560px;" src="Areas/Admin/Content/Fileuploads/images/Slide2024/nha-gia-tien.jpg"
                        style="max-height:400px" />

                </div>
            </a>
            <a href="film/nu-tu-bong-toi-t16/b3cab74d-6def-49c9-ae08-3ade07b0ef19.html">
                <div class="filmoja-main-slide">
                    <img style="height: 560px;" src="Areas/Admin/Content/Fileuploads/images/Slide2024/nu-tu-bong-toi.jpg"
                        style="max-height:400px" />

                </div>
            </a>
            <div class="filmoja-main-slide">
                <img style="height: 560px;" src="Areas/Admin/Content/Fileuploads/images/Slide2024/tham-tu-kien.jpg"
                    style="max-height:400px" />
            </div>
        </div>
    </section> --}}
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
                    <div class="dropdown-item" data-value="2025-05-22"><span class="marquee-text">Thứ Năm, 22/05</span>
                    </div>
                    <div class="dropdown-item" data-value="2025-05-23"><span class="marquee-text">Thứ Sáu, 23/05</span>
                    </div>
                    <div class="dropdown-item" data-value="2025-05-24"><span class="marquee-text">Thứ Bảy, 24/05</span>
                    </div>
                    <div class="dropdown-item" data-value="2025-05-25"><span class="marquee-text">Chủ Nhật, 25/05</span>
                    </div>
                </div>
            </div>

            <div class="booking-dropdown" id="time-dropdown">
                <button class="dropdown-btn disabled" id="time-btn">
                    <span>4. Chọn Suất</span>
                    <span><i class="fas fa-chevron-down"></i></span>
                </button>
                <div class="dropdown-content" id="time-content">
                    <div class="dropdown-item" data-value="10:15"><span class="marquee-text">10:15 - 2D Deluxe</span>
                    </div>
                    <div class="dropdown-item" data-value="13:30"><span class="marquee-text">13:30 - 2D Standard</span>
                    </div>
                    <div class="dropdown-item" data-value="16:20"><span class="marquee-text">16:20 - 3D Deluxe</span>
                    </div>
                    <div class="dropdown-item" data-value="19:45"><span class="marquee-text">19:45 - 2D Deluxe</span>
                    </div>
                    <div class="dropdown-item" data-value="22:10"><span class="marquee-text">22:10 - 2D Standard</span>
                    </div>
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
                Phim Đang Chiếu
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
                                <div class="movie-rating">
                                    <div class="stars">
                                        <span class="star">★</span>
                                        <span class="star">★</span>
                                        <span class="star">★</span>
                                        <span class="star">★</span>
                                        <span class="star">★</span>
                                    </div>
                                    <span class="rating-number">8.2</span>
                                </div>
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
            <h1>Phim Sắp Chiếu</h1>
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
                                <div class="movie-rating">
                                    <div class="stars">
                                        <span class="star">★</span>
                                        <span class="star">★</span>
                                        <span class="star">★</span>
                                        <span class="star">★</span>
                                        <span class="star">★</span>
                                    </div>
                                    <span class="rating-number">8.2</span>
                                </div>
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

    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="container">
        <header class="header">
            <h1 class="main-title">Góc Điện Ảnh</h1>
            <nav class="nav-tabs">
                {{-- <a href="#" class="nav-tab">Bình luận phim</a> --}}
                <a href="#" class="nav-tab active">Blog điện ảnh</a>
            </nav>
        </header>

        <main class="content-grid">
            <article class="main-article">
                <div class="main-article-image">
                    <img src="https://images.unsplash.com/photo-1489599417793-4d8f4dfc6b10?w=800&h=400&fit=crop"
                        alt="Final Destination Bloodlines">
                    <div class="main-article-overlay">
                        <div class="article-meta">
                            <span class="article-tag">Thích</span>
                            <div class="article-views">
                                <span>👁</span>
                                <span>54</span>
                            </div>
                        </div>
                        <h2 class="main-article-title">Final Destination Bloodlines: Hé Lộ Bí Mật Về Vòng Lặp Tử Thần</h2>
                    </div>
                </div>
            </article>

            <aside class="sidebar">
                <article class="sidebar-article">
                    <div class="sidebar-image">
                        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=150&h=150&fit=crop"
                            alt="Bùi Thạc Chuyên">
                    </div>
                    <div class="sidebar-content">
                        <h3 class="sidebar-title">Bùi Thạc Chuyên Và 11 Năm Tâm Huyết Với Địa Đạo: Mặt Trời Trong Bóng Tối
                        </h3>
                        <div class="article-meta">
                            <span class="article-tag">Thích</span>
                            <div class="article-views">
                                <span>👁</span>
                                <span>104</span>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="sidebar-article">
                    <div class="sidebar-image">
                        <img src="https://images.unsplash.com/photo-1579952363873-27d3bfad9c0d?w=150&h=150&fit=crop"
                            alt="Oscar 2025">
                    </div>
                    <div class="sidebar-content">
                        <h3 class="sidebar-title">Tổng Hợp Oscar 2025: Anora Thắng Lớn</h3>
                        <div class="article-meta">
                            <span class="article-tag">Thích</span>
                            <div class="article-views">
                                <span>👁</span>
                                <span>33</span>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="sidebar-article">
                    <div class="sidebar-image">
                        <img src="https://images.unsplash.com/photo-1594908900066-3f4adf00b6f6?w=150&h=150&fit=crop"
                            alt="Nụ Hôn Bạc Tỷ">
                    </div>
                    <div class="sidebar-content">
                        <h3 class="sidebar-title">Nụ Hôn Bạc Tỷ: Thúy Kiều - Thúy Vân Phiên Bản 2025?</h3>
                        <div class="article-meta">
                            <span class="article-tag">Thích</span>
                            <div class="article-views">
                                <span>👁</span>
                                <span>114</span>
                            </div>
                        </div>
                    </div>
                </article>
            </aside>
        </main>

        <div class="view-more">
            <a href="#" class="view-more-btn">Xem thêm</a>
        </div>
    </div>
    <div class="container">
        <header class="section-header">
            <h1 class="section-title">Tin Khuyến Mãi</h1>
        </header>

        <div class="promotions-grid">
            <div class="promotion-card promotion-special">
                <div class="promotion-badge">New</div>
                <div class="promotion-image">
                    <img src="https://images.unsplash.com/photo-1489599417793-4d8f4dfc6b10?w=400&h=250&fit=crop"
                        alt="U22 Vui Vẻ - Bắp Nước Siêu Hạt Dẻ">
                </div>
                <div class="promotion-content">
                    <h3 class="promotion-title">U22 Vui Vẻ - Bắp Nước Siêu Hạt Dẻ</h3>
                    <div class="promotion-meta">
                        <span class="promotion-date">Còn 5 ngày</span>
                        <button class="promotion-cta">Xem Chi Tiết</button>
                    </div>
                </div>
            </div>

            <div class="promotion-card">
                <div class="promotion-badge">Hot</div>
                <div class="promotion-image">
                    <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=250&fit=crop"
                        alt="Snack Dư Vị - Xem Phim Hay Hết Ý">
                </div>
                <div class="promotion-content">
                    <h3 class="promotion-title">Snack Dư Vị - Xem Phim Hay Hết Ý</h3>
                    <div class="promotion-meta">
                        <span class="promotion-date">Còn 12 ngày</span>
                        <button class="promotion-cta">Khám Phá</button>
                    </div>
                </div>
            </div>

            <div class="promotion-card">
                <div class="promotion-badge">Premium</div>
                <div class="promotion-image">
                    <img src="https://images.unsplash.com/photo-1579952363873-27d3bfad9c0d?w=400&h=250&fit=crop"
                        alt="Trọn Vẹn Cảm Giác Điện Ảnh">
                </div>
                <div class="promotion-content">
                    <h3 class="promotion-title">Trọn Vẹn Cảm Giác Điện Ảnh: Từ Rạp Phim Về Đến Nhà</h3>
                    <div class="promotion-meta">
                        <span class="promotion-date">Còn 8 ngày</span>
                        <button class="promotion-cta">Tham Gia</button>
                    </div>
                </div>
            </div>

            <div class="promotion-card">
                <div class="promotion-badge">25% Off</div>
                <div class="promotion-image">
                    <img src="https://images.unsplash.com/photo-1594908900066-3f4adf00b6f6?w=400&h=250&fit=crop"
                        alt="Bánh Phồng Đế Rec Rec">
                </div>
                <div class="promotion-content">
                    <h3 class="promotion-title">Bánh Phồng Đế Rec Rec - Snack Để Giàu Đạm Nhiều Dinh Dưỡng</h3>
                    <div class="promotion-meta">
                        <span class="promotion-date">Còn 15 ngày</span>
                        <button class="promotion-cta">Mua Ngay</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="view-all-section">
            <a href="#" class="view-all-btn">Xem Tất Cả Khuyến Mãi</a>
        </div>
    </div>
    <script src=" {{ asset('frontend/Content/js/home.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookBtn = document.getElementById('book-btn');
            bookBtn.addEventListener('click', function() {
                // Lấy giá trị đã chọn
                const rap = document.querySelector('#theater-content .dropdown-item.selected')?.dataset
                    .value;
                const phim = document.querySelector('#movie-content .dropdown-item.selected')?.dataset
                    .value;
                const ngay = document.querySelector('#date-content .dropdown-item.selected')?.dataset.value;
                const gio = document.querySelector('#time-content .dropdown-item.selected')?.dataset.value;

                if (!rap || !phim || !ngay || !gio) {
                    alert('Vui lòng chọn đủ Rạp, Phim, Ngày, Suất!');
                    return;
                }

                // Chuyển hướng sang route đặt vé (theo slug phim, ngày, giờ)
                window.location.href = `/dat-ve/${phim}/${ngay}/${gio}`;
            });

            // Xử lý chọn item dropdown
            document.querySelectorAll('.dropdown-content .dropdown-item').forEach(item => {
                item.addEventListener('click', function() {
                    this.parentElement.querySelectorAll('.dropdown-item').forEach(i => i.classList
                        .remove('selected'));
                    this.classList.add('selected');
                });
            });
        }); // Khi chọn rạp hoặc phim
        $('#theater-content .dropdown-item, #movie-content .dropdown-item').on('click', function() {
            let idRap = $('#theater-content .dropdown-item.selected').data('value');
            let idPhim = $('#movie-content .dropdown-item.selected').data('id');
            if (idRap && idPhim) {
                $.get('/ajax/ngay-chieu', {
                    id_rap: idRap,
                    id_phim: idPhim
                }, function(data) {
                    $('#date-content').html('');
                    data.forEach(function(ngay) {
                        $('#date-content').append(
                            `<div class="dropdown-item" data-value="${ngay}"><span class="marquee-text">${ngay}</span></div>`
                        );
                    });
                });
            }
        });
        $('#date-content').on('click', '.dropdown-item', function() {
            let idRap = $('#theater-content .dropdown-item.selected').data('value');
            let idPhim = $('#movie-content .dropdown-item.selected').data('id');
            let ngay = $(this).data('value');
            if (idRap && idPhim && ngay) {
                $.get('/ajax/suat-chieu', {
                    id_rap: idRap,
                    id_phim: idPhim,
                    ngay: ngay
                }, function(data) {
                    $('#time-content').html('');
                    data.forEach(function(suat) {
                        $('#time-content').append(
                            `<div class="dropdown-item" data-value="${suat.gio}" data-id="${suat.id}">
                        <span class="marquee-text">${suat.gio} - ${suat.dinh_dang}</span>
                    </div>`
                        );
                    });
                });
            }
        });
    </script>
@stop
