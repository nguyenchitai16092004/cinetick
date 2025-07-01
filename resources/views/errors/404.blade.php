<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang</title>
    <link rel="stylesheet" href="{{ asset('user/Content/css/404.css') }}">
    <link
    href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lexend+Deca:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:wght@300;400;500&display=swap"
    rel="stylesheet">
</head>

<body>
    <div class="error-container">
        <div class="error-logo">
            <a href="{{ asset('/') }}">
                <img src="{{ $thongTinTrangWeb->Logo ? asset('storage/' . $thongTinTrangWeb->Logo) : asset('images/no-image.jpg') }}"
                    alt="{{ $thongTinTrangWeb->TenWebsite }}" width="120" height="auto" />
            </a>
        </div>
        <div class="error-content">
            <div class="error-gif-wrapper">
                <img src="{{ asset('user/Content/img/error.gif') }}" alt="Error" class="error-gif">
            </div>
            <div class="error-code">404</div>
            <div class="error-title">Không tìm thấy trang</div>
            <div class="error-desc">
                Trang bạn tìm kiếm không tồn tại hoặc đã bị di chuyển.<br>
                Vui lòng quay về trang chủ để tiếp tục trải nghiệm CineTick!
            </div>
            <a href="{{ asset('/') }}" class="error-home-btn">
                <span>Về Trang Chủ</span>
            </a>
        </div>
    </div>
</body>

</html>
