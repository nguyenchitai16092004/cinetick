<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận tài khoản - CineTick</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 480px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            padding: 32px 28px;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .header h1 {
            color: #e74c3c;
            font-size: 24px;
            margin: 0;
            font-weight: 700;
        }

        .content {
            color: #333;
            font-size: 15px;
            line-height: 1.6;
        }

        .content p {
            margin: 12px 0;
        }

        .btn-container {
            text-align: center;
            margin: 24px 0 20px;
        }

        .verify-btn {
            display: inline-block;
            background: linear-gradient(135deg, #e74c3c, #ffbe76);
            color: #fff;
            padding: 12px 30px;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);
            transition: all 0.3s ease;
        }

        .verify-btn:hover {
            background: linear-gradient(135deg, #ff5e57, #f9ca24);
            box-shadow: 0 6px 16px rgba(231, 76, 60, 0.3);
        }

        .fallback-link {
            background-color: #fef9f4;
            padding: 12px;
            border-radius: 6px;
            font-size: 13px;
            color: #e67e22;
            word-break: break-word;
            margin-top: 16px;
        }

        .footer {
            text-align: center;
            font-size: 13px;
            color: #888;
            border-top: 1px solid #eee;
            margin-top: 32px;
            padding-top: 16px;
        }

        @media (max-width: 520px) {
            .container {
                padding: 20px 16px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Xác nhận tài khoản</h1>
        </div>
        <div class="content">
            <p>Chào <strong><?php echo e($TenDN); ?></strong>,</p>
            <p>Hãy nhấn vào nút bên dưới để hoàn tất xác nhận tài khoản CineTick của bạn:</p>

            <div class="btn-container">
                <a href="<?php echo e(route('verify.account', $token)); ?>" class="verify-btn" target="_blank">
                    Xác nhận tài khoản
                </a>
            </div>

            <p>Nếu nút không hoạt động, hãy sao chép và dán liên kết sau vào trình duyệt:</p>
            <div class="fallback-link">
                <?php echo e(route('verify.account', $token)); ?>

            </div>

            <p>Nếu bạn không yêu cầu đăng ký tài khoản, vui lòng bỏ qua email này.</p>
        </div>
        <div class="footer">
            Trân trọng,<br>
            Đội ngũ <strong>CineTick</strong><br>
            &copy; <?php echo e(date('Y')); ?> CineTick
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/emails/verify-email.blade.php ENDPATH**/ ?>