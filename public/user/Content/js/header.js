document
    .querySelector(".header__search button")
    .addEventListener("click", function () {
        document.querySelector(".header__search input").focus();
    });

document.addEventListener("DOMContentLoaded", function () {
    var bookingBtn = document.querySelector(".header__center img");

    if (bookingBtn) {
        bookingBtn.style.cursor = "pointer";
        bookingBtn.addEventListener("click", function () {
            var bookingSection = document.getElementById("bookingSection");
            if (bookingSection) {
                // Đang ở trang home, có section => scroll luôn
                bookingSection.scrollIntoView({ behavior: "smooth" });
            } else {
                // Không phải trang home hoặc chưa có section => chuyển về home kèm hash
                if (window.location.pathname !== "/") {
                    window.location.href = "/#scrollToBooking";
                } else {
                    setTimeout(function () {
                        var section = document.getElementById("bookingSection");
                        if (section)
                            section.scrollIntoView({ behavior: "smooth" });
                    }, 300);
                }
            }
        });
    }

    // Nếu đã về home với hash thì tự động scroll đến bookingSection
    if (window.location.hash === "#scrollToBooking") {
        setTimeout(function () {
            var section = document.getElementById("bookingSection");
            if (section) {
                section.scrollIntoView({ behavior: "smooth" });
                // Xóa hash để không scroll lại khi reload
                history.replaceState(null, "", window.location.pathname);
            }
        }, 300);
    }

    // var searchForm = document.querySelector(".header__search-form");
    // if (searchForm) {
    //     searchForm.addEventListener("submit", function (e) {
    //         var input = searchForm.querySelector(".header__search-input");
    //         if (input && input.value.trim() === "") {
    //             e.preventDefault();
    //             if (typeof $.sweetModal === "function") {
    //                 $.sweetModal({
    //                     content: "Vui lòng nhập từ khóa tìm kiếm!",
    //                     icon: $.sweetModal.ICON_WARNING,
    //                     theme: $.sweetModal.THEME_DARK,
    //                 });
    //             } else {
    //                 alert("Vui lòng nhập từ khóa tìm kiếm!");
    //             }
    //             input.focus();
    //         }
    //     });
    // }
});
// Hiện/ẩn menu mobile
document.getElementById('menuToggle').onclick = function() {
    document.getElementById('mobileMenu').classList.toggle('active');
    document.getElementById('menuOverlay').classList.toggle('active');
};
document.getElementById('menuOverlay').onclick = function() {
    document.getElementById('mobileMenu').classList.remove('active');
    document.getElementById('menuOverlay').classList.remove('active');
};

// Hiện/ẩn khung search
document.getElementById('searchToggle').onclick = function(e) {
    e.stopPropagation();
    document.getElementById('headerSearch').classList.toggle('active');
    document.querySelector('.header__search-input').focus();
};
// Ẩn search khi click ngoài
document.addEventListener('click', function(e) {
    if (!document.getElementById('headerSearch').contains(e.target)) {
        document.getElementById('headerSearch').classList.remove('active');
    }
} );
// Mở/đóng menu mobile
document.getElementById('menuToggle').onclick = function() {
    document.getElementById('mobileMenu').classList.add('active');
    document.getElementById('menuOverlay').classList.add('active');
    document.getElementById('menuClose').style.display = 'block';
};
document.getElementById('menuClose').onclick = function() {
    document.getElementById('mobileMenu').classList.remove('active');
    document.getElementById('menuOverlay').classList.remove('active');
    this.style.display = 'none';
};
document.getElementById('menuOverlay').onclick = function() {
    document.getElementById('mobileMenu').classList.remove('active');
    document.getElementById('menuOverlay').classList.remove('active');
    document.getElementById('menuClose').style.display = 'none';
};

// Dropdown cho mobile menu
document.querySelectorAll('.dropdown-toggle-mobile').forEach(function(toggle) {
    toggle.onclick = function(e) {
        e.preventDefault();
        var parent = this.closest('.dropdown-mobile');
        parent.classList.toggle('open');
    };
});