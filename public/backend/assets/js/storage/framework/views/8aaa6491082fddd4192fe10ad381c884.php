
<?php $__env->startSection('title', 'CineTick - Tìm kiếm'); ?>
<?php $__env->startSection('main'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/tim-kiem.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/rap.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/home.css')); ?>">

    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="container search-page">
        <h3 class="result-search">Kết quả tìm kiếm cho: <u class="search-keyword"><?php echo e($keyword); ?></u></h3>

        <?php
            $hasPhim = isset($phims) && !$phims->isEmpty();
            $hasRap = isset($raps) && $raps && $raps->count();
            $hasRapsByPhim = isset($rapsByPhim) && $rapsByPhim && $rapsByPhim->count();
        ?>

        <?php if(!$hasPhim && !$hasRap && !$hasRapsByPhim): ?>
            <p class="not-found">Không tìm thấy kết quả cho "<u class="search-keyword"><?php echo e($keyword); ?></u>"</p>
        <?php else: ?>
            
            <?php if($hasPhim): ?>
                <div class="header-2">
                    <h1 class="twinkle-title-pro">
                        <span class="star-pro star-pro-1"></span>
                        <span class="star-pro star-pro-2"></span>
                        <span class="star-pro star-pro-3"></span>
                        Phim
                        <span class="star-pro star-pro-4"></span>
                        <span class="star-pro star-pro-5"></span>
                        <span class="star-pro star-pro-6"></span>
                    </h1>
                    <p>Trải nghiệm điện ảnh đỉnh cao tại rạp chiếu phim CineTick</p>
                </div>
                <div class="films-list">
                    <?php $__currentLoopData = $phims; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('phim.chiTiet', ['slug' => $phim->Slug])); ?>" class="film-card-link">
                            <div class="film-card">
                                <div class="film-poster">
                                    <img src="<?php echo e($phim->HinhAnh ? asset('storage/' . $phim->HinhAnh) : asset('images/no-image.jpg')); ?>"
                                        alt="<?php echo e($phim->TenPhim); ?>">
                                    <div class="film-rating-crou film-rating-crou-3 ">
                                        <span class="star">
                                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="star"
                                                class="svg-inline--fa fa-star text-yellow-400 mr-3 ml-4 text-[12px]"
                                                role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
                                                width="15px">
                                                <path fill="#FFD700"
                                                    d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z">
                                                </path>
                                            </svg>
                                        </span>
                                        <span><?php echo e($phim->avg_rating); ?></span>
                                    </div>
                                    <div class="age-rating"><?php echo e($phim->DoTuoi); ?></div>
                                </div>
                                <div class="film-title"><?php echo e($phim->TenPhim); ?></div>
                                <p class="movie-genre">
                                    <?php $__currentLoopData = $phim->theLoai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $theLoai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($theLoai->TenTheLoai); ?><?php echo e($index < count($phim->theLoai) - 1 ? ', ' : ''); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </p>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="pagination-wrapper">
                    <?php echo e($phims->links('pagination::bootstrap-4')); ?>

                </div>
            <?php endif; ?>

            <?php if(isset($rapsSearch) && $rapsSearch->count()): ?>
                <div class="header-2 ">
                    <h1 class="twinkle-title-pro">
                        <span class="star-pro star-pro-1"></span>
                        <span class="star-pro star-pro-2"></span>
                        <span class="star-pro star-pro-3"></span>
                        Rạp
                        <span class="star-pro star-pro-4"></span>
                        <span class="star-pro star-pro-5"></span>
                        <span class="star-pro star-pro-6"></span>
                    </h1>
                    <p>Khám phá hệ thống rạp CineTick trên toàn quốc</p>
                </div>
                <div class="cinema-list-wrapper">
                    <?php $__currentLoopData = $rapsSearch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="cinema-card">
                            <div class="cinema-thumb">
                                <a href="<?php echo e(route('rap.chiTiet', ['slug' => $rap->Slug])); ?>">
                                    <img src="<?php echo e($rap->HinhAnh ? asset('storage/' . $rap->HinhAnh) : asset('images/no-image.jpg')); ?>"
                                        alt="<?php echo e($rap->TenRap); ?>">
                                </a>
                            </div>
                            <div class="cinema-body">
                                <h3 class="cinema-title"><?php echo e($rap->TenRap); ?></h3>
                                <div class="cinema-address">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span><?php echo e($rap->DiaChi); ?></span>
                                </div>
                                <div class="cinema-hotline">
                                    <i class="fa-solid fa-phone-volume"></i>
                                    <span><?php echo e($rap->Hotline ?: 'Đang cập nhật'); ?></span>
                                </div>
                                <a href="<?php echo e(route('rap.chiTiet', ['slug' => $rap->Slug])); ?>"
                                    class="cinema-ticket-btn submit-btn">Đặt
                                    vé ngay</a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php elseif(request('keyword')): ?>
                <div class="no-result">
                    <p>Không tìm thấy rạp phù hợp với từ khóa "<strong><?php echo e(request('keyword')); ?></strong>"</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/pages/tim-kiem.blade.php ENDPATH**/ ?>