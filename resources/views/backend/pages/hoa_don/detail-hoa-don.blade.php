@extends('backend.layouts.master')
@section('title', 'Quản lý hóa đơn')

@section('main')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Chi tiết hóa đơn #{{ $hoaDon->ID_HoaDon }}</h1>
            <div>
                <a href="{{ route('admin.hoadon.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('admin.hoadon.edit', $hoaDon->ID_HoaDon) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
            </div>
        </div>

        <!-- Thông tin hóa đơn -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Thông tin hóa đơn</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th class="w-25">ID hóa đơn:</th>
                                <td>{{ $hoaDon->ID_HoaDon }}</td>
                            </tr>
                            <tr>
                                <th>Ngày tạo:</th>
                                <td>{{ date('d/m/Y', strtotime($hoaDon->NgayTao)) }}</td>
                            </tr>
                            <tr>
                                <th>Tổng tiền:</th>
                                <td>{{ number_format($hoaDon->TongTien, 0, ',', '.') }} VNĐ</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th class="w-25">PTTT:</th>
                                <td>{{ $hoaDon->PTTT }}</td>
                            </tr>
                            <tr>
                                <th>ID tài khoản:</th>
                                <td>{{ $hoaDon->ID_TaiKhoan }}</td>
                            </tr>
                            <tr>
                                <th>Tên người dùng:</th>
                                <td>{{ $hoaDon->taiKhoan->TenTaiKhoan ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách vé xem phim -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Danh sách vé xem phim</h5>
                <a href="{{ route('admin.vexemphim.create', $hoaDon->ID_HoaDon) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Thêm vé mới
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID Vé</th>
                                <th>Tên phim</th>
                                <th>Ngày xem</th>
                                <th>Số lượng</th>
                                <th>Giá vé</th>
                                <th>Thành tiền</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hoaDon->veXemPhim as $ve)
                                <tr>
                                    <td>{{ $ve->ID_Ve }}</td>
                                    <td>{{ $ve->TenPhim }}</td>
                                    <td>{{ date('d/m/Y', strtotime($ve->NgayXem)) }}</td>
                                    <td>{{ $ve->SoLuong }}</td>
                                    <td>{{ number_format($ve->GiaVe, 0, ',', '.') }} VNĐ</td>
                                    <td>{{ number_format($ve->GiaVe * $ve->SoLuong, 0, ',', '.') }} VNĐ</td>
                                    <td>
                                        <span class="badge bg-{{ $ve->TrangThai ? 'success' : 'danger' }}">
                                            {{ $ve->TrangThai ? 'Còn hiệu lực' : 'Đã hủy' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.vexemphim.show', [$hoaDon->ID_HoaDon, $ve->ID_Ve]) }}"
                                                class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.vexemphim.edit', [$hoaDon->ID_HoaDon, $ve->ID_Ve]) }}"
                                                class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form
                                                action="{{ route('admin.vexemphim.status', [$hoaDon->ID_HoaDon, $ve->ID_Ve]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-sm btn-{{ $ve->TrangThai ? 'secondary' : 'success' }}"
                                                    title="{{ $ve->TrangThai ? 'Hủy vé' : 'Kích hoạt vé' }}">
                                                    <i class="fas fa-{{ $ve->TrangThai ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteVeModal{{ $ve->ID_Ve }}" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Modal xóa vé -->
                                        <div class="modal fade" id="deleteVeModal{{ $ve->ID_Ve }}" tabindex="-1"
                                            aria-labelledby="deleteVeModalLabel{{ $ve->ID_Ve }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteVeModalLabel{{ $ve->ID_Ve }}">
                                                            Xác nhận xóa</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Bạn có chắc chắn muốn xóa vé xem phim #{{ $ve->ID_Ve }} không?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Hủy</button>
                                                        <form
                                                            action="{{ route('admin.vexemphim.destroy', [$hoaDon->ID_HoaDon, $ve->ID_Ve]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Xóa</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Không có vé xem phim nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Tổng tiền:</th>
                                <th>{{ number_format($hoaDon->TongTien, 0, ',', '.') }} VNĐ</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
