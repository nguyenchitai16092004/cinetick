@extends('backend.layouts.master')
@section('title', 'Chi tiết bảng tin tức')

@section('main')
    <div class="container mt-4">
        <h2>Cập nhật tin tức</h2>

        <form action="{{ route('tin_tuc.update', $tinTuc->ID_TinTuc) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')


            <div class="form-group">
                @if ($tinTuc->HinhAnh)
                    <img src="{{ asset($tinTuc->HinhAnh) }}"" width="120">
                @endif
                <label>Hình ảnh hiện tại</label><br>
                <div class="form-group">
                    <input type="file" name="HinhAnh" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label>Tiêu đề</label>
                <input type="text" name="TieuDe" value="{{ $tinTuc->TieuDe }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nội dung</label>
                <textarea name="NoiDung" class="form-control" rows="5" required>{{ $tinTuc->NoiDung }}</textarea>
            </div>

            <div class="form-group">
                <label>Loại bài viết</label>
                <select name="LoaiBaiViet" class="form-control" required>
                    <option value="0" {{ $tinTuc->LoaiBaiViet == 0 ? 'selected' : '' }}>Phim</option>
                    <option value="1" {{ $tinTuc->LoaiBaiViet == 1 ? 'selected' : '' }}>Khuyến mãi</option>
                </select>
            </div>



            <div class="form-group">
                <label>ID Tài khoản</label>
                <input type="number" name="ID_TaiKhoan" value="{{ $tinTuc->ID_TaiKhoan }}" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection
