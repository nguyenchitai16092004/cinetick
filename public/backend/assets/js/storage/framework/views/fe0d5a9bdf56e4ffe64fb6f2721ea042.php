
<?php $__env->startSection('title', 'CineTick - Danh sách bài viết điện ảnh'); ?>
<?php $__env->startSection('main'); ?>
    <script>
        window.Laravel = {
            csrfToken: '<?php echo e(csrf_token()); ?>'
        };
    </script>
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/dien-anh.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/home.css')); ?>">

    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="container-blog">
        <header class="section-header">
            <h1 class="section-title">Góc điện ảnh</h1>
        </header>
        <div class="section-title-bar"></div>
        <div class="movie-list">
            <?php if($dienAnhs->count()): ?>
                <?php $__currentLoopData = $dienAnhs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dienAnh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('bai-viet.chiTiet.dien-anh', ['slug' => $dienAnh->Slug])); ?>">
                        <div class="movie-item">
                            <img class="movie-thumb"
                                src="<?php echo e($dienAnh->AnhDaiDien ? asset('storage/' . $dienAnh->AnhDaiDien) : asset('images/no-image.jpg')); ?>"
                                alt="<?php echo e($dienAnh->TieuDe); ?>">
                            <div class="movie-detail">
                                <div class="post-movie-title"><?php echo e($dienAnh->TieuDe); ?></div>
                                <div class="movie-actions">
                                    <button class="btn-like" data-slug="<?php echo e($dienAnh->Slug); ?>">
                                        <i class="fa-regular fa-thumbs-up"></i> Thích
                                        <span><?php echo e($dienAnh->LuotThich); ?></span>
                                    </button>
                                    <div class="like-count">
                                        <i class="fa-regular fa-eye"></i>
                                        <span class="like-num"><?php echo e($dienAnh->LuotXem); ?></span>
                                    </div>
                                </div>
                                <div class="movie-desc">
                                    <?php echo Str::words(strip_tags($dienAnh->NoiDung), 60, '...'); ?> </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <p class="update-showtime">
                    <span class="marquee-text">Ôi không! Các bài viết điện ảnh đâu rồi? Đừng lo lắng chúng tôi sẽ cập nhật chúng ngay thôi.</span>
                </p>
            <?php endif; ?>
        </div>
        <div class="pagination-wrapper">
            <?php echo e($dienAnhs->links('pagination::bootstrap-4')); ?>

        </div>
    </div>
    <script src="<?php echo e(asset('user/Content/js/dien-anh.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/pages/dien-anh.blade.php ENDPATH**/ ?>