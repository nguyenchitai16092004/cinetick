
<?php $__env->startSection('title', 'CineTick - ' . ($rap->TenRap ?? 'Thông tin rạp')); ?>
<?php $__env->startSection('main'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/home.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/rap.css')); ?>">
    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <section class="film-section">
        <div class="film-box">
            <div class="header-content">
                <div class="cinema-info">
                    <h2><?php echo e($rap->TenRap); ?></h2>
                    <div class="cinema-address">
                        Địa chỉ: <?php echo e($rap->DiaChi); ?><br>
                        Hotline: <a href="tel:<?php echo e($rap->Hotline); ?>" class="hotline"><?php echo e($rap->Hotline); ?></a>
                    </div>
                </div>
            </div>

            <header class="section-header">
                <h1 class="section-title">PHIM</h1>
            </header>
            <div class="date-tabs">
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="tab <?php echo e($idx == 0 ? 'active' : ''); ?>" data-date="<?php echo e($date); ?>">
                        <?php echo e(ucwords(\Carbon\Carbon::parse($date)->translatedFormat('l'))); ?><br>
                        <span><?php echo e(\Carbon\Carbon::parse($date)->format('d/m')); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="films-lists-wrapper">
                <div class="header-2">
                    <h1 class="twinkle-title-pro">
                        <span class="star-pro star-pro-1"></span>
                        <span class="star-pro star-pro-2"></span>
                        <span class="star-pro star-pro-3"></span>
                        Suất chiếu hiện đang có tại rạp <?php echo e($rap->TenRap); ?>

                        <span class="star-pro star-pro-4"></span>
                        <span class="star-pro star-pro-5"></span>
                        <span class="star-pro star-pro-6"></span>
                    </h1>
                    <p>Trải nghiệm điện ảnh đỉnh cao tại rạp chiếu phim CineTick</p>
                </div>
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="films-by-date" data-date="<?php echo e($date); ?>"
                        style="<?php echo e($idx == 0 ? '' : 'display:none'); ?>">
                        <div class="films-list">
                            <?php $__empty_1 = true; $__currentLoopData = $phimsByDay[$date]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e(route('phim.chiTiet', ['slug' => $phim->Slug])); ?>" class="film-card-link">
                                    <div class="film-card">
                                        <div class="film-poster">
                                            <img src="<?php echo e($phim->HinhAnh ? asset('storage/' . $phim->HinhAnh) : asset('images/no-image.jpg')); ?>"
                                                alt="<?php echo e($phim->TenPhim); ?>">
                                            <div class="film-rating-crou film-rating-crou-3">
                                                <span class="star"><svg aria-hidden="true" focusable="false"
                                                        data-prefix="fas" data-icon="star"
                                                        class="svg-inline--fa fa-star text-yellow-400 mr-3 ml-4 text-[12px]"
                                                        role="img" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 576 512" width="15px">
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="update-showtime">
                                    <span class="marquee-text">Suất chiếu tại rạp <?php echo e($rap->TenRap); ?> đang được cập
                                        nhật...</span>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="info-container">
            <div class="cinema-detail">
                <header class="section-header">
                    <h1 class="section-title">Thông tin chi tiết</h1>
                </header>
                <div class="detail-address">
                    <strong>Địa chỉ:</strong> <?php echo e($rap->DiaChi); ?><br>
                    <strong>Số điện thoại:</strong> <a href="tel:<?php echo e($rap->Hotline); ?>"
                        class="hotline"><?php echo e($rap->Hotline); ?></a>
                </div>

                <div class="detail-desc">
                    <?php echo $rap->MoTa ?? 'Đang cập nhật...'; ?>

                </div>
            </div>
        </div>
    </section>


    <script src=" <?php echo e(asset('user/Content/js/home.js')); ?>"></script>
    <script src="<?php echo e(asset('user/Content/js/rap.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/pages/rap.blade.php ENDPATH**/ ?>