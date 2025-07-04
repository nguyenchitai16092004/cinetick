
<?php $__env->startSection('title', 'Tạo Phim'); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .btn-primary {
            background-color: rgb(111, 66, 193);
            border-color: rgb(111, 66, 193);
        }

        .btn-primary:hover {
            background-color: rgb(95, 56, 165);
            border-color: rgb(95, 56, 165);
        }

        .card-header {
            background-color: rgb(111, 66, 193);
            color: white;
        }

        .card-title {
            color: white;
        }

        label {
            margin-top: 10px;
        }

        .form-control:focus {
            border-color: rgb(111, 66, 193);
            box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thêm phim mới</h3>
                        <div class="card-tools">
                            <a href="<?php echo e(route('phim.index')); ?>" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('phim.store')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="TenPhim">Tên phim <span class="text-danger">*</span></label>
                                        <input type="text" name="TenPhim" id="TenPhim" class="form-control"
                                            value="<?php echo e(old('TenPhim')); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="ThoiLuong">Thời lượng (phút) <span class="text-danger">*</span></label>
                                        <input type="number" name="ThoiLuong" id="ThoiLuong" class="form-control"
                                            value="<?php echo e(old('ThoiLuong')); ?>" required min="1">
                                    </div>

                                    <div class="form-group">
                                        <label for="NgayKhoiChieu">Ngày khởi chiếu <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="NgayKhoiChieu" id="NgayKhoiChieu" class="form-control"
                                            value="<?php echo e(old('NgayKhoiChieu')); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="NgayKetThuc">Ngày kết thúc <span class="text-danger">*</span></label>
                                        <input type="date" name="NgayKetThuc" id="NgayKetThuc" class="form-control"
                                            value="<?php echo e(old('NgayKetThuc')); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="Trailer">Trailer URL</label>
                                        <input type="url" name="Trailer" id="Trailer" class="form-control"
                                            value="<?php echo e(old('Trailer')); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="ID_TheLoaiPhim">Thể loại phim <span class="text-danger">*</span></label>
                                        <select name="ID_TheLoaiPhim[]" id="ID_TheLoaiPhim" class="form-control" multiple
                                            required>
                                            <?php $__currentLoopData = $theLoais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theLoai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($theLoai->ID_TheLoaiPhim); ?>"
                                                    <?php echo e(collect(old('ID_TheLoaiPhim'))->contains($theLoai->ID_TheLoaiPhim) ? 'selected' : ''); ?>>
                                                    <?php echo e($theLoai->TenTheLoai); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="NhaSanXuat">Nhà sản xuất <span class="text-danger">*</span></label>
                                        <input type="text" name="NhaSanXuat" id="NhaSanXuat" class="form-control"
                                            value="<?php echo e(old('NhaSanXuat')); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="DaoDien">Đạo diễn <span class="text-danger">*</span></label>
                                        <input type="text" name="DaoDien" id="DaoDien" class="form-control"
                                            value="<?php echo e(old('DaoDien')); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="DienVien">Diễn viên <span class="text-danger">*</span></label>
                                        <input type="text" name="DienVien" id="DienVien" class="form-control"
                                            value="<?php echo e(old('DienVien')); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="DoHoa">Đồ họa</label>
                                        <select name="DoHoa" id="DoHoa" class="form-control" required>
                                            <option value="" disabled selected hidden>-- Chọn đồ họa --</option>
                                            <option value="2D" <?php echo e(old('DoHoa') == '2D' ? 'selected' : ''); ?>>2D</option>
                                            <option value="3D" <?php echo e(old('DoHoa') == '3D' ? 'selected' : ''); ?>>3D</option>
                                        </select>
                                    </div>

                                    <label for="QuocGia">Quốc gia <span class="text-danger">*</span></label>
                                    <select name="QuocGia" id="QuocGia" class="form-control" required>
                                        <option value="" disabled selected hidden>-- Chọn quốc gia --</option>
                                        <option value="Việt Nam">Việt Nam</option>
                                        <option value="Hoa Kỳ">Hoa Kỳ</option>
                                        <option value="Tây Ban Nha">Tây Ban Nha</option>
                                        <option value="Pháp">Pháp</option>
                                        <option value="Đức">Đức</option>
                                        <option value="Ý">Ý</option>
                                        <option value="Nhật Bản">Nhật Bản</option>
                                        <option value="Hàn Quốc">Hàn Quốc</option>
                                        <option value="Trung Quốc">Trung Quốc</option>
                                        <option value="Nga">Nga</option>
                                        <option value="Mỹ">Mỹ</option>
                                        <option value="Thái Lan">Thái Lan</option>
                                    </select>

                                    <div class="form-group">
                                        <label for="DoTuoi">Độ tuổi <span class="text-danger">*</span></label>
                                        <select name="DoTuoi" id="DoTuoi" class="form-control" required>
                                            <option value="P">Loại P - Cho mọi lứa tuổi</option>
                                            <option value="K">Loại K - Dưới 13 tuổi (cần giám hộ)</option>
                                            <option value="T13 (13+)">Loại T13 (13+)</option>
                                            <option value="T16 (16+)">Loại T16 (16+)</option>
                                            <option value="T18 (18+)">Loại T18 (18+)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="HinhAnh">Hình ảnh</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="HinhAnh"
                                                name="HinhAnh" accept="image/*">
                                            <label class="custom-file-label" for="HinhAnh">Chọn ảnh</label>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="MoTaPhim">Mô tả phim <span class="text-danger">*</span></label>
                                        <textarea name="MoTaPhim" id="MoTaPhim" class="form-control" rows="5" required><?php echo e(old('MoTaPhim')); ?></textarea>
                                    </div>

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary" style="margin: 10px">
                                            <i class="fas fa-save"></i> Lưu phim
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/phim/create_phim.blade.php ENDPATH**/ ?>