
<?php $__env->startSection('title', 'CineTick - Thanh toán vé xem phim'); ?>
<?php $__env->startSection('main'); ?>

    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/thanh-toan.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/phim.css')); ?>">
    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="ctn-thanh-toan">
        <div class="left-column">
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
                <div class="step active" id="step-3">
                    <div class="step-number">3</div>
                    <div class="step-label">Thanh toán</div>
                </div>
                <div class="step-separator active" id="separator-3"></div>
                <div class="step" id="step-4">
                    <div class="step-number">4</div>
                    <div class="step-label">Suất</div>
                </div>
            </div>

            <div class="promotion-section">
                <h2>Khuyến mãi</h2>
                <div class="promo-code">
                    <input type="text" placeholder="Mã khuyến mãi" id="promoCodeInput">
                    <button type="button" id="promoApplyBtn">Áp Dụng</button>
                </div>
                <div id="promo-error" style="color:red; margin-top:5px;"></div>
                <div id="promo-success" style="color:green; margin-top:5px;"></div>
            </div>

            <div class="payment-method-section">
                <h2>Phương thức thanh toán</h2>
                <div class="payment-options">
                    <div class="payment-option">
                        <input type="radio" name="payment" id="payos" checked>
                        <img id="payos-logo" src="/user/Content/img/Casso-payOSLogo-1.svg" alt="payos">
                        <div class="payment-option-text payos-promo">PayOS</div>
                    </div>
                </div>
                <div class="disclaimer">
                    (*) Bằng việc click vào THANH TOÁN bên phải, bạn đã xác nhận hiểu rõ các
                    <a href="<?php echo e(url('thong-tin-cinetick/chinh-sach-thanh-toan')); ?>">Chính sách thanh toán</a> của CineTick.
                </div>
            </div>

            <form id="paymentForm" method="POST" action="<?php echo e(route('payment')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="ten_khach_hang" value="<?php echo e(session('user_fullname') ?? ''); ?>">
                <input type="hidden" name="email" value="<?php echo e(session('user_email') ?? ''); ?>">
                <input type="hidden" name="selectedSeats" value="<?php echo e(implode(',', $selectedSeats)); ?>">
                <input type="hidden" name="ID_SuatChieu" value="<?php echo e($suatChieu->ID_SuatChieu); ?>">
                <input type="hidden" name="paymentMethod" value="PAYOS">
                <input type="hidden" name="seatDetails" id="seatDetailsInput">
                <input type="hidden" name="ma_khuyen_mai" id="maKhuyenMaiInput">
                <input type="hidden" name="so_tien_giam" id="soTienGiamInput">
                <input type="hidden" name="tong_tien_sau_giam" id="tongTienSauGiamInput">
            </form>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <div class="right-column">
            <div class="seat-hold-timer">
                <span>GIỮ GHẾ TRONG: </span>
                <span class="timer pay-hold-timer-text"></span>
            </div>
            <div class="movie-details">
                <div class="movie-poster">
                    <img src="<?php echo e($suatChieu->phim->HinhAnh ? asset('storage/' . $suatChieu->phim->HinhAnh) : asset('images/no-image.jpg')); ?>"
                        alt="<?php echo e($suatChieu->phim->TenPhim); ?>">
                </div>
                <div class="movie-info">
                    <h3><?php echo e($suatChieu->phim->TenPhim); ?></h3>
                    <div class="movie-meta">
                        <span class="badge badge-format"><?php echo e($suatChieu->phim->DoHoa); ?></span>
                        <span class="age-rating"><?php echo e($suatChieu->phim->DoTuoi); ?></span>
                    </div>
                </div>
            </div>

            <div class="ticket-details">
                <div>
                    <div class="ticket-details-local"><?php echo e($suatChieu->rap->TenRap); ?> - <?php echo e($suatChieu->rap->DiaChi); ?></div>
                    <div class="ticket-details-date"> Suất:
                        <strong><?php echo e(substr($suatChieu->GioChieu, 0, 5)); ?> -
                            <?php echo e(ucfirst(\Carbon\Carbon::parse($suatChieu->NgayChieu)->translatedFormat('l, d/m/Y'))); ?></strong>
                    </div>
                    <strong>
                        <div><?php echo e($suatChieu->phongChieu->TenPhongChieu ?? ''); ?></div>
                    </strong>
                </div>
            </div>

            <div class="ticket-details">
                <div class="ticket-row">
                    <div class="ticket-row-seated"><strong>
                        <span><?php echo e(count($selectedSeats)); ?>x Vé
                                <?php echo e($suatChieu->phim->DoHoa); ?></span></strong></div>
                    <div><strong> <?php echo e(number_format($totalPrice, 0, ',', '.')); ?> đ</strong></div>
                </div>
                <div class="ticket-row-title">
                    <br>
                    Ghế:
                    <strong>
                        <?php $__currentLoopData = $seatDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($detail['TenGhe']); ?> (<?php echo e($detail['LoaiGhe']); ?>)<?php echo e(!$loop->last ? ', ' : ''); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </strong>
                </div>
            </div>

            <div class="total-row" style="display: flex; justify-content: space-between;">
                <div>Giảm giá</div>
                <div class="discount-amount" id="discountAmount">0 đ</div>
            </div>
            <div class="total-row" style="display: flex; justify-content: space-between;">
                <div>Tổng cộng</div>
                <div class="total-price" id="totalPrice"><?php echo e(number_format($totalPrice, 0, ',', '.')); ?> đ</div>
            </div>
            <div class="action-buttons">
                <button type="button" class="back-button"
                    onclick="window.location.href='/dat-ve/<?php echo e($suatChieu->phim->Slug); ?>/<?php echo e($suatChieu->NgayChieu); ?>/<?php echo e($suatChieu->GioChieu); ?>'">Quay
                    lại</button> <button type="button" class="confirm-button" id="payos-submit-btn">Thanh Toán</button>
            </div>
        </div>
    </div>
    <div id="paymentConfirmPopup" class="payment-confirm-popup">
        <div class="payment-confirm-content">
            <div class="payment-confirm-header">
                <h2>Xác nhận thanh toán</h2>
                <button class="close-popup" onclick="closePaymentPopup()">&times;</button>
            </div>

            <div class="movie-info-with-poster">
                <div class="movie-poster-popup">
                    <img src="<?php echo e($suatChieu->phim->HinhAnh ? asset('storage/' . $suatChieu->phim->HinhAnh) : asset('images/no-image.jpg')); ?>"
                        alt="<?php echo e($suatChieu->phim->TenPhim); ?>">
                </div>
                <div class="movie-info-popup">
                    <h3><?php echo e($suatChieu->phim->TenPhim); ?></h3>
                    <div class="payment-info-row">
                        <span class="label">Đồ họa:</span>
                        <span class="value"><?php echo e($suatChieu->phim->DoHoa); ?></span>
                    </div>
                    <div class="payment-info-row">
                        <span class="label">Độ tuổi:</span>
                        <span class="value"><?php echo e($suatChieu->phim->DoTuoi); ?></span>
                    </div>
                </div>
            </div>

            <div class="payment-info-grid">
                <div class="payment-info-section">
                    <h3>Thông tin rạp chiếu</h3>
                    <div class="payment-info-row">
                        <span class="label">Tên rạp:</span>
                        <span class="value"><?php echo e($suatChieu->rap->TenRap); ?></span>
                    </div>
                    <div class="payment-info-row">
                        <span class="label">Địa chỉ:</span>
                        <span class="value"><?php echo e($suatChieu->rap->DiaChi); ?></span>
                    </div>
                </div>

                <div class="payment-info-section">
                    <h3>Thông tin suất chiếu</h3>
                    <div class="payment-info-row">
                        <span class="label">Ngày chiếu:</span>
                        <span class="value"><?php echo e(\Carbon\Carbon::parse($suatChieu->NgayChieu)->format('d/m/Y')); ?></span>
                    </div>
                    <div class="payment-info-row">
                        <span class="label">Giờ chiếu:</span>
                        <span class="value"><?php echo e(substr($suatChieu->GioChieu, 0, 5)); ?></span>
                    </div>
                    <div class="payment-info-row">
                        <span class="label">Phòng chiếu:</span>
                        <span class="value"><?php echo e($suatChieu->phongChieu->TenPhongChieu); ?></span>
                    </div>
                </div>

                <div class="payment-info-section">
                    <h3>Thông tin vé</h3>
                    <div class="payment-info-row">
                        <span class="label">Ghế đã chọn:</span>
                        <span class="value">
                            <?php $__currentLoopData = $seatDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($detail['TenGhe']); ?> (<?php echo e($detail['LoaiGhe']); ?>)<?php echo e(!$loop->last ? ', ' : ''); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </span>
                    </div>
                    <div class="payment-info-row">
                        <span class="label">Số lượng vé:</span>
                        <span class="value"><?php echo e(count($selectedSeats)); ?> vé</span>
                    </div>
                </div>

                <div class="payment-info-section">
                    <h3>Thông tin thanh toán</h3>
                    <div class="total-row" style="display: flex; justify-content: space-between;">
                        <div>Giảm giá</div>
                        <div class="discount-amount" id="discountAmountPopup">0 đ</div>
                    </div>
                    <div class="total-row" style="display: flex; justify-content: space-between;">
                        <div>Tổng cộng</div>
                        <div class="total-price" id="totalPricePopup"><?php echo e(number_format($totalPrice, 0, ',', '.')); ?> đ
                        </div>
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

    <script src="https://cdn.payos.vn/payos-checkout/v1/stable/payos-initialize.js"></script>
    <script>
        window.userId = <?php echo json_encode(session('user_id'), 15, 512) ?>;
        window.selectedSeats = <?php echo json_encode(explode(', ', request('selectedSeats') ?? '')) ?>;
        window.myHeldSeats = <?php echo json_encode($myHeldSeats ?? [], 15, 512) ?>;
        window.bookingData = window.bookingData || {};
        window.bookingData.suatChieuId = <?php echo e($suatChieu->ID_SuatChieu); ?>;
        window.totalPrice = Number(<?php echo e($totalPrice ?? 0); ?>);
        window.seatDetails = <?php echo json_encode($seatDetails ?? [], 15, 512) ?>;
        window.bookingTimeLeft = <?php echo e($bookingTimeLeft); ?>;

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
    <script src="<?php echo e(asset('user/Content/js/thanh-toan.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/pages/thanh-toan.blade.php ENDPATH**/ ?>