@extends('backend.layouts.master')
@section('title', 'Quản lý suất chiếu')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Danh sách suất chiếu</h5>
                        <a href="{{ route('suat-chieu.create') }}" class="btn btn-primary">Thêm suất chiếu</a>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <form action="{{ route('suat-chieu.filter.date') }}" method="GET" class="form-inline">
                                    <div class="input-group">
                                        <input type="date" name="date" class="form-control"
                                            value="{{ $date ?? '' }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-outline-secondary">Lọc theo ngày</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form action="{{ route('suat-chieu.filter.phim') }}" method="GET" class="form-inline">
                                    <div class="input-group">
                                        <select name="phim_id" class="form-control">
                                            <option value="">-- Chọn phim --</option>
                                            @foreach (\App\Models\Phim::where('TrangThai', 1)->get() as $phim)
                                                <option value="{{ $phim->ID_Phim }}">{{ $phim->TenPhim }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-outline-secondary">Lọc theo phim</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
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
                                            <td>{{ $suatChieu->ID_SuatChieu }}</td>
                                            <td>{{ $suatChieu->phim->TenPhim ?? 'N/A' }}</td>
                                            <td style="max-width: 300px;">
                                                {{ $suatChieu->phongChieu->TenPhongChieu ?? 'N/A' }} -
                                                {{ $suatChieu->phongChieu->rap->DiaChi ?? 'N/A' }}
                                            </td>
                                            <td>{{ date('d/m/Y', strtotime($suatChieu->NgayChieu)) }}</td>
                                            <td>{{ date('H:i', strtotime($suatChieu->GioChieu)) }}</td>
                                            <td>{{ number_format($suatChieu->GiaVe, 0, ',', '.') }} VNĐ</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('suat-chieu.edit', $suatChieu->ID_SuatChieu) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-pen"></i>
                                                    </a>

                                                    <form
                                                        action="{{ route('suat-chieu.destroy', $suatChieu->ID_SuatChieu) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc muốn xóa suất chiếu này?');"
                                                        style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $suatChieus->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
