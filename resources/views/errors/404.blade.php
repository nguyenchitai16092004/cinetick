<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Trang Không Tìm Thấy</title>
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/404.css') }}">
</head>

<body>
    <!-- CineTick Brand -->

    <div class="brand"> 
        <a href="{{ asset('/') }}">
            <img width="120px" src="{{ asset('frontend/Content/img/logoCineTick.png') }}" alt="filmoja" />
        </a>
    </div>

    <!-- Floating Bubbles -->
    <div class="ticket-icon">🎫</div>
    <div class="ticket-icon">🎟️</div>
    <div class="ticket-icon">🎫</div>
    <div class="ticket-icon">🎟️</div>

    <!-- Particles -->
    <div class="particles" id="particles"></div>

    <!-- Floating Ticket Icons -->
    <div class="ticket-icon">🎫</div>
    <div class="ticket-icon">🎟️</div>
    <div class="ticket-icon">🎫</div>
    <div class="ticket-icon">🎟️</div>
    <div class="ticket-icon">🎫</div>
    <div class="ticket-icon">🎟️</div>
    <div class="ticket-icon">🎫</div>
    <div class="ticket-icon">🎟️</div>
<!-- Ticket icons ở 4 góc màn hình -->
<div class="ticket-icon ticket-top-right">🎟️</div>
<div class="ticket-icon ticket-bottom-right">🎫</div>
<div class="ticket-icon ticket-bottom-left">🎟️</div>
    <!-- Main Content -->
    <div class="container">
        <div class="content">
            <div class="error-number">404</div>
            <div class="oops-text">Oops</div>
            <div class="subtitle">Trang Không Tìm Thấy</div>
            <div class="description">
                Có vẻ như trang bạn đang tìm kiếm đã được di chuyển, xóa hoặc không bao giờ tồn tại.
                Đừng lo lắng, hãy quay về trang chủ và khám phá những điều thú vị khác nhé!
            </div>
            <a href="{{ asset('/') }}" class="home-btn" id="homeBtn">
                <svg class="home-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9,22 9,12 15,12 15,22" />
                </svg>
                Về Trang Chủ
            </a>
        </div>
    </div>
    <script src="{{ asset('frontend/Content/js/404.js') }}">
        >
    </script>
</body>

</html>
