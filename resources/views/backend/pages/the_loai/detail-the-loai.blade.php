@extends('backend.layouts.master')
@section('title', 'Chỉnh sửa thể loại phim')

@section('main')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-film"></i> Thêm thể loại phim</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('the-loai.update',$theloai->ID_TheLoaiPhim) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="TenTheLoai" class="form-label">Tên thể loại</label>
                                <input type="text" class="form-control" name="TenTheLoai"
                                    value="{{ $theloai->TenTheLoai }}" placeholder="Nhập tên thể loại..." required>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Lưu
                            </button>
                            <a href="{{ route('the-loai.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
