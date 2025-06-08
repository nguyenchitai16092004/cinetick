<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Vé Xem Phim - {{ $data['ma_hoa_don'] ?? '' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        .ticket-container {
            max-width: 450px;
            margin: 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
            padding: 24px 32px 32px 32px;
            border: 2px dashed #e74c3c;
            position: relative;
        }

        .ticket-header {
            text-align: center;
            padding-bottom: 8px;
        }

        .ticket-header h2 {
            margin: 0;
            color: #e74c3c;
        }

        .ticket-info {
            margin: 18px 0;
        }

        .ticket-info strong {
            display: inline-block;
            width: 120px;
            color: #444;
        }

        .divider {
            border-top: 1px dashed #e74c3c;
            margin: 18px 0;
        }

        .ticket-footer {
            text-align: center;
            font-size: 13px;
            color: #888;
            margin-top: 22px;
        }

        .seat-list {
            display: inline-block;
            background: #e74c3c;
            color: #fff;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: bold;
            margin-right: 4px;
        }

        .qr-code {
            text-align: center;
            margin-top: 18px;
        }

        .qr-code img {
            width: 96px;
            height: 96px;
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <div class="ticket-header">
            <h2>VÉ XEM PHIM</h2>
            <div>Mã hóa đơn: <b>{{ $data['ma_hoa_don'] ?? '' }}</b></div>
        </div>
        <div class="divider"></div>
        <div class="ticket-info">
            <div><strong>Khách hàng:</strong> {{ $data['ten_khach_hang'] ?? '' }}</div>
            <div><strong>Email:</strong> {{ $data['email'] ?? '' }}</div>
            <div><strong>Tên phim:</strong> {{ $data['ten_phim'] ?? '' }}</div>
            <div><strong>Suất chiếu:</strong> {{ $data['gio_chieu'] ?? '' }} - {{ $data['ngay_chieu'] ?? '' }}</div>
            <div><strong>Phòng:</strong> {{ $data['phong'] ?? '' }}</div>
            <div>
                <strong>Ghế:</strong>
                @if (!empty($data['ghe']))
                    @foreach (explode(',', $data['ghe']) as $ghe)
                        <span class="seat-list">{{ trim($ghe) }}</span>
                    @endforeach
                @endif
            </div>
            <div><strong>Giá vé:</strong> {{ number_format($data['gia_ve'] ?? 0, 0, ',', '.') }} VNĐ</div>
            <div><strong>Thanh toán:</strong> {{ $data['hinh_thuc_thanh_toan'] ?? '' }}</div>
            <div><strong>Thời gian đặt:</strong> {{ $data['thoi_gian_dat'] ?? '' }}</div>
            <div><strong>Trạng thái:</strong> {{ $data['trang_thai'] ?? '' }}</div>
        </div>
        <div class="divider"></div>
        @if (!empty($data['ma_hoa_don']))
            <div class="qr-code">
                {{-- Nếu có mã QR code, bạn thay thế src này bằng mã QR thật --}}
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $data['ma_hoa_don'] }}"
                    alt="QR Vé Xem Phim" />
                <div>Mã hóa đơn dùng để check-in</div>
            </div>
        @endif
        <div class="ticket-footer">
            Cảm ơn bạn đã mua vé tại hệ thống! <br>
            Vui lòng xuất trình vé này (hoặc mã QR) khi đến rạp.
        </div>
    </div>
</body>

</html>
