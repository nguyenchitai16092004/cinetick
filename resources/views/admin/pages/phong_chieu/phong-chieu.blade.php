@extends('admin.layouts.master')
@section('title', 'Quản lý Phòng Chiếu')

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
                        <h3 class="card-title mb-0">🎭 Quản lý phòng chiếu</h3>
                        <a href="{{ route('phong-chieu.create') }}" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm phòng chiếu mới
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên Phòng</th>
                                        <th>Rạp</th>
                                        <th>Số ghế</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($phongChieus as $phong)
                                        <tr>
                                            <td>{{ $phong->ID_PhongChieu }}</td>
                                            <td class="fw-bold">{{ $phong->TenPhongChieu }}</td>
                                            <td>{{ $phong->rap->TenRap ?? 'N/A' }}</td>
                                            <td>{{ $phong->SoLuongGhe }}</td>
                                            <td>
                                                @if($phong->TrangThai)
                                                    <span class="badge bg-success">Hoạt động</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Bảo trì</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('phong-chieu.show', $phong->ID_PhongChieu) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('phong-chieu.destroy', $phong->ID_PhongChieu) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa phòng chiếu này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Không có dữ liệu</td>
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