
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

        tr td {
            padding: 20px !important;
        }
    </style>

    <div class="container-fluid mt-4 h-100">
        <div class="row justify-content-center h-100">
            <div class="col-lg-12">
                <div class="card shadow rounded h-100">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">🎭 Quản lý phòng chiếu</h3>
                        <a href="<?php echo e(route('phong-chieu.create')); ?>" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm phòng chiếu mới
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tên Phòng</th>
                                        <th>Rạp</th>
                                        <th>Số ghế</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $phongChieus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phong): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo e($phong->TenPhongChieu); ?></td>
                                            <td><?php echo e($phong->rap->TenRap ?? 'N/A'); ?></td>
                                            <td><?php echo e($phong->SoLuongGhe); ?></td>
                                            <td>
                                                <?php if($phong->TrangThai): ?>
                                                    <span class="badge bg-success">Hoạt động</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">Bảo trì</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('phong-chieu.show', $phong->ID_PhongChieu)); ?>"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('<?php echo e(route('phong-chieu.destroy', $phong->ID_PhongChieu)); ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            <div class="d-flex justify-content-center mt-3">
                                <?php echo e($phongChieus->links()); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal xác nhận xóa -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deleteForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">⚠️ Cảnh báo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn sắp xóa <strong>phòng chiếu</strong> này.</p>
                        <p><span class="text-danger">⚠️ Toàn bộ suất chiếu liên quan sẽ bị xóa vĩnh viễn.</span></p>
                        <p>Bạn có chắc chắn muốn tiếp tục?</p>
                        <p class="fw-bold text-danger">Vui lòng chờ <span id="countdown">5</span> giây để xác nhận.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" id="confirmDeleteBtn" class="btn btn-danger" disabled>Xác nhận xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        let deleteForm = document.getElementById('deleteForm');
        let countdownSpan = document.getElementById('countdown');
        let confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        let countdownInterval;

        function confirmDelete(actionUrl) {
            deleteForm.action = actionUrl;
            confirmDeleteBtn.disabled = true;
            let countdown = 5;
            countdownSpan.textContent = countdown;

            clearInterval(countdownInterval);
            countdownInterval = setInterval(() => {
                countdown--;
                countdownSpan.textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    confirmDeleteBtn.disabled = false;
                }
            }, 1000);

            let modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/phong_chieu/phong-chieu.blade.php ENDPATH**/ ?>