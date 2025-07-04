@extends('admin.layouts.master')
@section('title', 'CineTick - Chi tiết hóa đơn')
@section('main')
<link rel="stylesheet" href="{{ asset('backend/assets/css/detail-hoa-don.css') }}">
<style>


    .success {
        background-color: #28a745;
    }

    .pending {
        background-color: #ffc107;
        color: black;
    }

    .seat-badge {
        background-color: #007bff;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        margin: 4px 6px 4px 0;
        font-weight: bold;
        display: inline-block;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="mb-1">Chi tiết hóa đơn</h4>
                <small>Mã hóa đơn: <strong>#{{ $hoaDon->ID_HoaDon }}</strong></small>
            </div>
        </div>

        <div class="card-body">
            {{-- Thông tin hóa đơn --}}
            <div class="row mb-3">
                <div class="col-md-6"><strong>Ngày tạo:</strong> {{ $hoaDon->created_at->format('d/m/Y H:i') }}</div>
                <div class="col-md-6"><strong>Phương thức thanh toán:</strong> {{ $hoaDon->PTTT }}</div>
                <div class="col-md-6"><strong>Email:</strong> {{ $hoaDon->Email }}</div>
                <div class="col-md-6"><strong>Số lượng vé:</strong> {{ $hoaDon->SoLuongVe }} vé</div>
                @if ($hoaDon->TenNganHang)
                    <div class="col-md-6"><strong>Ngân hàng:</strong> {{ $hoaDon->TenNganHang }}</div>
                @endif
            </div>

            {{-- Thông tin vé --}}
            @if ($hoaDon->veXemPhim->isNotEmpty())
                @php
                    $vePhim = $hoaDon->veXemPhim->first();
                    $suatChieu = $vePhim->suatChieu;
                @endphp
                <h5 class="mt-4">Thông tin vé</h5>
                <div class="row mb-3">
                    <div class="col-md-6"><strong>Phim:</strong> {{ $suatChieu?->phim?->DoTuoi . ' ' . $suatChieu?->phim?->TenPhim ?? $vePhim->TenPhim }}</div>
                    <div class="col-md-6"><strong>Ngày chiếu:</strong> {{ \Carbon\Carbon::parse($vePhim->NgayXem)->format('d/m/Y') }}</div>
                    <div class="col-md-6"><strong>Giờ chiếu:</strong> {{ $suatChieu ? substr($suatChieu->GioChieu, 0, 5) : '--:--' }}</div>
                    <div class="col-md-6"><strong>Rạp:</strong> {{ $suatChieu->rap->TenRap ?? '---' }}</div>
                    <div class="col-md-12"><strong>Địa chỉ:</strong> {{ $vePhim->DiaChi }}</div>
                </div>
                <div class="mb-3"><strong>Ghế đã đặt:</strong>
                    <div class="d-flex flex-wrap mt-2">
                        @foreach ($hoaDon->veXemPhim as $ve)
                            <span class="seat-badge">{{ $ve->TenGhe }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- QR Code --}}
            @if ($hoaDon->TrangThaiXacNhanThanhToan == 'Thanh toán thành công')
                <div class="text-center my-4">
                    <h6>Mã QR vé</h6>
                    <small class="d-block mb-2">Xuất trình mã này tại rạp</small>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $hoaDon->ID_HoaDon }}"
                        alt="QR Code" class="img-fluid mb-3" id="qr-image" style="cursor:pointer;" />
                    <br>
                    <button id="download-qr" class="btn btn-outline-primary btn-sm no-print">
                        <i class="fas fa-download"></i> Tải QR xuống
                    </button>
                </div>
            @endif

            {{-- Tổng kết thanh toán --}}
            <hr>
            <div class="mt-3">
                <h5>Tổng kết thanh toán</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tạm tính:</span>
                    <strong>{{ number_format($hoaDon->TongTien, 0, ',', '.') }}đ</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Giảm giá:</span>
                    <strong class="text-danger">
                        {{ $hoaDon->SoTienGiam > 0 ? '-' . number_format($hoaDon->SoTienGiam, 0, ',', '.') . 'đ' : '0đ' }}
                    </strong>
                </div>
                <div class="d-flex justify-content-between border-top pt-2">
                    <span><strong>Tổng cộng:</strong></span>
                    <strong>{{ number_format($hoaDon->TongTien - $hoaDon->SoTienGiam, 0, ',', '.') }}đ</strong>
                </div>
            </div>

            {{-- Nút hành động --}}
            <div class="text-end mt-4 no-print">
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="fas fa-print"></i> In hóa đơn
                </button>
                <a href="{{ route('hoa-don.index') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Trang chủ
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('download-qr')?.addEventListener('click', function () {
        const imageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={{ $hoaDon->ID_HoaDon }}";
        const fileName = "QRCode-{{ $hoaDon->ID_HoaDon }}.png";

        fetch(imageUrl)
            .then(resp => resp.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = fileName;
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(() => alert('Không thể tải QR Code. Vui lòng thử lại.'));
    });
</script>
@stop
