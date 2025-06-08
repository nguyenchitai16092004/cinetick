@extends('backend.layouts.master')
@section('title', 'Quản lý Phòng Chiếu')

@section('main')
    <section class="px-6 py-8">
        <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-6 text-purple">Quản lý Phòng Chiếu</h2>
            <a href="{{ route('phong-chieu.create') }}" class="btn btn-purple mb-4">➕ Thêm phòng chiếu mới</a>
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-purple text-white">
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
                    @foreach ($phongChieus as $phong)
                        <tr>
                            <td>{{ $phong->ID_PhongChieu }}</td>
                            <td>{{ $phong->TenPhongChieu }}</td>
                            <td>{{ $phong->rap->TenRap ?? 'N/A' }}</td>
                            <td>{{ $phong->SoLuongGhe }}</td>
                            <td>
                                <span class="badge {{ $phong->TrangThai ? 'bg-success' : 'bg-danger' }}">
                                    {{ $phong->TrangThai ? 'Hoạt động' : 'Bảo trì' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('phong-chieu.show', $phong->ID_PhongChieu) }}"
                                    class="btn btn-sm btn-warning">Sửa</a>
                                <form action="{{ route('phong-chieu.destroy', $phong->ID_PhongChieu) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
