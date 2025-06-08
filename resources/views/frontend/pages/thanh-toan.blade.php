@extends('frontend.layouts.master')
@section('title', 'Thanh toán vé xem phim')
@section('main')

    <link rel="stylesheet" href="{{ asset('frontend/Content/css/thanh-toan.css') }}">

    <div class="ctn-thanh-toan">
        <div class="left-column">
            <div class="booking-timer">
                Thời gian giữ ghế: <span class="timer">06:00</span>
            </div>

            <div class="movie-details">
                <div class="movie-poster">
                    <img src="{{ $suatChieu->phim->HinhAnh ? asset('storage/' . $suatChieu->phim->HinhAnh) : asset('images/no-image.jpg') }}" alt="{{ $suatChieu->phim->TenPhim }}">
                </div>
                <div class="movie-info">
                    <h3>{{ $suatChieu->phim->TenPhim }}</h3>
                    <div class="movie-meta">{{ $suatChieu->phim->DoHoa }}
                        <span class="age-rating">{{ $suatChieu->phim->DoTuoi }}</span>
                    </div>
                </div>
            </div>

            <div class="ticket-details">
                <div>
                    <div>{{ $suatChieu->rap->TenRap }} - {{ $suatChieu->rap->DiaChi }}</div>
                    <div> Suất:
                        <strong>{{ substr($suatChieu->GioChieu, 0, 5) }} -
                            {{ ucfirst(\Carbon\Carbon::parse($suatChieu->NgayChieu)->translatedFormat('l, d/m/Y')) }}</strong>
                    </div>
                    <strong>
                        <div>{{ $suatChieu->phongChieu->TenPhongChieu ?? '' }}</div>
                    </strong>
                </div>
            </div>

            <div class="ticket-details">
                <div class="ticket-row" style="display: flex; justify-content: space-between;">
                    <div><strong><span>{{ count($selectedSeats) }}x Vé {{ $suatChieu->phim->DoHoa }}</span></strong></div>
                    <div><strong> {{ number_format($totalPrice, 0, ',', '.') }} VNĐ</strong>
                    </div>
                </div>
                <div style="margin-top: 2px;">
                  
                    <br>
                    Ghế:
                    <strong>
                        {{-- Show từng ghế, phân biệt VIP/Thường và giá --}}
                        @foreach($seatDetails as $detail)
                        {{ $detail['TenGhe'] }} ({{ $detail['LoaiGhe'] }}){{ !$loop->last ? ', ' : '' }}
                    @endforeach
                    </strong>
                </div>
            </div>

            <div class="total-row">
                <div>Tổng cộng</div>
                <div class="total-price">
                    {{ number_format($totalPrice, 0, ',', '.') }} VNĐ
                </div>
            </div>
        </div>

        <div class="right-column">
            <div class="steps">
                <div class="step active">Chọn phim / Rạp / Suất</div>
                <div class="step active">Chọn ghế</div>
                <div class="step active">Thanh toán</div>
                <div class="step">Xác nhận</div>
            </div>

            <div class="promotion-section">
                <h2>Khuyến mãi</h2>
                <div class="promo-code">
                    <input type="text" placeholder="Mã khuyến mãi">
                    <button>Áp Dụng</button>
                </div>
            </div>

            <div class="payment-method-section">
                <h2>Phương thức thanh toán</h2>
                <div class="payment-options">
                    <div class="payment-option">
                        <input type="radio" name="payment" id="payos" checked>
                        <img id="payos-logo" src="/frontend/Content/img/payos.jpg" alt="payos">
                        <div class="payment-option-text payos-promo">PayOS</div>
                    </div>
                </div>
                <div class="disclaimer">
                    (*) Bằng việc click vào THANH TOÁN bên phải, bạn đã xác nhận hiểu rõ các
                    <a href="{{ route('chinh-sach.thanh-toan') }}">Chính sách thanh toán</a> của CineTick.
                </div>
            </div>

            <form id="paymentForm" method="POST" action="{{ route('payment') }}">
                @csrf
                <input type="hidden" name="ten_khach_hang" value="{{ session('user_fullname') ?? '' }}">
                <input type="hidden" name="email" value="{{ session('user_email') ?? '' }}">
                <input type="hidden" name="selectedSeats" value="{{ implode(',', $selectedSeats) }}">
                <input type="hidden" name="ID_SuatChieu" value="{{ $suatChieu->ID_SuatChieu }}">
                <input type="hidden" name="paymentMethod" value="PAYOS">
            </form>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="action-buttons">
                <button type="button" class="back-button" onclick="window.history.back()">Quay lại</button>
                <button type="button" class="confirm-button" id="payos-submit-btn">Thanh Toán</button>
            </div>
        </div>
    </div>

    {{-- PayOS script --}}
    <script src="https://cdn.payos.vn/payos-checkout/v1/stable/payos-initialize.js"></script>
    <script>
        // Đồng hồ đếm ngược 6 phút
        let timeLeft = 360;

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            document.querySelector('.timer').textContent =
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            if (timeLeft > 0) {
                timeLeft--;
                setTimeout(updateTimer, 1000);
            } else {
                alert('Thời gian giữ ghế đã hết!');
                window.location.href = "{{ route('home') }}";
            }
        }
        updateTimer();

    </script>

    <div id="paymentConfirmPopup" class="payment-confirm-popup">
        <div class="payment-confirm-content">
            <div class="payment-confirm-header">
                <h2>Xác nhận thanh toán</h2>
                <button class="close-popup" onclick="closePaymentPopup()">&times;</button>
            </div>
            
            <div class="movie-info-with-poster">
                <div class="movie-poster-popup">
                    <img src="{{ $suatChieu->phim->HinhAnh ? asset('storage/' . $suatChieu->phim->HinhAnh) : asset('images/no-image.jpg') }}" 
                         alt="{{ $suatChieu->phim->TenPhim }}">
                </div>
                <div class="movie-info-popup">
                    <h3>{{ $suatChieu->phim->TenPhim }}</h3>
                    <div class="payment-info-row">
                        <span class="label">Đồ họa:</span>
                        <span class="value">{{ $suatChieu->phim->DoHoa }}</span>
                    </div>
                    <div class="payment-info-row">
                        <span class="label">Độ tuổi:</span>
                        <span class="value">{{ $suatChieu->phim->DoTuoi }}</span>
                    </div>
                </div>
            </div>

            <div class="payment-info-grid">
                <div class="payment-info-section">
                    <h3>Thông tin rạp chiếu</h3>
                    <div class="payment-info-row">
                        <span class="label">Tên rạp:</span>
                        <span class="value">{{ $suatChieu->rap->TenRap }}</span>
                    </div>
                    <div class="payment-info-row">
                        <span class="label">Địa chỉ:</span>
                        <span class="value">{{ $suatChieu->rap->DiaChi }}</span>
                    </div>
                </div>

                <div class="payment-info-section">
                    <h3>Thông tin suất chiếu</h3>
                    <div class="payment-info-row">
                        <span class="label">Ngày chiếu:</span>
                        <span class="value">{{ \Carbon\Carbon::parse($suatChieu->NgayChieu)->format('d/m/Y') }}</span>
                    </div>
                    <div class="payment-info-row">
                        <span class="label">Giờ chiếu:</span>
                        <span class="value">{{ substr($suatChieu->GioChieu, 0, 5) }}</span>
                    </div>
                    <div class="payment-info-row">
                        <span class="label">Phòng chiếu:</span>
                        <span class="value">{{ $suatChieu->phongChieu->TenPhongChieu }}</span>
                    </div>
                </div>

                <div class="payment-info-section">
                    <h3>Thông tin vé</h3>
                    <div class="payment-info-row">
                        <span class="label">Ghế đã chọn:</span>
                        <span class="value">
                            @foreach($seatDetails as $detail)
                                {{ $detail['TenGhe'] }} ({{ $detail['LoaiGhe'] }}){{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </span>
                    </div>
                    <div class="payment-info-row">
                        <span class="label">Số lượng vé:</span>
                        <span class="value">{{ count($selectedSeats) }} vé</span>
                    </div>
                </div>

                <div class="payment-info-section">
                    <h3>Thông tin thanh toán</h3>
                    <div class="payment-info-row">
                        <span class="label">Phương thức thanh toán:</span>
                        <span class="value">PayOS</span>
                    </div>
                    <div class="payment-info-row">
                        <span class="label">Tổng tiền:</span>
                        <span class="value" style="color: #f7941d; font-weight: bold;">{{ number_format($totalPrice, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>
            </div>

            <div class="payment-note">
                Vui lòng thanh toán trước khi thời gian giữ ghế kết thúc.</br> Đội ngũ CineTick xin cảm ơn!
            </div>

            <div class="payment-confirm-actions">
                <button class="payment-confirm-btn cancel-btn" onclick="closePaymentPopup()">Hủy</button>
                <button class="payment-confirm-btn confirm-btn" onclick="proceedToPayment()">Xác nhận thanh toán</button>
            </div>
        </div>
    </div>

    <script>
        function showPaymentPopup() {
            document.getElementById('paymentConfirmPopup').style.display = 'flex';
        }

        function closePaymentPopup() {
            document.getElementById('paymentConfirmPopup').style.display = 'none';
        }

        function proceedToPayment() {
            const form = document.getElementById('paymentForm');
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.checkoutUrl) {
                    window.location.href = data.checkoutUrl;
                } else if (data.error) {
                    alert('Lỗi: ' + data.error);
                } else {
                    alert('Không thể tạo đơn hàng. Vui lòng thử lại.');
                }
            })
            .catch(err => {
                alert('Lỗi khi kết nối tới máy chủ.');
                console.error(err);
            });
        }

        // Replace the old click handler with the new one
        document.getElementById('payos-submit-btn').addEventListener('click', function(e) {
            e.preventDefault();
            showPaymentPopup();
        });

        // Close popup when clicking outside
        document.getElementById('paymentConfirmPopup').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentPopup();
            }
        });
    </script>
@stop
