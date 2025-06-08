@extends('backend.layouts.master')
@section('title', 'Quản lý thể loại phim')

@section('main')
    <div class="container">
        <h2>Danh sách thể loại phim</h2>

        <a href="{{ route('the-loai.create') }}" class="btn btn-primary mb-3">Thêm thể loại</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên thể loại</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($theloais as $tl)
                    <tr>
                        <td>{{ $tl->ID_TheLoaiPhim }}</td>
                        <td>{{ $tl->TenTheLoai }}</td>
                        <td>
                            <a href="{{ route('the-loai.edit', $tl->ID_TheLoaiPhim) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="{{ route('the-loai.delete', $tl->ID_TheLoaiPhim) }}" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xoá không?')">Xoá</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
