@extends('backend.layouts.master')

@section('title', 'Quản lý tài khoản')

@section('main')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Quản lý tài khoản</h1>
            <div>
                <a href="{{ route('tai-khoan.export', request()->query()) }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
                    <i class="fas fa-download fa-sm text-white-50"></i> Xuất danh sách
                </a>
                <a href="{{ route('tai-khoan.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Thêm tài khoản mới
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Search & Filter Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tìm kiếm và lọc</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('tai-khoan.index') }}" method="GET" class="mb-0">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Tìm kiếm theo tên, email, số điện thoại..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search fa-sm"></i> Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Sắp xếp</span>
                                </div>
                                <select class="form-control" name="sort_by" onchange="this.form.submit()">
                                    <option value="ID_TaiKhoan" {{ request('sort_by') == 'ID_TaiKhoan' ? 'selected' : '' }}>ID</option>
                                    <option value="TenDN" {{ request('sort_by') == 'TenDN' ? 'selected' : '' }}>Tên đăng nhập</option>
                                    <option value="HoTen" {{ request('sort_by') == 'HoTen' ? 'selected' : '' }}>Họ tên</option>
                                    <option value="VaiTro" {{ request('sort_by', 'VaiTro') == 'VaiTro' ? 'selected' : '' }}>Vai trò</option>
                                </select>
                                <select class="form-control" name="sort_order" onchange="this.form.submit()">
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                                    <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách tài khoản</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên đăng nhập</th>
                                <th>Họ và tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taiKhoans as $taiKhoan)
                                <tr>
                                    <td>{{ $taiKhoan->ID_TaiKhoan }}</td>
                                    <td>{{ $taiKhoan->TenDN }}</td>
                                    <td>{{ $taiKhoan->HoTen }}</td>
                                    <td>{{ $taiKhoan->Email }}</td>
                                    <td>{{ $taiKhoan->SDT }}</td>
                                    <td>
                                        @if ($taiKhoan->VaiTro == 2)
                                            <span class="">Quản trị viên</span>
                                        @elseif ($taiKhoan->VaiTro == 1)
                                            <span class="">Nhân viên</span>
                                        @else
                                            <span class="">Người dùng</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($taiKhoan->TrangThai)
                                            <span class="">Hoạt động</span>
                                        @else
                                            <span class="">Vô hiệu</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('tai-khoan.edit', $taiKhoan->ID_TaiKhoan) }}"
                                            class="btn btn-primary btn-sm" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('tai-khoan.status', $taiKhoan->ID_TaiKhoan) }}"
                                            class="btn btn-warning btn-sm" title="Đổi trạng thái">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                        <a href="{{ route('tai-khoan.delete', $taiKhoan->ID_TaiKhoan) }}"
                                            class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $taiKhoans->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Bỏ DataTable mặc định vì chúng ta đã có phân trang và tìm kiếm từ server
            // Nếu cần phân trang và tìm kiếm client-side, hãy kích hoạt lại DataTable
            // $('#dataTable').DataTable({
            //     "paging": false,
            //     "ordering": true,
            //     "info": false,
            //     "searching": true
            // });
        });
    </script>
@endsection