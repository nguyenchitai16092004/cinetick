
<?php $__env->startSection('title', 'CineTick - Đặt vé xem phim'); ?>
<?php $__env->startSection('main'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/dat-ve.css')); ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            <div><b>Tối đa:</b> 8 ghế/lần đặt</div>
                            <div><b>Ghế VIP:</b> +20% giá vé so với ghế thường</div>
                        </div>
                    </div>
                </div>
                <div class="screen-wrapper">
                    <img src="<?php echo e(asset('user/Content/img/img-screen.png')); ?>" alt="Screen" class="screen-image">
                    <div class="screen-text">Màn hình</div>
                </div>
                <div id="seatLayout" class="seat-container">
                    
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
                        <div class="movie-poster-wrapper">
                            <img src="<?php echo e($suatChieu->phim->HinhAnh ? asset('storage/' . $suatChieu->phim->HinhAnh) : asset('images/no-image.jpg')); ?>"
                                alt="<?php echo e($suatChieu->phim->TenPhim); ?>">
                            <span class="age-rating">
                                <?php echo e($suatChieu->phim->DoTuoi); ?>

                            </span>
                        </div>
                    </div>
                    <div class="movie-name">
                        <h3 class="movie-title"><?php echo e($suatChieu->phim->TenPhim); ?>

                        </h3>
                        <span class="badge badge-format"><?php echo e($suatChieu->phim->DoHoa); ?></span>
                    </div>
                    <p class="cinema-info"> <strong><?php echo e($suatChieu->rap->TenRap); ?></strong> - <?php echo e($suatChieu->rap->DiaChi); ?>

                    </p>
                    <p class="cinema-info"><strong><?php echo e($suatChieu->phongChieu->TenPhongChieu); ?></strong></p>

                    <p class="showtime-info">
                        <span>Suất: <?php echo e(substr($suatChieu->GioChieu, 0, 5)); ?> -
                            <?php echo e(ucfirst(\Carbon\Carbon::parse($suatChieu->NgayChieu)->translatedFormat('l, d/m/Y'))); ?></span>
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
                        <a href="<?php echo e(route('phim.chiTiet', ['slug' => $suatChieu->phim->Slug])); ?>" id="btn-back-link"
                            class="btn btn-back">Quay
                            lại</a>
                        <button id="btn-continue" class="btn btn-continue">Tiếp tục</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="form-chuyen-thanh-toan" method="GET" action="<?php echo e(route('dat-ve.thanh-toan')); ?>" style="display:none;">
        <input type="hidden" name="ID_SuatChieu" value="<?php echo e($suatChieu->ID_SuatChieu); ?>">
        <input type="hidden" name="selectedSeats" id="selectedSeatsInput">
    </form>

    
    <script>
        window.bookingData = {
            seatLayout: <?php echo json_encode($seatLayout, 15, 512) ?>,
            rowAisles: <?php echo json_encode($rowAisles, 15, 512) ?>,
            colAisles: <?php echo json_encode($colAisles, 15, 512) ?>,
            bookedSeats: <?php echo json_encode($bookedSeats, 15, 512) ?>,
            suatChieuId: <?php echo e($suatChieu->ID_SuatChieu); ?>,
            ticketPrice: <?php echo e($suatChieu->GiaVe); ?>,
            vipSurcharge: 20000,
            heldSeatsByOthers: <?php echo json_encode($heldSeatsByOthers ?? [], 15, 512) ?>
        };
        window.myHeldSeats = <?php echo json_encode($myHeldSeats ?? [], 15, 512) ?>;
        window.currentUserId = <?php echo e(Session::has('user_id') ? Session::get('user_id') : 'null'); ?>;
        window.csrfToken = "<?php echo e(csrf_token()); ?>";
        window.thanhToanUrl = "<?php echo e(route('thanh-toan')); ?>";
        console.log('window.myHeldSeats =', window.myHeldSeats);
    </script>
    <script src="<?php echo e(asset('backend/assets/js/booking-seat.js')); ?>" defer></script>
    <script src="<?php echo e(asset('user/Content/js/dat-ve.js')); ?>" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>


    
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
        var age = <?php echo json_encode($suatChieu->phim->DoTuoi, 15, 512) ?>;
        console.log('heldSeatsByOthers:', window.bookingData.heldSeatsByOthers);
        console.log('seatLayout:', window.bookingData.seatLayout);
        window.myHeldSeats = <?php echo json_encode($myHeldSeats ?? [], 15, 512) ?>;
        window.holdUntilMap = <?php echo json_encode($holdUntilMap ?? [], 15, 512) ?>;

        <?php if(!empty($showPopup) && !empty($popupMessage)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof $ !== 'undefined' && $.sweetModal) {
                    $.sweetModal({
                        title: "Thông báo",
                        content: "<?php echo e($popupMessage); ?>",
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
                    alert("<?php echo e($popupMessage); ?>");
                    window.location.href = "/";
                }
            });
        <?php endif; ?>
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/pages/dat-ve.blade.php ENDPATH**/ ?>