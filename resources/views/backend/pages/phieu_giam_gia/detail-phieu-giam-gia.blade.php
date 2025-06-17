@extends('backend.layouts.master')
@section('title', 'Chỉnh sửa khuyến mãi')

@section('main')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h4><i class="fas fa-edit"></i> Chỉnh sửa khuyến mãi</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('khuyen-mai.update', $km->ID_KhuyenMai) }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="MaKhuyenMai">Mã khuyến mãi</label>
                    <input type="text" name="MaKhuyenMai" class="form-control" value="{{ $km->MaKhuyenMai }}"   >
                </div>
                <div class="form-group mb-3">
                    <label for="PhanTramGiam">Phần trăm giảm (%)</label>
                    <input type="number" name="PhanTramGiam" class="form-control" value="{{ $km->PhanTramGiam }}" required min="1" max="100">
                </div>
                <div class="form-group mb-3">
                    <label for="NgayKetThuc">Ngày hết hạn</label>
                    <input type="date" name="NgayKetThuc" class="form-control" value="{{ $km->NgayKetThuc }}" required >
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Cập nhật</button>
                <a href="{{ route('khuyen-mai.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0]; // YYYY-MM-DD
            document.getElementById("NgayKetThuc").setAttribute('min', today);
        });
    </script>
@endsection
