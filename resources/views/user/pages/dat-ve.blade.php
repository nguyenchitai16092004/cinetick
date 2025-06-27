@extends('user.layouts.master')
@section('title', 'CineTick - Đặt vé xem phim')
@section('main')
    <link rel="stylesheet" href="{{ asset('user/Content/css/dat-ve.css') }}">
    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="boking-container">
        <div class="steps-indicator">
            <div class="step active" id="step-1">
                <div class="step-number">1</div>
                <div class="step-label">Chọn phim/Chọn suất</div>
            </div>
            <div class="step-separator active" id="separator-1"></div>
            <div class="step active" id="step-2">
                <div class="step-number">2</div>
                <div class="step-label">Chọn ghế</div>
            </div>
            <div class="step-separator active" id="separator-2"></div>
            <div class="step" id="step-3">
                <div class="step-number">3</div>
                <div class="step-label">Thanh toán</div>
            </div>
            <div class="step-separator" id="separator-3"></div>
            <div class="step" id="step-4">
                <div class="step-number">4</div>
                <div class="step-label">Xác nhận</div>
            </div>
        </div>
        <div id="seat-hold-timer">
            <span>GHẾ GIỮ TRONG: </span>
            <span id="seat-hold-timer-text"></span>
        </div>
        <!-- Main Content -->
        <div class="content">
            <!-- Left Panel - Seating -->
            <div class="left-panel">
                <div class="booking-note-pro">
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
                    <img src="{{ asset('user/Content/img/img-screen.png') }}" alt="Screen" class="screen-image">
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
                    </div>
                    <div class="movie-name">
                        <h3 class="movie-title">{{ $suatChieu->phim->TenPhim }} - {{ $suatChieu->phim->DoHoa }} </h3><span
                            class="age-rating">{{ $suatChieu->phim->DoTuoi }}</span>
                    </div>
                    <p class="cinema-info"> <strong>{{ $suatChieu->rap->TenRap }}</strong> - {{ $suatChieu->rap->DiaChi }}
                    </p>
                    <p class="cinema-info"><strong>{{ $suatChieu->phongChieu->TenPhongChieu }}</strong></p>

                    <p class="showtime-info">
                        <span>Suất: {{ substr($suatChieu->GioChieu, 0, 5) }} -
                            {{ ucfirst(\Carbon\Carbon::parse($suatChieu->NgayChieu)->translatedFormat('l, d/m/Y')) }}</span>
                    </p>
                </div>

                <div class="ticket-summary">

                    <div class="ticket-item">
                        <div class="ticket-info">
                            <div class="seat-summary"></div>
                            <div style="display: flex; align-items: center;">
                                <span>Ghế:</span>
                                <div class="seat-numbers" style="margin-left: 5px;"></div>
                            </div>
                        </div>
                        <div class="ticket-price"></div>
                    </div>

                    <div class="total-section">
                        <div class="total-title">Tổng cộng</div>
                        <div class="total-price">0 đ</div>
                    </div>

                    <div class="button-group">
                        <a href="{{ route('phim.chiTiet', ['slug' => $suatChieu->phim->Slug]) }}" id="btn-back-link"
                            class="btn btn-back">Quay
                            lại</a>
                        <button id="btn-continue" class="btn btn-continue">Tiếp tục</button>
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
            vipSurcharge: 20000,
            heldSeatsByOthers: @json($heldSeatsByOthers ?? [])
        };
        window.myHeldSeats = @json($myHeldSeats ?? []);
        window.currentUserId = {{ Session::has('user_id') ? Session::get('user_id') : 'null' }};
        window.csrfToken = "{{ csrf_token() }}";
        window.thanhToanUrl = "{{ route('thanh-toan') }}";
        console.log('window.myHeldSeats =', window.myHeldSeats);
    </script>
    <script src="{{ asset('backend/assets/js/booking-seat.js') }}" defer></script>
    <script src="{{ asset('user/Content/js/dat-ve.js') }}" defer></script>
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
        var age = @json($suatChieu->phim->DoTuoi);
        console.log('heldSeatsByOthers:', window.bookingData.heldSeatsByOthers);
        console.log('seatLayout:', window.bookingData.seatLayout);
        window.myHeldSeats = @json($myHeldSeats ?? []);
        window.holdUntilMap = @json($holdUntilMap ?? []);
        
        @if (!empty($showPopup) && !empty($popupMessage))
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof $ !== 'undefined' && $.sweetModal) {
                    $.sweetModal({
                        title: "Thông báo",
                        content: "{{ $popupMessage }}",
                        icon: $.sweetModal.ICON_WARNING,
                        theme: $.sweetModal.THEME_DARK,
                        buttons: {
                            'OK': {
                                classes: 'redB',
                                action: function() {
                                    window.location.href = "/";
                                }
                            }
                        }
                    });
                } else {
                    alert("{{ $popupMessage }}");
                    window.location.href = "/";
                }
            });
        @endif
    </script>

@stop
