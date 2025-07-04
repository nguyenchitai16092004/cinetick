
<?php $__env->startSection('title', 'Quản lý suất chiếu'); ?>

<?php $__env->startSection('main'); ?>
    <style>
        .btn-purple {
            background-color: #6f42c1;
            color: white;
        }

        .btn-purple:hover {
            background-color: #5a32a3;
            color: white;
        }

        .card-header.bg-purple {
            background-color: #6f42c1;
            color: white;
        }

        .table thead th {
            background-color: #e9d8fd;
            color: #4b0082;
        }

        .table-hover tbody tr:hover {
            background-color: #f3e5f5;
        }

        .filter-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: dark;
        }

        .filter-card .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.2);
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">🎭 Danh sách suất chiếu</h3>
                        <a href="<?php echo e(route('suat-chieu.create')); ?>" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm suất chiếu
                        </a>
                    </div>

                    <div class="card-body">
                        
                        <div class="filter-card">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <form action="<?php echo e(route('suat-chieu.filter.date')); ?>" method="GET">
                                        <label for="date" class="form-label">Lọc theo ngày:</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-secondary text-white">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                            <input type="date" name="date" class="form-control"
                                                value="<?php echo e($date ?? ''); ?>">
                                            <button type="submit" class="btn btn-light">
                                                <i class="fas fa-filter"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-4">
                                    <form action="<?php echo e(route('suat-chieu.filter.phim')); ?>" method="GET">
                                        <label for="phim_id" class="form-label">Lọc theo phim:</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-secondary text-white">
                                                <i class="fas fa-film"></i>
                                            </span>
                                            <select name="phim_id" class="form-select">
                                                <option value="">-- Chọn phim --</option>
                                                <?php $__currentLoopData = $phims; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($phim->ID_Phim); ?>"><?php echo e($phim->TenPhim); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <button type="submit" class="btn btn-light">
                                                <i class="fas fa-filter"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <form action="<?php echo e(route('suat-chieu.filter.rap')); ?>" method="GET">
                                        <label for="rap_id" class="form-label">Lọc theo rạp:</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-secondary text-white">
                                                <i class="fas fa-building"></i>
                                            </span>
                                            <select name="rap_id" class="form-select">
                                                <option value="">-- Chọn rạp --</option>
                                                <?php $__currentLoopData = $raps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($rap->ID_Rap); ?>"><?php echo e($rap->TenRap); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <button type="submit" class="btn btn-light">
                                                <i class="fas fa-filter"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Phim</th>
                                        <th>Phòng chiếu</th>
                                        <th>Ngày chiếu</th>
                                        <th>Giờ chiếu</th>
                                        <th>Giá vé</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $suatChieus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $suatChieu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><strong>#<?php echo e($suatChieu->ID_SuatChieu); ?></strong></td>
                                            <td class="text-start fw-bold"><?php echo e($suatChieu->phim->TenPhim ?? 'N/A'); ?></td>
                                            <td class="text-start" style="max-width: 250px;">
                                                <div class="fw-bold"><?php echo e($suatChieu->phongChieu->TenPhongChieu ?? 'N/A'); ?>

                                                </div>
                                                <small
                                                    class="text-muted"><?php echo e($suatChieu->phongChieu->rap->DiaChi ?? 'N/A'); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge text-dark">
                                                    <?php echo e(date('d/m/Y', strtotime($suatChieu->NgayChieu))); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <?php echo e(date('H:i', strtotime($suatChieu->GioChieu))); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-danger">
                                                    <?php echo e(number_format($suatChieu->GiaVe, 0, ',', '.')); ?> đ
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('suat-chieu.edit', $suatChieu->ID_SuatChieu)); ?>"
                                                        class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form
                                                        action="<?php echo e(route('suat-chieu.destroy', $suatChieu->ID_SuatChieu)); ?>"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc muốn xóa suất chiếu này?');"
                                                        class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-film fa-3x mb-3 text-muted"></i>
                                                <div>Không có dữ liệu suất chiếu</div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="mt-3 d-flex justify-content-center">
                            <?php echo e($suatChieus->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/suat_chieu/suat-chieu.blade.php ENDPATH**/ ?>