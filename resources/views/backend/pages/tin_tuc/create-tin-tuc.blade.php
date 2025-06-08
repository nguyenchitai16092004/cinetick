@extends('backend.layouts.master')
@section('title', 'Tạo bảng tin tức')

@section('main')
    <div class="container mt-4">
        <form action="{{ route('tin_tuc.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Hình ảnh</label>
                <input type="file" name="HinhAnh" class="form-control">
            </div>
            <div class="form-group">
                <label>Tiêu đề</label>
                <input type="text" name="TieuDe" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nội dung</label>
                <textarea name="NoiDung" class="form-control" rows="5" required></textarea>
            </div>

            <div class="form-group">
                <label>Loại bài viết</label>
                <select name="LoaiBaiViet" class="form-control" required>
                    <option value="0">Phim</option>
                    <option value="1">Khuyến Mãi</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Thêm mới</button>
        </form>
    </div>
@endsection
