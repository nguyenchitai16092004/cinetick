
<?php $__env->startSection('title', 'CineTick - Danh sách tin khuyến mãi'); ?>
<?php $__env->startSection('main'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/khuyen-mai.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/home.css')); ?>">

    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="promotion-section">
        <header class="section-header">
            <h1 class="section-title">Tin khuyến mãi</h1>
        </header>
        <?php if($khuyenMais->count()): ?>
          <div class="promotion-list">
            <?php $__currentLoopData = $khuyenMais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $khuyenMai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a href="<?php echo e(route('bai-viet.chiTiet.khuyen-mai', ['slug' => $khuyenMai->Slug])); ?>">
                <div class="promotion-card">
                  <img class="promotion-img"
                    src="<?php echo e($khuyenMai->AnhDaiDien ? asset('storage/' . $khuyenMai->AnhDaiDien) : asset('images/no-image.jpg')); ?>"
                    alt="<?php echo e($khuyenMai->TieuDe); ?>">
                  <div class="promotion-overlay">
                    <div class="promotion-overlay-title">
                      <?php echo e($khuyenMai->TieuDe); ?>

                    </div>
                  </div>
                </div>
              </a>
              <?php if(($index + 1) % 4 == 0 && $index + 1 < $khuyenMais->count()): ?>
                </div><div class="promotion-list">
              <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php else: ?>
          <p class="update-showtime">
            <span class="marquee-text">Ôi không! Các chương trình khuyến mãi đâu rồi? Đừng lo lắng chúng tôi sẽ cập nhật chúng ngay thôi.</span>
          </p>
        <?php endif; ?>
        <div class="pagination-wrapper">
            <?php echo e($khuyenMais->links('pagination::bootstrap-4')); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/pages/khuyen-mai.blade.php ENDPATH**/ ?>