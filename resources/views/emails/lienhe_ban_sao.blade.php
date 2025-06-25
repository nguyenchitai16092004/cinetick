<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Liên hệ CineTick</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
            padding: 30px 36px;
        }

        .email-header {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 18px;
            text-align: left;
        }

        .email-body p {
            font-size: 15px;
            margin: 6px 0 14px;
        }

        .email-body table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 15px;
        }

        .email-body td {
            padding: 8px 4px;
            vertical-align: top;
        }

        .email-body td:first-child {
            font-weight: bold;
            color: #666;
            width: 120px;
            padding-right: 12px;
        }

        .highlight {
            color: #e74c3c;
            font-weight: bold;
        }

        .email-footer {
            margin-top: 30px;
            padding-top: 16px;
            font-size: 13px;
            color: #888;
            border-top: 1px dotted #ccc;
            text-align: center;
        }

        @media (max-width: 600px) {
            .email-wrapper {
                padding: 20px 18px;
            }

            .email-body td:first-child {
                width: 100px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-header">
            Cảm ơn bạn đã liên hệ với CineTick!
        </div>

        <div class="email-body">
            <p>Xin chào <span class="highlight">{{ $name }}</span>,</p>
            <p>Chúng tôi đã nhận được thông tin liên hệ của bạn. Dưới đây là chi tiết:</p>

            <table>
                <tr>
                    <td>Họ và tên:</td>
                    <td>{{ $name }}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{ $email }}</td>
                </tr>
                <tr>
                    <td>Điện thoại:</td>
                    <td>{{ $phone }}</td>
                </tr>
                <tr>
                    <td>Tiêu đề:</td>
                    <td>{{ $subject }}</td>
                </tr>
                <tr>
                    <td>Nội dung:</td>
                    <td>{!! nl2br(e($message_body)) !!}</td>
                </tr>
            </table>
        </div>

        <div class="email-footer">
            Vui lòng không trả lời email này.<br>
            Đội ngũ <strong>CineTick</strong> sẽ phản hồi bạn sớm nhất có thể.<br>
            &copy; {{ date('Y') }} CineTick
        </div>
    </div>
</body>

</html>
