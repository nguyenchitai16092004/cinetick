
<?php $__env->startSection('title', 'CineTick - Kiểm tra thanh toán'); ?>
<?php $__env->startSection('main'); ?>
<link rel="stylesheet" href="<?php echo e(asset('user/Content/css/check-payment.css')); ?>">

    <div class="container-payment-status">
        <div class="status-header">
            <?php if(session('status') === 'success' && isset($hoaDon) && isset($suatChieu)): ?>
                <div class="success-icon">✓</div>
                <div class="status-title success">Đặt vé thành công!</div>
                <div class="status-subtitle">
                    Cảm ơn bạn đã thanh toán thành công đơn hàng #<?php echo e($hoaDon->ID_HoaDon ?? '---'); ?>

                </div>
            <?php else: ?>
                <div class="failure-icon">✗</div>
                <div class="status-title failure">Đặt vé thất bại!</div>
                <div class="status-subtitle">
                    Rất tiếc, thanh toán không thành công cho đơn hàng #<?php echo e($hoaDon->ID_HoaDon ?? '---'); ?>

                </div>
            <?php endif; ?>
        </div>
        <?php if(session('status') === 'fail' || session('status') === 'cancel'): ?>
            <div class="error-message">
                <?php echo e(session('error_message') ?? 'Giao dịch bị từ chối. Vui lòng kiểm tra thông tin thẻ hoặc thử lại sau.'); ?>

            </div>
        <?php endif; ?>

        <div class="content-wrapper">
            <div class="ticket-details">
                <div class="movie-card <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                    <div class="ticket-number <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                        #<?php echo e($hoaDon->ID_HoaDon ?? '---'); ?>

                    </div>
                    <div class="ticket-header <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                        <div class="movie-title <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                            <?php if(isset($suatChieu->phim)): ?>
                                <?php echo e($suatChieu->phim->DoTuoi ?? ''); ?> <?php echo e($suatChieu->phim->TenPhim ?? ''); ?>

                            <?php else: ?>
                                <?php echo e($movieTitle ?? '---'); ?>

                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="ticket-content">
                        <div class="movie-info">
                            <div class="info-item">
                                <div class="movie-info-label <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">Thời gian
                                </div>
                                <div class="movie-info-value <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                                    <?php if(isset($suatChieu->GioChieu)): ?>
                                        <?php echo e(substr($suatChieu->GioChieu, 0, 5)); ?>

                                    <?php else: ?>
                                        <?php echo e($time ?? '---'); ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="movie-info-label <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">Ngày chiếu
                                </div>
                                <div class="movie-info-value <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                                    <?php if(isset($suatChieu->NgayChieu)): ?>
                                        <?php echo e(\Carbon\Carbon::parse($suatChieu->NgayChieu)->format('d/m/Y')); ?>

                                    <?php else: ?>
                                        <?php echo e($date ?? '---'); ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="cinema-section <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                            <div class="cinema-name <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                                <?php echo e($suatChieu->rap->TenRap ?? ($cinemaName ?? '---')); ?>

                            </div>
                            <div class="cinema-address <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                                <?php echo e($suatChieu->rap->DiaChi ?? ($cinemaAddress ?? '---')); ?>

                            </div>
                            <div class="cinema-details">
                                <div class="info-item">
                                    <div class="movie-info-label <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">Phòng
                                        chiếu</div>
                                    <div class="cinema-room <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                                        <?php echo e($suatChieu->phongChieu->TenPhongChieu ?? ($cinemaRoom ?? '---')); ?>

                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="movie-info-label <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">Ghế
                                    </div>
                                    <div class="cinema-format <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                                        <?php echo e(isset($selectedSeats) && is_array($selectedSeats) ? implode(', ', $selectedSeats) : $seats ?? '---'); ?>

                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="movie-info-label <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">Đồ họa
                                    </div>
                                    <div class="cinema-format <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                                        <?php echo e($suatChieu->phim->DoHoa ?? ($cinemaFormat ?? '---')); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pricing-section <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                    <div class="pricing-row">
                        <span class="pricing-label <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">GHẾ</span>
                    </div>
                    <div class="pricing-row">
                        <span class="pricing-label <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                            <?php echo e(isset($selectedSeats) && is_array($selectedSeats) ? implode(', ', $selectedSeats) : $seats ?? '---'); ?>

                        </span>
                        <span class="pricing-value <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                            <?php echo e(isset($hoaDon->TongTien) ? number_format($hoaDon->TongTien, 0, ',', '.') . 'đ' : $totalPrice ?? '---'); ?>

                        </span>
                    </div>
                    <?php
                        $goc = isset($hoaDon->TongTien) ? $hoaDon->TongTien : $totalPrice ?? 0;
                        $giam = isset($hoaDon->SoTienGiam) ? $hoaDon->SoTienGiam : $soTienGiam ?? 0;
                        $thanhTien = $goc - $giam;
                    ?>
                    <div class="seats-row <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                        <div class="pricing-row">
                            <span class="pricing-label <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">Tạm tính</span>
                            <span class="pricing-value <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                                <?php echo e(number_format($goc, 0, ',', '.') . 'đ'); ?>

                            </span>
                        </div>
                        <div class="pricing-row">
                            <span class="pricing-label <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">Giảm giá</span>
                            <span class="pricing-value <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                                <?php echo e($giam > 0 ? '-' . number_format($giam, 0, ',', '.') . 'đ' : '0đ'); ?>

                            </span>
                        </div>
                        <div class="pricing-row">
                            <span class="pricing-label <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">Thành tiền</span>
                            <span class="pricing-value <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                                <?php echo e(number_format($thanhTien, 0, ',', '.') . 'đ'); ?>

                            </span>
                        </div>
                        <div class="pricing-row <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                            <span class="pricing-label total  <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">Tổng
                                cộng</span>
                            <span class="pricing-value <?php if(session('status') !== 'success'): ?> failure <?php endif; ?>">
                                <?php echo e(number_format($thanhTien, 0, ',', '.') . 'đ'); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(session('status') === 'success' && isset($hoaDon)): ?>
                <div class="qr-section">
                    <a href="#" id="download-qr-link" class="qr-button" title="Tải xuống QRCode">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo e($hoaDon->ID_HoaDon ?? '---'); ?>"
                            alt="QR Code" style="width:200px;height:200px;cursor:pointer;" />
                    </a>
                    <div class="qr-text">Click vào mã QR để tải xuống</div>
                    <div class="qr-text">Vui lòng cung cấp mã QR Code này cho nhân viên tại rạp. Đội ngũ CineTick xin cảm
                        ơn!</div>
                </div>

                <script>
                    document.getElementById('download-qr-link').addEventListener('click', function(e) {
                        e.preventDefault();
                        // Dùng link ảnh lớn để tải về
                        var imageUrl =
                            "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=<?php echo e($hoaDon->ID_HoaDon ?? '---'); ?>";
                        var fileName = "QRCode-<?php echo e($hoaDon->ID_HoaDon ?? ''); ?>.png";
                        fetch(imageUrl, {
                                mode: 'cors'
                            })
                            .then(resp => resp.blob())
                            .then(blob => {
                                var url = window.URL.createObjectURL(blob);
                                var a = document.createElement('a');
                                a.style.display = 'none';
                                a.href = url;
                                a.download = fileName;
                                document.body.appendChild(a);
                                a.click();
                                window.URL.revokeObjectURL(url);
                            }).catch(() => alert(
                                'Không thể tự động tải về QR code. Vui lòng click chuột phải vào ảnh để lưu.'));
                    });
                </script>
            <?php else: ?>
                <div class="failure-section">
                    <div class="failure-icon-large">✗</div>
                    <div class="failure-text">Thanh toán thất bại</div>
                    <div class="failure-reason">
                        Giao dịch không thể hoàn tất.<br>
                        Vui lòng thử lại hoặc liên hệ hỗ trợ.
                    </div>
                    <a href="<?php echo e(route('lien-he')); ?>" class="contact-button">Liên hệ hỗ trợ</a>
                </div>
            <?php endif; ?>
        </div>
        <?php if(session('status') === 'success'): ?>
            <div class="action-buttons">
                <a href="<?php echo e(route('home')); ?>" class="main-button" style="background: #E91E63;">Quay lại trang chủ</a>
            </div>
        <?php else: ?>
            <div class="action-buttons">
                
                <a href="<?php echo e(route('home')); ?>" class="secondary-button">Quay lại trang chủ</a>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/pages/kiem-tra-thanh-toan.blade.php ENDPATH**/ ?>