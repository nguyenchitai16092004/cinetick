
<?php $__env->startSection('title', 'Quản lý Phòng Chiếu'); ?>

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
                        <h3 class="card-title mb-0">🎬 Danh sách phim</h3>
                        <a href="<?php echo e(route('phim.create')); ?>" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm phim mới
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Tên phim</th>
                                        <th>Thể loại</th>
                                        <th>Ngày khởi chiếu</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Thời lượng</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $phims; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td hidden><?php echo e($phim->ID_Phim); ?></td>
                                            <td>
                                                <img src="<?php echo e($phim->HinhAnh ? asset('storage/' . $phim->HinhAnh) : asset('images/no-image.jpg')); ?>"
                                                    width="80" class="img-thumbnail">
                                            </td>
                                            <td><?php echo e($phim->TenPhim); ?></td>
                                            <td>
                                                <?php $__currentLoopData = $phim->theLoai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theLoai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span ><?php echo e($theLoai->TenTheLoai); ?>,</span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>

                                            <td><?php echo e(date('d/m/Y', strtotime($phim->NgayKhoiChieu))); ?></td>
                                            <td><?php echo e(date('d/m/Y', strtotime($phim->NgayKetThuc))); ?></td>
                                            <td><?php echo e($phim->ThoiLuong); ?> phút</td>
                                            <td>
                                                <?php
                                                    $today = \Carbon\Carbon::now()->format('Y-m-d');
                                                    $ngayKhoiChieu = \Carbon\Carbon::parse(
                                                        $phim->NgayKhoiChieu,
                                                    )->format('Y-m-d');
                                                    $ngayKetThuc = \Carbon\Carbon::parse($phim->NgayKetThuc)->format(
                                                        'Y-m-d',
                                                    );

                                                    if ($ngayKhoiChieu > $today && $ngayKetThuc > $today) {
                                                        $trangThaiText = 'Sắp công chiếu';
                                                        $bgColor = '#ffc107'; // Vàng
                                                    } elseif ($ngayKhoiChieu <= $today && $ngayKetThuc >= $today) {
                                                        $trangThaiText = 'Công chiếu';
                                                        $bgColor = '#28a745'; // Xanh
                                                    } elseif ($ngayKetThuc < $today) {
                                                        $trangThaiText = 'Đã công chiếu';
                                                        $bgColor = '#dc3545'; // Đỏ
                                                    } else {
                                                        $trangThaiText = 'Không xác định';
                                                        $bgColor = '#6c757d'; // Xám
                                                    }
                                                ?>

                                                <span
                                                    style="width:80% ;display: inline-block; padding: 4px 8px; border-radius: 5px; color: #fff; background-color: <?php echo e($bgColor); ?>;">
                                                    <?php echo e($trangThaiText); ?>

                                                </span>
                                            </td>


                                            <td>
                                                <a href="<?php echo e(route('phim.show', $phim->ID_Phim)); ?>"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('phim.destroy', $phim->ID_Phim)); ?>" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa phim này?')">
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

                        <div class="mt-3 d-flex justify-content-center">
                            <?php echo e($phims->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/phim/phim.blade.php ENDPATH**/ ?>