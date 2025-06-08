<!-- Preloader -->
<div style="display:none" class="main-reloader">
    <div class="loader"></div>
</div>
<!-- Preloader End -->
<!-- Main Header -->

<header class="filmoja-header-area">
    <!-- Header Top Area Start -->
    <div class="header-top-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-3 col-sm-12">
                    <div class="header-top-social">
                        <ul>
                            {{-- <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter-square"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus-square"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin-square"></i></a></li>
                            <li><a href="#"><i class="fa fa-pinterest-square"></i></a></li> --}}
                        </ul>
                    </div>
                </div>
                <div class="col-lg-7 col-md-9 col-sm-12">
                    <div class="header-top-menu">
                        <ul>
                            @if (session()->has('user_id'))
                                <li class="user-loged" style="border: 1px solid #f37737;">
                                    <a style="color:#fff" title="{{ session('user_fullname') }}"
                                        href="{{ asset('/') }}">{{ session('user_fullname') }}</a>
                                    <ul class="info_option">
                                        <li><a href="{{ route('user.info') }}"><i class="fa fa-user"></i>
                                                Thông tin</a></li>
                                        <li><a href="{{ asset('/') }}" onclick="logOut()"><i
                                                    class="fa fa-sign-out"></i> Đăng xuất</a></li>
                                    </ul>
                                </li>
                            @else
                                <li>
                                    <div style="display:flex; gap: 10px;">
                                        <img src="{{ asset('frontend/Content/img/user.svg') }}" alt="user-login-svg">
                                        <a href="{{ route('login.form') }}" class="btn-member">Đăng nhập</a>
                                    </div>
                                </li>
                            @endif
                            <script>
                                function logOut() {
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    });

                                    $.ajax({
                                        url: "{{ route('logout') }}", // Route logout
                                        type: "POST",
                                        success: function(result) {
                                            window.location.href = "{{ url('/') }}";
                                        },
                                        error: function(xhr) {
                                            console.error("Lỗi đăng xuất:", xhr.responseText);
                                        }
                                    });
                                }
                            </script>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Top Area End -->
    <!-- Main Header Area Start -->
    <div class="main-header-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="site-logo">
                        <a href="{{ asset('/') }}">
                            <img width="150px" src="Content/img/logoCineTick.png" alt="filmoja" />
                        </a>
                    </div>
                    <!-- Responsive Menu Start -->
                    <div class="filmoja-responsive-menu"></div>
                    <!-- Responsive Menu End -->
                </div>

                <div class="col-lg-9">
                    <div class="mainmenu">
                        <nav>
                            <ul id="responsive_navigation">
                                <!-- Mobile Search Start -->
                                <!-- Mobile Search End -->
                                <a href="#"><img width="120px;"
                                        src="{{ asset('frontend/Content/img/btn-ticket.webp') }}" alt="">
                                </a>

                                <li><a href="{{ asset('/') }}">Trang chủ</a></li>

                                <li>
                                    <a href="{{ asset('#') }}">Phim</a>
                                    <ul style=" background: -webkit-linear-gradient(left, #171e38, #5841a7);">
                                        <li><a href="{{ route('phim.dangChieu') }}">Phim Đang Chiếu</a></li>
                                        <li><a href="{{ route('phim.sapChieu') }}">Phim Sắp Chiếu</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ asset('#') }}">Rạp/Giá vé</a>
                                    <ul style=" background: -webkit-linear-gradient(left, #171e38, #5841a7);">
                                        <li><a href="#">CineTick 1</a></li>
                                        <li><a href="#">CineTick 2</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ asset('/uu-dai') }}">Khuyến Mãi</a></li>
                                <li><a href="{{ asset('/tin-tuc') }}">Điện Ảnh</a></li>
                                <li><a href="{{ asset('/lien-he') }}">Liên hệ</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Header Area End -->

</header>
<script>
    function openNav() {
        document.getElementById("mobileMenu").style.width = "300px";
    }

    function closeNav() {
        document.getElementById("mobileMenu").style.width = "0";
    }
</script>

<!-- Main Header End -->
