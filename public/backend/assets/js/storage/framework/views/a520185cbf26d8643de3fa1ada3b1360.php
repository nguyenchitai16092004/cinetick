
<?php $__env->startSection('title', 'Quản lý tài khoản'); ?>

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

        .search-filter-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .search-filter-card .form-control, .search-filter-card .form-select {
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .search-filter-card .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-filter-card .form-control:focus, .search-filter-card .form-select:focus {
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.2);
        }

        .search-filter-card .input-group-text {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .badge-role {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }

        .badge-status {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }
    </style>

    <div class="container-fluid mt-4">
        <!-- Page Heading -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow rounded border-0">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">👥 Quản lý tài khoản</h3>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('tai-khoan.export', request()->query())); ?>" 
                               class="btn btn-success">
                                <i class="fas fa-download"></i> Xuất danh sách
                            </a>
                            <a href="<?php echo e(route('tai-khoan.create')); ?>" 
                               class="btn btn-purple">
                                <i class="fas fa-plus"></i> Thêm tài khoản mới
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Search & Filter Section -->
        <div class="card shadow mb-4 border-0">
            <div class="card search-filter-card">
                <div class="card-header bg-transparent border-0">
                    <h6 class="m-0 font-weight-bold">🔍 Tìm kiếm và lọc</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('tai-khoan.index')); ?>" method="GET" class="mb-0">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group mb-3 mb-md-0">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Tìm kiếm theo tên, email, số điện thoại..." 
                                           value="<?php echo e(request('search')); ?>">
                                    <button class="btn btn-light" type="submit">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-6">
                                        <select class="form-select" name="sort_by" onchange="this.form.submit()">
                                            <option value="ID_TaiKhoan" <?php echo e(request('sort_by') == 'ID_TaiKhoan' ? 'selected' : ''); ?>>ID</option>
                                            <option value="TenDN" <?php echo e(request('sort_by') == 'TenDN' ? 'selected' : ''); ?>>Tên đăng nhập</option>
                                            <option value="HoTen" <?php echo e(request('sort_by') == 'HoTen' ? 'selected' : ''); ?>>Họ tên</option>
                                            <option value="VaiTro" <?php echo e(request('sort_by', 'VaiTro') == 'VaiTro' ? 'selected' : ''); ?>>Vai trò</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-select" name="sort_order" onchange="this.form.submit()">
                                            <option value="asc" <?php echo e(request('sort_order') == 'asc' ? 'selected' : ''); ?>>Tăng dần</option>
                                            <option value="desc" <?php echo e(request('sort_order', 'desc') == 'desc' ? 'selected' : ''); ?>>Giảm dần</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow rounded border-0">
            <div class="card-header bg-purple">
                <h6 class="m-0 font-weight-bold text-white">📋 Danh sách tài khoản</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tên đăng nhập</th>
                                <th>Họ và tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $taiKhoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taiKhoan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><strong>#<?php echo e($taiKhoan->ID_TaiKhoan); ?></strong></td>
                                    <td class="text-start">
                                        <div class="fw-bold text-primary"><?php echo e($taiKhoan->TenDN); ?></div>
                                    </td>
                                    <td class="text-start"><?php echo e($taiKhoan->HoTen); ?></td>
                                    <td class="text-start"><?php echo e($taiKhoan->Email); ?></td>
                                    <td><?php echo e($taiKhoan->SDT); ?></td>
                                    <td>
                                        <?php if($taiKhoan->VaiTro == 2): ?>
                                            <span class="badge bg-danger badge-role">Quản trị viên</span>
                                        <?php elseif($taiKhoan->VaiTro == 1): ?>
                                            <span class="badge bg-warning badge-role">Nhân viên</span>
                                        <?php else: ?>
                                            <span class="badge bg-info badge-role">Người dùng</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($taiKhoan->TrangThai): ?>
                                            <span class="badge bg-success badge-status">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary badge-status">Vô hiệu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('tai-khoan.edit', $taiKhoan->ID_TaiKhoan)); ?>"
                                                class="btn btn-primary btn-sm" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?php echo e(route('tai-khoan.status', $taiKhoan->ID_TaiKhoan)); ?>"
                                                class="btn btn-warning btn-sm" title="Đổi trạng thái">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                            <a href="<?php echo e(route('tai-khoan.delete', $taiKhoan->ID_TaiKhoan)); ?>"
                                                class="btn btn-danger btn-sm" title="Xóa"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                                        <div>Không có dữ liệu tài khoản</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex justify-content-center">
                    <?php echo e($taiKhoans->links()); ?>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        $(document).ready(function() {
            // Auto hide alerts after 5 seconds
            $('.alert').delay(5000).fadeOut('slow');
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/tai_khoan/tai-khoan.blade.php ENDPATH**/ ?>