
<?php $__env->startSection('title', 'Chi tiết bình luận'); ?>

<?php $__env->startSection('main'); ?>
    <div class="container-fluid mt-3">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Chi tiết bình luận </h5>
                <a href="<?php echo e(route('binh-luan.index')); ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <div class="card-body ">
                <div class="mb-4">
                    <h6 class="text-secondary">Phim liên quan</h6>
                    <div class="border rounded p-2">
                        <p><strong><?php echo e($binhLuan->TenPhim); ?></strong></p>
                        <p class="text-muted mb-0">ID Phim: <?php echo e($binhLuan->ID_Phim); ?></p>
                    </div>
                </div>
                <div class="mb-4">
                    <h6 class="text-secondary">Người dùng</h6>
                    <div class="border rounded p-2">
                        <p><strong>Tên đăng nhập:</strong> <?php echo e($binhLuan->TenDN); ?></p>
                        <?php if(isset($binhLuan->Email)): ?>
                            <p><strong>Email:</strong> <?php echo e($binhLuan->Email); ?></p>
                        <?php endif; ?>
                        <p><strong>ID:</strong> <?php echo e($binhLuan->ID_TaiKhoan); ?></p>
                    </div>
                </div>
                <div class="mb-4">
                    <h6 class="text-secondary">Thời gian</h6>
                    <div class="border rounded p-2">
                        <p><strong>Ngày tạo:</strong><br> <?php echo e(date('d/m/Y H:i:s', strtotime($binhLuan->created_at))); ?>

                        </p>
                        <?php if($binhLuan->updated_at && $binhLuan->updated_at != $binhLuan->created_at): ?>
                            <p><strong>Cập nhật lần cuối:</strong><br>
                                <?php echo e(date('d/m/Y H:i:s', strtotime($binhLuan->updated_at))); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($binhLuan->DiemDanhGia): ?>
                    <div class="mb-4">
                        <h6 class="text-secondary">Điểm đánh giá</h6>
                        <span class="badge badge-warning text-dark"><?php echo e($binhLuan->DiemDanhGia); ?>/10</span>
                    </div>
                <?php endif; ?>

                <div>
                    <h6 class="text-secondary">Thao tác</h6>
                    <div class="btn-group">
                        <form method="POST" action="<?php echo e(route('binh-luan.destroy', $binhLuan->ID_BinhLuan)); ?>"
                            onsubmit="return confirm('Xác nhận xóa bình luận này?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">

                    <div>
                        <div class="text-center">
                            <?php if(isset($binhLuan->TrangThai)): ?>
                                <span
                                    class="badge badge-lg <?php echo e($binhLuan->TrangThai == 1 ? 'badge-success' : 'badge-secondary'); ?>">
                                    <i class="fas <?php echo e($binhLuan->TrangThai == 1 ? 'fa-eye' : 'fa-eye-slash'); ?>"></i>
                                    <?php echo e($binhLuan->TrangThai == 1 ? 'Đang hiển thị' : 'Đang ẩn'); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge badge-info">Không xác định</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge-lg {
            padding: 0.5rem 1rem;
            font-size: 0.95rem;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/binh_luan/detail-binh-luan.blade.php ENDPATH**/ ?>