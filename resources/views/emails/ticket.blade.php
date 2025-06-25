<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Vé Xem Phim - {{ $data['ma_hoa_don'] ?? '' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f6f7fb;
            font-family: Arial, Helvetica, sans-serif;
            color: #2c3e50;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }

        .header {
            background: #e74c3c;
            color: #ffffff;
            text-align: center;
            padding: 24px 16px;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 1px;
        }

        .content {
            padding: 24px 30px;
            font-size: 15px;
            line-height: 1.6;
        }

        .info-row {
            display: flex;
            margin-bottom: 10px;
        }

        .label {
            width: 130px;
            font-weight: bold;
            color: #555;
        }

        .value {
            flex: 1;
            color: #222;
        }

        .seat-list {
            display: inline-block;
            background: #e74c3c;
            color: #fff;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13.5px;
            margin-right: 5px;
        }

        .divider {
            margin: 20px 0;
            border-top: 1px dashed #ccc;
        }

        .qr-section {
            text-align: center;
            padding: 16px;
        }

        .qr-section img {
            width: 110px;
            height: 110px;
            border-radius: 8px;
            border: 2px solid #e74c3c22;
        }

        .qr-section .hint {
            font-size: 12px;
            color: #e67e22;
            margin-top: 8px;
            font-style: italic;
        }

        .footer {
            background: #fafafa;
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: #777;
            border-top: 1px solid #eee;
        }

        @media (max-width: 600px) {
            .content {
                padding: 16px;
            }

            .label {
                width: 110px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>VÉ XEM PHIM</h1>
            <div style="font-size: 13px;">Mã hóa đơn: {{ $data['ma_hoa_don'] ?? '' }}</div>
        </div>

        <div class="content">
            <div class="info-row">
                <div class="label">Khách hàng:</div>
                <div class="value">{{ $data['ten_khach_hang'] ?? '' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Email:</div>
                <div class="value">{{ $data['email'] ?? '' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Tên phim:</div>
                <div class="value">{{ $data['ten_phim'] ?? '' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Suất chiếu:</div>
                <div class="value">{{ $data['gio_chieu'] ?? '' }} @if(!empty($data['ngay_chieu'])) - {{ $data['ngay_chieu'] }} @endif</div>
            </div>
            <div class="info-row">
                <div class="label">Phòng:</div>
                <div class="value">{{ $data['phong'] ?? '' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Ghế:</div>
                <div class="value">
                    @foreach (explode(',', $data['ghe'] ?? '') as $ghe)
                        <span class="seat-list">{{ trim($ghe) }}</span>
                    @endforeach
                </div>
            </div>
            <div class="info-row">
                <div class="label">Giá vé:</div>
                <div class="value">{{ number_format($data['gia_ve'] ?? 0, 0, ',', '.') }} đ</div>
            </div>
            <div class="info-row">
                <div class="label">Thanh toán:</div>
                <div class="value">{{ $data['hinh_thuc_thanh_toan'] ?? '' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Thời gian đặt:</div>
                <div class="value">{{ $data['thoi_gian_dat'] ?? '' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Trạng thái:</div>
                <div class="value">{{ $data['trang_thai'] ?? '' }}</div>
            </div>

            @if (!empty($data['ma_hoa_don']))
                <div class="divider"></div>
                <div class="qr-section">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $data['ma_hoa_don'] }}" alt="QR Code">
                    <div class="hint">Quét mã để check-in tại rạp</div>
                </div>
            @endif
        </div>

        <div class="footer">
            Cảm ơn bạn đã đặt vé tại <strong>CineTick</strong>!<br>
            Vui lòng xuất trình email này hoặc mã QR tại quầy check-in.<br>
            &copy; {{ date('Y') }} CineTick
        </div>
    </div>
</body>

</html>
