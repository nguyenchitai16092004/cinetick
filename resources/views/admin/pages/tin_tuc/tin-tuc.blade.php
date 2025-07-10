@extends('admin.layouts.master')
@section('title', 'Quản lý tin tức')

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

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.5rem;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .pagination li {
            margin: 0 3px;
        }

        .page-link {
            color: #2c3e50;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.4rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
            font-weight: 500;
        }

        .page-link:hover,
        .page-link:focus {
            color: #fff;
            background-color: #6f42c1;
            border-color: #6f42c1;
            box-shadow: 0 2px 8px rgba(111, 66, 193, 0.3);
            text-decoration: none;
        }

        .pagination .active .page-link {
            color: #fff;
            background-color: #6f42c1;
            border-color: #6f42c1;
            pointer-events: none;
        }

        .pagination .disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
            border-color: #dee2e6;
            pointer-events: none;
            opacity: 0.6;
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="fas fa-newspaper"></i> Quản lý tin tức</h3>
                        <a href="{{ route('tin_tuc.create') }}" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm tin tức mới
                        </a>
                    </div>
                    <div class="card-body">
                        {{-- Form lọc loại bài viết --}}
                        <form action="" method="GET" class="mb-4">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label" for="loai_bai_viet">Lọc theo loại bài viết:</label>
                                    <select name="loai_bai_viet" id="loai_bai_viet" class="form-select">
                                        <option value="">Tất cả</option>
                                        <option value="1" {{ request('loai_bai_viet') == 1 ? 'selected' : '' }}>Khuyến
                                            mãi</option>
                                        <option value="2" {{ request('loai_bai_viet') == 2 ? 'selected' : '' }}>Giới
                                            thiệu</option>
                                        <option value="3" {{ request('loai_bai_viet') == 3 ? 'selected' : '' }}>Chính
                                            sách</option>
                                        <option value="4" {{ request('loai_bai_viet') == 4 ? 'selected' : '' }}>Phim
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-filter"></i> Lọc
                                    </button>
                                </div>
                            </div>
                        </form>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Loại bài</th>
                                        <th>Hình ảnh</th>
                                        <th>Người đăng</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tinTucs as $tin)
                                        <tr>
                                            <td class="fw-bold text-start">{{ $tin->TieuDe }}</td>
                                            <td>
                                                @switch($tin->LoaiBaiViet)
                                                    @case(1)
                                                        <span class="badge bg-warning text-dark">Khuyến mãi</span>
                                                    @break

                                                    @case(2)
                                                        <span class="badge bg-info">Giới thiệu</span>
                                                    @break

                                                    @case(3)
                                                        <span class="badge bg-secondary">Chính sách</span>
                                                    @break

                                                    @default
                                                        <span class="badge bg-primary">Phim</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if ($tin->AnhDaiDien)
                                                    <img src="{{ asset('storage/' . $tin->AnhDaiDien) }}" width="80"
                                                        class="img-thumbnail" alt="Ảnh đại diện">
                                                @else
                                                    <img src="{{ asset('images/no-image.jpg') }}" width="80"
                                                        class="img-thumbnail" alt="Không có ảnh">
                                                @endif
                                            </td>
                                            <td>{{ $tin->TenDN }}</td>
                                            <td>
                                                @if ($tin->TrangThai == 1)
                                                    <span class="badge bg-success">Đã xuất bản</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Chờ xuất bản</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('tin_tuc.edit', $tin->ID_TinTuc) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('tin_tuc.destroy', $tin->ID_TinTuc) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin tức này?')">
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

                            {{-- Phân trang --}}
                            <div class="mt-3 d-flex justify-content-center">
                                {{ $tinTucs->appends(request()->all())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
