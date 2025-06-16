@extends('backend.layouts.master')
@section('title', 'Chi tiết bình luận')

@section('main')
    <div class="container-fluid mt-3">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Chi tiết bình luận </h5>
                <a href="{{ route('binh-luan.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <div class="card-body ">
                <div class="mb-4">
                    <h6 class="text-secondary">Phim liên quan</h6>
                    <div class="border rounded p-2">
                        <p><strong>{{ $binhLuan->TenPhim }}</strong></p>
                        <p class="text-muted mb-0">ID Phim: {{ $binhLuan->ID_Phim }}</p>
                    </div>
                </div>
                <div class="mb-4">
                    <h6 class="text-secondary">Người dùng</h6>
                    <div class="border rounded p-2">
                        <p><strong>Tên đăng nhập:</strong> {{ $binhLuan->TenDN }}</p>
                        @if (isset($binhLuan->Email))
                            <p><strong>Email:</strong> {{ $binhLuan->Email }}</p>
                        @endif
                        <p><strong>ID:</strong> {{ $binhLuan->ID_TaiKhoan }}</p>
                    </div>
                </div>
                <div class="mb-4">
                    <h6 class="text-secondary">Thời gian</h6>
                    <div class="border rounded p-2">
                        <p><strong>Ngày tạo:</strong><br> {{ date('d/m/Y H:i:s', strtotime($binhLuan->created_at)) }}
                        </p>
                        @if ($binhLuan->updated_at && $binhLuan->updated_at != $binhLuan->created_at)
                            <p><strong>Cập nhật lần cuối:</strong><br>
                                {{ date('d/m/Y H:i:s', strtotime($binhLuan->updated_at)) }}</p>
                        @endif
                    </div>
                </div>

                @if ($binhLuan->DiemDanhGia)
                    <div class="mb-4">
                        <h6 class="text-secondary">Điểm đánh giá</h6>
                        <span class="badge badge-warning text-dark">{{ $binhLuan->DiemDanhGia }}/10</span>
                    </div>
                @endif

                <div>
                    <h6 class="text-secondary">Thao tác</h6>
                    <div class="btn-group">
                        <form method="POST" action="{{ route('binh-luan.destroy', $binhLuan->ID_BinhLuan) }}"
                            onsubmit="return confirm('Xác nhận xóa bình luận này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">

                    <div>
                        <div class="text-center">
                            @if (isset($binhLuan->TrangThai))
                                <span
                                    class="badge badge-lg {{ $binhLuan->TrangThai == 1 ? 'badge-success' : 'badge-secondary' }}">
                                    <i class="fas {{ $binhLuan->TrangThai == 1 ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                    {{ $binhLuan->TrangThai == 1 ? 'Đang hiển thị' : 'Đang ẩn' }}
                                </span>
                            @else
                                <span class="badge badge-info">Không xác định</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge-lg {
            padding: 0.5rem 1rem;
            font-size: 0.95rem;
        }
    </style>
@endsection
