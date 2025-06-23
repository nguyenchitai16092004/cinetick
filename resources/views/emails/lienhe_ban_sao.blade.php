<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Liên hệ CineTick</title>
    <style>
        body {
            font-family: "Josefin Sans", sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .mail-container {
            max-width: 540px;
            margin: 30px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 32px 36px 24px 36px;
        }
        .mail-title {
            color: #2c3e50;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 18px;
            letter-spacing: 0.5px;
        }
        .mail-content p {
            margin: 8px 0;
            color: #303030;
            font-size: 15px;
        }
        .mail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0;
        }
        .mail-table td {
            padding: 7px 4px;
            font-size: 15px;
            vertical-align: top;
            color: #222;
        }
        .mail-table tr td:first-child {
            color: #888;
            font-weight: bold;
            width: 120px;
        }
        .mail-footer {
            margin-top: 34px;
            padding-top: 15px;
            font-size: 13px;
            color: #888;
            border-top: 1px dotted #ccc;
            text-align: center;
        }
        .highlight {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="mail-container">
        <div class="mail-title">Cảm ơn bạn đã liên hệ với CineTick!</div>
        <div class="mail-content">
            <p>Xin chào <strong class="highlight">{{ $name }}</strong>,</p>
            <p>Đây là bản sao thông tin liên hệ bạn đã gửi tới <b>CineTick</b>:</p>
            <table class="mail-table">
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
        <div class="mail-footer">
            Vui lòng không trả lời email này.<br>
            Đội ngũ <b>CineTick</b> sẽ liên hệ lại với bạn trong thời gian sớm nhất!<br>
            &copy; {{ date('Y') }} CineTick
        </div>
    </div>
</body>
</html>