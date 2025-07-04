
<?php $__env->startSection('title', 'Chỉnh sửa khuyến mãi'); ?>

<?php $__env->startSection('main'); ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4><i class="fas fa-edit"></i> Chỉnh sửa khuyến mãi</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('khuyen-mai.update', $khuyenMai->ID_KhuyenMai)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="form-group mb-3">
                        <label for="MaKhuyenMai" class="form-label">Mã khuyến mãi</label>
                        <input type="text" name="MaKhuyenMai" id="MaKhuyenMai" class="form-control" required value="<?php echo e(old('MaKhuyenMai', $khuyenMai->MaKhuyenMai)); ?>" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="DieuKienToiThieu">Điều kiện tối thiểu (đ)</label>
                        <input type="number" name="DieuKienToiThieu" class="form-control" required min="0" step="1000" value="<?php echo e(old('DieuKienToiThieu', $khuyenMai->DieuKienToiThieu)); ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="PhanTramGiam">Phần trăm giảm (%)</label>
                        <input type="number" name="PhanTramGiam" class="form-control" required min="1" max="100" value="<?php echo e(old('PhanTramGiam', $khuyenMai->PhanTramGiam)); ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="GiamToiDa">Giảm tối đa (đ)</label>
                        <input type="number" name="GiamToiDa" class="form-control" required min="0" step="1000" value="<?php echo e(old('GiamToiDa', $khuyenMai->GiamToiDa)); ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="NgayKetThuc">Ngày hết hạn</label>
                        <input type="date" name="NgayKetThuc" id="NgayKetThuc" class="form-control" required value="<?php echo e(old('NgayKetThuc', $khuyenMai->NgayKetThuc)); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                    <a href="<?php echo e(route('khuyen-mai.index')); ?>" class="btn btn-secondary">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById("NgayKetThuc").setAttribute('min', today);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/phieu_giam_gia/detail-phieu-giam-gia.blade.php ENDPATH**/ ?>