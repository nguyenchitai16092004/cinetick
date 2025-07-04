
<?php $__env->startSection('title', 'Chi tiết Phòng Chiếu'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/css/seat_layout.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main'); ?>
    <div class="container mt-4">
        <h2 class="text-center text-primary fw-bold mb-4">Chi tiết Phòng Chiếu</h2>
        <div class="row">
            
            <div class="col-md-7 mb-4">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-primary mb-3">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Sơ đồ ghế ngồi
                        </h5>
                        <div id="seatLayout" class="seat-container">
                            <?php if(isset($seatLayout) && count($seatLayout) > 0): ?>
                                
                            <?php else: ?>
                                <div class="placeholder-text text-muted text-center py-5">
                                    Không có thông tin về sơ đồ ghế
                                </div>
                            <?php endif; ?>
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
                                <div class="legend-box legend-disabled"></div>
                                <span>Không hoạt động</span>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <small id="seatCount" class="text-muted">Số ghế: <?php echo e($phongChieu->SoLuongGhe); ?></small>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-5">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-success mb-3">
                            <i class="bi bi-info-circle me-1"></i> Thông tin phòng chiếu
                        </h5>
                        <form id="roomForm" method="POST"
                            action="<?php echo e(route('phong-chieu.update', $phongChieu->ID_PhongChieu)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            
                            <div class="mb-3">
                                <label class="form-label">Tên phòng chiếu</label>
                                <input type="text" class="form-control form-control-sm" name="roomName"
                                    value="<?php echo e($phongChieu->TenPhongChieu); ?>" required>
                                <?php $__errorArgs = ['roomName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label">Rạp chiếu</label>
                                <select class="form-select form-select-sm" name="ID_Rap" required>
                                    <option value="" disabled>Chọn rạp</option>
                                    <?php $__currentLoopData = $raps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($rap->ID_Rap); ?>"
                                            <?php echo e($phongChieu->ID_Rap == $rap->ID_Rap ? 'selected' : ''); ?>>
                                            <?php echo e($rap->TenRap); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['ID_Rap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label">Loại phòng</label>
                                <select class="form-select form-select-sm" name="LoaiPhong" required>
                                    <option value="" disabled>Chọn loại phòng</option>
                                    <option value="0" <?php echo e($phongChieu->LoaiPhong == 0 ? 'selected' : ''); ?>>Phòng thường</option>
                                    <option value="1" <?php echo e($phongChieu->LoaiPhong == 1 ? 'selected' : ''); ?>>Phòng VIP</option>
                                </select>
                                <?php $__errorArgs = ['LoaiPhong'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select form-select-sm" name="TrangThai" required>
                                    <option value="1" <?php echo e($phongChieu->TrangThai == 1 ? 'selected' : ''); ?>>Hoạt động</option>
                                    <option value="0" <?php echo e($phongChieu->TrangThai == 0 ? 'selected' : ''); ?>>Không hoạt động</option>
                                </select>
                                <?php $__errorArgs = ['TrangThai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="row">
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số hàng ghế</label>
                                    <select class="form-select form-select-sm" id="rowCount" name="rowCount" required>
                                        <option value="" disabled>Chọn số hàng</option>
                                        <?php for($i = 5; $i <= 12; $i++): ?>
                                            <option value="<?php echo e($i); ?>" <?php echo e($rowCount == $i ? 'selected' : ''); ?>>
                                                <?php echo e($i); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <?php $__errorArgs = ['rowCount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số ghế mỗi hàng</label>
                                    <select class="form-select form-select-sm" id="colCount" name="colCount" required>
                                        <option value="" disabled>Chọn số ghế</option>
                                        <?php $__currentLoopData = [6, 8, 10, 12, 14, 16]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($col); ?>" <?php echo e($colCount == $col ? 'selected' : ''); ?>>
                                                <?php echo e($col); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['colCount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label">Đường đi giữa các hàng</label>
                                <select class="form-select form-select-sm" id="rowAisles" name="rowAisles[]" multiple>
                                    <?php for($i = 1; $i < $rowCount; $i++): ?>
                                        <option value="<?php echo e($i); ?>"
                                            <?php echo e(in_array($i, json_decode($phongChieu->HangLoiDi ?: '[]')) ? 'selected' : ''); ?>>
                                            Sau hàng <?php echo e(chr(64 + $i)); ?>

                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <small class="text-muted">Giữ Ctrl để chọn nhiều hàng</small>
                                <?php $__errorArgs = ['rowAisles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label">Đường đi giữa các cột</label>
                                <select class="form-select form-select-sm" id="colAisles" name="colAisles[]" multiple>
                                    <?php for($i = 1; $i < $colCount; $i++): ?>
                                        <option value="<?php echo e($i); ?>"
                                            <?php echo e(in_array($i, json_decode($phongChieu->CotLoiDi ?: '[]')) ? 'selected' : ''); ?>>
                                            Sau cột <?php echo e($i); ?>

                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <small class="text-muted">Giữ Ctrl để chọn nhiều cột</small>
                                <?php $__errorArgs = ['colAisles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="mb-3">
                                <button type="button" id="clearAislesBtn" onclick="clearAisles()" 
                                    class="btn btn-outline-warning btn-sm"
                                    style="display: <?php echo e((json_decode($phongChieu->HangLoiDi ?: '[]')) ? 'inline-block' : 'none'); ?>;">
                                    <i class="bi bi-eraser me-1"></i> Xóa lối đi
                                </button>
                            </div>

                            
                            <input type="hidden" name="seatLayout" id="seatLayoutInput"
                                value="<?php echo e(json_encode($seatLayout)); ?>">

                            
                            <?php if(session('error')): ?>
                                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                            <?php endif; ?>

                            
                            <?php if(session('success')): ?>
                                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                            <?php endif; ?>

                            
                            <div class="d-flex justify-content-center gap-2 mt-4">
                                <button type="submit" id="submitBtn" class="btn btn-success btn-sm">
                                    <i class="bi bi-save me-1"></i> Cập nhật phòng
                                </button>
                                <a href="<?php echo e(route('phong-chieu.index')); ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        // Khởi tạo dữ liệu từ server
        let seats = <?php echo json_encode($seatLayout, 15, 512) ?>;
        let rowAisles = <?php echo json_encode(json_decode($phongChieu->HangLoiDi ?: '[]'), 15, 512) ?>;
        let colAisles = <?php echo json_encode(json_decode($phongChieu->CotLoiDi ?: '[]'), 15, 512) ?>;
        let seatCount = <?php echo e($phongChieu->SoLuongGhe); ?>;

        // Enhanced Room Management JavaScript
        // Supports both creation and detail views

        // Khởi tạo biến toàn cục nếu chưa có
        seats = seats || [];
        rowAisles = rowAisles || [];
        colAisles = colAisles || [];
        seatCount = seatCount || 0;
        let isDetailView = true; // Đặt true cho trang detail
        
        console.log('Detail view initialized with:', {
            seats: seats,
            rowAisles: rowAisles,
            colAisles: colAisles,
            seatCount: seatCount,
            isDetailView: isDetailView
        });
    </script>
    <script src="<?php echo e(asset('backend/assets/js/seat.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/phong_chieu/detail_phong_chieu.blade.php ENDPATH**/ ?>