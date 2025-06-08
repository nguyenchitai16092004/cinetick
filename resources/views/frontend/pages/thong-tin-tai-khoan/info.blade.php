@extends('frontend.layouts.master')
@section('title', 'Thông tin tài khoản')
@section('main')
    <style>
        .container-info {
            max-width: 1500px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 20px;
        }

        .sidebar-info {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }

        .profile-info {
            text-align: center;
            margin-bottom: 24px;
        }

        .avatar {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: #e0e7ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 24px;
            color: #6366f1;
        }

        .profile-name {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .password-toggle {
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            font-size: 18px;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: #374151;
            background: #f3f4f6;
        }

        .form-input:disabled {
            background: #f3f4f6;
            color: #9ca3af;
            opacity: 1;
            cursor: not-allowed;
            border-color: #e5e7eb;
        }

        .spending-info {
            margin-bottom: 24px;
        }

        .spending-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .spending-amount {
            color: #f59e0b;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .spending-slider {
            position: relative;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            margin-bottom: 12px;
        }

        .spending-progress {
            height: 100%;
            background: linear-gradient(to right, #3b82f6, #10b981);
            border-radius: 4px;
            width: 0%;
        }

        .spending-labels {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #6b7280;
        }

        .contact-info {
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .contact-item {
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            font-size: 14px;
            cursor: pointer;
            transition: color 0.2s;
        }

        .contact-item:hover {
            color: #3b82f6;
        }

        .main-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .tabs {
            display: flex;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .tab {
            padding: 16px 24px;
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
            background: white;
        }

        .tab-content {
            display: none;
            padding: 24px;
        }

        .tab-content.active {
            display: block;
        }

        .transaction-list {
            margin-top: 16px;
        }

        .transaction-item {
            display: flex;
            align-items: center;
            padding: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }

        .transaction-item:hover {
            border-color: #3b82f6;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .movie-poster {
            width: 60px;
            height: 80px;
            border-radius: 4px;
            overflow: hidden;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .transaction-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .movie-title {
            font-weight: 600;
            color: #1f2937;
            font-size: 16px;
        }

        .movie-subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        .age-rating {
            background: #f59e0b;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            width: fit-content;
            margin-top: 4px;
        }

        .transaction-details {
            text-align: right;
            display: flex;
            flex-direction: column;
            gap: 4px;
            align-items: flex-end;
        }

        .cinema-location {
            color: #1f2937;
            font-weight: 500;
            font-size: 14px;
        }

        .showtime {
            color: #6b7280;
            font-size: 14px;
        }

        .detail-link {
            color: #f59e0b;
            font-weight: 600;
            font-size: 14px;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input.error {
            border-color: #ef4444;
        }

        .password-field {
            position: relative;
        }

        .password-field .form-input {
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            font-size: 18px;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: #374151;
            background: #f3f4f6;
        }

        .gender-options {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }

        .gender-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .gender-option input[type="radio"] {
            accent-color: #3b82f6;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #f59e0b;
            color: white;
        }

        .btn-primary:hover {
            background: #d97706;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            position: relative;
            animation: slideIn 0.3s ease;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .transaction-detail {
            text-align: center;
        }

        .transaction-detail-icon {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: #fee2e2;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
            font-weight: bold;
            color: #dc2626;
        }

        .transaction-detail-name {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .transaction-detail-type {
            background: #f59e0b;
            color: white;
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 20px;
        }

        .transaction-detail-info {
            background: #f9fafb;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            color: #6b7280;
            font-size: 14px;
        }

        .info-value {
            color: #1f2937;
            font-weight: 500;
            font-size: 14px;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="white"/><rect x="10" y="10" width="15" height="15" fill="black"/><rect x="75" y="10" width="15" height="15" fill="black"/><rect x="10" y="75" width="15" height="15" fill="black"/><rect x="45" y="45" width="10" height="10" fill="black"/></svg>') center/contain no-repeat;
            margin: 0 auto;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .change-password-form {
            display: none;
        }

        .change-password-form.show {
            display: block;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 768px) {
            .container-info {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .btn-group {
                justify-content: stretch;
            }

            .btn-group .btn {
                flex: 1;
            }
        }
    </style>
    <div class="container-info">
        <div class="sidebar-info">
            <div class="profile-info">
                <div class="avatar">
                    <img src="{{ asset('frontend/Content/img/user.avif') }}"
                        style="display:block;width:200px;height:auto;margin: 0 auto;" />
                </div>
                <div class="profile-name">{{ session('user_fullname') }}</div>
            </div>
            <div class="spending-info">
                <div class="spending-title">Tổng chi tiêu 2025 <i class="fa-solid fa-circle-info"></i></div>
                <div class="spending-amount">0 đ</div>
                <div class="spending-slider">
                    <div class="spending-progress"></div>
                </div>
                <div class="spending-labels">
                    <span>0 đ</span>
                    <span>2.000.000 đ</span>
                    <span>4.000.000 đ</span>
                </div>
            </div>
            <div class="contact-info">
                <div class="contact-item">
                    HOTLINE hỗ trợ: 19002224 (9:00 - 22:00)
                </div>
                <div class="contact-item">Email: hotro@galaxystudio.vn</div>
                <div class="contact-item">Câu hỏi thường gặp</div>
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
                <p style="color: #6b7280; margin-bottom: 16px">
                    Lưu ý: chỉ hiển thị 20 giao dịch gần nhất
                </p>
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
                            data-age="{{ $age }}" data-rap="{{ $tenRap }}" data-ngayxem="{{ $ngayXem }}"
                            data-giochieu="{{ $gioChieu }}" data-mave="{{ $veMa }}"
                            data-giave="{{ number_format($giaVe, 0, ',', '.') }} đ">
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
                        <div>Bạn chưa có hóa đơn nào.</div>
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
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">CCCD</label>
                            <input type="tel" class="form-input" value="{{ session('cccd_id') }}" disabled>
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
                    <input type="text" class="form-control" id="modalCMND" name="ID_CCCD"
                        value="{{ $thongTin->ID_CCCD ?? '' }}" placeholder="CMND(*)" readonly>
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
    <script>
        const tabs = document.querySelectorAll(".tab");
        const tabContents = document.querySelectorAll(".tab-content");
        tabs.forEach((tab) => {
            tab.addEventListener("click", () => {
                tabs.forEach((t) => t.classList.remove("active"));
                tabContents.forEach((content) =>
                    content.classList.remove("active")
                );
                tab.classList.add("active");
                const targetTab = tab.getAttribute("data-tab");
                document.getElementById(targetTab).classList.add("active");
            });
        });

        function showTransactionDetail(el) {
            // Lấy các thông tin từ data-*
            document.getElementById('modal-movie-poster').src = el.getAttribute('data-poster');
            document.getElementById('modal-movie-title').textContent = el.getAttribute('data-tenphim');
            document.getElementById('modal-movie-age').textContent = el.getAttribute('data-age');
            document.getElementById('modal-cinema').textContent = el.getAttribute('data-rap');
            document.getElementById('modal-showtime').textContent = el.getAttribute('data-giochieu') + ' ' + el
                .getAttribute('data-ngayxem');
            document.getElementById('modal-ticket-code').textContent = el.getAttribute('data-mave');
            document.getElementById('modal-seat-list').textContent = el.getAttribute('data-ghe');
            document.getElementById('modal-total-price').textContent = el.getAttribute('data-giave');

            // Render QR code từ ID_HoaDon (data-mahoadon)
            const maHoaDon = el.getAttribute('data-mahoadon');
            document.getElementById('modal-qr').innerHTML = ""; // Xóa QR cũ
            if (maHoaDon) {
                new QRCode(document.getElementById("modal-qr"), {
                    text: maHoaDon,
                    width: 100,
                    height: 100,
                });
            }

            document.getElementById("transactionModal").classList.add("show");
        }

        function closeTransactionDetail() {
            document.getElementById("transactionModal").classList.remove("show");
        }

        function openChangePasswordModal() {
            document.getElementById("changePasswordModal").classList.add("show");
        }

        function closeChangePasswordModal() {
            document.getElementById("changePasswordModal").classList.remove("show");
        }

        // Xử lý đổi mật khẩu
        function changePass() {
            var oldPass = $("#lgPassword").val();
            var newPassWord = $("#lgPasswordNew").val();
            var reNewPassWord = $("#lgRePasswordNew").val();

            if (!oldPass) {
                sweetModalWarn('Vui lòng nhập mật khẩu cũ.', "#lgPassword");
                return false;
            }
            if (!newPassWord) {
                sweetModalWarn('Vui lòng nhập mật khẩu mới.', "#lgPasswordNew");
                return false;
            }
            if (!reNewPassWord) {
                sweetModalWarn('Vui lòng nhập lại mật khẩu mới.', "#lgRePasswordNew");
                return false;
            }

            var data = {
                OldPass: oldPass,
                NewPass: newPassWord,
                ReNewPass: reNewPassWord,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                url: "{{ route('doi-mat-khau.post') }}",
                type: "POST",
                data: JSON.stringify(data),
                datatype: "json",
                contentType: 'application/json; charset=utf-8',
                success: function(result) {
                    if (result === "true" || result === true) {
                        $("#lgPassword").val("");
                        $("#lgPasswordNew").val("");
                        $("#lgRePasswordNew").val("");
                        closeChangePasswordModal();
                        $.sweetModal({
                            content: 'Đổi mật khẩu thành công. Bạn có thể đăng nhập <a href="{{ asset('/') }}">tại đây</a>',
                            title: 'Thông báo',
                            icon: $.sweetModal.ICON_SUCCESS,
                            theme: $.sweetModal.THEME_DARK,
                            buttons: {
                                'OK': {
                                    classes: 'redB'
                                }
                            }
                        });
                    } else {
                        let msg = '';
                        if (result === "1") msg = 'Phiên đăng nhập của bạn đã hết hạn, vui lòng đăng nhập lại.';
                        else if (result === "2") msg = 'Mật khẩu cũ không chính xác!';
                        else if (result === "3") msg = 'Mật khẩu mới nhập lại không trùng khớp!';
                        else msg = result;
                        $.sweetModal({
                            content: msg,
                            title: 'Lỗi',
                            icon: $.sweetModal.ICON_WARNING,
                            theme: $.sweetModal.THEME_DARK,
                            buttons: {
                                'OK': {
                                    classes: 'redB'
                                }
                            }
                        });
                    }
                },
                error: function(xhr) {
                    $.sweetModal({
                        content: 'Có lỗi xảy ra: ' + xhr.status + ' - ' + xhr.responseText,
                        title: 'Lỗi',
                        icon: $.sweetModal.ICON_WARNING,
                        theme: $.sweetModal.THEME_DARK,
                        buttons: {
                            'OK': {
                                classes: 'redB'
                            }
                        }
                    });
                }
            });
        }

        function sweetModalWarn(msg, focusSelector) {
            $.sweetModal({
                content: msg,
                title: '',
                icon: $.sweetModal.ICON_WARNING,
                theme: $.sweetModal.THEME_DARK,
                buttons: {
                    'OK': {
                        classes: 'redB'
                    }
                }
            });
            if (focusSelector) setTimeout(function() {
                $(focusSelector).focus();
            }, 300);
        }

        function openUpdateInfoModal() {
            document.getElementById("updateInfoModal").classList.add("show");
        }

        function closeUpdateInfoModal() {
            document.getElementById("updateInfoModal").classList.remove("show");
        }

        function submitUpdateInfo() {
            var data = {
                HoTen: $("#modalFullName").val(),
                ID_CCCD: $("#modalCMND").val(),
                Email: $("#modalEmail").val(),
                NgaySinh: $("#modalBirthDay").val(),
                SDT: $("#modalPhone").val(),
                GioiTinh: $("input[name='GioiTinh']:checked", "#updateInfoFormModal").val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            $.ajax({
                url: "{{ route('user.updateInfo.post') }}",
                type: "POST",
                data: data,
                success: function(res) {
                    closeUpdateInfoModal();
                    $.sweetModal({
                        content: "Cập nhật thông tin thành công!",
                        title: "Thông báo",
                        icon: $.sweetModal.ICON_SUCCESS,
                        theme: $.sweetModal.THEME_DARK,
                        buttons: {
                            'OK': {
                                classes: 'redB'
                            }
                        }
                    });
                    // Cập nhật lại trên giao diện nếu cần
                    // location.reload(); // hoặc tự cập nhật các field hiển thị
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || "Có lỗi xảy ra!";
                    $.sweetModal({
                        content: msg,
                        title: "Lỗi",
                        icon: $.sweetModal.ICON_WARNING,
                        theme: $.sweetModal.THEME_DARK,
                        buttons: {
                            'OK': {
                                classes: 'redB'
                            }
                        }
                    });
                }
            });
        }
        function togglePassword(inputId, btn) {
    var input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        btn.querySelector('i').classList.remove('fa-eye');
        btn.querySelector('i').classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        btn.querySelector('i').classList.remove('fa-eye-slash');
        btn.querySelector('i').classList.add('fa-eye');
    }
}
    </script>
@stop
