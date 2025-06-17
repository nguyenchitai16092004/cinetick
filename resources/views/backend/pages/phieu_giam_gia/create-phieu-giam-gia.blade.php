@extends('backend.layouts.master')
@section('title', 'Thêm khuyến mãi')

@section('main')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4><i class="fas fa-plus"></i> Thêm khuyến mãi</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('khuyen-mai.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="MaKhuyenMai">Mã khuyến mãi</label>
                        <input type="text" name="MaKhuyenMai" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="PhanTramGiam">Phần trăm giảm (%)</label>
                        <input type="number" name="PhanTramGiam" class="form-control" required min="1"
                            max="100">
                    </div>
                    <div class="form-group mb-3">
                        <label for="NgayKetThuc">Ngày hết hạn</label>
                        <input type="date" name="NgayKetThuc" id="NgayKetThuc" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu</button>
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
