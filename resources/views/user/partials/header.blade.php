<link rel="stylesheet" href="{{ asset('user/Content/css/header.css') }}">

<header class="header-menu">
    <div class="header__top">
        <div class="header__left">
            <a href="{{ asset('/') }}">
                <img src="{{ $thongTinTrangWeb->Logo ? asset('storage/' . $thongTinTrangWeb->Logo) : asset('images/no-image.jpg') }}"
                    alt="{{ $thongTinTrangWeb->TenWebsite }}" alt="filmoja" />
            </a>
        </div>
        <div class="header__icons-mobile">
            <!-- Icon search -->
            <button class="header__search-toggle" id="searchToggle" aria-label="Tìm kiếm" type="button">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>

            <!-- Avatar user -->
            @if (session()->has('user_id'))
                <a href="{{ route('user.info') }}" class="header__user-mobile" aria-label="Tài khoản">
                    <img src="{{ asset('user/Content/img/user.svg') }}" alt="user-login-svg" class="user-menu__avatar"
                        style="width:32px;height:32px;">
                </a>
            @endif
            <!-- Nút menu hamburger -->
            <button class="header__menu-toggle" id="menuToggle" aria-label="Mở menu">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <div class="header__search" id="headerSearch">
            <form action="{{ route('tim-kiem') }}" method="GET" class="header__search-form">
                <input type="text" name="keyword" placeholder="Tìm phim, rạp, thể loại phim..."
                    value="{{ request('keyword') ?? '' }}" autocomplete="off" class="header__search-input">
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
        <div class="header__right">
            <div class="header__center" id="goToBooking">
                <img src="{{ asset('user/Content/img/btn-ticket.webp') }}" alt="">
            </div>
            <ul class="user-menu">
                @if (session()->has('user_id'))
                    <li class="user-menu__item user-menu__dropdown">
                        <button class="user-menu__toggle" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('user/Content/img/user.svg') }}" alt="user-login-svg"
                                class="user-menu__avatar">
                            <span class="user-menu__name" title="{{ session('user_fullname') }}">
                                {{ session('user_fullname') }}
                            </span>
                            <i class="fa fa-caret-down user-menu__caret"></i>
                        </button>
                        <ul class="user-menu__dropdown-list">
                            <li>
                                <a href="{{ route('user.info') }}">
                                    <i class="fa fa-user"></i> Thông tin
                                </a>
                            </li>
                            <li>
                                <a href="{{ asset('/') }}" onclick="logOut()">
                                    <i class="fa fa-sign-out"></i> Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="user-menu__item">
                        <a href="{{ route('login.form') }}" class="btn-member user-menu__login">
                            <img src="{{ asset('user/Content/img/user.svg') }}" alt="user-login-svg"
                                class="user-menu__avatar">
                            Đăng nhập / Đăng ký
                        </a>
                    </li>
                @endif
            </ul>

            <img src="{{ asset('user/Content/img/user-avatar.webp') }}" alt="">
        </div>
    </div>
    <div class="header_bottom_line"></div>
    <div class="header__bottom">
        <div class="header__nav">
        </div>
        <div class="mainmenu">
            <nav>
                <ul id="responsive_navigation">
                    <li><a href="{{ asset('/') }}">Trang chủ</a></li>
                    <li>
                        <a>Phim</a>
                        <ul class="sub-menu-film">
                            <li><a href="{{ route('phim.dangChieu') }}">Phim đang chiếu</a></li>
                            <li><a href="{{ route('phim.sapChieu') }}">Phim sắp chiếu</a></li>
                        </ul>
                    </li>
                    <li>
                        <a>Rạp chiếu</a>
                        <ul class="sub-menu-rap">
                            @foreach ($raps as $rap)
                                @if ($rap->TrangThai == 1)
                                    <li>
                                        <a
                                            href="{{ route('rap.chiTiet', ['slug' => $rap->Slug]) }}">{{ $rap->TenRap }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    <li><a href="{{ route('ds-bai-viet-khuyen-mai') }}">Khuyến mãi</a></li>
                    <li><a href="{{ route('ds-bai-viet-dien-anh') }}">Điện ảnh</a></li>
                    <li><a href="{{ asset('/lien-he') }}">Liên hệ</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="header__nav-mobile" id="mobileMenu">
        <!-- Icon đóng menu -->
        <button class="header__menu-close" id="menuClose" aria-label="Đóng menu" type="button" style="display:none;">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <nav>
            <ul>
                <li>
                    <form action="{{ route('tim-kiem') }}" method="GET"
                        class="header__search-form header__search-form--mobile">
                        <input type="text" name="keyword" placeholder="Tìm phim, rạp, thể loại phim..."
                            value="{{ request('keyword') ?? '' }}" autocomplete="off" class="header__search-input">
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </li>
                <li><a href="{{ asset('/') }}">Trang chủ</a></li>
                <li class="dropdown-mobile">
                    <a href="javascript:void(0)" class="dropdown-toggle-mobile">
                        Phim <i class="fa-solid fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-content-mobile">
                        <li><a href="{{ route('phim.dangChieu') }}">Phim đang chiếu</a></li>
                        <li><a href="{{ route('phim.sapChieu') }}">Phim sắp chiếu</a></li>
                    </ul>
                </li>
                <li class="dropdown-mobile">
                    <a href="javascript:void(0)" class="dropdown-toggle-mobile">
                        Rạp chiếu <i class="fa-solid fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-content-mobile">
                        @foreach ($raps as $rap)
                            @if ($rap->TrangThai == 1)
                                <li>
                                    <a
                                        href="{{ route('rap.chiTiet', ['slug' => $rap->Slug]) }}">{{ $rap->TenRap }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
                <li><a href="{{ route('ds-bai-viet-khuyen-mai') }}">Khuyến mãi</a></li>
                <li><a href="{{ route('ds-bai-viet-dien-anh') }}">Điện ảnh</a></li>
                <li><a href="{{ asset('/lien-he') }}">Liên hệ</a></li>
                @if (session()->has('user_id'))
                    <li><a href="{{ route('user.info') }}">Thông tin cá nhân</a></li>
                    <li><a href="{{ asset('/') }}" onclick="logOut()">Đăng xuất</a></li>
                @else
                    <li><a href="{{ route('login.form') }}">Đăng nhập / Đăng ký</a></li>
                @endif
            </ul>
        </nav>
    </div>
    <div class="header__overlay" id="menuOverlay"></div>
</header>
<script src="{{ asset('user/Content/js/header.js') }}"></script>
<script>
    function logOut() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
        });

        $.ajax({
            url: "{{ route('logout') }}",
            type: "POST",
            success: function(result) {
                window.location.href = "{{ url('/') }}";
            },
            error: function(xhr) {
                console.error("Lỗi đăng xuất:", xhr.responseText);
            },
        });
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.querySelector('.header__search-form');
        const searchInput = searchForm.querySelector('.header__search-input');

        searchForm.addEventListener('submit', function(e) {
            const keyword = searchInput.value.trim();
            if (!keyword) {
                e.preventDefault(); // Ngăn submit form
            }
        });
    });
</script>
