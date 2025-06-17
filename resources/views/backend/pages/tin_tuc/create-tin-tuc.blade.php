@extends('backend.layouts.master')
@section('title', 'Tạo bảng tin tức')

@section('main')
    <div class="container mt-4">
        <form action="{{ route('tin_tuc.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Ảnh đại diện</label>
                <input type="file" name="AnhDaiDien" class="form-control">
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
            <div class="form-group">
                <label>Trạng thái</label>
                <select name="TrangThai" class="form-control" required>
                    <option value="0">Chờ xuất bản</option>
                    <option value="1">Xuất bản</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Thêm mới</button>
        </form>
    </div>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('NoiDung', {
            filebrowserUploadUrl: "{{ route('tin_tuc.ckeditor.upload') . '?_token=' . csrf_token() }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endsection
