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
                        <h3 class="card-title mb-0"><i class="fa-solid fa-tags"></i> Danh sách mã giảm giá</h3>
                        <a href="{{ route('khuyen-mai.create') }}" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm khuyến mãi mới
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã giảm giá</th>
                                        <th>Điều kiện tối thiểu</th>
                                        <th>Phần trăm giảm</th>
                                        <th>Tối đa</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Số tiền đã giảm</th>
                                        <th>Số lượng</th>
                                        <th>Trạng thái</th>
                                        <th>Tình trạng</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        use Carbon\Carbon;
                                    @endphp

                                    @forelse ($dsKhuyenMai as $km)
                                        <tr>
                                            <td><span class="badge bg-primary">{{ $km->MaKhuyenMai }}</span></td>
                                            <td>{{ number_format($km->DieuKienToiThieu, 0, ',', '.') }} đ</td>
                                            <td>{{ $km->PhanTramGiam }}%</td>
                                            <td>{{ number_format($km->GiamToiDa, 0, ',', '.') }} đ</td>
                                            <td>{{ Carbon::parse($km->NgayKetThuc)->format('d/m/Y') }}</td>
                                            <td>{{ number_format($km->TongTienDaGiam, 0, ',', '.') }} đ</td>
                                            <td>{{ $km->SoLuong }}</td>

                                            {{-- Trạng thái --}}
                                            <td>
                                                @if ($km->TrangThai == 1)
                                                    <span class="badge bg-success">Đang áp dụng</span>
                                                @else
                                                    <span class="badge bg-secondary">Đã ngưng</span>
                                                @endif
                                            </td>

                                            {{-- Tình trạng --}}
                                            <td>
                                                @if ($km->SoLuong == 0)
                                                    <span class="badge bg-danger">Đã hết</span>
                                                @elseif (Carbon::parse($km->NgayKetThuc)->lt(Carbon::now()))
                                                    <span class="badge bg-warning">Hết thời gian sử dụng</span>
                                                @else
                                                    <span class="badge bg-success">Còn hiệu lực</span>
                                                @endif
                                            </td>

                                            {{-- Hành động --}}
                                            <td>
                                                <a href="{{ route('khuyen-mai.edit', $km->ID_KhuyenMai) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('khuyen-mai.delete', $km->ID_KhuyenMai) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        title="Đổi trạng thái">
                                                        <i
                                                            class="fas {{ $km->TrangThai == 1 ? 'fa-lock' : 'fa-lock-open' }}"></i>
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Phân trang --}}
                        @if (method_exists($dsKhuyenMai, 'links'))
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
