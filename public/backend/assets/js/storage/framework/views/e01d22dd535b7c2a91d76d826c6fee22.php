
<?php $__env->startSection('title', 'Chi tiết Hóa Đơn'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/css/detail-hoa-don.css')); ?>">
    <style>
        .ticket-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .seat-booked {
            background-color: #dc3545 !important;
            border: 2px solid #dc3545 !important;
            color: white !important;
        }
        
        .seat-normal {
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
        }
        
        .seat-vip {
            background-color: #ffc107;
            border: 1px solid #ffb300;
        }
        
        .seat-disabled {
            background-color: #6c757d;
            border: 1px solid #6c757d;
            opacity: 0.5;
        }
        
        .legend-booked {
            background-color: #dc3545;
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .ticket-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .ticket-card:hover {
            border-color: #007bff;
            box-shadow: 0 4px 15px rgba(0,123,255,0.1);
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main'); ?>
    <div class="container mt-4">
        
        <div class="invoice-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2">
                        <i class="bi bi-receipt me-2"></i>Chi tiết Hóa Đơn #<?php echo e($hoaDon->ID_HoaDon); ?>

                    </h2>
                    <p class="mb-0 opacity-90">
                        <i class="bi bi-calendar3 me-1"></i>
                        Ngày tạo: <?php echo e(\Carbon\Carbon::parse($hoaDon->created_at)->format('d/m/Y H:i')); ?>

                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <h3 class="fw-bold mb-1"><?php echo e(number_format($hoaDon->TongTien)); ?> VND</h3>
                    <span class="status-badge <?php echo e($hoaDon->TrangThaiXacNhanThanhToan == 1 ? 'status-confirmed' : 'status-pending'); ?>">
                        <?php echo e($hoaDon->TrangThaiXacNhanThanhToan == 1 ? 'Đã thanh toán' : 'Chưa thanh toán'); ?>

                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-7 mb-4">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-primary mb-3">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Sơ đồ ghế đã đặt
                        </h5>
                        
                        <?php if($veXemPhim->isNotEmpty()): ?>
                            <div class="ticket-info text-center mb-3">
                                <h6 class="mb-1"><?php echo e($veXemPhim->first()->TenPhim); ?></h6>
                                <p class="mb-0">
                                    <i class="bi bi-geo-alt-fill me-1"></i><?php echo e($veXemPhim->first()->DiaChi); ?>

                                    <br>
                                    <i class="bi bi-clock me-1"></i><?php echo e(\Carbon\Carbon::parse($veXemPhim->first()->NgayXem)->format('d/m/Y')); ?>

                                </p>
                            </div>
                        <?php endif; ?>
                        
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
                                <div class="legend-box legend-booked"></div>
                                <span>Ghế đã đặt</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-box legend-disabled"></div>
                                <span>Không hoạt động</span>
                            </div>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                Tổng số ghế đã đặt: <strong><?php echo e($hoaDon->SoLuongVe); ?></strong>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-5">
                
                <div class="card shadow rounded-4 mb-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-success mb-3">
                            <i class="bi bi-info-circle me-1"></i> Thông tin hóa đơn
                        </h5>
                        
                        <div class="row mb-2">
                            <div class="col-5"><strong>Mã hóa đơn:</strong></div>
                            <div class="col-7"><?php echo e($hoaDon->ID_HoaDon); ?></div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-5"><strong>Khách hàng:</strong></div>
                            <div class="col-7"><?php echo e($hoaDon->taiKhoan->HoTen ?? 'N/A'); ?></div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-5"><strong>Email:</strong></div>
                            <div class="col-7"><?php echo e($hoaDon->taiKhoan->Email ?? $hoaDon->Email ?? 'N/A'); ?></div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-5"><strong>Số lượng vé:</strong></div>
                            <div class="col-7"><?php echo e($hoaDon->SoLuongVe); ?></div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-5"><strong>Phương thức:</strong></div>
                            <div class="col-7">
                                <span class="badge bg-info"><?php echo e($hoaDon->PTTT); ?></span>
                            </div>
                        </div>
                        
                        <?php if($hoaDon->SoTienGiam > 0): ?>
                        <div class="row mb-2">
                            <div class="col-5"><strong>Giảm giá:</strong></div>
                            <div class="col-7 text-success">-<?php echo e(number_format($hoaDon->SoTienGiam)); ?> VND</div>
                        </div>
                        <?php endif; ?>
                        
                        <hr>
                        <div class="row">
                            <div class="col-5"><strong>Tổng tiền:</strong></div>
                            <div class="col-7"><strong class="text-primary"><?php echo e(number_format($hoaDon->TongTien)); ?> VND</strong></div>
                        </div>
                    </div>
                </div>

                
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-warning mb-3">
                            <i class="bi bi-ticket-perforated me-1"></i> Danh sách vé đã đặt
                        </h5>
                        
                        <?php if($veXemPhim->isNotEmpty()): ?>
                            <?php $__currentLoopData = $veXemPhim; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ve): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="ticket-card p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0 text-primary">Vé #<?php echo e($index + 1); ?></h6>
                                        <span class="status-badge <?php echo e($ve->TrangThai == 0 ? 'status-confirmed' : ($ve->TrangThai == 1 ? 'status-pending' : 'status-cancelled')); ?>">
                                            <?php switch($ve->TrangThai):
                                                case (0): ?> Đã xác nhận <?php break; ?>
                                                <?php case (1): ?> Đang chờ <?php break; ?>
                                                <?php case (2): ?> Đã hủy <?php break; ?>
                                                <?php default: ?> Không xác định
                                            <?php endswitch; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="row small">
                                        <div class="col-12 mb-1">
                                            <i class="bi bi-geo-alt text-muted me-1"></i>
                                            <strong>Ghế:</strong> <?php echo e($ve->TenGhe); ?>

                                        </div>
                                        <div class="col-12 mb-1">
                                            <i class="bi bi-currency-dollar text-muted me-1"></i>
                                            <strong>Giá vé:</strong> <?php echo e(number_format($ve->GiaVe)); ?> VND
                                        </div>
                                        <?php if($ve->gheNgoi): ?>
                                        <div class="col-12">
                                            <i class="bi bi-star text-muted me-1"></i>
                                            <strong>Loại ghế:</strong> 
                                            <span class="badge <?php echo e($ve->gheNgoi->LoaiTrangThaiGhe == 2 ? 'bg-warning' : 'bg-secondary'); ?>">
                                                <?php echo e($ve->gheNgoi->LoaiTrangThaiGhe == 2 ? 'VIP' : 'Thường'); ?>

                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-3">
                                <i class="bi bi-inbox display-6"></i>
                                <p class="mt-2">Không có vé nào được tìm thấy</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <a href="<?php echo e(route('hoa-don.index')); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                    </a>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> In hóa đơn
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        // Dữ liệu từ server - tương thích với cấu trúc dat-ghe.js
        const hoaDonData = {
            veXemPhim: <?php echo json_encode($veXemPhim, 15, 512) ?>,
            seatLayout: <?php echo json_encode($seatLayout ?? [], 15, 512) ?>,
            rowAisles: <?php echo json_encode($rowAisles ?? [], 15, 512) ?>,
            colAisles: <?php echo json_encode($colAisles ?? [], 15, 512) ?>,
            bookedSeats: <?php echo json_encode($bookedSeats ?? [], 15, 512) ?>,
            bookedSeatIds: <?php echo json_encode($bookedSeatIds ?? [], 15, 512) ?>
        };
        
        // Biến toàn cục cho compatibility với dat-ghe.js
        let danhSachGheDaDat = <?php echo json_encode($bookedSeatIds ?? [], 15, 512) ?>;
        let seatLayout = <?php echo json_encode($seatLayout ?? [], 15, 512) ?>;
        
        console.log('Hóa đơn data:', hoaDonData);
        console.log('Ghế đã đặt IDs:', danhSachGheDaDat);
        console.log('Seat layout:', seatLayout);
    </script>
    <script src="<?php echo e(asset('backend/assets/js/hoa-don-detail.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/hoa_don/detail-hoa-don.blade.php ENDPATH**/ ?>