@extends('backend.layouts.master')

@section('title', 'Thêm tài khoản mới')

@section('main')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 text-primary font-weight-bold mb-0">
            <i class="fas fa-user-plus"></i> Thêm tài khoản mới
        </h1>
        <a href="{{ route('tai-khoan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <!-- Alert Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-dark font-weight-bold">Thông tin tài khoản</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('tai-khoan.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Thông tin cá nhân -->
                    <div class="col-md-6 border-left pl-4">
                        <h6 class="text-muted mb-3">Thông tin cá nhân</h6>

                        <div class="form-group">
                            <label for="ID_CCCD">Số CCCD/CMND <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ID_CCCD" name="ID_CCCD" value="{{ old('ID_CCCD') }}" placeholder="Nhập số CCCD/CMND" required>
                        </div>

                        <div class="form-group">
                            <label for="HoTen">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="HoTen" name="HoTen" value="{{ old('HoTen') }}" placeholder="Nhập họ và tên" required>
                        </div>

                        <div class="form-group">
                            <label>Giới tính <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="GioiTinh" id="GioiTinhNam" value="1" {{ old('GioiTinh') == '1' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="GioiTinhNam">Nam</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="GioiTinh" id="GioiTinhNu" value="0" {{ old('GioiTinh') == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="GioiTinhNu">Nữ</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="NgaySinh">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" value="{{ old('NgaySinh') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="Email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="Email" name="Email" value="{{ old('Email') }}" placeholder="example@domain.com" required>
                        </div>

                        <div class="form-group">
                            <label for="SDT">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="SDT" name="SDT" value="{{ old('SDT') }}" placeholder="Nhập số điện thoại" required>
                        </div>
                    </div>

                    <!-- Thông tin tài khoản -->
                    <div class="col-md-6 border-left pl-4">
                        <h6 class="text-muted mb-3">Thông tin đăng nhập</h6>

                        <div class="form-group">
                            <label for="TenDN">Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="TenDN" name="TenDN" value="{{ old('TenDN') }}" placeholder="Tên đăng nhập" required>
                        </div>

                        <div class="form-group">
                            <label for="MatKhau">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="MatKhau" name="MatKhau" placeholder="Nhập mật khẩu" required>
                        </div>

                        <div class="form-group">
                            <label for="VaiTro">Vai trò <span class="text-danger">*</span></label>
                            <select class="form-control" id="VaiTro" name="VaiTro" required>
                                <option value="0" {{ old('VaiTro') == '0' ? 'selected' : '' }}>Người dùng</option>
                                <option value="1" {{ old('VaiTro') == '1' ? 'selected' : '' }}>Nhân viên</option>
                                <option value="2" {{ old('VaiTro') == '2' ? 'selected' : '' }}>Quản trị viên</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="TrangThai">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control" id="TrangThai" name="TrangThai" required>
                                <option value="1" {{ old('TrangThai') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('TrangThai') == '0' ? 'selected' : '' }}>Vô hiệu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save"></i> Lưu tài khoản
                    </button>
                    <a href="{{ route('tai-khoan.index') }}" class="btn btn-outline-secondary ml-2 px-4">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
