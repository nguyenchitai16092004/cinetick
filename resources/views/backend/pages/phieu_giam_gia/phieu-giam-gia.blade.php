@extends('backend.layouts.master')
@section('title', 'Danh sách khuyến mãi')

@section('main')
    <div class="container mt-4">
        <h2>Danh sách khuyến mãi</h2>
        <a href="{{ route('khuyen-mai.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Thêm mới
        </a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mã khuyến mãi</th>
                    <th>Phần trăm giảm</th>
                    <th>Giá trị tối đa</th>
                    <th>Ngày Kết Thúc(hết date)</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dsKhuyenMai as $km)
                    <tr>
                        <td>{{ $km->ID_KhuyenMai }}</td>
                        <td>{{ $km->MaKhuyenMai }}</td>
                        <td>{{ $km->PhanTramGiam }}%</td>
                        <td>{{ number_format($km->GiaTriToiDa, 0, ',', '.') }}đ</td>
                        <td>{{ \Carbon\Carbon::parse($km->NgayKetThuc)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('khuyen-mai.edit', $km->ID_KhuyenMai) }}" class="btn btn-warning btn-sm"><i
                                    class="fas fa-edit"></i></a>
                            <a href="{{ route('khuyen-mai.delete', $km->ID_KhuyenMai) }}" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn chắc chắn xoá?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
