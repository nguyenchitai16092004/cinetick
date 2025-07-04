<link rel="stylesheet" href="<?php echo e(asset('user/Content/css/footer.css')); ?>">
<footer class="custom-footer">
    <div class="footer-main">
        <div class="footer-section">
            <h3>GIỚI THIỆU</h3>
            <ul>
                <?php $__currentLoopData = $footerGioiThieu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e(route('thongtincinetick.static', ['slug' => $item->Slug])); ?>">
                            <?php echo e($item->TieuDe); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <div class="footer-section footer-section-policy">
            <h3>CHÍNH SÁCH</h3>
            <ul>
                <?php $__currentLoopData = $footerChinhSach; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e(route('thongtincinetick.static', ['slug' => $item->Slug])); ?>">
                            <?php echo e($item->TieuDe); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <div class="footer-section">
            <h3>HỖ TRỢ</h3>
            <ul>
                <li><a href="<?php echo e(route('lien-he')); ?>">Liên hệ</a></li>

                </li>
            </ul>
        </div>
        <div class="footer-brand">
            <div class="footer-logo">
                <img src="<?php echo e($thongTinTrangWeb->Logo ? asset('storage/' . $thongTinTrangWeb->Logo) : asset('images/no-image.jpg')); ?>"
                    alt="<?php echo e($thongTinTrangWeb->TenWebsite); ?>" alt="Logo" />
            </div>
            <div class="footer-social">
                <a href="<?php echo e($thongTinTrangWeb->Facebook); ?>"><i class="fab fa-facebook-f"></i></a>
                <a href="<?php echo e($thongTinTrangWeb->Youtube); ?>"><i class="fab fa-youtube"></i></a>
                <a href="<?php echo e($thongTinTrangWeb->Instagram); ?>"><i class="fab fa-instagram"></i></a>
            </div>
            
        </div>
    </div>
    <hr />
    <div class="footer-bottom">
        <div class="footer-bottom-logo">
            <img src="<?php echo e($thongTinTrangWeb->Logo ? asset('storage/' . $thongTinTrangWeb->Logo) : asset('images/no-image.jpg')); ?>"
                alt="<?php echo e($thongTinTrangWeb->TenWebsite); ?>" alt="Logo" />
        </div>
        <div class="footer-bottom-info">
            <div class="info-name-company">
                <strong ><?php echo e($thongTinTrangWeb->TenDonVi); ?></strong>
            </div>
            <div class="info-name-company">
                <?php echo e($thongTinTrangWeb->DiaChi); ?>

            </div>
            <div>
                <div>
                    <i class="fa-solid fa-phone footer-icon"></i> <?php echo e($thongTinTrangWeb->Hotline); ?> (9:00 - 22:00) -
                    <i class="fa-solid fa-envelope footer-icon"></i> <?php echo e($thongTinTrangWeb->Email); ?>

                </div>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/partials/footer.blade.php ENDPATH**/ ?>