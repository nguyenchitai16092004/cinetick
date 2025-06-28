@extends('admin.layouts.master')
@section('title', 'Quản lý Khuyến Mãi')

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
                        <h3 class="card-title mb-0">🎫 Danh sách khuyến mãi</h3>
                        <a href="{{ route('khuyen-mai.create') }}" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm khuyến mãi mới
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Mã khuyến mãi</th>
                                        <th>Điều kiện tối thiểu</th>
                                        <th>Phần trăm giảm</th>
                                        <th>Giá trị tối đa</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dsKhuyenMai as $km)
                                        <tr>
                                            <td>{{ $km->ID_KhuyenMai }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $km->MaKhuyenMai }}</span>
                                            </td>
                                            <td>{{ number_format($km->DieuKienToiThieu, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ $km->PhanTramGiam }}%</td>
                                            <td>{{ number_format($km->GiamToiDa, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ \Carbon\Carbon::parse($km->NgayKetThuc)->format('d/m/Y') }}</td>
                                            <td>
                                                @php
                                                    $today = \Carbon\Carbon::now()->format('Y-m-d');
                                                    $ngayKetThuc = \Carbon\Carbon::parse($km->NgayKetThuc)->format('Y-m-d');

                                                    if ($ngayKetThuc >= $today) {
                                                        $trangThaiText = 'Có hiệu lực';
                                                        $bgColor = '#28a745'; // Xanh
                                                    } else {
                                                        $trangThaiText = 'Hết hạn';
                                                        $bgColor = '#dc3545'; // Đỏ
                                                    }
                                                @endphp

                                                <span
                                                    style="width:80%; display: inline-block; padding: 4px 8px; border-radius: 5px; color: #fff; background-color: {{ $bgColor }};">
                                                    {{ $trangThaiText }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('khuyen-mai.edit', $km->ID_KhuyenMai) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('khuyen-mai.delete', $km->ID_KhuyenMai) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')">
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
                                            <td colspan="8" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($dsKhuyenMai, 'links'))
                            <div class="mt-3 d-flex justify-content-center">
                                {{ $dsKhuyenMai->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection