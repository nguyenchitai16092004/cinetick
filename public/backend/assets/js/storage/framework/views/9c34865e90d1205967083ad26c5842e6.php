
<?php $__env->startSection('title', 'Chi tiết bảng tin tức'); ?>

<?php $__env->startSection('main'); ?>
    <div class="container mt-4">
        <h2>Cập nhật tin tức</h2>

        <form action="<?php echo e(route('tin_tuc.update', $tinTuc->ID_TinTuc)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>


            <div class="form-group">
                <?php if($tinTuc->AnhDaiDien): ?>
                    <img src="<?php echo e($tinTuc->AnhDaiDien ? asset('storage/' . $tinTuc->AnhDaiDien) : asset('images/no-image.jpg')); ?>"
                        width="120">
                <?php endif; ?>
                <label>Hình ảnh hiện tại</label><br>
                <div class="form-group">
                    <input type="file" name="AnhDaiDien" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label>Tiêu đề</label>
                <input type="text" name="TieuDe" value="<?php echo e($tinTuc->TieuDe); ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nội dung</label>
                <textarea name="NoiDung" class="form-control" rows="10"><?php echo e(old('NoiDung', $tinTuc->NoiDung)); ?></textarea>
            </div>

            <div class="form-group">
                <label>Loại bài viết</label>
                <select name="LoaiBaiViet" class="form-control" required>
                    <option value="1" <?php echo e($tinTuc->LoaiBaiViet == 1 ? 'selected' : ''); ?>>Khuyến mãi</option>
                    <option value="2" <?php echo e($tinTuc->LoaiBaiViet == 2 ? 'selected' : ''); ?>>Giới thiệu</option>
                    <option value="3" <?php echo e($tinTuc->LoaiBaiViet == 3 ? 'selected' : ''); ?>>Chính sách</option>
                    <option value="4" <?php echo e($tinTuc->LoaiBaiViet == 4 ? 'selected' : ''); ?>>Phim</option>
                </select>
            </div>



            <div class="form-group">
                <label>ID Tài khoản</label>
                <input type="number" name="ID_TaiKhoan" value="<?php echo e($tinTuc->ID_TaiKhoan); ?>" class="form-control" required>
            </div>
            <select name="TrangThai" class="form-control" required>
                <option value="0" <?php echo e($tinTuc->TrangThai == 0 ? 'selected' : ''); ?>>Chờ xuất bản</option>
                <option value="1" <?php echo e($tinTuc->TrangThai == 1 ? 'selected' : ''); ?>>Xuất bản</option>
            </select>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
    <script src="https://cdn.tiny.cloud/1/sasoygoht1uf9889ttoe6d3ut0fkhp824q1z9fmh7zoea39y/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea[name=NoiDung]',
            plugins: [
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media',
                'searchreplace', 'table', 'visualblocks', 'wordcount',
                'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker',
                'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage',
                'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags',
                'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject(
                'See docs to implement AI Assistant')),
            images_upload_url: '<?php echo e(route("tin_tuc.upload_image")); ?>',
            images_upload_credentials: true,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/tin_tuc/detail-tin-tuc.blade.php ENDPATH**/ ?>