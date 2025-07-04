
<?php $__env->startSection('title', 'Tạo Phòng Chiếu'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/css/seat_layout.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main'); ?>
    <div class="container mt-4">
        <h2 class="text-center text-primary fw-bold mb-4">Quản lý Phòng Chiếu</h2>
        <div class="row">
            
            <div class="col-md-7 mb-4">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-primary mb-3">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Sơ đồ ghế ngồi
                        </h5>
                        <div id="seatLayout" class="seat-container">
                            <div class="placeholder-text text-muted text-center py-5">
                                Chọn thông tin phòng để tạo sơ đồ ghế
                            </div>
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
                            <small id="seatCount" class="text-muted">Số ghế đã chọn: 0</small>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-5">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-success mb-3">
                            <i class="bi bi-plus-square me-1"></i> Tạo Phòng Chiếu
                        </h5>
                        <form id="roomForm" method="POST" action="<?php echo e(route('phong-chieu.store')); ?>">
                            <?php echo csrf_field(); ?>

                            
                            <div class="mb-3">
                                <label class="form-label">Tên phòng chiếu</label>
                                <input type="text" class="form-control form-control-sm" name="roomName"
                                    value="<?php echo e(old('roomName')); ?>" required>
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
                                    <option value="" disabled selected>Chọn rạp</option>
                                    <?php $__currentLoopData = $raps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($rap->ID_Rap); ?>"
                                            <?php echo e(old('ID_Rap') == $rap->ID_Rap ? 'selected' : ''); ?>>
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
                                    <option value="" disabled selected>Chọn loại phòng</option>
                                    <option value="0" <?php echo e(old('LoaiPhong') == '0' ? 'selected' : ''); ?>>
                                        Phòng thường
                                    </option>
                                    <option value="1" <?php echo e(old('LoaiPhong') == '1' ? 'selected' : ''); ?>>
                                        Phòng VIP
                                    </option>
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

                            <div class="row">
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số hàng ghế</label>
                                    <select class="form-select form-select-sm" id="rowCount" name="rowCount" required>
                                        <option value="" disabled selected>Chọn số hàng</option>
                                        <?php for($i = 5; $i <= 10; $i++): ?>
                                            <option value="<?php echo e($i); ?>"
                                                <?php echo e(old('rowCount') == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
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
                                        <option value="" disabled selected>Chọn số ghế</option>
                                        <?php $__currentLoopData = [6, 7, 8, 9, 10]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($col); ?>"
                                                <?php echo e(old('colCount') == $col ? 'selected' : ''); ?>><?php echo e($col); ?>

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
                                    <option disabled>Chọn số hàng trước</option>
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
                                    <option disabled>Chọn số cột trước</option>
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

                            
                            <input type="hidden" name="seatLayout" id="seatLayoutInput" value="<?php echo e(old('seatLayout')); ?>">

                            
                            <?php if(session('error')): ?>
                                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                            <?php endif; ?>

                            
                            <div class="d-flex justify-content-center gap-2 mt-4">
                                <button type="submit" id="submitBtn" class="btn btn-success btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Tạo phòng
                                </button>
                                <button type="button" id="clearAislesBtn" onclick="clearAisles()"
                                    class="btn btn-outline-danger btn-sm" style="display: none;">
                                    <i class="bi bi-x-circle me-1"></i> Hủy đường
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(asset('backend/assets/js/seat.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/phong_chieu/create_phong_chieu.blade.php ENDPATH**/ ?>