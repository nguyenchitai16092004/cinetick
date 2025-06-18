@extends('backend.layouts.master')
@section('title', 'Quản lý Hóa Đơn')

@section('main')
    <style>
        .table thead th {
            background-color: #7b1fa2;
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
            border: none;
        }

        .btn-purple:hover {
            background-color: #6a1b9a;
            box-shadow: 0 0 6px rgba(106, 27, 154, 0.6);
        }

        .filter-box {
            background: #ede7f6;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .title-header {
            font-weight: bold;
            color: #6a1b9a;
        }

        .btn-info {
            background-color: #9575cd;
            border: none;
            color: white;
        }

        .btn-info:hover {
            background-color: #7e57c2;
        }

        .btn-primary {
            background-color: #ab47bc;
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background-color: #8e24aa;
        }

        .btn-danger {
            background-color: #d32f2f;
            border: none;
            color: white;
        }

        .btn-danger:hover {
            background-color: #b71c1c;
        }
    </style>


    <div class="container-fluid">
        <h4 class="title-header mb-3">Quản lý Hóa Đơn</h4>

        {{-- Bộ lọc --}}
        <form method="GET" action="{{ route('hoa-don.index') }}" class="filter-box row align-items-end">
            <div class="col-md-2 mb-2">
                <label for="start_date">Từ ngày:</label>
                <input type="date" id="start_date" name="start_date" class="form-control"
                    value="{{ request('start_date') }}">
            </div>
            <div class="col-md-2 mb-2">
                <label for="end_date">Đến ngày:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2 mb-2">
                <label for="id_tai_khoan">ID Tài khoản:</label>
                <input type="number" id="id_tai_khoan" name="id_tai_khoan" class="form-control" placeholder="VD: 12"
                    value="{{ request('id_tai_khoan') }}">
            </div>
            <div class="col-md-2 mb-2">
                <label for="pttt">PT Thanh toán:</label>
                <input type="text" id="pttt" name="pttt" class="form-control" placeholder="Tiền mặt, VNPay..."
                    value="{{ request('pttt') }}">
            </div>
            <div class="col-md-2 mb-2 d-flex gap-2">
                <button type="submit" class="btn btn-purple w-100">Lọc</button>
                <a href="{{ route('hoa-don.index') }}" class="btn btn-secondary w-100">Xóa</a>
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
                                    <button onclick="return confirm('Bạn có chắc muốn xóa?')"
                                        class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Không tìm thấy hóa đơn nào.</td>
                        </tr>
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
