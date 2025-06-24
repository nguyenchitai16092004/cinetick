@extends('frontend.layouts.master')
@section('title', 'CineTick - Liên hệ')
@section('main')
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/lien-he.css') }}">


    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="contact-container">
        <!-- Left: Social & Title -->
        <div class="left-col">
            <div class="header">
                <h1>LIÊN HỆ VỚI CHÚNG TÔI</h1>
            </div>
            <div>
                <a href="{{ $thongTinTrangWeb->Facebook }}">
                    <div class="social-box facebook">
                        <img src="{{ asset('frontend/Content/img/fb.png') }}" alt="Facebook" />
                        <span class="social-label">FACEBOOK</span>
                    </div>
                </a>
                <a href="{{ $thongTinTrangWeb->Instagram }}">
                    <div class="social-box instagram">
                        <span class="social-label">INSTAGRAM</span>
                        <img src="{{ asset('frontend/Content/img/instagram.png') }}" alt="Instagram" />
                    </div>
                </a>
            </div>


        </div>
        <!-- Right: Info & Form -->
        <div class="right-col">
            <div class="contact-info-title">THÔNG TIN LIÊN HỆ</div>
            <ul class="info-list">
                <li>
                    <i class="fa fa-envelope"></i> {{ $thongTinTrangWeb->Email }}
                </li>
                <li>
                    <i class="fa fa-phone"></i> {{ $thongTinTrangWeb->Hotline }}
                </li>
                <li>
                    <i class="fa-solid fa-location-dot"></i> {{ $thongTinTrangWeb->DiaChi }}
                </li>
            </ul>
            <form class="contact-form" id="contactForm" autocomplete="off" method="POST"
                action="{{ route('lien-he.gui-lien-he') }}" enctype="multipart/form-data">
                @csrf
                <input type="text" name="name" placeholder="Họ và tên" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="tel" name="phone" placeholder="Số điện thoại" pattern="[0-9]{8,11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,11);" required>
                <input type="text" name="subject" placeholder="Tiêu đề" required>
                <textarea name="message" placeholder="Thông tin liên hệ hoặc phản ánh" required></textarea>
                <div class="upload-preview">
                    <label for="imageUpload" style="font-weight:500;color:#fff;font-size:16px;margin-bottom:0;">
                        Ảnh minh họa:
                    </label>
                    <input type="file" id="imageUpload" name="image" accept="image/*" style="margin-left:14px;">
                </div>
                <img id="uploadedImage" alt="Ảnh đã upload" style="display:none;" />
                <div class="g-recaptcha" data-sitekey="6Ldai2grAAAAAFzqDGUH58U8bQji9SJTuGTXeyTe"></div>
                <button type="submit" class="submit-btn"><span>GỬI NGAY</span></button>
            </form>
        </div>
    </div>
    <script src="{{ asset('frontend/Content/js/lien-he.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->has('captcha'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi xác thực',
                text: '{{ $errors->first('captcha') }}',
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: '{{ session('success') }}',
            });
        </script>
    @endif
    @if (session('email_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi gửi email',
            text: '{{ session('email_error') }}',
        });
    </script>
@endif
@stop
