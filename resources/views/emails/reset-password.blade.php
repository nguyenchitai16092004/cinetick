<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Khôi phục mật khẩu - CineTick</title>
    <style>
        body {
            font-family: "Josefin Sans", sans-serif;
            background: #f7f8fa;
            padding: 0;
            margin: 0;
        }

        .mail-container {
            max-width: 420px;
            background: #fff;
            margin: 32px auto 0 auto;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(44, 62, 80, 0.12);
            padding: 36px 30px 28px 30px;
            border: 1.5px solid #e74c3c44;
        }

        .mail-header {
            text-align: center;
            margin-bottom: 18px;
        }

        .mail-header h2 {
            color: #e74c3c;
            margin: 0 0 2px 0;
            font-size: 22px;
            letter-spacing: 0.3px;
            font-weight: 800;
        }

        .mail-header .brand {
            color: #2c3e50;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .mail-content p {
            margin: 14px 0;
            color: #333;
            font-size: 15px;
        }

        .mail-content b {
            color: #e74c3c;
            font-size: 18px;
            letter-spacing: 1px;
        }

        .mail-footer {
            border-top: 1px solid #f1d5d5;
            margin-top: 32px;
            padding-top: 16px;
            color: #888;
            font-size: 13px;
            text-align: center;
        }

        @media (max-width: 500px) {
            .mail-container {
                padding: 12px 2vw 16px 2vw;
            }
        }
    </style>
</head>

<body>
    <div class="mail-container">
        <div class="mail-header">
            <h2>Khôi phục mật khẩu</h2>
            <div class="brand">CineTick</div>
        </div>
        <div class="mail-content">
            <p>Xin chào <strong>{{ $user->HoTen ?? 'bạn' }}</strong>,</p>
            <p>Bạn vừa yêu cầu <b>lấy lại mật khẩu</b> tại hệ thống CineTick.</p>
            <p>
                Mật khẩu mới của bạn là:<br>
                <b>{{ $newPassword }}</b>
            </p>
            <p>
                <span style="color:#e67e22;">
                    Vui lòng đăng nhập và <b>đổi lại mật khẩu</b> để bảo mật tài khoản.
                </span>
            </p>
            <p style="margin-top:16px;">
                Nếu bạn không thực hiện yêu cầu này, hãy bỏ qua email này.<br>
                Nếu cần hỗ trợ, vui lòng liên hệ bộ phận chăm sóc khách hàng của CineTick.
            </p>
        </div>
        <div class="mail-footer">
            Trân trọng,<br>
            Đội ngũ <span>CineTick</span><br>
            &copy; {{ date('Y') }} CineTick
        </div>
    </div>
</body>

</html>
