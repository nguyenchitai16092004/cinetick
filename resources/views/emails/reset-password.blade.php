<p>Xin chào {{ $user->HoTen ?? 'bạn' }},</p>
<p>Bạn vừa yêu cầu lấy lại mật khẩu tại hệ thống CineTick.</p>
<p>Mật khẩu mới của bạn là: <b>{{ $newPassword }}</b></p>
<p>Vui lòng đăng nhập và đổi lại mật khẩu để bảo mật tài khoản.</p>
<p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
<p>Trân trọng,<br>CineTick</p>
{{-- <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineTick - Thay đổi mật khẩu</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #4a4a4a;
            color: #333;
        }
        
        .top-banner {
            background-color: #333;
            color: white;
            padding: 2px;
            text-align: right;
            font-size: 12px;
        }
        
        .email-container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .header {
            background-color: #f47629;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-text {
            font-weight: bold;
        }
        
        .hotline {
            text-align: right;
        }
        
        .logo-section {
            background-color: #f8f8f8;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            width: 200px;
        }
        
        .logo img {
            max-width: 100%;
        }
        
        .nav-links {
            text-align: right;
        }
        
        .nav-links a {
            color: #333;
            text-decoration: none;
            margin-left: 15px;
            font-size: 12px;
        }
        
        .content {
            background-color: white;
            padding: 20px;
        }
        
        .greeting {
            margin-bottom: 20px;
        }
        
        .info-text {
            margin-bottom: 20px;
        }
        
        .password-change-box {
            background-color: #0066a6;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        
        .account-info {
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        .account-info table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .account-info td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .account-info td:first-child {
            width: 150px;
        }
        
        .instructions {
            margin-bottom: 20px;
        }
        
        .login-button {
            text-align: center;
            margin: 20px 0;
        }
        
        .btn {
            background-color: #f47629;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .alternative {
            text-align: center;
            margin: 15px 0;
        }
        
        .login-link {
            text-align: center;
            margin: 15px 0;
        }
        
        .login-link a {
            color: #0066a6;
            text-decoration: none;
        }
        
        .thank-you {
            margin-top: 20px;
        }
        
        .footer {
            background-color: #f8f8f8;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }
        
        .footer a {
            color: #0066a6;
            text-decoration: none;
        }
        
        .copyright {
            background-color: #f47629;
            color: white;
            padding: 10px 20px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="top-banner">
        Đây là email tự động. Quý khách vui lòng không trả lời email này.
    </div>
    
    <div class="email-container">
        <div class="header">
            <div class="header-text">{{ now()->format('d/m/Y H:i') }}</div>
            <div class="hotline">Hotline: 1900 1722</div>
        </div>
        
        <div class="logo-section">
            <div class="logo">
                <img src="{{ asset('frontend/Content/img/lgCineTick.png') }}" alt="CineTick Logo">
            </div>
            <div class="nav-links">
                <a href="#">CineTick</a>
                <a href="#">HỆ THỐNG RẠP CHIẾU PHIM CineTick</a>
            </div>
        </div>
        
        <div class="content">
            <div class="greeting">
                Chào bạn {{ $user->HoTen ?? '' }},
            </div>
            
            <div class="info-text">
                Bạn hoặc một ai đó đã thực hiện chức năng quên mật khẩu trên hệ thống rạp chiếu phim CineTick.
            </div>
            
            <div class="password-change-box">
                THÔNG TIN THAY ĐỔI MẬT KHẨU
            </div>
            
            <div class="account-info">
                <table>
                    <tr>
                        <td>Tài khoản:</td>
                        <td><a href="mailto:{{ $user->Email }}">{{ $user->Email }}</a></td>
                    </tr>
                    <tr>
                        <td>Mật khẩu mới</td>
                        <td>{{ $newPassword }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="instructions">
                <strong>Thao tác:</strong><br>
                Để thay đổi mật khẩu, bạn cần đăng nhập với mật khẩu mới chúng tôi đã gửi cho bạn. Đăng nhập bằng cách truy cập vào link bên dưới
            </div>
            
            <div class="login-button">
                <a href="{{ url('/dang-nhap') }}" class="btn">Đăng Nhập</a>
            </div>
            
            <div class="alternative">
                Hoặc truy cập link
            </div>
            
            <div class="login-link">
                <a href="{{ url('/dang-nhap') }}">{{ url('/dang-nhap') }}</a>
            </div>
            
            <div class="thank-you">
                Trân trọng cảm ơn!
            </div>
        </div>
        
        <div class="footer">
            <div>Tel: 1900 1722</div>
            <div>|Web: <a href="http://www.cinetick.vn">www.cinetick.vn</a></div>
        </div>
        
        <div class="copyright">
            Copyright (c) 2018 CineTick. All Rights Reserved
        </div>
    </div>
    
</body>
</html> --}}