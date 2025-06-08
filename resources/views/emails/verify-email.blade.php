<h2>Chào {{ $TenDN }},</h2>
<p>Vui lòng bấm vào liên kết dưới đây để xác nhận tài khoản của bạn:</p>
<p>
    <a href="{{ route('verify.account', $token) }}">
        Xác nhận tài khoản
    </a>
</p>
<p>Nếu bạn không đăng ký tài khoản, vui lòng bỏ qua email này.</p>
