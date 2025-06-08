@extends('backend.layouts.master')

@section('title', 'Chi tiết và cập nhật tài khoản')

@section('main')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa tài khoản: {{ $taiKhoan->TenDN }}</h1>
            <a href="{{ route('tai-khoan.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
            </a>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Form Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin tài khoản</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('tai-khoan.update', $taiKhoan->ID_TaiKhoan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Thông tin cá nhân -->
                        <div class="col-md-6">
                            <div class="card mb-4 border-left-primary shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <strong>Thông tin cá nhân</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="ID_CCCD">Số CCCD/CMND</label>
                                        <input type="text" class="form-control-plaintext" id="ID_CCCD"
                                            value="{{ $taiKhoan->ID_CCCD }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="HoTen">Họ và tên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="HoTen" name="HoTen"
                                            value="{{ old('HoTen', $taiKhoan->HoTen) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Giới tính <span class="text-danger">*</span></label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="GioiTinh" id="GioiTinhNam"
                                                value="1"
                                                {{ old('GioiTinh', $taiKhoan->GioiTinh) == 1 ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="GioiTinhNam">Nam</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="GioiTinh" id="GioiTinhNu"
                                                value="0"
                                                {{ old('GioiTinh', $taiKhoan->GioiTinh) == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="GioiTinhNu">Nữ</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="NgaySinh">Ngày sinh <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="NgaySinh" name="NgaySinh"
                                            value="{{ old('NgaySinh', date('Y-m-d', strtotime($taiKhoan->NgaySinh))) }}"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="Email">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="Email" name="Email"
                                            value="{{ old('Email', $taiKhoan->Email) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="SDT">Số điện thoại <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="SDT" name="SDT"
                                            value="{{ old('SDT', $taiKhoan->SDT) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin tài khoản -->
                        <div class="col-md-6">
                            <div class="card mb-4 border-left-info shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <strong>Thông tin tài khoản</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="TenDN">Tên đăng nhập <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="TenDN" name="TenDN"
                                            value="{{ old('TenDN', $taiKhoan->TenDN) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="MatKhau">Mật khẩu <small class="text-muted">(Để trống nếu không muốn
                                                thay đổi)</small></label>
                                        <input type="password" class="form-control" id="MatKhau" name="MatKhau">
                                    </div>

                                    <div class="form-group">
                                        <label for="VaiTro">Vai trò <span class="text-danger">*</span></label>
                                        <select class="form-control" id="VaiTro" name="VaiTro" required>
                                            <option value="0"
                                                {{ old('VaiTro', $taiKhoan->VaiTro) == 0 ? 'selected' : '' }}>Người dùng
                                            </option>
                                            <option value="1"
                                                {{ old('VaiTro', $taiKhoan->VaiTro) == 1 ? 'selected' : '' }}>Nhân viên
                                            </option>
                                            <option value="2"
                                                {{ old('VaiTro', $taiKhoan->VaiTro) == 2 ? 'selected' : '' }}>Quản trị
                                                viên</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="TrangThai">Trạng thái <span class="text-danger">*</span></label>
                                        <select class="form-control" id="TrangThai" name="TrangThai" required>
                                            <option value="1"
                                                {{ old('TrangThai', $taiKhoan->TrangThai) == 1 ? 'selected' : '' }}>Hoạt
                                                động</option>
                                            <option value="0"
                                                {{ old('TrangThai', $taiKhoan->TrangThai) == 0 ? 'selected' : '' }}>Vô
                                                hiệu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                        <a href="{{ route('tai-khoan.index') }}" class="btn btn-outline-secondary px-4 ml-2">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
