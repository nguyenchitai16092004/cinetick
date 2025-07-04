
<?php $__env->startSection('title', 'Quản lý Khuyến Mãi'); ?>

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
    </style>

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">🎫 Danh sách khuyến mãi</h3>
                        <a href="<?php echo e(route('khuyen-mai.create')); ?>" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm khuyến mãi mới
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Mã khuyến mãi</th>
                                        <th>Điều kiện tối thiểu</th>
                                        <th>Phần trăm giảm</th>
                                        <th>Giá trị tối đa</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $dsKhuyenMai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $km): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($km->ID_KhuyenMai); ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo e($km->MaKhuyenMai); ?></span>
                                            </td>
                                            <td><?php echo e(number_format($km->DieuKienToiThieu, 0, ',', '.')); ?> VNĐ</td>
                                            <td><?php echo e($km->PhanTramGiam); ?>%</td>
                                            <td><?php echo e(number_format($km->GiamToiDa, 0, ',', '.')); ?> VNĐ</td>
                                            <td><?php echo e(\Carbon\Carbon::parse($km->NgayKetThuc)->format('d/m/Y')); ?></td>
                                            <td>
                                                <?php
                                                    $today = \Carbon\Carbon::now()->format('Y-m-d');
                                                    $ngayKetThuc = \Carbon\Carbon::parse($km->NgayKetThuc)->format('Y-m-d');

                                                    if ($ngayKetThuc >= $today) {
                                                        $trangThaiText = 'Có hiệu lực';
                                                        $bgColor = '#28a745'; // Xanh
                                                    } else {
                                                        $trangThaiText = 'Hết hạn';
                                                        $bgColor = '#dc3545'; // Đỏ
                                                    }
                                                ?>

                                                <span
                                                    style="width:80%; display: inline-block; padding: 4px 8px; border-radius: 5px; color: #fff; background-color: <?php echo e($bgColor); ?>;">
                                                    <?php echo e($trangThaiText); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('khuyen-mai.edit', $km->ID_KhuyenMai)); ?>"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('khuyen-mai.delete', $km->ID_KhuyenMai)); ?>" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if(method_exists($dsKhuyenMai, 'links')): ?>
                            <div class="mt-3 d-flex justify-content-center">
                                <?php echo e($dsKhuyenMai->links()); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/phieu_giam_gia/phieu-giam-gia.blade.php ENDPATH**/ ?>