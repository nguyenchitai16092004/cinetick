@extends('admin.layouts.master')
@section('title', 'Chỉnh sửa khuyến mãi')

@section('main')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4><i class="fas fa-edit"></i> Chỉnh sửa khuyến mãi</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('khuyen-mai.update', $khuyenMai->ID_KhuyenMai) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="MaKhuyenMai" class="form-label">Mã khuyến mãi</label>
                        <input type="text" name="MaKhuyenMai" id="MaKhuyenMai" class="form-control" required value="{{ old('MaKhuyenMai', $khuyenMai->MaKhuyenMai) }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="DieuKienToiThieu">Điều kiện tối thiểu (đ)</label>
                        <input type="number" name="DieuKienToiThieu" class="form-control" required min="0" step="1000"  max="1000000000" value="{{ old('DieuKienToiThieu', $khuyenMai->DieuKienToiThieu) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="PhanTramGiam">Phần trăm giảm (%)</label>
                        <input type="number" name="PhanTramGiam" class="form-control" required min="1" max="100" value="{{ old('PhanTramGiam', $khuyenMai->PhanTramGiam) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="GiamToiDa">Giảm tối đa (đ)</label>
                        <input type="number" name="GiamToiDa" class="form-control" required min="0" step="1000"  max="1000000000" value="{{ old('GiamToiDa', $khuyenMai->GiamToiDa) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="NgayKetThuc">Ngày hết hạn</label>
                        <input type="date" name="NgayKetThuc" id="NgayKetThuc" class="form-control" required value="{{ old('NgayKetThuc', $khuyenMai->NgayKetThuc) }}">
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                    <a href="{{ route('khuyen-mai.index') }}" class="btn btn-secondary">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById("NgayKetThuc").setAttribute('min', today);
        });
    </script>
@endsection
