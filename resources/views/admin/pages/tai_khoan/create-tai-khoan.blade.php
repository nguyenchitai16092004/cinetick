@extends('admin.layouts.master')

@section('title', 'Thêm tài khoản mới')

@section('main')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h4 text-purple font-weight-bold mb-0">
                <i class="fas fa-user-plus"></i> Thêm tài khoản mới
            </h1>
            <a href="{{ route('tai-khoan.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
        <div class="card shadow border-0">
            <div class="card-header bg-purple text-white" style="background-color: rgb(111, 66, 193)">
                <h5 class="mb-0 font-weight-bold">Thông tin tài khoản</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tai-khoan.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="TenDN">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="TenDN" name="TenDN" value="{{ old('TenDN') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="MatKhau">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="MatKhau" name="MatKhau" required>
                    </div>

                    <div class="form-group">
                        <label for="HoTen">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="HoTen" name="HoTen" value="{{ old('HoTen') }}"
                            required>
                    </div>

                    @php
                        $maxDate = \Carbon\Carbon::now()->subYears(18)->format('Y-m-d');
                    @endphp

                    <div class="form-group">
                        <label for="NgaySinh">Ngày sinh <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="NgaySinh" name="NgaySinh"
                            value="{{ old('NgaySinh') }}" max="{{ $maxDate }}" required>
                    </div>
                    <div class="form-group">
                        <label for="Email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="Email" name="Email" value="{{ old('Email') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="SDT">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="SDT" name="SDT" value="{{ old('SDT') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="VaiTro">Vai trò <span class="text-danger">*</span></label>
                        <select class="form-control" id="VaiTro" name="VaiTro" required>
                            <option value="1" selected>Nhân viên</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ID_Rap">Địa chỉ làm việc <span class="text-danger">*</span></label>
                        <select class="form-control" id="ID_Rap" name="ID_Rap" required>
                            @foreach ($raps as $rap)
                                <option value="{{ $rap->ID_Rap }}">{{ $rap->TenRap }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Luong">Lương <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="Luong" name="Luong"
                            value="{{ old('Luong', $taiKhoan->thongTin->Luong ?? '') }}" required min="1000000"
                            max="1000000000">
                    </div>
                    <div class="form-group">
                        <label for="TrangThai">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-control" id="TrangThai" name="TrangThai" required>
                            <option value="1" {{ old('TrangThai') == '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ old('TrangThai') == '0' ? 'selected' : '' }}>Vô hiệu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Giới tính <span class="text-danger">*</span></label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="GioiTinh" id="GioiTinhNam" value="1"
                                {{ old('GioiTinh') == '1' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="GioiTinhNam">Nam</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="GioiTinh" id="GioiTinhNu"
                                value="0" {{ old('GioiTinh') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="GioiTinhNu">Nữ</label>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-purple px-4"
                            style="background-color: rgb(111, 66, 193) ; color: white;">
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
