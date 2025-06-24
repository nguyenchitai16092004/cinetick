<link rel="stylesheet" href="{{ asset('frontend/Content/css/footer.css') }}">
<footer class="custom-footer">
    <div class="footer-main">
        <div class="footer-section">
            <h3>GIỚI THIỆU</h3>
            <ul>
                @foreach ($footerGioiThieu as $item)
                    <li>
                        <a href="{{ route('thongtincinetick.static', ['slug' => $item->Slug]) }}">
                            {{ $item->TieuDe }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="footer-section">
            <h3>CHÍNH SÁCH</h3>
            <ul>
                @foreach ($footerChinhSach as $item)
                    <li>
                        <a href="{{ route('thongtincinetick.static', ['slug' => $item->Slug]) }}">
                            {{ $item->TieuDe }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="footer-section">
            <h3>HỖ TRỢ</h3>
            <ul>
                <li><a href="{{ route('lien-he') }}">Liên hệ</a></li>

                </li>
            </ul>
        </div>
        <div class="footer-brand">
            <div class="footer-logo">
                <img src="{{ $thongTinTrangWeb->Logo ? asset('storage/' . $thongTinTrangWeb->Logo) : asset('images/no-image.jpg') }}"
                    alt="{{ $thongTinTrangWeb->TenWebsite }}" alt="Logo" />
            </div>
            <div class="footer-social">
                <a href="{{ $thongTinTrangWeb->Facebook }}"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ $thongTinTrangWeb->Youtube }}"><i class="fab fa-youtube"></i></a>
                <a href="{{ $thongTinTrangWeb->Instagram }}"><i class="fab fa-instagram"></i></a>
            </div>
            {{-- <div class="footer-cert">
                <img src="cert.svg" alt="Certification" />
                <img src="btn_dathongbao_bocongthuong.png" alt="Đã thông báo Bộ Công Thương" />
            </div> --}}
        </div>
    </div>
    <hr />
    <div class="footer-bottom">
        <div class="footer-bottom-logo">
            <img src="{{ $thongTinTrangWeb->Logo ? asset('storage/' . $thongTinTrangWeb->Logo) : asset('images/no-image.jpg') }}"
                alt="{{ $thongTinTrangWeb->TenWebsite }}" alt="Logo" />
        </div>
        <div class="footer-bottom-info">
            <div>
                <strong>{{ $thongTinTrangWeb->TenDonVi }}</strong>
            </div>
            <div>
                {{ $thongTinTrangWeb->DiaChi }}
            </div>
            <div>
                <div>
                    <i class="fa-solid fa-phone footer-icon"></i> {{ $thongTinTrangWeb->Hotline }} (9:00 - 22:00) -
                    <i class="fa-solid fa-envelope footer-icon"></i> {{ $thongTinTrangWeb->Email }}
                </div>
            </div>
        </div>
    </div>
</footer>
