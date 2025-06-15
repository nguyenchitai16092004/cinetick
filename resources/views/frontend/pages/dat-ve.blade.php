@extends('frontend.layouts.master')
@section('title', 'Đặt vé xem phim')
@section('main')
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/dat-ve.css') }}">

    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="boking-container">
        <!-- Booking Steps -->
        <div class="booking-steps">
            <div class="step active">Chọn phim / Rạp / Suất</div>
            <div class="step active">Chọn ghế</div>
            <div class="step">Thanh toán</div>
            <div class="step">Xác nhận</div>
        </div>
        <div class="bg-gradient"></div>
        <!-- Main Content -->
        <div class="content">
            <!-- Left Panel - Seating -->
            <div class="left-panel">
                <div class="booking-note-pro" style="display: flex; align-items: center; gap: 10px;">
                    <div class="booking-note-icon-tooltip" style="position: relative;">
                        <i class="fa-solid fa-circle-info" style="font-size: 1.5rem; color: #1976d2; cursor: pointer;"></i>
                        <div class="booking-note-tooltip">
                            <div><b>Số ghế:</b> {{ $phongChieu->SoLuongGhe }} ghế/phòng</div>
                            <div><b>Tối đa:</b> 8 ghế/lần đặt</div>
                            <div><b>Ghế VIP:</b> +20% giá vé so với ghế thường</div>
                        </div>
                    </div>
                </div>
                <div class="screen-wrapper" style="display: flex; flex-direction: column; align-items: center;">
                    <img src="{{ asset('frontend/Content/img/img-screen.png') }}" alt="Screen" class="screen-image">
                    <div class="screen-text">Màn hình</div>
                </div>
                <div id="seatLayout" class="seat-container">
                    {{-- Sơ đồ ghế được render bằng JavaScript --}}
                </div>
                <div class="seat-legend mt-3">
                    <div class="legend-item">
                        <div class="legend-box legend-normal"></div>
                        <span>Ghế thường</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box legend-vip"></div>
                        <span>Ghế VIP</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box choosing"></div>
                        <span>Ghế đang chọn</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box legend-booked"></div>
                        <span>Ghế đã đặt</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box legend-disabled"></div>
                        <span>Ghế đang bảo trì</span>
                    </div>
                </div>
            </div>
  
            <!-- Right Panel - Movie Info & Summary -->
            <div class="right-panel">
                <div class="movie-info">
                    <div class="movie-poster">
                        <img src="{{ $suatChieu->phim->HinhAnh ? asset('storage/' . $suatChieu->phim->HinhAnh) : asset('images/no-image.jpg') }}"
                            alt="{{ $suatChieu->phim->TenPhim }}">
                        <div class="movie-badge">{{ $suatChieu->phim->DoTuoi }}</div>
                    </div>
                    <div class="movie-name">
                        <h3 class="movie-title">{{ $suatChieu->phim->TenPhim }} - {{ $suatChieu->phim->DoHoa }} </h3><span
                            class="age-rating">{{ $suatChieu->phim->DoTuoi }}</span>
                    </div>
                    <p class="cinema-info"> <strong>{{ $suatChieu->rap->TenRap }}</strong> - {{ $suatChieu->rap->DiaChi }}</p>
                    <p class="cinema-info"><strong>{{ $suatChieu->phongChieu->TenPhongChieu }}</strong></p>
                    
                    <p class="showtime-info">
                        <span>Suất: {{ substr($suatChieu->GioChieu, 0, 5) }} -
                            {{ ucfirst(\Carbon\Carbon::parse($suatChieu->NgayChieu)->translatedFormat('l, d/m/Y')) }}</span>
                    </p>
                </div>
               
                <div class="ticket-summary">
                    <div id="seat-hold-timer" style="margin-bottom:10px; color:#1976d2; font-weight:bold; display:none;">
                        <i class="fa-regular fa-clock"></i>
                        <span id="seat-hold-timer-text" >06:00</span>
                    </div>
                    <div class="ticket-item">
                        <div class="ticket-info">
                            <div class="seat-summary" style="margin-top: 5px; font-weight: bold; color: #000"></div>
                            <div style="display: flex; align-items: center;">
                                Ghế: <div class="seat-numbers" style="margin-left: 5px;"></div>
                            </div>
                        </div>
                        <div class="ticket-price"></div>
                    </div>

                    <div class="total-section">
                        <div class="total-title">Tổng cộng</div>
                        <div class="total-price">0 đ</div>
                    </div>

                    <div class="button-group">
                        <a href="{{ route('phim.chiTiet', ['slug' => $suatChieu->phim->Slug]) }}" id="btn-back-link" class="btn btn-back">Quay
                            lại</a>
                        <button id="btn-continue" class="btn btn-continue" >Tiếp tục</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="form-chuyen-thanh-toan" method="GET" action="{{ route('dat-ve.thanh-toan') }}" style="display:none;">
        <input type="hidden" name="ID_SuatChieu" value="{{ $suatChieu->ID_SuatChieu }}">
        <input type="hidden" name="selectedSeats" id="selectedSeatsInput">
    </form>

    {{-- Pass data to JavaScript - ĐẶT TRƯỚC CÁC FILE JS RENDER GHẾ --}}
    <script>
        window.bookingData = {
            seatLayout: @json($seatLayout),
            rowAisles: @json($rowAisles),
            colAisles: @json($colAisles),
            bookedSeats: @json($bookedSeats),
            suatChieuId: {{ $suatChieu->ID_SuatChieu }},
            ticketPrice: {{ $suatChieu->GiaVe }},
            vipSurcharge: 20000
        };
        window.myHeldSeats = @json($myHeldSeats ?? []);
        window.currentUserId = {{ Session::has('user_id') ? Session::get('user_id') : 'null' }};
        window.csrfToken = "{{ csrf_token() }}";
        window.thanhToanUrl = "{{ route('thanh-toan') }}";
        console.log('window.myHeldSeats =', window.myHeldSeats);
    </script>
    <script src="{{ asset('backend/assets/js/booking-seat.js') }}" defer></script>
    <script src="{{ asset('frontend/Content/js/dat-ve.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
    

    {{-- SweetModal for notifications --}}
    <script>
        function showBookingNotification(title, message, type = 'info') {
            if (typeof $ !== 'undefined' && $.sweetModal) {
                $.sweetModal({
                    content: message,
                    title: title,
                    icon: type === 'warning' ? $.sweetModal.ICON_WARNING : $.sweetModal.ICON_INFO,
                    theme: $.sweetModal.THEME_DARK,
                    buttons: {
                        'OK': {
                            classes: 'redB'
                        }
                    }
                });
            } else {
                alert(title + ': ' + message);
            }
        }
        window.showBookingNotification = showBookingNotification;
    </script>
    <script>
         var age = @json($suatChieu->phim->DoTuoi);
    </script>
@stop
