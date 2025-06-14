@extends('backend.layouts.master')
@section('title', 'Quản lý Hóa Đơn')

@section('main')
<style>
    .table thead th {
        background-color: #6a1b9a;
        color: #fff;
        text-align: center;
    }

    .table td {
        text-align: center;
        vertical-align: middle;
    }

    .btn-purple {
        background-color: #8e24aa;
        color: white;
    }

    .btn-purple:hover {
        background-color: #6a1b9a;
    }

    .filter-box {
        background: #f3e5f5;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .title-header {
        font-weight: bold;
        color: #6a1b9a;
    }
</style>

<div class="container-fluid">
    <h4 class="title-header mb-3">Quản lý Hóa Đơn</h4>

    {{-- Bộ lọc --}}
    <form method="GET" action="{{ route('hoa-don.index') }}" class="filter-box row">
        <div class="col-md-2 mb-2">
            <label>Từ ngày:</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-2 mb-2">
            <label>Đến ngày:</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-2 mb-2">
            <label>ID Tài khoản:</label>
            <input type="number" name="id_tai_khoan" class="form-control" placeholder="VD: 12" value="{{ request('id_tai_khoan') }}">
        </div>
        <div class="col-md-2 mb-2">
            <label>PT Thanh toán:</label>
            <input type="text" name="pttt" class="form-control" placeholder="Tiền mặt, VNPay..." value="{{ request('pttt') }}">
        </div>
        <div class="col-md-2 mb-2">
            <label>Min Tổng tiền:</label>
            <input type="number" name="min_amount" class="form-control" value="{{ request('min_amount') }}">
        </div>
        <div class="col-md-2 mb-2">
            <label>Max Tổng tiền:</label>
            <input type="number" name="max_amount" class="form-control" value="{{ request('max_amount') }}">
        </div>
        <div class="col-md-12 mt-3 text-end">
            <button class="btn btn-purple">Lọc</button>
            <a href="{{ route('hoa-don.index') }}" class="btn btn-secondary">Xóa bộ lọc</a>
        </div>
    </form>

    {{-- Danh sách hóa đơn --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Khách hàng</th>
                    <th>Ngày tạo</th>
                    <th>PT Thanh toán</th>
                    <th>Tổng tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hoaDons as $index => $hoaDon)
                <tr>
                    <td>{{ $hoaDons->firstItem() + $index }}</td>
                    <td>{{ $hoaDon->taiKhoan->HoTen ?? 'Không có thông tin' }}</td>
                    <td>{{ \Carbon\Carbon::parse($hoaDon->NgayTao)->format('d/m/Y') }}</td>
                    <td>{{ $hoaDon->PTTT }}</td>
                    <td>{{ number_format($hoaDon->TongTien, 0, ',', '.') }} VNĐ</td>
                    <td>
                        <a href="{{ route('hoa-don.show', $hoaDon->id) }}" class="btn btn-sm btn-info">Xem</a>
                        <a href="{{ route('hoa-don.edit', $hoaDon->id) }}" class="btn btn-sm btn-primary">Sửa</a>
                        <form action="{{ route('hoa-don.destroy', $hoaDon->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6">Không tìm thấy hóa đơn nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="d-flex justify-content-center">
        {{ $hoaDons->appends(request()->all())->links() }}
    </div>
</div>
@endsection
