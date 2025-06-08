@extends('frontend.layouts.master')
@section('title', 'Kiểm tra thanh toán')
@section('main')
    <style>
        .container-payment-status {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .status-header {
            text-align: center;
            padding: 40px 20px 20px 20px;
            background: white;
        }

        .success-icon,
        .failure-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
        }

        .success-icon {
            background: #4CAF50;
        }

        .failure-icon {
            background: #f44336;
        }

        .status-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .status-title.success {
            color: #333;
        }

        .status-title.failure {
            color: #f44336;
        }

        .status-subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .error-message {
            background: #ffebee;
            border: 1px solid #ffcdd2;
            border-radius: 8px;
            padding: 15px;
            margin: 20px;
            color: #c62828;
            font-size: 14px;
            text-align: center;
        }

        .content-wrapper {
            display: flex;
            padding: 0 20px 20px;
            gap: 20px;
        }

        .ticket-details {
            flex: 1;
        }

        .movie-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            color: #374151;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            font-family: 'Courier New', monospace;
            opacity: 0.95;
        }

        .movie-card.failure {
            border: 2px solid #ffcdd2;
            opacity: 0.7;
        }

        .ticket-header {
            background: #f9fafb;
            margin: 0;
            padding: 20px 30px;
            border-bottom: 2px dashed #d1d5db;
            position: relative;
        }

        .ticket-header.failure {
            background: #ffebee;
            border-bottom: 2px dashed #ffcdd2;
        }

        .ticket-header::before,
        .ticket-header::after {
            content: '';
            position: absolute;
            top: 100%;
            width: 24px;
            height: 24px;
            background: #f5f5f5;
            border-radius: 50%;
            transform: translateY(-50%);
            border: 2px solid #e5e7eb;
        }

        .ticket-header.failure::before,
        .ticket-header.failure::after {
            border: 2px solid #ffcdd2;
        }

        .ticket-header::before {
            left: -12px;
        }

        .ticket-header::after {
            right: -12px;
        }

        .movie-title {
            background: #374151;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            display: inline-block;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .movie-title.failure {
            background: #757575;
        }

        .ticket-content {
            padding: 25px 30px;
            position: relative;
            z-index: 2;
        }

        .movie-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 25px;
        }

        .info-item {
            text-align: left;
        }

        .movie-info-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .movie-info-value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            font-family: 'Courier New', monospace;
        }

        .cinema-section {
            border-top: 2px dashed #d1d5db;
            padding-top: 20px;
        }

        .cinema-section.failure {
            border-top: 2px dashed #ffcdd2;
        }

        .cinema-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cinema-name.failure {
            color: #757575;
        }

        .cinema-address {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .cinema-address.failure {
            color: #9e9e9e;
        }

        .cinema-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .cinema-room,
        .cinema-format {
            font-size: 14px;
            font-weight: bold;
            background: #f3f4f6;
            color: #374151;
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cinema-room.failure,
        .cinema-format.failure {
            background: #f5f5f5;
            color: #757575;
            border: 1px solid #e0e0e0;
        }

        .ticket-number {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 12px;
            color: #9ca3af;
            font-weight: bold;
            font-family: 'Courier New', monospace;
        }

        .ticket-number.failure {
            color: #c62828;
        }

        .pricing-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .pricing-section.failure {
            background: #ffebee;
            border: 1px solid #ffcdd2;
            opacity: 0.7;
        }

        .pricing-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .pricing-label {
            color: #666;
        }

        .pricing-label.failure {
            color: #757575;
        }

        .pricing-value {
            font-weight: bold;
            color: #333;
        }

        .pricing-value.failure {
            color: #757575;
        }

        .seats-row {
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
        }

        .seats-row.failure {
            border-top: 1px solid #ffcdd2;
        }

        .total-section {
            background: white;
            padding: 15px 20px;
            border-top: 1px solid #eee;
        }

        .total-section.failure {
            background: #ffebee;
            border-top: 1px solid #ffcdd2;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .total-label.failure {
            color: #757575;
        }

        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .total-amount.failure {
            color: #757575;
            text-decoration: line-through;
        }

        .qr-section {
            width: 400px;
            background: linear-gradient(135deg, #E91E63, #9C27B0);
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            margin: 0 auto;
            border-radius: 8px;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            background: white;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-text {
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .qr-button {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .qr-button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .failure-section {
            width: 400px;
            background: linear-gradient(135deg, #f44336, #d32f2f);
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            margin: 0 auto;
            border-radius: 8px;
        }

        .failure-icon-large {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 48px;
        }

        .failure-text {
            font-size: 16px;
            margin-bottom: 15px;
            line-height: 1.4;
            font-weight: bold;
        }

        .failure-reason {
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.4;
            opacity: 0.9;
        }

        .contact-button {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .contact-button:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            padding: 20px;
        }

        .main-button {
            background: #f44336;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .main-button:hover {
            background: #d32f2f;
            transform: translateY(-1px);
        }

        .secondary-button {
            background: transparent;
            color: #f44336;
            border: 2px solid #f44336;
            padding: 13px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .total {
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
        }

        .secondary-button:hover {
            background: #f44336;
            color: white;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                flex-direction: column;
            }

            .qr-section,
            .failure-section {
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .main-button,
            .secondary-button {
                width: 80%;
                max-width: 300px;
            }
        }
    </style>
    <div class="container-payment-status">
        <div class="status-header">
            @if (session('status') === 'success' && isset($hoaDon) && isset($suatChieu))
                <div class="success-icon">✓</div>
                <div class="status-title success">Đặt vé thành công!</div>
                <div class="status-subtitle">
                    Cảm ơn bạn đã thanh toán thành công đơn hàng #{{ $hoaDon->ID_HoaDon ?? '---' }}
                </div>
            @else
                <div class="failure-icon">✗</div>
                <div class="status-title failure">Đặt vé thất bại!</div>
                <div class="status-subtitle">
                    Rất tiếc, thanh toán không thành công cho đơn hàng #{{ $hoaDon->ID_HoaDon ?? '---' }}
                </div>
            @endif
        </div>
        @if (session('status') === 'fail' || session('status') === 'cancel')
            <div class="error-message">
                <strong>Lỗi thanh toán:</strong>
                {{ session('error_message') ?? 'Giao dịch bị từ chối. Vui lòng kiểm tra thông tin thẻ hoặc thử lại sau.' }}
            </div>
        @endif

        <div class="content-wrapper">
            <div class="ticket-details">
                <div class="movie-card @if (session('status') !== 'success') failure @endif">
                    <div class="ticket-number @if (session('status') !== 'success') failure @endif">
                        #{{ $hoaDon->ID_HoaDon ?? '---' }}
                    </div>
                    <div class="ticket-header @if (session('status') !== 'success') failure @endif">
                        <div class="movie-title @if (session('status') !== 'success') failure @endif">
                            @if (isset($suatChieu->phim))
                                {{ $suatChieu->phim->DoTuoi ?? '' }} {{ $suatChieu->phim->TenPhim ?? '' }}
                            @else
                                {{ $movieTitle ?? '---' }}
                            @endif
                        </div>
                    </div>
                    <div class="ticket-content">
                        <div class="movie-info">
                            <div class="info-item">
                                <div class="movie-info-label @if (session('status') !== 'success') failure @endif">Thời gian
                                </div>
                                <div class="movie-info-value @if (session('status') !== 'success') failure @endif">
                                    @if (isset($suatChieu->GioChieu))
                                        {{ substr($suatChieu->GioChieu, 0, 5) }}
                                    @else
                                        {{ $time ?? '---' }}
                                    @endif
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="movie-info-label @if (session('status') !== 'success') failure @endif">Ngày chiếu
                                </div>
                                <div class="movie-info-value @if (session('status') !== 'success') failure @endif">
                                    @if (isset($suatChieu->NgayChieu))
                                        {{ \Carbon\Carbon::parse($suatChieu->NgayChieu)->format('d/m/Y') }}
                                    @else
                                        {{ $date ?? '---' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="cinema-section @if (session('status') !== 'success') failure @endif">
                            <div class="cinema-name @if (session('status') !== 'success') failure @endif">
                                {{ $suatChieu->rap->TenRap ?? ($cinemaName ?? '---') }}
                            </div>
                            <div class="cinema-address @if (session('status') !== 'success') failure @endif">
                                {{ $suatChieu->rap->DiaChi ?? ($cinemaAddress ?? '---') }}
                            </div>
                            <div class="cinema-details">
                                <div class="info-item">
                                    <div class="movie-info-label @if (session('status') !== 'success') failure @endif">Phòng
                                        chiếu</div>
                                    <div class="cinema-room @if (session('status') !== 'success') failure @endif">
                                        {{ $suatChieu->phongChieu->TenPhongChieu ?? ($cinemaRoom ?? '---') }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="movie-info-label @if (session('status') !== 'success') failure @endif">Ghế
                                    </div>
                                    <div class="cinema-format @if (session('status') !== 'success') failure @endif">
                                        {{ isset($selectedSeats) && is_array($selectedSeats) ? implode(', ', $selectedSeats) : $seats ?? '---' }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="movie-info-label @if (session('status') !== 'success') failure @endif">Đồ họa
                                    </div>
                                    <div class="cinema-format @if (session('status') !== 'success') failure @endif">
                                        {{ $suatChieu->phim->DoHoa ?? ($cinemaFormat ?? '---') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pricing-section @if (session('status') !== 'success') failure @endif">
                    <div class="pricing-row">
                        <span class="pricing-label @if (session('status') !== 'success') failure @endif">GHẾ</span>
                    </div>
                    <div class="pricing-row">
                        <span class="pricing-label @if (session('status') !== 'success') failure @endif">
                            {{ isset($selectedSeats) && is_array($selectedSeats) ? implode(', ', $selectedSeats) : $seats ?? '---' }}
                        </span>
                        <span class="pricing-value @if (session('status') !== 'success') failure @endif">
                            {{ isset($hoaDon->TongTien) ? number_format($hoaDon->TongTien, 0, ',', '.') . 'đ' : $totalPrice ?? '---' }}
                        </span>
                    </div>
                    <div class="seats-row @if (session('status') !== 'success') failure @endif">
                        <div class="pricing-row">
                            <span class="pricing-label @if (session('status') !== 'success') failure @endif">Tạm tính</span>
                            <span class="pricing-value @if (session('status') !== 'success') failure @endif">
                                {{ isset($hoaDon->TongTien) ? number_format($hoaDon->TongTien, 0, ',', '.') . 'VNĐ' : $totalPrice ?? '---' }}
                            </span>
                        </div>
                        <div class="pricing-row">
                            <span class="pricing-label @if (session('status') !== 'success') failure @endif">Giảm giá</span>
                            <span class="pricing-value @if (session('status') !== 'success') failure @endif">0 VNĐ</span>
                        </div>
                        <div class="pricing-row">
                            <span class="pricing-label @if (session('status') !== 'success') failure @endif">Thành tiền</span>
                            <span class="pricing-value @if (session('status') !== 'success') failure @endif">
                                {{ isset($hoaDon->TongTien) ? number_format($hoaDon->TongTien, 0, ',', '.') . 'VNĐ' : $totalPrice ?? '---' }}
                            </span>
                        </div>
                        <div class="pricing-row @if (session('status') !== 'success') failure @endif">
                            <span class="pricing-label total  @if (session('status') !== 'success') failure @endif">Tổng
                                cộng</span>
                            <span class="pricing-value @if (session('status') !== 'success') failure @endif">
                                {{ isset($hoaDon->TongTien) ? number_format($hoaDon->TongTien, 0, ',', '.') . 'VNĐ' : $totalPrice ?? '---' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @if (session('status') === 'success' && isset($hoaDon))
                <div class="qr-section">
                    <a href="#" id="download-qr-link" class="qr-button" title="Tải xuống QRCode">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $hoaDon->ID_HoaDon ?? '---' }}"
                            alt="QR Code" style="width:200px;height:200px;cursor:pointer;" />
                    </a>
                    <div class="qr-text">Click vào mã QR để tải xuống</div>
                    <div class="qr-text">Vui lòng cung cấp mã QR Code này cho nhân viên tại rạp. Đội ngũ CineTick xin cảm
                        ơn!</div>
                </div>

                <script>
                    document.getElementById('download-qr-link').addEventListener('click', function(e) {
                        e.preventDefault();
                        // Dùng link ảnh lớn để tải về
                        var imageUrl =
                            "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={{ $hoaDon->ID_HoaDon ?? '---' }}";
                        var fileName = "QRCode-{{ $hoaDon->ID_HoaDon ?? '' }}.png";
                        fetch(imageUrl, {
                                mode: 'cors'
                            })
                            .then(resp => resp.blob())
                            .then(blob => {
                                var url = window.URL.createObjectURL(blob);
                                var a = document.createElement('a');
                                a.style.display = 'none';
                                a.href = url;
                                a.download = fileName;
                                document.body.appendChild(a);
                                a.click();
                                window.URL.revokeObjectURL(url);
                            }).catch(() => alert(
                                'Không thể tự động tải về QR code. Vui lòng click chuột phải vào ảnh để lưu.'));
                    });
                </script>
            @else
                <div class="failure-section">
                    <div class="failure-icon-large">✗</div>
                    <div class="failure-text">Thanh toán thất bại</div>
                    <div class="failure-reason">
                        Giao dịch không thể hoàn tất.<br>
                        Vui lòng thử lại hoặc liên hệ hỗ trợ.
                    </div>
                    <a href="{{ route('lien-he') }}" class="contact-button">Liên hệ hỗ trợ</a>
                </div>
            @endif
        </div>
        @if (session('status') === 'success')
            <div class="action-buttons">
                <a href="{{ route('home') }}" class="main-button" style="background: #E91E63;">Quay lại trang chủ</a>
            </div>
        @else
            <div class="action-buttons">
                {{-- <a href="{{ route('checkout_status') }}" class="main-button">Thử lại</a> --}}
                <a href="{{ route('home') }}" class="secondary-button">Quay lại trang chủ</a>
            </div>
        @endif
    </div>
@stop
