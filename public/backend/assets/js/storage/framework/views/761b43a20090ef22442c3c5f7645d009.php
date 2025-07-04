
<?php $__env->startSection('title', 'Liên hệ / Trợ giúp'); ?>

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

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.5rem;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .pagination li {
            margin: 0 3px;
        }

        .page-link {
            color: #2c3e50;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.4rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
            font-weight: 500;
        }

        .page-link:hover,
        .page-link:focus {
            color: #fff;
            background-color: #6f42c1;
            border-color: #6f42c1;
            box-shadow: 0 2px 8px rgba(111, 66, 193, 0.3);
            text-decoration: none;
        }

        .pagination .active .page-link {
            color: #fff;
            background-color: #6f42c1;
            border-color: #6f42c1;
            pointer-events: none;
        }

        .pagination .disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
            border-color: #dee2e6;
            pointer-events: none;
            opacity: 0.6;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">📞 Quản lý liên hệ khách hàng</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Điện thoại</th>
                                        <th>Tiêu đề</th>
                                        <th>Nội dung</th>
                                        <th>Ảnh minh họa</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian gửi</th>
                                        <th class="text-end">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr <?php if($contact->TrangThai == 0): ?> style="background:#fff7f5;" <?php endif; ?>>
                                            <td><?php echo e($contact->ID_LienHe); ?></td>
                                            <td class="fw-bold"><?php echo e($contact->HoTenNguoiLienHe); ?></td>
                                            <td>
                                                <a href="mailto:<?php echo e($contact->Email); ?>" class="text-decoration-none">
                                                    <?php echo e($contact->Email); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($contact->SDT); ?></td>
                                            <td class="fw-bold"><?php echo e($contact->TieuDe); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                                    data-bs-target="#modalNoiDung<?php echo e($contact->ID_LienHe); ?>">
                                                    <i class="fas fa-eye"></i> Xem
                                                </button>
                                                <!-- Modal nội dung -->
                                                <div class="modal fade" id="modalNoiDung<?php echo e($contact->ID_LienHe); ?>" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-purple text-white">
                                                                <h5 class="modal-title">Nội dung liên hệ</h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="mb-0"><?php echo e($contact->NoiDung); ?></p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($contact->AnhMinhHoa): ?>
                                                    <a href="<?php echo e(asset('storage/' . $contact->AnhMinhHoa)); ?>" target="_blank">
                                                        <img src="<?php echo e(asset('storage/' . $contact->AnhMinhHoa)); ?>"
                                                            alt="Ảnh minh họa" class="img-thumbnail"
                                                            style="width:48px; height:48px; object-fit:cover;">
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic">Không có</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($contact->TrangThai == 0): ?>
                                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Đã xử lý</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span title="<?php echo e($contact->created_at); ?>">
                                                    <?php echo e(\Carbon\Carbon::parse($contact->created_at)->format('d/m/Y H:i')); ?>

                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <?php if($contact->TrangThai == 0): ?>
                                                    <form action="<?php echo e(route('lien-he.xuly', $contact->ID_LienHe)); ?>" method="POST"
                                                        class="d-inline form-xuly">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="button" title="Đánh dấu đã xử lý liên hệ này?"
                                                            class="btn btn-sm btn-success btn-xuly">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <form action="<?php echo e(route('lien-he.destroy', $contact->ID_LienHe)); ?>" method="POST"
                                                    class="d-inline form-xoa">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="button" title="Xóa liên hệ này?"
                                                        class="btn btn-sm btn-outline-danger btn-xoa">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-4">Chưa có liên hệ nào.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            <?php echo e($contacts->links('pagination::bootstrap-4')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('backend/assets/js/lien-he.js')); ?>"></script>
    <?php if(session('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: '<?php echo e(session('success')); ?>',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/lien_he/lien_he.blade.php ENDPATH**/ ?>