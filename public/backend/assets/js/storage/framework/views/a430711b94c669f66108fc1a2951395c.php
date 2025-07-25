
<?php $__env->startSection('title', 'Thông tin tài khoản'); ?>
<?php $__env->startSection('main'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user/Content/css/info.css')); ?>">
    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="container-info">
        <div class="sidebar-info">
            <div class="profile-info">
                <div class="avatar">
                    <img src="<?php echo e(asset('user/Content/img/user.avif')); ?>"
                        style="display:block;width:200px;height:auto;margin: 0 auto;" />
                </div>
                <div class="profile-name"><?php echo e(session('user_fullname')); ?></div>
            </div>
            <div class="contact-info">
                <div class="contact-item">
                    HOTLINE hỗ trợ: <?php echo e($thongTinTrangWeb->Hotline); ?> (9:00 - 22:00)
                </div>
                <div class="contact-item">Email: <?php echo e($thongTinTrangWeb->Email); ?></div>

            </div>
        </div>
        <div class="main-content">
            <div class="tabs">
                <div class="tab active" data-tab="transaction-history">
                    Lịch Sử Giao Dịch
                </div>
                <div class="tab" data-tab="personal-info">
                    Thông Tin Cá Nhân
                </div>
            </div>
            <div class="tab-content active" id="transaction-history">
                <div class="transaction-list">
                    <?php $__empty_1 = true; $__currentLoopData = $hoaDons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hoaDon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $ve = $hoaDon->veXemPhim->first();
                            $phim = $ve->suatChieu->phim ?? null;
                            $rap = $ve->suatChieu->rap ?? null;
                            $veMa = $ve->MaVe ?? $hoaDon->ID_HoaDon;
                            $giaVe = $ve->GiaVe ?? 0;
                            $dsGhe = $hoaDon->veXemPhim->pluck('TenGhe')->implode(', ');
                            $poster =
                                $phim && $phim->HinhAnh
                                    ? asset('storage/' . $phim->HinhAnh)
                                    : asset('user/Content/img/default-movie.png');
                            $ngayXem = $ve->NgayXem ?? '';
                            $gioChieu = $ve->suatChieu->GioChieu ?? '';
                            $tenPhim = $phim->TenPhim ?? '';
                            $age = $phim->DoTuoi ?? '---';
                            $tenRap = $rap->TenRap ?? '---';
                        ?>
                        <div class="transaction-item" onclick="showTransactionDetail(this)"
                            data-poster="<?php echo e($poster); ?>" data-ghe="<?php echo e($dsGhe); ?>"
                            data-tenphim="<?php echo e($tenPhim); ?>"data-mahoadon="<?php echo e($hoaDon->ID_HoaDon); ?>"
                            data-age="<?php echo e($age); ?>" data-rap="<?php echo e($tenRap); ?>"
                            data-ngayxem="<?php echo e($ngayXem); ?>" data-giochieu="<?php echo e($gioChieu); ?>"
                            data-mave="<?php echo e($veMa); ?>" data-giave="<?php echo e(number_format($giaVe, 0, ',', '.')); ?> đ">
                            <div class="movie-poster">
                                <img src="<?php echo e($poster); ?>" alt="Movie poster"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;" />
                            </div>
                            <div class="transaction-info">
                                <div class="movie-title"> <?php echo e($tenPhim); ?></div>
                                <div class="movie-subtitle">Digital Phụ Đề</div>
                                <div class="age-rating"><?php echo e($age); ?></div>
                            </div>
                            <div class="transaction-details">
                                <div class="cinema-location"><?php echo e($tenRap); ?></div>
                                <div class="showtime">
                                    <?php echo e($gioChieu); ?> <?php echo e($ngayXem); ?>

                                </div>
                                <div class="detail-link">Chi tiết</div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="blank-invoice">Bạn chưa có hóa đơn nào.</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab-content" id="personal-info">
                <form id="personalInfoForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" class="form-input" value="<?php echo e(session('user_fullname')); ?>" disabled />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" class="form-input"
                                value="<?php echo e(\Carbon\Carbon::parse(session('user_date'))->format('Y-m-d')); ?>" disabled />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" value="<?php echo e(session('user_email')); ?>" disabled />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-input" value="<?php echo e(session('user_phone')); ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="gender-options">
                            <label class="form-label">Giới tính</label>
                            <div class="gender-option">
                                <input type="radio" id="male" name="gender" value="male"
                                    <?php echo e(session('user_sex') == 1 ? 'checked' : ''); ?> disabled />
                                <label for="male">Nam</label>
                            </div>
                            <div class="gender-option">
                                <input type="radio" id="female" name="gender" value="female"
                                    <?php echo e(session('user_sex') == 0 ? 'checked' : ''); ?> disabled />
                                <label for="female">Nữ</label>
                            </div>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" onclick="openChangePasswordModal()">
                            Đổi mật khẩu
                        </button>
                        <button type="button" class="btn btn-primary" onclick="openUpdateInfoModal()">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="transactionModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Chi tiết giao dịch</div>
                <button class="close-btn" onclick="closeTransactionDetail()">
                    &times;
                </button>
            </div>
            <div class="transaction-detail">
                <div class="transaction-detail-icon">
                    <img id="modal-movie-poster" src="<?php echo e(asset('user/Content/img/default-movie.png')); ?>"
                        alt="Movie poster" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;" />
                </div>
                <div class="transaction-detail-name" id="modal-movie-title">Tên phim</div>
                <div class="transaction-detail-type" id="modal-movie-age">--- </div>
                <div class="transaction-detail-info">
                    <div class="info-row">
                        <span class="info-label" id="modal-cinema">Tên rạp</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Suất:</span>
                        <span class="info-value" id="modal-showtime">--:-- --/--/----</span>
                    </div>
                </div>
                <div class="qr-code" id="modal-qr"></div>
                <div style="margin-top: 20px">
                    <div class="info-row">
                        <span class="info-label">Mã vé</span>
                        <span class="info-label">Ghế</span>
                        <span class="info-label">Giá</span>
                    </div>
                    <div class="info-row">
                        <span class="info-value" id="modal-ticket-code">---</span>
                        <span class="info-value" id="modal-seat-list">---</span>
                        <span class="info-value" id="modal-total-price">---</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal change-password-modal" id="changePasswordModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Đổi mật khẩu</div>
                <button class="close-btn" onclick="closeChangePasswordModal()">&times;</button>
            </div>
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <div class="register-form">
                    <div class="form-group" style="position:relative;">
                        <input type="password" minlength="6" maxlength="50" class="form-control" id="lgPassword"
                            placeholder="Mật khẩu hiện tại" style="padding-right: 40px;">
                        <button type="button" class="password-toggle" tabindex="-1"
                            onclick="togglePassword('lgPassword', this)"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%);">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-group" style="position:relative;">
                        <input type="password" minlength="6" maxlength="50" class="form-control" id="lgPasswordNew"
                            placeholder="Mật khẩu mới" style="padding-right: 40px;">
                        <button type="button" class="password-toggle" tabindex="-1"
                            onclick="togglePassword('lgPasswordNew', this)"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%);">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-group" style="position:relative;">
                        <input type="password" minlength="6" maxlength="50" class="form-control" id="lgRePasswordNew"
                            placeholder="Xác nhận mật khẩu mới" style="padding-right: 40px;">
                        <button type="button" class="password-toggle" tabindex="-1"
                            onclick="togglePassword('lgRePasswordNew', this)"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%);">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <input type="button" class="btn btn-primary" onclick="changePass()" value="Đổi mật khẩu">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal cập nhật thông tin -->
    <div class="modal" id="updateInfoModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Cập nhật thông tin tài khoản</div>
                <button class="close-btn" onclick="closeUpdateInfoModal()">&times;</button>
            </div>
            <form id="updateInfoFormModal">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <input type="text" class="form-control" id="modalFullName" name="HoTen"
                        value="<?php echo e($thongTin->HoTen ?? ''); ?>" placeholder="Họ & tên(*)">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="modalEmail" name="Email" placeholder="Email (*)"
                        value="<?php echo e($thongTin->Email ?? ''); ?>" readonly>
                </div>
                <div class="form-group">
                    <input type="text" id="modalBirthDay" class="form-control" name="NgaySinh"
                        placeholder="Ngày sinh"
                        value="<?php echo e($thongTin->NgaySinh ? \Carbon\Carbon::parse($thongTin->NgaySinh)->format('d/m/Y') : ''); ?>"
                        readonly>
                </div>
                <div class="form-group">
                    <input type="tel" minlength="10" maxlength="10" id="modalPhone" name="SDT"
                        class="form-control" placeholder="Điện thoại(*)" value="<?php echo e($thongTin->SDT ?? ''); ?>">
                </div>
                <div class="form-group">
                    <div class="maxl">
                        <label class="radio inline">
                            <input type="radio" id="modalGenderTrue" name="GioiTinh" value="1"
                                <?php echo e(($thongTin->GioiTinh ?? 1) == 1 ? 'checked' : ''); ?>>
                            <span> Nam </span>
                        </label>
                        <label class="radio inline">
                            <input type="radio" id="modalGenderFalse" name="GioiTinh" value="0"
                                <?php echo e(($thongTin->GioiTinh ?? 1) == 0 ? 'checked' : ''); ?>>
                            <span> Nữ </span>
                        </label>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="submitUpdateInfo()">Lưu thay đổi</button>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const tabs = document.querySelectorAll(".tab");
        const tabContents = document.querySelectorAll(".tab-content");
        tabs.forEach((tab) => {
            tab.addEventListener("click", () => {
                tabs.forEach((t) => t.classList.remove("active"));
                tabContents.forEach((content) =>
                    content.classList.remove("active")
                );
                tab.classList.add("active");
                const targetTab = tab.getAttribute("data-tab");
                document.getElementById(targetTab).classList.add("active");
            });
        });

        function showTransactionDetail(el) {
            // Lấy các thông tin từ data-*
            document.getElementById('modal-movie-poster').src = el.getAttribute('data-poster');
            document.getElementById('modal-movie-title').textContent = el.getAttribute('data-tenphim');
            document.getElementById('modal-movie-age').textContent = el.getAttribute('data-age');
            document.getElementById('modal-cinema').textContent = el.getAttribute('data-rap');
            document.getElementById('modal-showtime').textContent = el.getAttribute('data-giochieu') + ' ' + el
                .getAttribute('data-ngayxem');
            document.getElementById('modal-ticket-code').textContent = el.getAttribute('data-mave');
            document.getElementById('modal-seat-list').textContent = el.getAttribute('data-ghe');
            document.getElementById('modal-total-price').textContent = el.getAttribute('data-giave');

            // Render QR code từ ID_HoaDon (data-mahoadon)
            const maHoaDon = el.getAttribute('data-mahoadon');
            document.getElementById('modal-qr').innerHTML = ""; // Xóa QR cũ
            if (maHoaDon) {
                new QRCode(document.getElementById("modal-qr"), {
                    text: maHoaDon,
                    width: 100,
                    height: 100,
                });
            }

            document.getElementById("transactionModal").classList.add("show");
        }

        function closeTransactionDetail() {
            document.getElementById("transactionModal").classList.remove("show");
        }

        function openChangePasswordModal() {
            document.getElementById("changePasswordModal").classList.add("show");
        }

        function closeChangePasswordModal() {
            document.getElementById("changePasswordModal").classList.remove("show");
        }

        // Xử lý đổi mật khẩu
        function changePass() {
            var oldPass = $("#lgPassword").val();
            var newPassWord = $("#lgPasswordNew").val();
            var reNewPassWord = $("#lgRePasswordNew").val();

            if (!oldPass) {
                sweetModalWarn('Vui lòng nhập mật khẩu cũ.', "#lgPassword");
                return false;
            }
            if (!newPassWord) {
                sweetModalWarn('Vui lòng nhập mật khẩu mới.', "#lgPasswordNew");
                return false;
            }
            if (newPassWord.length < 6) {
                sweetModalWarn('Mật khẩu mới phải có ít nhất 6 ký tự.', "#lgPasswordNew");
                return false;
            }
            if (!reNewPassWord) {
                sweetModalWarn('Vui lòng nhập lại mật khẩu mới.', "#lgRePasswordNew");
                return false;
            }

            var data = {
                OldPass: oldPass,
                NewPass: newPassWord,
                ReNewPass: reNewPassWord,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                url: "<?php echo e(route('doi-mat-khau.post')); ?>",
                type: "POST",
                data: JSON.stringify(data),
                datatype: "json",
                contentType: 'application/json; charset=utf-8',
                success: function(result) {
                    if (result === "true" || result === true) {
                        $("#lgPassword").val("");
                        $("#lgPasswordNew").val("");
                        $("#lgRePasswordNew").val("");
                        closeChangePasswordModal();
                        $.sweetModal({
                            content: 'Đổi mật khẩu thành công. Bạn có thể đăng nhập <a href="<?php echo e(asset('/')); ?>">tại đây</a>',
                            title: 'Thông báo',
                            icon: $.sweetModal.ICON_SUCCESS,
                            theme: $.sweetModal.THEME_DARK,
                            buttons: {
                                'OK': {
                                    classes: 'redB'
                                }
                            }
                        });
                    } else {
                        let msg = '';
                        if (result === "1") msg = 'Phiên đăng nhập của bạn đã hết hạn, vui lòng đăng nhập lại.';
                        else if (result === "2") msg = 'Mật khẩu cũ không chính xác!';
                        else if (result === "3") msg = 'Mật khẩu mới nhập lại không trùng khớp!';
                        else if (result === "4") msg = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
                        else msg = result;
                        $.sweetModal({
                            content: msg,
                            title: 'Lỗi',
                            icon: $.sweetModal.ICON_WARNING,
                            theme: $.sweetModal.THEME_DARK,
                            buttons: {
                                'OK': {
                                    classes: 'redB'
                                }
                            }
                        });
                    }
                },
                error: function(xhr) {
                    $.sweetModal({
                        content: 'Có lỗi xảy ra: ' + xhr.status + ' - ' + xhr.responseText,
                        title: 'Lỗi',
                        icon: $.sweetModal.ICON_WARNING,
                        theme: $.sweetModal.THEME_DARK,
                        buttons: {
                            'OK': {
                                classes: 'redB'
                            }
                        }
                    });
                }
            });
        }

        function sweetModalWarn(msg, focusSelector) {
            $.sweetModal({
                content: msg,
                title: '',
                icon: $.sweetModal.ICON_WARNING,
                theme: $.sweetModal.THEME_DARK,
                buttons: {
                    'OK': {
                        classes: 'redB'
                    }
                }
            });
            if (focusSelector) setTimeout(function() {
                $(focusSelector).focus();
            }, 300);
        }

        function openUpdateInfoModal() {
            document.getElementById("updateInfoModal").classList.add("show");
        }

        function closeUpdateInfoModal() {
            document.getElementById("updateInfoModal").classList.remove("show");
        }

        function submitUpdateInfo() {
            var data = {
                HoTen: $("#modalFullName").val(),
                ID_ThongTin: $("#modalCMND").val(),
                Email: $("#modalEmail").val(),
                NgaySinh: $("#modalBirthDay").val(),
                SDT: $("#modalPhone").val(),
                GioiTinh: $("input[name='GioiTinh']:checked", "#updateInfoFormModal").val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            $.ajax({
                url: "<?php echo e(route('user.updateInfo.post')); ?>",
                type: "POST",
                data: data,
                success: function(res) {
                    closeUpdateInfoModal();
                    $.sweetModal({
                        content: "Cập nhật thông tin thành công!",
                        title: "Thông báo",
                        icon: $.sweetModal.ICON_SUCCESS,
                        theme: $.sweetModal.THEME_DARK,
                        buttons: {
                            'OK': {
                                classes: 'redB'
                            }
                        }
                    });
                    // Cập nhật lại trên giao diện nếu cần
                    // location.reload(); // hoặc tự cập nhật các field hiển thị
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || "Có lỗi xảy ra!";
                    $.sweetModal({
                        content: msg,
                        title: "Lỗi",
                        icon: $.sweetModal.ICON_WARNING,
                        theme: $.sweetModal.THEME_DARK,
                        buttons: {
                            'OK': {
                                classes: 'redB'
                            }
                        }
                    });
                }
            });
        }

        function togglePassword(inputId, btn) {
            var input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                btn.querySelector('i').classList.remove('fa-eye');
                btn.querySelector('i').classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                btn.querySelector('i').classList.remove('fa-eye-slash');
                btn.querySelector('i').classList.add('fa-eye');
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/pages/thong-tin-tai-khoan/info.blade.php ENDPATH**/ ?>