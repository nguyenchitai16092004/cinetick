
<?php $__env->startSection('title', 'CineTick - Đăng nhập / Đăng ký'); ?>
<?php $__env->startSection('main'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/dang-nhap.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/phim.css')); ?>">
    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-tabs">
                <div class="auth-tab active" id="tab-login" tabindex="0">ĐĂNG NHẬP</div>
                <div class="auth-tab" id="tab-register" tabindex="0">ĐĂNG KÝ</div>
            </div>
            <div class="auth-form-wrapper" id="form-wrapper" style="height:auto;">
                <!-- Login Form -->
                <form class="auth-form active" id="login-form" autocomplete="off" action="<?php echo e(route('login')); ?>"
                    method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label class="form-label required">Email / Tên đăng nhập</label>
                        <input type="text" class="form-input" name="TenDN" placeholder="Email / Tên đăng nhập" required
                            autocomplete="off" value="<?php echo e(old('TenDN')); ?>">
                        <?php $__errorArgs = ['TenDN'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group" style="position:relative;">
                        <label class="form-label required">Mật khẩu</label>
                        <input type="password" class="form-input" id="login-password" name="MatKhau" placeholder="Mật khẩu"
                            required minlength="6" autocomplete="new-password">
                        <span class="input-icon" id="toggle-login-pw"><i class="fa-regular fa-eye"></i></span>
                        <?php $__errorArgs = ['MatKhau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-actions">
                        <a href="<?php echo e(route('quen-mat-khau.get')); ?>" class="form-link">Quên mật khẩu?</a>
                    </div>
                    <button type="submit" class="btn-submit">ĐĂNG NHẬP</button>
                    <div class="form-note">
                        Chưa có tài khoản? <a href="#" id="goto-register">Đăng ký</a>
                    </div>
                </form>

                <!-- Register Form -->
                <form class="auth-form" id="register-form" autocomplete="off" action="<?php echo e(route('register.form.post')); ?>"
                    method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="form_type" value="register">
                    <div class="form-title">Đăng ký tài khoản thành viên và nhận ngay ưu đãi!</div>
                    <div class="form-group">
                        <label class="form-label required">Họ &amp; tên</label>
                        <input type="text" class="form-input" id="rgFullName" name="HoTen" placeholder="Họ &amp; tên"
                            value="<?php echo e(old('HoTen')); ?>" required>
                        <?php $__errorArgs = ['HoTen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Email</label>
                        <input type="email" class="form-input" id="rgEmail" name="Email" placeholder="Email"
                            value="<?php echo e(old('Email')); ?>" required>
                        <?php $__errorArgs = ['Email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Điện thoại</label>
                        <input type="tel" class="form-input" id="rgPhone" name="SDT" placeholder="Điện thoại"
                            value="<?php echo e(old('SDT')); ?>" required pattern="\d{10,11}"
                            title="Số điện thoại phải là dãy số từ 10 đến 11 chữ số">
                        <?php $__errorArgs = ['SDT'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group" style="position:relative;">
                        <label class="form-label required">Ngày sinh</label>
                        <input type="date" id="rgBirthDay" name="NgaySinh" class="form-input"
                            value="<?php echo e(old('NgaySinh')); ?>" required max="<?php echo e(date('Y-m-d', strtotime('-13 years'))); ?>"
                            min="1900-01-01">
                        <?php $__errorArgs = ['NgaySinh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Tên đăng nhập</label>
                        <input type="text" class="form-input" id="rgUserName" name="TenDN"
                            placeholder="Tên đăng nhập" value="<?php echo e(old('TenDN')); ?>" required autocomplete="username">
                        <?php $__errorArgs = ['TenDN'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group" style="position:relative;">
                        <label class="form-label required">Mật khẩu</label>
                        <input type="password" class="form-input" id="reg-password" name="MatKhau"
                            placeholder="Mật khẩu" required autocomplete="new-password">
                        <button type="button" class="input-icon" id="toggle-reg-pw" tabindex="-1"><i
                                class="fa-regular fa-eye"></i></button>
                        <?php $__errorArgs = ['MatKhau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group" style="position:relative;">
                        <label class="form-label required">Mật khẩu nhập lại</label>
                        <input type="password" class="form-input" id="reg-password2" name="MatKhau_confirmation"
                            placeholder="Mật khẩu nhập lại" required>
                        <button type="button" class="input-icon" id="toggle-reg-pw2" tabindex="-1"><i
                                class="fa-regular fa-eye"></i></button>
                        <?php $__errorArgs = ['MatKhau_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Giới tính</label>
                        <div class="form-radio-group" >
                            <label class="form-radio">
                                <input type="radio" name="GioiTinh" value="1"
                                    <?php echo e(old('GioiTinh', '1') == '1' ? 'checked' : ''); ?>>
                                <span></span>
                                Nam
                            </label>
                            <label class="form-radio">
                                <input type="radio" name="GioiTinh" value="0"
                                    <?php echo e(old('GioiTinh') == '0' ? 'checked' : ''); ?>>
                                <span></span>
                                Nữ
                            </label>
                        </div>
                        <?php $__errorArgs = ['GioiTinh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <button type="submit" class="btnRegister">ĐĂNG KÝ</button>
                    <div class="form-note">
                        Bạn đã có tài khoản? <a href="#" id="goto-login">Đăng nhập</a>
                    </div>
                    <p class="form-note" >Vui lòng nhập đầy đủ thông tin vào các trường có đánh dấu <b
                            style="color: red;">(*)</b></p>
                </form>
            </div>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <script>
            $(document).ready(function() {
                $.sweetModal({
                    content: `<?php echo implode('<br>', $errors->all()); ?>`,
                    title: 'Thông báo',
                    icon: $.sweetModal.ICON_WARNING,
                    theme: $.sweetModal.THEME_DARK,
                    buttons: {
                        'OK': {
                            classes: 'redB'
                        }
                    }
                });
            });
        </script>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <script>
            $(document).ready(function() {
                $.sweetModal({
                    content: `<?php echo e(session('success')); ?>`,
                    title: 'Thông báo',
                    icon: $.sweetModal.ICON_SUCCESS,
                    theme: $.sweetModal.THEME_DARK,
                    buttons: {
                        'OK': {
                            classes: 'redB',
                            action: function() {
                                window.location.href = "<?php echo e(route('login.form')); ?>";
                            }
                        }
                    }
                });
            });
        </script>
    <?php endif; ?>

    <script>
        (function() {
            // Tab switching and form animation
            const loginTab = document.getElementById('tab-login');
            const registerTab = document.getElementById('tab-register');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const formWrapper = document.getElementById('form-wrapper');
            let active = '<?php echo e(session('form_type', old('form_type', 'login')) == 'register' ? 'register' : 'login'); ?>';

            function showForm(form) {
                if (form === 'login') {
                    loginForm.classList.add('active');
                    registerForm.classList.remove('active');
                    loginTab.classList.add('active');
                    registerTab.classList.remove('active');
                    setTimeout(() => {
                        formWrapper.style.height = loginForm.offsetHeight + "px";
                    }, 5);
                    active = 'login';
                } else {
                    registerForm.classList.add('active');
                    loginForm.classList.remove('active');
                    registerTab.classList.add('active');
                    loginTab.classList.remove('active');
                    setTimeout(() => {
                        formWrapper.style.height = registerForm.offsetHeight + "px";
                    }, 5);
                    active = 'register';
                }
            }
            loginTab.addEventListener('click', () => showForm('login'));
            registerTab.addEventListener('click', () => showForm('register'));
            loginTab.addEventListener('keydown', e => {
                if (e.key === 'Enter') showForm('login');
            });
            registerTab.addEventListener('keydown', e => {
                if (e.key === 'Enter') showForm('register');
            });
            document.getElementById('goto-login').addEventListener('click', function(e) {
                e.preventDefault();
                showForm('login');
            });
            document.getElementById('goto-register').addEventListener('click', function(e) {
                e.preventDefault();
                showForm('register');
            });
            window.addEventListener('DOMContentLoaded', () => {
                if (active === 'register') showForm('register');
                else showForm('login');
                formWrapper.style.height = (active === 'register' ? registerForm.offsetHeight : loginForm
                    .offsetHeight) + "px";
            });

            // Password show/hide for login
            function togglePw(inputId, iconId) {
                const input = document.getElementById(inputId);
                const iconBox = document.getElementById(iconId);
                if (!input || !iconBox) return;
                iconBox.addEventListener('click', function() {
                    if (input.type === 'password') {
                        input.type = 'text';
                        iconBox.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
                    } else {
                        input.type = 'password';
                        iconBox.innerHTML = '<i class="fa-regular fa-eye"></i>';
                    }
                });
            }
            togglePw('login-password', 'toggle-login-pw');

            // For register form password toggles
            function togglePwBtn(inputId, btnId) {
                const input = document.getElementById(inputId);
                const btn = document.getElementById(btnId);
                if (!input || !btn) return;
                btn.addEventListener('click', function() {
                    const icon = btn.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }
            togglePwBtn('reg-password', 'toggle-reg-pw');
            togglePwBtn('reg-password2', 'toggle-reg-pw2');

            // Animate form wrapper height when switching forms
            const resizeObserver = new ResizeObserver(() => {
                if (active === 'login') formWrapper.style.height = loginForm.offsetHeight + "px";
                else formWrapper.style.height = registerForm.offsetHeight + "px";
            });
            resizeObserver.observe(loginForm);
            resizeObserver.observe(registerForm);
        })();

        document.getElementById('rgBirthDay').addEventListener('change', function() {
            const input = this;
            const dateValue = new Date(input.value);
            const today = new Date();
            const minAgeDate = new Date();
            minAgeDate.setFullYear(today.getFullYear() - 13);

            let error = '';
            if (dateValue > minAgeDate) {
                error = 'Bạn phải đủ 13 tuổi để đăng ký tài khoản.';
            } else if (dateValue > today) {
                error = 'Ngày sinh không được lớn hơn ngày hiện tại.';
            }

            if (error) {
                $.sweetModal({
                    content: error,
                    title: 'Thông báo',
                    icon: $.sweetModal.ICON_WARNING,
                    theme: $.sweetModal.THEME_DARK,
                    buttons: {
                        'OK': {
                            classes: 'redB'
                        }
                    }
                });
                input.value = '';
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/pages/dang-nhap-dang-ky.blade.php ENDPATH**/ ?>