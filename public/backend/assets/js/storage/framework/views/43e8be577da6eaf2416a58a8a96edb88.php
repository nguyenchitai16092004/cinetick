
<?php $__env->startSection('title', 'Quản lý tin tức'); ?>

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

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">📰 Quản lý tin tức</h3>
                        <a href="<?php echo e(route('tin_tuc.create')); ?>" class="btn btn-purple">
                            <i class="fas fa-plus"></i> Thêm tin tức mới
                        </a>
                    </div>
                    <div class="card-body">
                        
                        <form action="" method="GET" class="mb-4">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label" for="loai_bai_viet">Lọc theo loại bài viết:</label>
                                    <select name="loai_bai_viet" id="loai_bai_viet" class="form-select">
                                        <option value="">Tất cả</option>
                                        <option value="1" <?php echo e(request('loai_bai_viet') == 1 ? 'selected' : ''); ?>>Khuyến mãi</option>
                                        <option value="2" <?php echo e(request('loai_bai_viet') == 2 ? 'selected' : ''); ?>>Giới thiệu</option>
                                        <option value="3" <?php echo e(request('loai_bai_viet') == 3 ? 'selected' : ''); ?>>Chính sách</option>
                                        <option value="4" <?php echo e(request('loai_bai_viet') == 4 ? 'selected' : ''); ?>>Phim</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-filter"></i> Lọc
                                    </button>
                                </div>
                            </div>
                        </form>

                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Loại bài</th>
                                        <th>Hình ảnh</th>
                                        <th>Người đăng</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $tinTucs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="fw-bold text-start"><?php echo e($tin->TieuDe); ?></td>
                                            <td>
                                                <?php switch($tin->LoaiBaiViet):
                                                    case (1): ?>
                                                        <span class="badge bg-warning text-dark">Khuyến mãi</span>
                                                        <?php break; ?>
                                                    <?php case (2): ?>
                                                        <span class="badge bg-info">Giới thiệu</span>
                                                        <?php break; ?>
                                                    <?php case (3): ?>
                                                        <span class="badge bg-secondary">Chính sách</span>
                                                        <?php break; ?>
                                                    <?php default: ?>
                                                        <span class="badge bg-primary">Phim</span>
                                                <?php endswitch; ?>
                                            </td>
                                            <td>
                                                <?php if($tin->AnhDaiDien): ?>
                                                    <img src="<?php echo e(asset('storage/' . $tin->AnhDaiDien)); ?>"
                                                        width="80" class="img-thumbnail" alt="Ảnh đại diện">
                                                <?php else: ?>
                                                    <img src="<?php echo e(asset('images/no-image.jpg')); ?>"
                                                        width="80" class="img-thumbnail" alt="Không có ảnh">
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($tin->TenDN); ?></td>
                                            <td>
                                                <?php if($tin->TrangThai == 1): ?>
                                                    <span class="badge bg-success">Đã xuất bản</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">Chờ xuất bản</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('tin_tuc.edit', $tin->ID_TinTuc)); ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('tin_tuc.destroy', $tin->ID_TinTuc)); ?>" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin tức này?')">
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
                                            <td colspan="6" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            <?php echo e($tinTucs->appends(request()->query())->links('pagination::bootstrap-4')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/tin_tuc/tin-tuc.blade.php ENDPATH**/ ?>