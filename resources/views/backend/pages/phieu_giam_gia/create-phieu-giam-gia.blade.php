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
                        <label for="MaKhuyenMai" class="form-label">Mã khuyến mãi</label>
                        <div class="input-group">
                            <input type="text" name="MaKhuyenMai" id="MaKhuyenMai" class="form-control" required>
                            <button class="btn btn-outline-secondary" type="button" id="randomMaKM">
                                <i class="fas fa-dice"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="DieuKienToiThieu">Điều kiện tối thiểu (đ)</label>
                        <input type="number" name="DieuKienToiThieu" class="form-control" required min="0" step="1000">
                    </div>

                    <div class="form-group mb-3">
                        <label for="PhanTramGiam">Phần trăm giảm (%)</label>
                        <input type="number" name="PhanTramGiam" class="form-control" required min="1"max="100">
                    </div>
                    <div class="form-group mb-3">
                        <label for="GiamToiDa">Giảm tối đa (đ)</label>
                        <input type="number" name="GiamToiDa" class="form-control" required min="0" step="1000">
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
        let maKhuyenMai = document.getElementById('MaKhuyenMai');
        let randomMaKM = document.getElementById('randomMaKM');

        randomMaKM.addEventListener('click', function() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 6; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('MaKhuyenMai').value = result;
        });
    </script>
@endsection
