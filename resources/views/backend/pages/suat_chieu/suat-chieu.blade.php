@extends('backend.layouts.master')
@section('title', 'Quản lý suất chiếu')

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

        .filter-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .filter-card .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.2);
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">🎭 Danh sách suất chiếu</h3>
                        <a href="{{ route('suat-chieu.create') }}" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm suất chiếu
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="card border-0 text-white">
                            <div class="card-body">
                                <div class="row g-3">
                                    {{-- Lọc theo ngày --}}
                                    <div class="col-md-6">
                                        <form action="{{ route('suat-chieu.filter.date') }}" method="GET">
                                            <div class="input-group">
                                                <span class="input-group-text bg-secondary text-white">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                                <input type="date" name="date" class="form-control"
                                                    value="{{ $date ?? '' }}">
                                                <button type="submit" class="btn btn-purple">
                                                    <i class="fas fa-filter"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Lọc theo phim --}}
                                    <div class="col-md-6">
                                        <form action="{{ route('suat-chieu.filter.phim') }}" method="GET">
                                            <div class="input-group">
                                                <span class="input-group-text bg-secondary text-white">
                                                    <i class="fas fa-film"></i>
                                                </span>
                                                <select name="phim_id" class="form-control">
                                                    <option value="">-- Chọn phim --</option>
                                                    @foreach ($phims as $phim)
                                                        <option value="{{ $phim->ID_Phim }}">{{ $phim->TenPhim }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-purple">
                                                    <i class="fas fa-filter"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Phim</th>
                                        <th>Phòng chiếu</th>
                                        <th>Ngày chiếu</th>
                                        <th>Giờ chiếu</th>
                                        <th>Giá vé</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($suatChieus as $suatChieu)
                                        <tr>
                                            <td><strong>#{{ $suatChieu->ID_SuatChieu }}</strong></td>
                                            <td class="text-start">
                                                <div class="fw-bold ">{{ $suatChieu->phim->TenPhim ?? 'N/A' }}</div>
                                            </td>
                                            <td class="text-start" style="max-width: 250px;">
                                                <div class="fw-bold">{{ $suatChieu->phongChieu->TenPhongChieu ?? 'N/A' }}
                                                </div>
                                                <small
                                                    class="text-muted">{{ $suatChieu->phongChieu->rap->DiaChi ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge text-dark">
                                                    {{ date('d/m/Y', strtotime($suatChieu->NgayChieu)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    {{ date('H:i', strtotime($suatChieu->GioChieu)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-danger">
                                                    {{ number_format($suatChieu->GiaVe, 0, ',', '.') }} đ
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('suat-chieu.edit', $suatChieu->ID_SuatChieu) }}"
                                                        class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('suat-chieu.destroy', $suatChieu->ID_SuatChieu) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc muốn xóa suất chiếu này?');"
                                                        style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-film fa-3x mb-3 text-muted"></i>
                                                <div>Không có dữ liệu suất chiếu</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            {{ $suatChieus->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
