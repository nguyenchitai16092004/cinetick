@extends('backend.layouts.master')
@section('title', 'Chỉnh sửa khuyến mãi')

@section('main')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h4><i class="fas fa-edit"></i> Chỉnh sửa khuyến mãi</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('khuyen-mai.update', $km->ID_ChiTietKM) }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="TenKhuyenMai">Tên khuyến mãi</label>
                    <input type="text" name="TenKhuyenMai" class="form-control" value="{{ $km->TenKhuyenMai }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="PhanTramGiam">Phần trăm giảm (%)</label>
                    <input type="number" name="PhanTramGiam" class="form-control" value="{{ $km->PhanTramGiam }}" required min="1" max="100">
                </div>
                <div class="form-group mb-3">
                    <label for="GiaTriToiDa">Giá trị tối đa (VNĐ)</label>
                    <input type="number" name="GiaTriToiDa" class="form-control" value="{{ $km->GiaTriToiDa }}" required step="1000">
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Cập nhật</button>
                <a href="{{ route('khuyen-mai.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection
