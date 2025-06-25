@extends('user.layouts.master')
@section('title', 'CineTick - Quên mật khẩu')
@section('main')
    <link rel="stylesheet" href="{{ asset('user/Content/css/quen-mat-khau.css') }}">
    <div class="forgot-outer">
        <div class="forgot-center-box">
            <h1 class="forgot-title">QUÊN MẬT KHẨU</h1>
            <div class="forgot-desc">
                Nhập địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn<br>
                hướng dẫn để tạo mật khẩu mới
            </div>
            <form class="forgot-form" id="forgotForm" autocomplete="off" onsubmit="return false;">
                <input type="email" id="fgEmail" placeholder="Email" required>
                <button type="button" class="btnRegister" onclick="forgotPass()">GỬI MẬT KHẨU MỚI</button>
            </form>
            <div id="message" style="margin-top:22px;font-family:Oswald,sans-serif;font-size:1.1rem;font-weight:600;">
            </div>
        </div>
    </div>
    <script>
        function forgotPass() {
            var email = $("#fgEmail").val().trim();
            var msg = $("#message");
            msg.css("color", "#f8e12f").text('');
            if (email === "" || !/^[^@]+@[^@]+\.[^@]+$/.test(email)) {
                msg.css("color", "#ff6060").text('Vui lòng nhập địa chỉ email hợp lệ!');
                return false;
            }
            msg.text('Đang xử lý...');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = JSON.stringify({
                email: email,
                _token: $('meta[name="csrf-token"]').attr('content')
            });
            $.ajax({
                url: "{{ route('quen-mat-khau.post') }}",
                type: "POST",
                data: data,
                traditional: true,
                datatype: "json",
                contentType: 'application/json; charset=utf-8',
                success: function(result) {
                    if (result === "true" || result === true) {
                        msg.css({
                            "color": "#6aff97",
                            "font-family": "'Josefin Sans', sans-serif"
                        });
                        msg.html(
                            'Vui lòng kiểm tra email để nhận mật khẩu mới.<br>Và đăng nhập <a href="{{ route('login.form') }}" style="color:#ffe440; font-family:\'Josefin Sans\', sans-serif;">tại đây</a>'
                            );
                    } else {
                        msg.css({
                            "color": "#ff6060",
                            "font-family": "'Josefin Sans', sans-serif"
                        }).text(result);
                    }
                },
                error: function(xhr) {
                    msg.css("color", "#ff6060").text(xhr.responseText || 'Có lỗi xảy ra!');
                }
            });
        }
    </script>
@endsection
