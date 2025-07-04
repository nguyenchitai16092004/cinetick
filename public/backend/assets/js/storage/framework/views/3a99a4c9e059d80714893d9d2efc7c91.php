
<?php $__env->startSection('title', 'CineTick - Chi tiết bài viết'); ?>
<?php $__env->startSection('main'); ?>
    <script>
        window.Laravel = {
            csrfToken: '<?php echo e(csrf_token()); ?>'
        };
    </script>
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/chi-tiet-bai-viet.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/home.css')); ?>">

    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <d class="floating-circle"></d    iv>
        <div class="floating-circle"></div>
    </div>
    
    <nav class="breadcrumb">
        <a class="home" href="<?php echo e(route('home')); ?>">Trang chủ</a> /
        <?php
            $prefix = request()->route()->getPrefix();
        ?>
        <?php if($prefix === '/goc-dien-anh'): ?>
            <a class="cinema-corner" href="<?php echo e(route('ds-bai-viet-dien-anh')); ?>">Góc điện ảnh</a> /
        <?php elseif($prefix === '/tin-khuyen-mai'): ?>
            <a class="promotion-corner" href="<?php echo e(route('ds-bai-viet-khuyen-mai')); ?>">Tin khuyến mãi</a> /
        <?php endif; ?>
        <span><?php echo e($tinTuc->TieuDe); ?></span>
    </nav>

    <main class="review-content">
        <h1><?php echo e($tinTuc->TieuDe); ?></h1>
        <div class="review-actions">
            <button id="likeBtn" class="action-btn">
                <i id="likeIcon" class="fa-regular fa-thumbs-up"></i> Thích <span
                    id="likeCount"><?php echo e($tinTuc->LuotThich); ?></span>
            </button>
            <button class="action-btn share-btn" id="shareBtn">
                <i class="fa-solid fa-share-nodes"></i> Chia sẻ
            </button>
            <div class="views">
                <i class="fa-regular fa-eye"></i> <span id="viewCount"><?php echo e($tinTuc->LuotXem); ?></span>
            </div>
        </div>
        <div class="intro">
            <?php echo $tinTuc->NoiDung; ?>

        </div>
    </main>
    <script src=" <?php echo e(asset('user/Content/js/chi-tiet-bai-viet.js')); ?>"></script>
    <script>
        const likeBtn = document.getElementById("likeBtn");
        const likeCount = document.getElementById("likeCount");
        const likeIcon = document.getElementById("likeIcon");
        const postSlug = "<?php echo e($tinTuc->Slug); ?>";
        let liked = <?php echo e($userLikedThis ? 'true' : 'false'); ?>;
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/pages/chi-tiet-bai-viet.blade.php ENDPATH**/ ?>