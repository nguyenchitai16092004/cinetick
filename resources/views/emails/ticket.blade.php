<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Vé Xem Phim - {{ $data['ma_hoa_don'] ?? '' }}</title>
    <style>
        body {
            background: #f4f6fb;
            font-family: "Josefin Sans", sans-serif;
            margin: 0;
            padding: 24px;
        }

        .ticket-container {
            max-width: 480px;
            margin: 32px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(44, 62, 80, 0.09);
            padding: 32px 38px 30px 38px;
            border: 1.5px solid #e74c3c;
            position: relative;
            overflow: hidden;
        }

        .ticket-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .ticket-header h2 {
            margin: 0;
            color: #e74c3c;
            font-size: 28px;
            font-weight: 800;
            text-shadow: 1px 3.5px 6px rgba(231, 76, 60, 0.08);
            letter-spacing: 1.2px;
        }

        .ticket-header .code {
            margin-top: 6px;
            color: #2c3e50;
            font-size: 13.5px;
            background: #fbeee9;
            display: inline-block;
            padding: 2.5px 14px;
            border-radius: 15px;
            font-weight: bold;
            border: 1px solid #ffd6cc;
        }

        .divider {
            border-top: 1px dashed #e74c3c;
            margin: 20px 0 22px 0;
        }

        .ticket-info {
            font-size: 15.5px;
            color: #30323d;
        }

        .ticket-info-row {
            margin-bottom: 11px;
            display: flex;
        }

        .ticket-info-label {
            width: 112px;
            color: #888;
            font-weight: bold;
            flex-shrink: 0;
        }

        .ticket-info-value {
            flex: 1;
            color: #222;
        }

        .seat-list {
            display: inline-block;
            background: #e74c3c;
            color: #fff;
            padding: 2.5px 11px;
            border-radius: 7px;
            font-weight: 600;
            font-size: 15px;
            margin-right: 5px;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 7px rgba(231, 76, 60, 0.06);
        }

        .qr-code {
            text-align: center;
            margin-top: 24px;
            margin-bottom: 16px;
        }

        .qr-code img {
            width: 104px;
            height: 104px;
            border-radius: 9px;
            border: 2.5px solid #e74c3c33;
            box-shadow: 0 2px 12px rgba(44, 62, 80, 0.08);
        }

        .qr-hint {
            color: #e67e22;
            font-size: 12.5px;
            margin-top: 7px;
            font-style: italic;
        }

        .ticket-footer {
            text-align: center;
            color: #888;
            font-size: 13.7px;
            border-top: 1px solid #f1d5d5;
            padding-top: 19px;
            margin-top: 18px;
        }

        @media (max-width: 550px) {
            .ticket-container {
                padding: 12px 4vw 18px 4vw;
            }
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <div class="ticket-header">
            <h2>VÉ XEM PHIM</h2>
            <div class="code">Mã hóa đơn: {{ $data['ma_hoa_don'] ?? '' }}</div>
        </div>
        <div class="divider"></div>
        <div class="ticket-info">
            <div class="ticket-info-row">
                <div class="ticket-info-label">Khách hàng:</div>
                <div class="ticket-info-value">{{ $data['ten_khach_hang'] ?? '' }}</div>
            </div>
            <div class="ticket-info-row">
                <div class="ticket-info-label">Email:</div>
                <div class="ticket-info-value">{{ $data['email'] ?? '' }}</div>
            </div>
            <div class="ticket-info-row">
                <div class="ticket-info-label">Tên phim:</div>
                <div class="ticket-info-value">{{ $data['ten_phim'] ?? '' }}</div>
            </div>
            <div class="ticket-info-row">
                <div class="ticket-info-label">Suất chiếu:</div>
                <div class="ticket-info-value">
                    {{ $data['gio_chieu'] ?? '' }}
                    @if (!empty($data['ngay_chieu']))
                        - {{ $data['ngay_chieu'] }}
                    @endif
                </div>
            </div>
            <div class="ticket-info-row">
                <div class="ticket-info-label">Phòng:</div>
                <div class="ticket-info-value">{{ $data['phong'] ?? '' }}</div>
            </div>
            <div class="ticket-info-row">
                <div class="ticket-info-label">Ghế:</div>
                <div class="ticket-info-value">
                    @if (!empty($data['ghe']))
                        @foreach (explode(',', $data['ghe']) as $ghe)
                            <span class="seat-list">{{ trim($ghe) }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="ticket-info-row">
                <div class="ticket-info-label">Giá vé:</div>
                <div class="ticket-info-value">{{ number_format($data['gia_ve'] ?? 0, 0, ',', '.') }} đ</div>
            </div>
            <div class="ticket-info-row">
                <div class="ticket-info-label">Thanh toán:</div>
                <div class="ticket-info-value">{{ $data['hinh_thuc_thanh_toan'] ?? '' }}</div>
            </div>
            <div class="ticket-info-row">
                <div class="ticket-info-label">Thời gian đặt:</div>
                <div class="ticket-info-value">{{ $data['thoi_gian_dat'] ?? '' }}</div>
            </div>
            <div class="ticket-info-row">
                <div class="ticket-info-label">Trạng thái:</div>
                <div class="ticket-info-value">{{ $data['trang_thai'] ?? '' }}</div>
            </div>
        </div>
        <div class="divider"></div>
        @if (!empty($data['ma_hoa_don']))
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $data['ma_hoa_don'] }}"
                    alt="QR Vé Xem Phim" />
                <div class="qr-hint">Quét mã hoặc xuất trình vé để check-in tại rạp</div>
            </div>
        @endif
        <div class="ticket-footer">
            Cảm ơn bạn đã mua vé tại <span>CineTick</span>!<br>
            Vui lòng xuất trình vé này (hoặc mã QR) khi đến rạp.<br>
            &copy; {{ date('Y') }} CineTick
        </div>
    </div>
</body>

</html>
