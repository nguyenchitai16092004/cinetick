<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận tài khoản - CineTick</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .mail-container {
            max-width: 420px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(44, 62, 80, 0.10);
            padding: 36px 34px 26px 34px;
            border: 1.5px solid #e74c3c33;
        }
        .mail-header {
            text-align: center;
            margin-bottom: 18px;
        }
        .mail-header h2 {
            color: #e74c3c;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .mail-content p {
            margin: 14px 0;
            color: #222;
            font-size: 15px;
        }
        .verify-btn {
            display: inline-block;
            margin: 16px 0 12px 0;
            padding: 10px 28px;
            background: linear-gradient(90deg, #e74c3c 50%, #ffbe76 100%);
            color: #fff !important;
            font-size: 16px;
            font-weight: bold;
            border-radius: 7px;
            text-decoration: none;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(231,76,60,0.07);
            transition: background 0.2s;
        }
        .verify-btn:hover {
            background: linear-gradient(90deg, #ff5e57 50%, #ffbe76 100%);
        }
        .mail-footer {
            border-top: 1px solid #f1d5d5;
            margin-top: 24px;
            padding-top: 13px;
            color: #888;
            font-size: 13px;
            text-align: center;
        }
        @media (max-width: 500px) {
            .mail-container { padding: 10px 3vw 16px 3vw; }
        }
    </style>
</head>
<body>
    <div class="mail-container">
        <div class="mail-header">
            <h2>Xác nhận tài khoản</h2>
        </div>
        <div class="mail-content">
            <p>Chào <strong>{{ $TenDN }}</strong>,</p>
            <p>Vui lòng bấm vào nút dưới đây để hoàn tất xác nhận tài khoản của bạn:</p>
            <p style="text-align:center;">
                <a href="{{ route('verify.account', $token) }}" class="verify-btn" target="_blank">
                    Xác nhận tài khoản
                </a>
            </p>
            <p style="font-size:13px; color:#e67e22;">
                Nếu nút không hoạt động, hãy sao chép liên kết sau vào trình duyệt:<br>
                <span style="word-break:break-all;">{{ route('verify.account', $token) }}</span>
            </p>
            <p style="margin-top:18px;">
                Nếu bạn không đăng ký tài khoản, vui lòng bỏ qua email này.
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