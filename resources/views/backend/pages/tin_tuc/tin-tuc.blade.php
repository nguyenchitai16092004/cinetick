@extends('backend.layouts.master')
@section('title', 'Quản lý các bảng tin tức')
@section('main')
    <style>
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
            background-color: #007bff;
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.08);
            text-decoration: none;
        }

        .pagination .active .page-link {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
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
    <div class="container mt-4">
        <h2>Danh sách tin tức</h2>
        <a href="{{ route('tin_tuc.create') }}" class="btn btn-primary mb-3">Thêm tin tức</a>

        {{-- Form lọc loại bài viết --}}
        <form action="" method="GET" class="mb-3 d-flex align-items-center">
            <label class="me-2" for="loai_bai_viet">Lọc theo loại bài viết:</label>
            <select name="loai_bai_viet" id="loai_bai_viet" class="form-select me-2"
                style="width:200px;display:inline-block;">
                <option value="">Tất cả</option>
                <option value="1" {{ request('loai_bai_viet') == 1 ? 'selected' : '' }}>Khuyến mãi</option>
                <option value="2" {{ request('loai_bai_viet') == 2 ? 'selected' : '' }}>Giới thiệu</option>
                <option value="3" {{ request('loai_bai_viet') == 3 ? 'selected' : '' }}>Chính sách</option>
                <option value="4" {{ request('loai_bai_viet') == 4 ? 'selected' : '' }}>Phim</option>
            </select>
            <button type="submit" class="btn btn-info">Lọc</button>
        </form>

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
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tinTucs as $tin)
                    <tr>
                        <td>{{ $tin->TieuDe }}</td>
                        <td>
                            @if ($tin->LoaiBaiViet == 1)
                                Khuyến mãi
                            @elseif ($tin->LoaiBaiViet == 2)
                                Giới thiệu
                            @elseif ($tin->LoaiBaiViet == 3)
                                Chính sách
                            @else
                                Phim
                            @endif
                        </td>
                        <td>
                            @if ($tin->AnhDaiDien)
                                <img src="{{ $tin->AnhDaiDien ? asset('storage/' . $tin->AnhDaiDien) : asset('images/no-image.jpg') }}"
                                    width="120" width="100">
                            @endif
                        </td>
                        <td>{{ $tin->TenDN }}</td>
                        <td>
                            <span
                                class="badge {{ $tin->TrangThai == 1 ? 'badge-success bg-success' : 'badge-danger bg-danger' }}">
                                {{ $tin->TrangThai == 1 ? 'Đã xuất bản' : 'Chờ xuất bản' }}
                            </span>
                        </td>

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
        <div class="pagination-wrapper">
            {{ $tinTucs->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
