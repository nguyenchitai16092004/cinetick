@extends('frontend.layouts.master')
@section('title', 'Đặt vé xem phim')
@section('main')
    <link rel="stylesheet" href="{{ asset('frontend/Content/css/dat-ve.css') }}">

    <div class="bg-gradient"></div>
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
            <div class="floating-elements">
                <div class="floating-circle"></div>
                <div class="floating-circle"></div>
                <div class="floating-circle"></div>
                <div class="floating-circle"></div>
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
                    <p class="cinema-info">{{ $suatChieu->rap->TenRap }} - {{ $suatChieu->rap->DiaChi }}</p>
                    <p class="showtime-info">
                        <span>Suất: {{ substr($suatChieu->GioChieu, 0, 5) }} -
                            {{ ucfirst(\Carbon\Carbon::parse($suatChieu->NgayChieu)->translatedFormat('l, d/m/Y')) }}</span>
                    </p>
                </div>

                <div class="ticket-summary">
                    <div class="ticket-item">
                        <div class="ticket-info">
                            <div class="seat-summary" style="margin-top: 5px; font-weight: bold;"></div>
                            <div style="display: flex; align-items: center;">
                                Ghế: <div class="seat-numbers" style="margin-left: 5px;"></div>
                            </div>
                        </div>
                        <div class="ticket-price"></div>
                    </div>

                    <div class="total-section">
                        <div class="total-title">Tổng cộng</div>
                        <div class="total-price">0 VNĐ</div>
                    </div>

                    <div class="button-group">
                        <a href="{{ route('phim.chiTiet', ['slug' => $suatChieu->phim->Slug]) }}" class="btn btn-back">Quay
                            lại</a>
                        <button id="btn-continue" class="btn btn-continue" disabled>Tiếp tục</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="form-chuyen-thanh-toan" method="GET" action="{{ route('dat-ve.thanh-toan') }}" style="display:none;">
        <input type="hidden" name="ID_SuatChieu" value="{{ $suatChieu->ID_SuatChieu }}">
        <input type="hidden" name="selectedSeats" id="selectedSeatsInput">
    </form>
    <script>
        document.getElementById('btn-continue').addEventListener('click', function(e) {
            let selectedSeats = window.selectedSeats || [];
            if (selectedSeats.length === 0) {
                showBookingNotification('Thông báo', 'Vui lòng chọn ít nhất 1 ghế!', 'warning');
                return;
            }

            // Kiểm tra logic chọn ghế ở đây
            if (!window.bookingApp.isValidSeatSelectionAll(selectedSeats)) {
                showBookingNotification(
                    'Thông báo',
                    'Việc chọn vị trí ghế của bạn không được để trống 1 ghế ở bên trái, giữa hoặc bên phải trên cùng hàng ghế mà bạn vừa chọn.',
                    'warning'
                );
                return;
            }

            // Lấy độ tuổi phim
            var age = "{{ $suatChieu->phim->DoTuoi }}";
            $.sweetModal({
                title: `<div style=" margin-bottom:8px;display:flex;justify-content:center;"><span style="background:#ff9800;color:#fff;padding:3px 7px;border-radius:6px;font-weight:100;">${age}</span> </div>
            <span style="color: #333; text-align: center; display: block; font-weight: bold;">Xác nhận mua vé cho người có độ tuổi phù hợp</span>`,
                content: `<div style="color:#4080FF;font-size:15px;margin-top:8px;font-style:italic;">
            Tôi xác nhận mua vé phim này cho người có độ tuổi từ <b>${age} tuổi trở lên</b> và đồng ý cung cấp giấy tờ tuỳ thân để xác minh độ tuổi.
        </div>`,
                icon: $.sweetModal.ICON_INFO,
                theme: $.sweetModal.THEME_DARK,
                buttons: {
                    'Từ chối': {
                        classes: 'grayB',
                        action: function() {},
                    },
                    'Xác nhận': {
                        classes: 'orangeB',
                        action: function() {
                            document.getElementById('selectedSeatsInput').value = selectedSeats.join(
                                ',');
                            document.getElementById('form-chuyen-thanh-toan').submit();
                        },
                    }
                }
            });
        });

        // Đảm bảo cập nhật window.selectedSeats mỗi khi chọn ghế (bổ sung vào JS render ghế)
        // window.selectedSeats = [...]; // cập nhật khi chọn ghế
    </script>
    {{-- Pass data to JavaScript --}}
    <script>
        // Global booking data for JavaScript
        window.bookingData = {
            seatLayout: @json($seatLayout),
            rowAisles: @json($rowAisles),
            colAisles: @json($colAisles),
            bookedSeats: @json($bookedSeats),
            suatChieuId: {{ $suatChieu->ID_SuatChieu }},
            ticketPrice: {{ $suatChieu->GiaVe }},
            vipSurcharge: 20000
        };

        // Route URLs
        window.thanhToanUrl = "{{ route('thanh-toan') }}";
    </script>
    <script>
        // console.log('Debug info:', {
        //     thanhToanUrl: "{{ route('thanh-toan') }}",
        //     csrfToken: "{{ csrf_token() }}",
        //     suatChieuId: {{ $suatChieu->ID_SuatChieu }}
        // });
    </script>
    {{-- Legacy support for existing JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Legacy variables for backward compatibility
            let seats = @json($seatLayout);
            let rowAisles = @json(json_decode($phongChieu->HangLoiDi ?: '[]')).map(Number);
            let colAisles = @json(json_decode($phongChieu->CotLoiDi ?: '[]')).map(Number);
            let seatCount = {{ $phongChieu->SoLuongGhe }};
            let bookedSeats = @json($bookedSeats);

            // console.log('Legacy data initialized:', {
            //     seats: seats,
            //     rowAisles: rowAisles,
            //     colAisles: colAisles,
            //     seatCount: seatCount,
            //     bookedSeats: bookedSeats
            // });

            // Handle showtime button clicks
            const timeButtons = document.querySelectorAll('.time-btn');
            const showtimeInfo = document.querySelector('.showtime-info');

            timeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    timeButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to clicked button
                    button.classList.add('active');

                    // Get showtime info
                    const gioChieu = button.getAttribute('data-gio');
                    const suatChieuId = button.getAttribute('data-id');
                    const ngayChieu =
                        "{{ ucfirst(\Carbon\Carbon::parse($suatChieu->NgayChieu)->translatedFormat('l, d/m/Y')) }}";

                    // Update showtime info display
                    if (showtimeInfo) {
                        showtimeInfo.innerHTML = `<span>Suất: ${gioChieu}</span> - ${ngayChieu}`;
                    }

                    // Redirect to new showtime if different
                    if (suatChieuId != {{ $suatChieu->ID_SuatChieu }}) {
                        const phimSlug = "{{ $suatChieu->phim->Slug }}";
                        const ngay = "{{ $suatChieu->NgayChieu }}";
                        const gio = gioChieu.replace(':', '-');

                        window.location.href = `/dat-ve/${phimSlug}/${ngay}/${gio}`;
                    }
                });
            });
        });
    </script>

    {{-- Load the enhanced booking seat JavaScript --}}
    <script src="{{ asset('backend/assets/js/booking-seat.js') }}" defer></script>

    {{-- SweetModal for notifications --}}
    <script>
        // Ensure SweetModal notification function is available
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

        // Make it globally available
        window.showBookingNotification = showBookingNotification;
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Subscribe to channel
        const channel = window.Echo.channel(`showtime.{{ $suatChieu->ID_SuatChieu }}`);

        // Listen for seat held event
        channel.listen('SeatHeld', (data) => {
            const seat = data.seat;
            const userId = data.userId;
            const heldUntil = new Date(data.heldUntil);
            const currentUserId = {{ session('user_id') ?? 'null' }};

            updateSeatStatus(seat, 'held', heldUntil, userId === currentUserId);
        });

        // Listen for seat released event
        channel.listen('SeatReleased', (data) => {
            const seat = data.seat;
            updateSeatStatus(seat, 'available');
        });

        // Function to update seat status
        function updateSeatStatus(seat, status, heldUntil = null, isCurrentUser = false) {
            const seatElement = document.querySelector(`[data-seat="${seat}"]`);
            if (!seatElement) return;

            seatElement.classList.remove('available', 'held', 'booked');
            seatElement.classList.add(status);

            if (status === 'held') {
                if (isCurrentUser) {
                    seatElement.setAttribute('title', `Giữ đến ${heldUntil.toLocaleTimeString()}`);
                    startHoldTimer(seat, heldUntil);
                } else {
                    seatElement.setAttribute('title', 'Đang được giữ bởi người khác');
                    seatElement.disabled = true;
                }
            }
        }

        // Function to start hold timer
        function startHoldTimer(seat, heldUntil) {
            const timer = setInterval(() => {
                const now = new Date();
                if (now >= heldUntil) {
                    clearInterval(timer);
                    releaseSeat(seat);
                    if (window.location.pathname.includes('thanh-toan')) {
                        window.location.href = '/';
                    }
                }
            }, 1000);
        }

        // Function to hold seat
        async function holdSeat(seat) {
            try {
                const response = await fetch('/hold-seat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        showtimeId: {{ $suatChieu->ID_SuatChieu }},
                        seat: seat
                    })
                });

                const data = await response.json();
                if (!data.success) {
                    showBookingNotification('Thông báo', data.error, 'warning');
                    return false;
                }
                return true;
            } catch (error) {
                console.error('Error holding seat:', error);
                return false;
            }
        }

        // Function to release seat
        async function releaseSeat(seat) {
            try {
                await fetch('/release-seat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        showtimeId: {{ $suatChieu->ID_SuatChieu }},
                        seat: seat
                    })
                });
            } catch (error) {
                console.error('Error releasing seat:', error);
            }
        }

        // Update seat click handlers
        document.querySelectorAll('.seat').forEach(seat => {
            seat.addEventListener('click', async function() {
                const seatName = this.dataset.seat;
                if (this.classList.contains('held') || this.classList.contains('booked')) {
                    return;
                }

                const success = await holdSeat(seatName);
                if (success) {
                    updateSeatStatus(seatName, 'held', new Date(Date.now() + 6 * 60 * 1000), true);
                }
            });
        });

        // Handle page unload
        window.addEventListener('beforeunload', function() {
            document.querySelectorAll('.seat.held').forEach(seat => {
                releaseSeat(seat.dataset.seat);
            });
        });
    });
    </script>
@stop
