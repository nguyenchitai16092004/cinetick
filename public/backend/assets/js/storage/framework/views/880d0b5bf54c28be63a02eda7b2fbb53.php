<link rel="stylesheet" href="<?php echo e(asset('user/Content/css/header.css')); ?>">

<header class="header-menu">
    <div class="header__top">
        <div class="header__left">
            <a href="<?php echo e(asset('/')); ?>">
                <img src="<?php echo e($thongTinTrangWeb->Logo ? asset('storage/' . $thongTinTrangWeb->Logo) : asset('images/no-image.jpg')); ?>"
                    alt="<?php echo e($thongTinTrangWeb->TenWebsite); ?>" alt="filmoja" />
            </a>
        </div>
        <div class="header__icons-mobile">
            <!-- Icon search -->
            <button class="header__search-toggle" id="searchToggle" aria-label="Tìm kiếm" type="button">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          
            <!-- Avatar user -->
            <?php if(session()->has('user_id')): ?>
                <a href="<?php echo e(route('user.info')); ?>" class="header__user-mobile" aria-label="Tài khoản">
                    <img src="<?php echo e(asset('user/Content/img/user.svg')); ?>" alt="user-login-svg" class="user-menu__avatar"
                        style="width:32px;height:32px;">
                </a>
            <?php endif; ?>
              <!-- Nút menu hamburger -->
              <button class="header__menu-toggle" id="menuToggle" aria-label="Mở menu">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <div class="header__search" id="headerSearch">
            <form action="<?php echo e(route('tim-kiem')); ?>" method="GET" class="header__search-form">
                <input type="text" name="keyword" placeholder="Tìm phim, rạp, thể loại phim..."
                    value="<?php echo e(request('keyword') ?? ''); ?>" autocomplete="off" class="header__search-input">
                <button type="submit">
                    <i  class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
        <div class="header__right">
            <div class="header__center" id="goToBooking">
                <img src="<?php echo e(asset('user/Content/img/btn-ticket.webp')); ?>" alt="">
            </div>
            <ul class="user-menu">
                <?php if(session()->has('user_id')): ?>
                    <li class="user-menu__item user-menu__dropdown">
                        <button class="user-menu__toggle" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo e(asset('user/Content/img/user.svg')); ?>" alt="user-login-svg"
                                class="user-menu__avatar">
                            <span class="user-menu__name" title="<?php echo e(session('user_fullname')); ?>">
                                <?php echo e(session('user_fullname')); ?>

                            </span>
                            <i class="fa fa-caret-down user-menu__caret"></i>
                        </button>
                        <ul class="user-menu__dropdown-list">
                            <li>
                                <a href="<?php echo e(route('user.info')); ?>">
                                    <i class="fa fa-user"></i> Thông tin
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(asset('/')); ?>" onclick="logOut()">
                                    <i class="fa fa-sign-out"></i> Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="user-menu__item">
                        <a href="<?php echo e(route('login.form')); ?>" class="btn-member user-menu__login">
                            <img src="<?php echo e(asset('user/Content/img/user.svg')); ?>" alt="user-login-svg"
                                class="user-menu__avatar">
                            Đăng nhập / Đăng ký
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <img src="<?php echo e(asset('user/Content/img/user-avatar.webp')); ?>" alt="">
        </div>
    </div>
    <div class="header_bottom_line"></div>
    <div class="header__bottom">
        <div class="header__nav">
        </div>
        <div class="mainmenu">
            <nav>
                <ul id="responsive_navigation">
                    <li><a href="<?php echo e(asset('/')); ?>">Trang chủ</a></li>
                    <li>
                        <a>Phim</a>
                        <ul class="sub-menu-film">
                            <li><a href="<?php echo e(route('phim.dangChieu')); ?>">Phim đang chiếu</a></li>
                            <li><a href="<?php echo e(route('phim.sapChieu')); ?>">Phim sắp chiếu</a></li>
                        </ul>
                    </li>
                    <li>
                        <a>Rạp chiếu</a>
                        <ul class="sub-menu-rap">
                            <?php $__currentLoopData = $raps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($rap->TrangThai == 1): ?>
                                    <li>
                                        <a
                                            href="<?php echo e(route('rap.chiTiet', ['slug' => $rap->Slug])); ?>"><?php echo e($rap->TenRap); ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </li>
                    <li><a href="<?php echo e(route('ds-bai-viet-khuyen-mai')); ?>">Khuyến mãi</a></li>
                    <li><a href="<?php echo e(route('ds-bai-viet-dien-anh')); ?>">Điện ảnh</a></li>
                    <li><a href="<?php echo e(asset('/lien-he')); ?>">Liên hệ</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="header__nav-mobile" id="mobileMenu">
        <!-- Icon đóng menu -->
        <button class="header__menu-close" id="menuClose" aria-label="Đóng menu" type="button" style="display:none;">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <nav>
            <ul>
                <li>
                    <form action="<?php echo e(route('tim-kiem')); ?>" method="GET" class="header__search-form header__search-form--mobile">
                        <input type="text" name="keyword" placeholder="Tìm phim, rạp, thể loại phim..."
                            value="<?php echo e(request('keyword') ?? ''); ?>" autocomplete="off" class="header__search-input">
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </li>
                <li><a href="<?php echo e(asset('/')); ?>">Trang chủ</a></li>
                <li class="dropdown-mobile">
                    <a href="javascript:void(0)" class="dropdown-toggle-mobile">
                        Phim <i class="fa-solid fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-content-mobile">
                        <li><a href="<?php echo e(route('phim.dangChieu')); ?>">Phim đang chiếu</a></li>
                        <li><a href="<?php echo e(route('phim.sapChieu')); ?>">Phim sắp chiếu</a></li>
                    </ul>
                </li>
                <li class="dropdown-mobile">
                    <a href="javascript:void(0)" class="dropdown-toggle-mobile">
                        Rạp chiếu <i class="fa-solid fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-content-mobile">
                        <?php $__currentLoopData = $raps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($rap->TrangThai == 1): ?>
                                <li>
                                    <a href="<?php echo e(route('rap.chiTiet', ['slug' => $rap->Slug])); ?>"><?php echo e($rap->TenRap); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>
                <li><a href="<?php echo e(route('ds-bai-viet-khuyen-mai')); ?>">Khuyến mãi</a></li>
                <li><a href="<?php echo e(route('ds-bai-viet-dien-anh')); ?>">Điện ảnh</a></li>
                <li><a href="<?php echo e(asset('/lien-he')); ?>">Liên hệ</a></li>
                <?php if(session()->has('user_id')): ?>
                    <li><a href="<?php echo e(route('user.info')); ?>">Thông tin cá nhân</a></li>
                    <li><a href="<?php echo e(asset('/')); ?>" onclick="logOut()">Đăng xuất</a></li>
                <?php else: ?>
                    <li><a href="<?php echo e(route('login.form')); ?>">Đăng nhập / Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <div class="header__overlay" id="menuOverlay"></div>
</header>
<script src="<?php echo e(asset('user/Content/js/header.js')); ?>"></script>
<script>
    function logOut() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
            },
        });

        $.ajax({
            url: "<?php echo e(route('logout')); ?>",
            type: "POST",
            success: function(result) {
                window.location.href = "<?php echo e(url('/')); ?>";
            },
            error: function(xhr) {
                console.error("Lỗi đăng xuất:", xhr.responseText);
            },
        });
    }
</script>
<?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/partials/header.blade.php ENDPATH**/ ?>