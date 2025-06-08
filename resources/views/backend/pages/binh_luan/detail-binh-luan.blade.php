@extends('backend.layouts.master')
@section('title', 'Chi tiết bình luận')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Chi tiết bình luận #{{ $binhLuan->ID_BinhLuan }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('binh-luan.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                {{-- Thông tin bình luận --}}
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Nội dung bình luận</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="comment-content p-3"
                                            style="background-color: #f8f9fa; border-radius: 5px;">
                                            {{ $binhLuan->NoiDung }}
                                        </div>

                                        @if ($binhLuan->DiemDanhGia)
                                            <div class="mt-3">
                                                <strong>Điểm đánh giá:</strong>
                                                <span class="badge badge-warning ml-2">
                                                    {{ $binhLuan->DiemDanhGia }}/10
                                                </span>
                                                <div class="progress mt-2" style="height: 10px;">
                                                    <div class="progress-bar bg-warning"
                                                        style="width: {{ ($binhLuan->DiemDanhGia / 10) * 100 }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Thao tác --}}
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Thao tác</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="btn-group" role="group">
                                            {{-- Toggle trạng thái --}}
                                            <a href="{{ route('binh-luan.update-status', $binhLuan->ID_BinhLuan) }}"
                                                class="btn btn-warning"
                                                onclick="return confirm('Xác nhận thay đổi trạng thái?')">
                                                <i
                                                    class="fas fa-eye{{ isset($binhLuan->TrangThai) && $binhLuan->TrangThai == 0 ? '-slash' : '' }}"></i>
                                                {{ isset($binhLuan->TrangThai) && $binhLuan->TrangThai == 0 ? 'Hiển thị' : 'Ẩn' }}
                                                bình luận
                                            </a>

                                            {{-- Xóa --}}
                                            <form method="POST"
                                                action="{{ route('binh-luan.destroy', $binhLuan->ID_BinhLuan) }}"
                                                style="display: inline;"
                                                onsubmit="return confirm('Xác nhận xóa bình luận này? Hành động này không thể hoàn tác!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i> Xóa bình luận
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                {{-- Thông tin phim --}}
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Thông tin phim</h5>
                                    </div>
                                    <div class="card-body">
                                        <h6><strong>{{ $binhLuan->TenPhim }}</strong></h6>
                                        <p class="text-muted">ID Phim: {{ $binhLuan->ID_Phim }}</p>
                                    </div>
                                </div>

                                {{-- Thông tin người dùng --}}
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Thông tin người dùng</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Tên đăng nhập:</strong> {{ $binhLuan->TenDN }}</p>
                                        @if (isset($binhLuan->Email))
                                            <p><strong>Email:</strong> {{ $binhLuan->Email }}</p>
                                        @endif
                                        <p><strong>ID Tài khoản:</strong> {{ $binhLuan->ID_TaiKhoan }}</p>
                                    </div>
                                </div>

                                {{-- Thông tin thời gian --}}
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Thông tin thời gian</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Ngày tạo:</strong><br>
                                            {{ date('d/m/Y H:i:s', strtotime($binhLuan->created_at)) }}</p>

                                        @if ($binhLuan->updated_at && $binhLuan->updated_at != $binhLuan->created_at)
                                            <p><strong>Cập nhật lần cuối:</strong><br>
                                                {{ date('d/m/Y H:i:s', strtotime($binhLuan->updated_at)) }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Trạng thái --}}
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Trạng thái</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        @if (isset($binhLuan->TrangThai))
                                            @if ($binhLuan->TrangThai == 1)
                                                <span class="badge badge-success badge-lg">
                                                    <i class="fas fa-eye"></i> Đang hiển thị
                                                </span>
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge-lg {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    </style>
@endsection
<i class="fas fa-eye"></i> Đang hiển thị
</span>
@else
<span class="badge badge-secondary badge-lg">
    <i class="fas fa-eye-slash"></i> Đang ẩn
</span>
@endif
@else
<span class="badge badge-success badge-lg">
