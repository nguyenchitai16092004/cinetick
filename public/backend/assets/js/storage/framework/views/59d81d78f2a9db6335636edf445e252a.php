
<?php $__env->startSection('title', isset($rap[0]) ? 'Chỉnh sửa Rạp' : 'Thêm mới Rạp'); ?>

<?php $__env->startSection('main'); ?>
    <section class="py-5 bg-light min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-primary text-white py-3">
                            <h2 class="h4 mb-0 d-flex align-items-center gap-2">
                                <?php echo e(isset($rap[0]) ? 'Chỉnh sửa Rạp' : 'Thêm mới Rạp'); ?>

                            </h2>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <form action="<?php echo e(isset($rap[0]) ? route('rap.update', $rap[0]->ID_Rap) : route('rap.store')); ?>"
                                method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php if(isset($rap[0])): ?>
                                    <?php echo method_field('PUT'); ?>
                                <?php endif; ?>

                                <div class="mb-4">
                                    <label for="TenRap" class="form-label fw-medium">Tên Rạp <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg rounded-3" id="TenRap"
                                        name="TenRap" value="<?php echo e(old('TenRap', $rap[0]->TenRap ?? '')); ?>" required>
                                </div>

                                <div class="mb-4">
                                    <label for="HinhAnh" class="form-label fw-medium">Hình ảnh <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="HinhAnh" name="HinhAnh" accept="image/*">
                                        <label class="custom-file-label" for="HinhAnh">Chọn ảnh</label>
                                    </div>
                                    <?php if($errors->has('HinhAnh')): ?>
                                        <span class="text-danger"><?php echo e($errors->first('HinhAnh')); ?></span>
                                    <?php endif; ?>
                                    <?php if(isset($rap[0]) && $rap[0]->HinhAnh): ?>
                                    <div class="mt-2">
                                        <label class="form-label">Ảnh đang lưu:</label><br>
                                        <img src="<?php echo e(asset('storage/' . $rap[0]->HinhAnh)); ?>" alt="Ảnh rạp hiện tại" style="max-width: 250px; max-height: 150px; border-radius: 8px;">
                                    </div>
                                <?php endif; ?>
                                </div>

                                <div class="mb-4">
                                    <label for="DiaChi" class="form-label fw-medium">Địa Chỉ <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg rounded-3" id="DiaChi"
                                        name="DiaChi" value="<?php echo e(old('DiaChi', $rap[0]->DiaChi ?? '')); ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label for="MoTa" class="form-label fw-medium">Mô tả <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg rounded-3" id="MoTa"
                                        name="MoTa" value="<?php echo e(old('MoTa', $rap[0]->MoTa ?? '')); ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label for="Hotline" class="form-label fw-medium">Hotline <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg rounded-3" name="Hotline"
                                        id="Hotline" value="<?php echo e(old('Hotline', $rap[0]->Hotline ?? '')); ?>">
                                    <?php $__errorArgs = ['Hotline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="mb-4">
                                    <label for="TrangThai" class="form-label fw-medium">Trạng Thái</label>
                                    <select class="form-select form-select-lg rounded-3" id="TrangThai" name="TrangThai">
                                        <option value="1"
                                            <?php echo e(old('TrangThai', $rap[0]->TrangThai ?? '') == '1' ? 'selected' : ''); ?>>Hoạt
                                            động</option>
                                        <option value="0"
                                            <?php echo e(old('TrangThai', $rap[0]->TrangThai ?? '') == '0' ? 'selected' : ''); ?>>Bảo
                                            trì</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-5">
                                    <a href="<?php echo e(route('rap.index')); ?>"
                                        class="btn btn-outline-secondary btn-lg d-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                                        </svg>
                                        Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg d-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                            <path
                                                d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                        </svg>
                                        <?php echo e(isset($rap[0]) ? 'Cập nhật' : 'Thêm mới'); ?>

                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->startSection('styles'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/rap/quan-ly-rap.blade.php ENDPATH**/ ?>