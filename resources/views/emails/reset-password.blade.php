<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Khôi phục mật khẩu - CineTick</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 0;
            margin: 0;
            color: #333;
        }

        .email-container {
            max-width: 480px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            padding: 30px 36px;
            border: 1px solid #f1f1f1;
        }

        .email-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .email-header h2 {
            color: #e74c3c;
            margin: 0;
            font-size: 22px;
        }

        .email-header .brand {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }

        .email-body p {
            font-size: 15px;
            margin: 14px 0;
            line-height: 1.6;
        }

        .new-password {
            font-size: 18px;
            font-weight: bold;
            background: #fcebea;
            color: #e74c3c;
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            margin-top: 4px;
            text-align: center;
        }

        .email-footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 13px;
            color: #888;
        }

        @media (max-width: 520px) {
            .email-container {
                padding: 20px 18px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Khôi phục mật khẩu</h2>
            <div class="brand">CineTick</div>
        </div>

        <div class="email-body">
            <p>Xin chào <strong>{{ $user->HoTen ?? 'bạn' }}</strong>,</p>
            <p>Bạn đã gửi yêu cầu <strong>khôi phục mật khẩu</strong> trên hệ thống CineTick.</p>

            <p>Mật khẩu mới của bạn là:</p>
            <div style="text-align: center;">
                <div class="new-password">{{ $newPassword }}</div>
            </div>
            <p style="color:#e67e22;"><b>Vui lòng đăng nhập và đổi lại mật khẩu ngay để bảo vệ tài khoản của bạn.</b></p>

            <p>Vì yêu cầu này đã được gửi đến chúng tôi. Nên mật khẩu của bạn đã được đặt lại vui lòng sử dụng mật khẩu
                này trong lần đăng nhập kế tiếp!<br>
                Nếu cần hỗ trợ, vui lòng liên hệ với bộ phận CSKH của CineTick.</p>
        </div>

        <div class="email-footer">
            Trân trọng,<br>
            Đội ngũ <strong>CineTick</strong><br>
            &copy; {{ date('Y') }} CineTick
        </div>
    </div>
</body>

</html>
