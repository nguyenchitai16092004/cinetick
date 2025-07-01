@extends('user.layouts.master')
@section('title', 'CineTick - Kiểm tra thanh toán')
@section('main')
<link rel="stylesheet" href="{{ asset('user/Content/css/check-payment.css') }}">

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
                    @php
                        $goc = isset($hoaDon->TongTien) ? $hoaDon->TongTien : $totalPrice ?? 0;
                        $giam = isset($hoaDon->SoTienGiam) ? $hoaDon->SoTienGiam : $soTienGiam ?? 0;
                        $thanhTien = $goc - $giam;
                    @endphp
                    <div class="seats-row @if (session('status') !== 'success') failure @endif">
                        <div class="pricing-row">
                            <span class="pricing-label @if (session('status') !== 'success') failure @endif">Tạm tính</span>
                            <span class="pricing-value @if (session('status') !== 'success') failure @endif">
                                {{ number_format($goc, 0, ',', '.') . 'đ' }}
                            </span>
                        </div>
                        <div class="pricing-row">
                            <span class="pricing-label @if (session('status') !== 'success') failure @endif">Giảm giá</span>
                            <span class="pricing-value @if (session('status') !== 'success') failure @endif">
                                {{ $giam > 0 ? '-' . number_format($giam, 0, ',', '.') . 'đ' : '0đ' }}
                            </span>
                        </div>
                        <div class="pricing-row">
                            <span class="pricing-label @if (session('status') !== 'success') failure @endif">Thành tiền</span>
                            <span class="pricing-value @if (session('status') !== 'success') failure @endif">
                                {{ number_format($thanhTien, 0, ',', '.') . 'đ' }}
                            </span>
                        </div>
                        <div class="pricing-row @if (session('status') !== 'success') failure @endif">
                            <span class="pricing-label total  @if (session('status') !== 'success') failure @endif">Tổng
                                cộng</span>
                            <span class="pricing-value @if (session('status') !== 'success') failure @endif">
                                {{ number_format($thanhTien, 0, ',', '.') . 'đ' }}
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
