@extends('admin.layouts.master')

@section('title', 'Chi tiết và cập nhật tài khoản')

@section('main')
    <style>
        label {
            font-weight: bold;
            padding-top: 10px;
        }
    </style>    
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <a href="{{ route('tai-khoan.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
            </a>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-purple text-white" style="background-color: rgb(111, 66, 193)">
                <h6 class="m-0 font-weight-bold">Thông tin tài khoản</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('tai-khoan.update', $taiKhoan->ID_TaiKhoan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="TenDN">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="TenDN" name="TenDN"
                            value="{{ old('TenDN', $taiKhoan->TenDN) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="MatKhau">Mật khẩu <small class="text-muted">(Để trống nếu không thay
                                đổi)</small></label>
                        <input type="password" class="form-control" id="MatKhau" name="MatKhau">
                    </div>

                    <div class="form-group">
                        <label for="HoTen">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="HoTen" name="HoTen"
                            value="{{ old('HoTen', $taiKhoan->thongTin->HoTen ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="NgaySinh">Ngày sinh <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="NgaySinh" name="NgaySinh"
                            value="{{ old('NgaySinh', optional($taiKhoan->thongTin)->NgaySinh ? date('Y-m-d', strtotime($taiKhoan->thongTin->NgaySinh)) : '') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="Email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="Email" name="Email"
                            value="{{ old('Email', $taiKhoan->thongTin->Email ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="SDT">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="SDT" name="SDT"
                            value="{{ old('SDT', $taiKhoan->thongTin->SDT ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="VaiTro">Vai trò <span class="text-danger">*</span></label>
                        <input type="hidden" name="VaiTro" value="{{ $taiKhoan->VaiTro }}">
                        <select class="form-control" id="VaiTro" disabled>
                            <option value="0" {{ $taiKhoan->VaiTro == 0 ? 'selected' : '' }}>Người dùng</option>
                            <option value="1" {{ $taiKhoan->VaiTro == 1 ? 'selected' : '' }}>Nhân viên</option>
                            <option value="2" {{ $taiKhoan->VaiTro == 2 ? 'selected' : '' }}>Quản trị viên</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ID_Rap">Địa chỉ làm việc <span class="text-danger">*</span></label>
                        <select class="form-control" id="ID_Rap" name="ID_Rap" required disabled>
                            @if ($taiKhoan->VaiTro == 1)
                                @foreach ($raps as $rap)
                                    <option value="{{ $rap->ID_Rap }}"
                                        {{ old('ID_Rap', $taiKhoan->ID_Rap ?? '') == $rap->ID_Rap ? 'selected' : '' }}>
                                        {{ $rap->TenRap }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Luong">Lương <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="Luong" name="Luong"
                            value="{{ old('Luong', $taiKhoan->thongTin->Luong ?? '') }}" required disabled>
                    </div>

                    <div class="form-group">
                        <label>Giới tính <span class="text-danger">*</span></label><br>
                        <div class="d-flex align-items-center pt-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="GioiTinh" id="GioiTinhNam"
                                    value="1"
                                    {{ old('GioiTinh', $taiKhoan->thongTin->GioiTinh ?? null) == 1 ? 'checked' : '' }}
                                    required>
                                <label class="form-check-label" style="padding: 0" for="GioiTinhNam">Nam</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="GioiTinh" id="GioiTinhNu"
                                    value="0"
                                    {{ old('GioiTinh', $taiKhoan->thongTin->GioiTinh ?? null) == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" style="padding: 0" for="GioiTinhNu">Nữ</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-purple px-4"
                            style="background-color: rgb(111, 66, 193); color: white;">
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
