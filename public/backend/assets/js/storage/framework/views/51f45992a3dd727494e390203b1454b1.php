
<?php $__env->startSection('title', 'Quản lý Hóa Đơn'); ?>

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
        }

        .filter-card .form-control {
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .filter-card .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .filter-card .form-control:focus {
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">🧾 Danh sách Hóa Đơn</h3>
                        <a href="<?php echo e(route('hoa-don.create')); ?>" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm hóa đơn
                        </a>
                    </div>

                    <div class="card-body">
                        
                        <div class="card border-0 filter-card mb-4">
                            <div class="card-body">
                                <form method="GET" action="<?php echo e(route('hoa-don.index')); ?>">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label for="start_date" class="form-label">Từ ngày:</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control"
                                                value="<?php echo e(request('start_date')); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="end_date" class="form-label">Đến ngày:</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control"
                                                value="<?php echo e(request('end_date')); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="id_tai_khoan" class="form-label">ID Tài khoản:</label>
                                            <input type="number" id="id_tai_khoan" name="id_tai_khoan" class="form-control"
                                                placeholder="VD: 12" value="<?php echo e(request('id_tai_khoan')); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="pttt" class="form-label">PT Thanh toán:</label>
                                            <input type="text" id="pttt" name="pttt" class="form-control"
                                                placeholder="Tiền mặt, VNPay..." value="<?php echo e(request('pttt')); ?>">
                                        </div>
                                        <div class="col-md-12 d-flex gap-2 mt-3">
                                            <button type="submit" class="btn btn-purple">
                                                <i class="fas fa-filter"></i> Lọc
                                            </button>
                                            <a href="<?php echo e(route('hoa-don.index')); ?>" class="btn btn-secondary">
                                                <i class="fas fa-eraser"></i> Xóa bộ lọc
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Khách hàng</th>
                                        <th>Ngày tạo</th>
                                        <th>PT Thanh toán</th>
                                        <th>Tổng tiền</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $hoaDons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $hoaDon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($hoaDons->firstItem() + $index); ?></td>
                                            <td><?php echo e($hoaDon->taiKhoan->HoTen ?? 'Không có thông tin'); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($hoaDon->NgayTao)->format('d/m/Y')); ?></td>
                                            <td><?php echo e($hoaDon->PTTT); ?></td>
                                            <td class="text-danger fw-bold">
                                                <?php echo e(number_format($hoaDon->TongTien, 0, ',', '.')); ?> đ</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('hoa-don.show', $hoaDon->ID_HoaDon)); ?>"
                                                        class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('hoa-don.destroy', $hoaDon->ID_HoaDon)); ?>"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-muted py-4">
                                                <i class="fas fa-receipt fa-3x mb-3 text-muted"></i>
                                                <div>Không tìm thấy hóa đơn nào.</div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="mt-3 d-flex justify-content-center">
                            <?php echo e($hoaDons->appends(request()->all())->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/hoa_don/hoa-don.blade.php ENDPATH**/ ?>