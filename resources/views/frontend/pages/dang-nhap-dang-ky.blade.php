@extends('frontend.layouts.master')
@section('title', 'CineTick - Đăng nhập / Đăng ký')
@section('main')
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/dang-nhap.css') }}">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-tabs">
                <div class="auth-tab active" id="tab-login" tabindex="0">ĐĂNG NHẬP</div>
                <div class="auth-tab" id="tab-register" tabindex="0">ĐĂNG KÝ</div>
            </div>
            <div class="auth-form-wrapper" id="form-wrapper" style="height:auto;">
                <!-- Login Form -->
                <form class="auth-form active" id="login-form" autocomplete="off" action="{{ route('login') }}"
                    method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label required">Email / Tên đăng nhập</label>
                        <input type="text" class="form-input" name="TenDN" placeholder="Email / Tên đăng nhập" required
                            autocomplete="username" value="{{ old('TenDN') }}">
                        @error('TenDN')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" style="position:relative;">
                        <label class="form-label required">Mật khẩu</label>
                        <input type="password" class="form-input" id="login-password" name="MatKhau" placeholder="Mật khẩu"
                            required minlength="6" autocomplete="current-password">
                        <span class="input-icon" id="toggle-login-pw"><i class="fa-regular fa-eye"></i></span>
                        @error('MatKhau')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-actions">
                        <a href="{{ route('quen-mat-khau.get') }}" class="form-link">Quên mật khẩu?</a>
                    </div>
                    <button type="submit" class="btn-submit">ĐĂNG NHẬP</button>
                    <div class="form-note">
                        Chưa có tài khoản? <a href="#" id="goto-register">Đăng ký</a>
                    </div>
                </form>

                <!-- Register Form -->
                <form class="auth-form" id="register-form" autocomplete="off" action="{{ route('register.form.post') }}"
                    method="POST">
                    @csrf
                    <div class="form-title">Đăng ký tài khoản thành viên và nhận ngay ưu đãi!</div>
                    <div class="form-group">
                        <label class="form-label required">Họ &amp; tên</label>
                        <input type="text" class="form-input" id="rgFullName" name="HoTen" placeholder="Họ &amp; tên"
                            value="{{ old('HoTen') }}" required>
                        @error('HoTen')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Email</label>
                        <input type="email" class="form-input" id="rgEmail" name="Email" placeholder="Email"
                            value="{{ old('Email') }}" required>
                        @error('Email')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Điện thoại</label>
                        <input type="tel" class="form-input" id="rgPhone" name="SDT" placeholder="Điện thoại"
                            value="{{ old('SDT') }}" required pattern="\d{10,11}"
                            title="Số điện thoại phải là dãy số từ 10 đến 11 chữ số">
                        @error('SDT')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" style="position:relative;">
                        <label class="form-label required">Ngày sinh</label>
                        <input type="date" id="rgBirthDay" name="NgaySinh" class="form-input"
                            value="{{ old('NgaySinh') }}" required>
                        @error('NgaySinh')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Tên đăng nhập</label>
                        <input type="text" class="form-input" id="rgUserName" name="TenDN"
                            placeholder="Tên đăng nhập" value="{{ old('TenDN') }}" required autocomplete="username">
                        @error('TenDN')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" style="position:relative;">
                        <label class="form-label required">Mật khẩu</label>
                        <input type="password" class="form-input" id="reg-password" name="MatKhau"
                            placeholder="Mật khẩu" required autocomplete="new-password">
                        <button type="button" class="input-icon" id="toggle-reg-pw" tabindex="-1"><i
                                class="fa-regular fa-eye"></i></button>
                        @error('MatKhau')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" style="position:relative;">
                        <label class="form-label required">Mật khẩu nhập lại</label>
                        <input type="password" class="form-input" id="reg-password2" name="MatKhau_confirmation"
                            placeholder="Mật khẩu nhập lại" required>
                        <button type="button" class="input-icon" id="toggle-reg-pw2" tabindex="-1"><i
                                class="fa-regular fa-eye"></i></button>
                        @error('MatKhau_confirmation')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Giới tính</label>
                        <div style="display: flex; gap: 24px; align-items: center;">
                            <label style="display: flex; align-items: center; gap: 6px; font-weight: 400;">
                                <input type="radio" name="GioiTinh" value="1"
                                    {{ old('GioiTinh', '1') == '1' ? 'checked' : '' }}>
                                Nam
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; font-weight: 400;">
                                <input type="radio" name="GioiTinh" value="0"
                                    {{ old('GioiTinh') == '0' ? 'checked' : '' }}>
                                Nữ
                            </label>
                        </div>
                        @error('GioiTinh')
                            <div class="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btnRegister">ĐĂNG KÝ</button>
                    <div class="form-note">
                        Bạn đã có tài khoản? <a href="#" id="goto-login">Đăng nhập</a>
                    </div>
                    <p style="color: #333; margin-top: 10px;">Vui lòng nhập đầy đủ thông tin vào các trường có đánh dấu <b
                            style="color: red;">(*)</b></p>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $.sweetModal({
                    content: `{!! implode('<br>', $errors->all()) !!}`,
                    title: 'Thông báo',
                    icon: $.sweetModal.ICON_WARNING,
                    theme: $.sweetModal.THEME_DARK,
                    buttons: {
                        'OK': {
                            classes: 'redB'
                        }
                    }
                });
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            $(document).ready(function() {
                $.sweetModal({
                    content: `{{ session('success') }}`,
                    title: 'Thông báo',
                    icon: $.sweetModal.ICON_SUCCESS,
                    theme: $.sweetModal.THEME_DARK,
                    buttons: {
                        'OK': {
                            classes: 'redB',
                            action: function() {
                                window.location.href = "{{ route('login.form') }}";
                            }
                        }
                    }
                });
            });
        </script>
    @endif

    <script>
        (function() {
            // Tab switching and form animation
            const loginTab = document.getElementById('tab-login');
            const registerTab = document.getElementById('tab-register');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const formWrapper = document.getElementById('form-wrapper');
            let active = '{{ old('form_type') == 'register' ? 'register' : 'login' }}';

            function showForm(form) {
                if (form === 'login') {
                    loginForm.classList.add('active');
                    registerForm.classList.remove('active');
                    loginTab.classList.add('active');
                    registerTab.classList.remove('active');
                    setTimeout(() => {
                        formWrapper.style.height = loginForm.offsetHeight + "px";
                    }, 5);
                    active = 'login';
                } else {
                    registerForm.classList.add('active');
                    loginForm.classList.remove('active');
                    registerTab.classList.add('active');
                    loginTab.classList.remove('active');
                    setTimeout(() => {
                        formWrapper.style.height = registerForm.offsetHeight + "px";
                    }, 5);
                    active = 'register';
                }
            }
            loginTab.addEventListener('click', () => showForm('login'));
            registerTab.addEventListener('click', () => showForm('register'));
            loginTab.addEventListener('keydown', e => {
                if (e.key === 'Enter') showForm('login');
            });
            registerTab.addEventListener('keydown', e => {
                if (e.key === 'Enter') showForm('register');
            });
            document.getElementById('goto-login').addEventListener('click', function(e) {
                e.preventDefault();
                showForm('login');
            });
            document.getElementById('goto-register').addEventListener('click', function(e) {
                e.preventDefault();
                showForm('register');
            });
            window.addEventListener('DOMContentLoaded', () => {
                if (active === 'register') showForm('register');
                else showForm('login');
                formWrapper.style.height = (active === 'register' ? registerForm.offsetHeight : loginForm
                    .offsetHeight) + "px";
            });

            // Password show/hide for login
            function togglePw(inputId, iconId) {
                const input = document.getElementById(inputId);
                const iconBox = document.getElementById(iconId);
                if (!input || !iconBox) return;
                iconBox.addEventListener('click', function() {
                    if (input.type === 'password') {
                        input.type = 'text';
                        iconBox.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
                    } else {
                        input.type = 'password';
                        iconBox.innerHTML = '<i class="fa-regular fa-eye"></i>';
                    }
                });
            }
            togglePw('login-password', 'toggle-login-pw');

            // For register form password toggles
            function togglePwBtn(inputId, btnId) {
                const input = document.getElementById(inputId);
                const btn = document.getElementById(btnId);
                if (!input || !btn) return;
                btn.addEventListener('click', function() {
                    const icon = btn.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }
            togglePwBtn('reg-password', 'toggle-reg-pw');
            togglePwBtn('reg-password2', 'toggle-reg-pw2');

            // Animate form wrapper height when switching forms
            const resizeObserver = new ResizeObserver(() => {
                if (active === 'login') formWrapper.style.height = loginForm.offsetHeight + "px";
                else formWrapper.style.height = registerForm.offsetHeight + "px";
            });
            resizeObserver.observe(loginForm);
            resizeObserver.observe(registerForm);
        })();
    </script>
@endsection
