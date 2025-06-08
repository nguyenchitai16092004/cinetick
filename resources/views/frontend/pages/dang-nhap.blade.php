@extends('frontend.layouts.master')
@section('title', 'Đăng nhập')
@section('main')
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/dang-nhap.css') }}">
    <div class="sign section--bg" style="background: #e6e7e9; max-width: 100%; border-top: 1px solid;">
        <div class="contain er register" style="max-width: 100%;">
            <div class="row">
                <div class="col-md-3 register-left">
                    <img src="Content/img/logoCineTick.png" alt="Logo" />
                    <p>Đăng nhập với tài khoản của bạn!</p>
                </div>
                <div class="col-md-9 register-right">
                    <div class="tab-content">
                        <div class="tab-pane fade show active">
                            <h3 class="register-heading">Đăng nhập</h3>
                            <form id="loginForm" action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="row register-form">
                                    <div class="col-md-9">

                                        <div class="form-group">
                                            <input type="text" class="form-control" name="TenDN"
                                                placeholder="Email / Tên đăng nhập" required autocomplete="username">
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="MatKhau"
                                                    placeholder="Mật khẩu" required minlength="6" id="password-input"
                                                    autocomplete="new-password">
                                                <button type="button" id="toggle-password" tabindex="-1">
                                                    <i class="fa fa-eye" id="eye-icon"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <ul style="display: flex; gap: 10px; padding-left: 0; list-style: none;">
                                                <li><a href="{{ route('register.form.get') }}">Đăng ký</a></li>


                                                <li><a href="{{ route('quen-mat-khau.get') }}">/ Quên mật khẩu?</a></li>
                                            </ul>
                                        </div>

                                        <button type="submit" class=" btnRegister">Đăng nhập</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                            classes: 'redB'
                        }
                    }
                });
            });
        </script>
    @endif
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const passwordInput = document.getElementById('password-input');
            const togglePassword = document.getElementById('toggle-password');
            const eyeIcon = document.getElementById('eye-icon');
        
            togglePassword.addEventListener('click', function() {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = "password";
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });
        });
        </script>
@stop
