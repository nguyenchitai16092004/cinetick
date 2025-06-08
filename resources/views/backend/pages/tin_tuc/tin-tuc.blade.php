@extends('backend.layouts.master')
@section('title', 'Quản lý các bảng tin tức')

@section('main')
    <div class="container mt-4">
        <h2>Danh sách tin tức</h2>
        <a href="{{ route('tin_tuc.create') }}" class="btn btn-primary mb-3">Thêm tin tức</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Loại bài</th>
                    <th>Hình ảnh</th>
                    <th>Người đăng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tinTucs as $tin)
                    <tr>
                        <td>{{ $tin->TieuDe }}</td>
                        <td>{{ $tin->LoaiBaiViet == 1 ? 'Khuyến mãi' : 'Phim' }}</td>
                        <td>
                            @if ($tin->HinhAnh)
                                <img src="{{ asset($tin->HinhAnh) }}" width="100">
                            @endif
                        </td>
                        <td>{{ $tin->TenDN }}</td>
                        <td>
                            <a href="{{ route('tin_tuc.edit', $tin->ID_TinTuc) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('tin_tuc.destroy', $tin->ID_TinTuc) }}" method="POST"
                                style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa tin này?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
