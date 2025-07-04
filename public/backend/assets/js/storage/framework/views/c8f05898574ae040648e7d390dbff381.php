
<?php $__env->startSection('title', 'Quản lý Rạp'); ?>

<?php $__env->startSection('main'); ?>
    <style>
        tr td{
            padding: 20px !important;
        }
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
                        <h3 class="card-title mb-0">🏢 Danh sách rạp</h3>
                        <a href="<?php echo e(route('rap.create')); ?>" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm rạp mới
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tên Rạp</th>
                                        <th>Hotline</th>
                                        <th>Địa Chỉ</th>
                                        <th>Trạng Thái</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $raps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="fw-bold text-start"><?php echo e($rap->TenRap); ?></td>
                                            <td><?php echo e($rap->Hotline); ?></td>
                                            <td class="text-start col-5"><?php echo e(\Illuminate\Support\Str::limit($rap->DiaChi, 200)); ?></td>
                                            <td>
                                                <?php if($rap->TrangThai == 1): ?>
                                                    <span class="badge bg-success">Hoạt động</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">Bảo trì</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('rap.edit', $rap->ID_Rap)); ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="moModalXoa(<?php echo e($rap->ID_Rap); ?>, '<?php echo e($rap->TenRap); ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                                <!-- Form xóa ẩn -->
                                                <form id="form-xoa-<?php echo e($rap->ID_Rap); ?>"
                                                    action="<?php echo e(route('rap.destroy', $rap->ID_Rap)); ?>" method="POST"
                                                    style="display: none;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xác nhận xoá -->
    <div class="modal fade" id="modalXacNhanXoa" tabindex="-1" aria-labelledby="modalXoaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalXoaLabel">Xác nhận xoá rạp</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xoá rạp <strong id="tenRapModal"></strong>?</p>
                    <p class="text-danger">Nút xoá sẽ kích hoạt sau <span id="demNguoc">5</span> giây.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="button" class="btn btn-danger" id="btnXacNhanXoa" disabled>Xoá</button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        let idRapCanXoa = null;
        let timeoutXoa = null;
        let intervalDemNguoc = null;

        function moModalXoa(id, tenRap) {
            idRapCanXoa = id;
            document.getElementById('tenRapModal').textContent = tenRap;
            document.getElementById('btnXacNhanXoa').disabled = true;

            let giay = 5;
            document.getElementById('demNguoc').textContent = giay;

            intervalDemNguoc = setInterval(() => {
                giay--;
                document.getElementById('demNguoc').textContent = giay;
                if (giay <= 0) {
                    clearInterval(intervalDemNguoc);
                    document.getElementById('btnXacNhanXoa').disabled = false;
                }
            }, 1000);

            const modal = new bootstrap.Modal(document.getElementById('modalXacNhanXoa'));
            modal.show();
        }

        document.getElementById('btnXacNhanXoa').addEventListener('click', function() {
            if (idRapCanXoa) {
                document.getElementById('form-xoa-' + idRapCanXoa).submit();
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/rap/rap.blade.php ENDPATH**/ ?>