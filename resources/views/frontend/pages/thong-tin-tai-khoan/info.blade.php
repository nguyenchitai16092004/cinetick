@extends('frontend.layouts.master')
@section('title', 'Thông tin tài khoản')
@section('main')
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/info.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/chi-tiet-phim.css') }}">
    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="container-info">
        <div class="sidebar-info">
            <div class="profile-info">
                <div class="avatar">
                    <img src="{{ asset('frontend/Content/img/user.avif') }}"
                        style="display:block;width:200px;height:auto;margin: 0 auto;" />
                </div>
                <div class="profile-name">{{ session('user_fullname') }}</div>
            </div>
            <div class="contact-info">
                <div class="contact-item">
                    HOTLINE hỗ trợ: {{ $thongTinTrangWeb->Hotline }} (9:00 - 22:00)
                </div>
                <div class="contact-item">Email: {{ $thongTinTrangWeb->Email }}</div>

            </div>
        </div>
        <div class="main-content">
            <div class="tabs">
                <div class="tab active" data-tab="transaction-history">
                    Lịch Sử Giao Dịch
                </div>
                <div class="tab" data-tab="personal-info">
                    Thông Tin Cá Nhân
                </div>
            </div>
            <div class="tab-content active" id="transaction-history">
                <div class="transaction-list">
                    @forelse ($hoaDons as $hoaDon)
                        @php
                            $ve = $hoaDon->veXemPhim->first();
                            $phim = $ve->suatChieu->phim ?? null;
                            $rap = $ve->suatChieu->rap ?? null;
                            $veMa = $ve->MaVe ?? $hoaDon->ID_HoaDon;
                            $giaVe = $ve->GiaVe ?? 0;
                            $dsGhe = $hoaDon->veXemPhim->pluck('TenGhe')->implode(', ');
                            $poster =
                                $phim && $phim->HinhAnh
                                    ? asset('storage/' . $phim->HinhAnh)
                                    : asset('frontend/Content/img/default-movie.png');
                            $ngayXem = $ve->NgayXem ?? '';
                            $gioChieu = $ve->suatChieu->GioChieu ?? '';
                            $tenPhim = $phim->TenPhim ?? '';
                            $age = $phim->DoTuoi ?? '---';
                            $tenRap = $rap->TenRap ?? '---';
                        @endphp
                        <div class="transaction-item" onclick="showTransactionDetail(this)"
                            data-poster="{{ $poster }}" data-ghe="{{ $dsGhe }}"
                            data-tenphim="{{ $tenPhim }}"data-mahoadon="{{ $hoaDon->ID_HoaDon }}"
                            data-age="{{ $age }}" data-rap="{{ $tenRap }}"
                            data-ngayxem="{{ $ngayXem }}" data-giochieu="{{ $gioChieu }}"
                            data-mave="{{ $veMa }}" data-giave="{{ number_format($giaVe, 0, ',', '.') }} đ">
                            <div class="movie-poster">
                                <img src="{{ $poster }}" alt="Movie poster"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;" />
                            </div>
                            <div class="transaction-info">
                                <div class="movie-title"> {{ $tenPhim }}</div>
                                <div class="movie-subtitle">Digital Phụ Đề</div>
                                <div class="age-rating">{{ $age }}</div>
                            </div>
                            <div class="transaction-details">
                                <div class="cinema-location">{{ $tenRap }}</div>
                                <div class="showtime">
                                    {{ $gioChieu }} {{ $ngayXem }}
                                </div>
                                <div class="detail-link">Chi tiết</div>
                            </div>
                        </div>
                    @empty
                        <div class="blank-invoice">Bạn chưa có hóa đơn nào.</div>
                    @endforelse
                </div>
            </div>
            <div class="tab-content" id="personal-info">
                <form id="personalInfoForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" class="form-input" value="{{ session('user_fullname') }}" disabled />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" class="form-input"
                                value="{{ \Carbon\Carbon::parse(session('user_date'))->format('Y-m-d') }}" disabled />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" value="{{ session('user_email') }}" disabled />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-input" value="{{ session('user_phone') }}" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="gender-options">
                            <label class="form-label">Giới tính</label>
                            <div class="gender-option">
                                <input type="radio" id="male" name="gender" value="male"
                                    {{ session('user_sex') == 1 ? 'checked' : '' }} disabled />
                                <label for="male">Nam</label>
                            </div>
                            <div class="gender-option">
                                <input type="radio" id="female" name="gender" value="female"
                                    {{ session('user_sex') == 0 ? 'checked' : '' }} disabled />
                                <label for="female">Nữ</label>
                            </div>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" onclick="openChangePasswordModal()">
                            Đổi mật khẩu
                        </button>
                        <button type="button" class="btn btn-primary" onclick="openUpdateInfoModal()">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="transactionModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Chi tiết giao dịch</div>
                <button class="close-btn" onclick="closeTransactionDetail()">
                    &times;
                </button>
            </div>
            <div class="transaction-detail">
                <div class="transaction-detail-icon">
                    <img id="modal-movie-poster" src="{{ asset('frontend/Content/img/default-movie.png') }}"
                        alt="Movie poster" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;" />
                </div>
                <div class="transaction-detail-name" id="modal-movie-title">Tên phim</div>
                <div class="transaction-detail-type" id="modal-movie-age">--- </div>
                <div class="transaction-detail-info">
                    <div class="info-row">
                        <span class="info-label" id="modal-cinema">Tên rạp</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Suất:</span>
                        <span class="info-value" id="modal-showtime">--:-- --/--/----</span>
                    </div>
                </div>
                <div class="qr-code" id="modal-qr"></div>
                <div style="margin-top: 20px">
                    <div class="info-row">
                        <span class="info-label">Mã vé</span>
                        <span class="info-label">Ghế</span>
                        <span class="info-label">Giá</span>
                    </div>
                    <div class="info-row">
                        <span class="info-value" id="modal-ticket-code">---</span>
                        <span class="info-value" id="modal-seat-list">---</span>
                        <span class="info-value" id="modal-total-price">---</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal change-password-modal" id="changePasswordModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Đổi mật khẩu</div>
                <button class="close-btn" onclick="closeChangePasswordModal()">&times;</button>
            </div>
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <div class="register-form">
                    <div class="form-group" style="position:relative;">
                        <input type="password" minlength="6" maxlength="50" class="form-control" id="lgPassword"
                            placeholder="Mật khẩu hiện tại" style="padding-right: 40px;">
                        <button type="button" class="password-toggle" tabindex="-1"
                            onclick="togglePassword('lgPassword', this)"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%);">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-group" style="position:relative;">
                        <input type="password" minlength="6" maxlength="50" class="form-control" id="lgPasswordNew"
                            placeholder="Mật khẩu mới" style="padding-right: 40px;">
                        <button type="button" class="password-toggle" tabindex="-1"
                            onclick="togglePassword('lgPasswordNew', this)"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%);">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-group" style="position:relative;">
                        <input type="password" minlength="6" maxlength="50" class="form-control" id="lgRePasswordNew"
                            placeholder="Xác nhận mật khẩu mới" style="padding-right: 40px;">
                        <button type="button" class="password-toggle" tabindex="-1"
                            onclick="togglePassword('lgRePasswordNew', this)"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%);">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <input type="button" class="btn btn-primary" onclick="changePass()" value="Đổi mật khẩu">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal cập nhật thông tin -->
    <div class="modal" id="updateInfoModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Cập nhật thông tin tài khoản</div>
                <button class="close-btn" onclick="closeUpdateInfoModal()">&times;</button>
            </div>
            <form id="updateInfoFormModal">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" id="modalFullName" name="HoTen"
                        value="{{ $thongTin->HoTen ?? '' }}" placeholder="Họ & tên(*)">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="modalCMND" name="ID_ThongTin"
                        value="{{ $thongTin->ID_ThongTin ?? '' }}" placeholder="CMND(*)" readonly>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="modalEmail" name="Email" placeholder="Email (*)"
                        value="{{ $thongTin->Email ?? '' }}" readonly>
                </div>
                <div class="form-group">
                    <input type="text" id="modalBirthDay" class="form-control" name="NgaySinh"
                        placeholder="Ngày sinh"
                        value="{{ $thongTin->NgaySinh ? \Carbon\Carbon::parse($thongTin->NgaySinh)->format('d/m/Y') : '' }}"
                        readonly>
                </div>
                <div class="form-group">
                    <input type="tel" minlength="10" maxlength="10" id="modalPhone" name="SDT"
                        class="form-control" placeholder="Điện thoại(*)" value="{{ $thongTin->SDT ?? '' }}">
                </div>
                <div class="form-group">
                    <div class="maxl">
                        <label class="radio inline">
                            <input type="radio" id="modalGenderTrue" name="GioiTinh" value="1"
                                {{ ($thongTin->GioiTinh ?? 1) == 1 ? 'checked' : '' }}>
                            <span> Nam </span>
                        </label>
                        <label class="radio inline">
                            <input type="radio" id="modalGenderFalse" name="GioiTinh" value="0"
                                {{ ($thongTin->GioiTinh ?? 1) == 0 ? 'checked' : '' }}>
                            <span> Nữ </span>
                        </label>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="submitUpdateInfo()">Lưu thay đổi</button>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="{{ asset('frontend/Content/js/info.js') }}" defer></script>
@stop
