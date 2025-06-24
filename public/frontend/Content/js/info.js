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
        url: "{{ route('doi-mat-khau.post') }}",
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
                    content: 'Đổi mật khẩu thành công. Bạn có thể đăng nhập <a href="{{ asset('/') }}">tại đây</a>',
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
        url: "{{ route('user.updateInfo.post') }}",
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
