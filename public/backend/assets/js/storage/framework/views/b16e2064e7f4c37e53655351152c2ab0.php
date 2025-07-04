
<?php $__env->startSection('title', 'CineTick - Liên hệ'); ?>
<?php $__env->startSection('main'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/lien-he.css')); ?>">


    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="contact-container">
        <!-- Left: Social & Title -->
        <div class="left-col">
            <div class="header">
                <h1>LIÊN HỆ VỚI CHÚNG TÔI</h1>
            </div>
            <div>
                <a href="<?php echo e($thongTinTrangWeb->Facebook); ?>">
                    <div class="social-box facebook">
                        <img src="<?php echo e(asset('user/Content/img/fb.png')); ?>" alt="Facebook" />
                        <span class="social-label">FACEBOOK</span>
                    </div>
                </a>
                <a href="<?php echo e($thongTinTrangWeb->Instagram); ?>">
                    <div class="social-box instagram">
                        <span class="social-label">INSTAGRAM</span>
                        <img src="<?php echo e(asset('user/Content/img/instagram.png')); ?>" alt="Instagram" />
                    </div>
                </a>
            </div>


        </div>
        <!-- Right: Info & Form -->
        <div class="right-col">
            <div class="contact-info-title">THÔNG TIN LIÊN HỆ</div>
            <ul class="info-list">
                <li>
                    <i class="fa fa-envelope"></i> <?php echo e($thongTinTrangWeb->Email); ?>

                </li>
                <li>
                    <i class="fa fa-phone"></i> <?php echo e($thongTinTrangWeb->Hotline); ?>

                </li>
                <li>
                    <i class="fa-solid fa-location-dot"></i> <?php echo e($thongTinTrangWeb->DiaChi); ?>

                </li>
            </ul>
            <form class="contact-form" id="contactForm" autocomplete="off" method="POST"
                action="<?php echo e(route('lien-he.gui-lien-he')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="text" name="name" placeholder="Họ và tên" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="tel" name="phone" placeholder="Số điện thoại" pattern="[0-9]{8,11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,11);" required>
                <input type="text" name="subject" placeholder="Tiêu đề" required>
                <textarea name="message" placeholder="Thông tin liên hệ hoặc phản ánh" required></textarea>
                <div class="upload-preview">
                    <label for="imageUpload" style="font-weight:500;color:#fff;font-size:16px;margin-bottom:0;">
                        Ảnh minh họa:
                    </label>
                    <input type="file" id="imageUpload" name="image" accept="image/*" style="margin-left:14px;">
                </div>
                <img id="uploadedImage" alt="Ảnh đã upload" style="display:none;" />
                <div class="g-recaptcha" data-sitekey="6Ldai2grAAAAAFzqDGUH58U8bQji9SJTuGTXeyTe"></div>
                <button type="submit" class="submit-btn"><span>GỬI NGAY</span></button>
            </form>
        </div>
    </div>
    <script src="<?php echo e(asset('user/Content/js/lien-he.js')); ?>"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if($errors->has('captcha')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi xác thực',
                text: '<?php echo e($errors->first('captcha')); ?>',
            });
        </script>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: '<?php echo e(session('success')); ?>',
            });
        </script>
    <?php endif; ?>
    <?php if(session('email_error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi gửi email',
            text: '<?php echo e(session('email_error')); ?>',
        });
    </script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/pages/lien-he.blade.php ENDPATH**/ ?>