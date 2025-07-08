@extends('admin.layouts.master')
@section('title', 'Quản lý thể loại phim')

@section('main')
    <style>
        .btn-purple {
            background-color: #6f42c1;
            color: white;
        }

        .btn-purple:hover {
            background-color: #5a32a3;
            color: white;
        }

        .card-header.bg-purple {
            background-color: #6f42c1;
            color: white;
        }

        .table thead th {
            background-color: #e9d8fd;
            color: #4b0082;
        }

        .table-hover tbody tr:hover {
            background-color: #f3e5f5;
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="fas fa-layer-group"></i> Quản lý thể loại phim</h3>
                        <a href="{{ route('the-loai.create') }}" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm thể loại mới
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên thể loại</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($theloais as $tl)
                                        <tr>
                                            <td>{{ $tl->ID_TheLoaiPhim }}</td>
                                            <td class="fw-bold">{{ $tl->TenTheLoai }}</td>
                                            <td>
                                                <a href="{{ route('the-loai.edit', $tl->ID_TheLoaiPhim) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('the-loai.delete', $tl->ID_TheLoaiPhim) }}" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa thể loại này?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            {{-- Pagination if needed --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection