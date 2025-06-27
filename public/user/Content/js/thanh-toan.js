
// ====== Popup xác nhận thanh toán ======
function showPaymentPopup() {
    updatePopupDiscountAndTotal(finalTotal, appliedDiscount);
    document.getElementById("paymentConfirmPopup").style.display = "flex";
}

function closePaymentPopup() {
    document.getElementById("paymentConfirmPopup").style.display = "none";
}

// ====== XỬ LÝ THANH TOÁN (POST FORM) ======
function proceedToPayment() {
    // Gán seatDetails vào input hidden trước khi gửi
    document.getElementById("seatDetailsInput").value = JSON.stringify(
        window.seatDetails || []
    );
    document.getElementById("maKhuyenMaiInput").value = appliedCode || "";
    document.getElementById("soTienGiamInput").value = appliedDiscount || 0;
    let tongTienSauGiam =
        typeof finalTotal === "number" ? finalTotal : Number(finalTotal);
    // Đảm bảo truyền tổng tiền sau giảm giá nếu có
    if (document.getElementById("tongTienSauGiamInput"))
        document.getElementById("tongTienSauGiamInput").value =
            finalTotal > 0 ? finalTotal : totalPrice;

    const form = document.getElementById("paymentForm");
    const formData = new FormData(form);

    fetch(form.action, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                .value,
            Accept: "application/json",
        },
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.checkoutUrl) {
                window.location.href = data.checkoutUrl;
            } else if (data.error) {
                alert("Lỗi: " + data.error);
            } else {
                alert("Không thể tạo đơn hàng. Vui lòng thử lại.");
            }
        })
        .catch((err) => {
            alert("Lỗi khi kết nối tới máy chủ.");
            console.error(err);
        });
}

document
    .getElementById("payos-submit-btn")
    .addEventListener("click", function (e) {
        e.preventDefault();
        showPaymentPopup();
    });

document
    .getElementById("paymentConfirmPopup")
    .addEventListener("click", function (e) {
        if (e.target === this) {
            closePaymentPopup();
        }
    });

// ====== GIỮ GHẾ VÀ XÓA GIỮ GHẾ ======
function getAllHeldSeats() {
    // Ưu tiên window.myHeldSeats, fallback sang window.selectedSeats
    return window.myHeldSeats && window.myHeldSeats.length
        ? window.myHeldSeats
        : window.selectedSeats || [];
}

window.addEventListener("beforeunload", releaseAllHeldSeats);
window.addEventListener("visibilitychange", function () {
    if (document.visibilityState === "hidden") releaseAllHeldSeats();
});

// ====== ĐỒNG HỒ GIỮ GHẾ ======
let timeLeft = window.bookingTimeLeft || 360;

function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    // Sửa selector để lấy đúng phần tử timer
    const timerEls = document.querySelectorAll(".timer");
    timerEls.forEach((el) => {
        el.textContent = `${minutes.toString().padStart(2, "0")}:${seconds
            .toString()
            .padStart(2, "0")}`;
    });
    if (timeLeft > 0) {
        timeLeft--;
        setTimeout(updateTimer, 1000);
    } else {
        if (typeof window.routeHome !== "undefined") {
            window.location.href = window.routeHome;
        } else {
            window.location.href = "/";
        }
    }
}
updateTimer();

// ====== ÁP DỤNG MÃ KHUYẾN MÃI (AJAX) ======
let appliedDiscount = 0;
let appliedCode = "";
let finalTotal =
    typeof totalPrice !== "undefined"
        ? totalPrice
        : typeof window !== "undefined" && window.totalPrice
        ? window.totalPrice
        : 0;

// CẤU HÌNH ĐƯỜNG DẪN AJAX KHUYẾN MÃI
const khuyenMaiApi =
    typeof window.KhuyenMaiCheckUrl !== "undefined"
        ? window.KhuyenMaiCheckUrl
        : "/ajax/kiem-tra-khuyen-mai";

function updateTotalDisplay(newTotal, discount, msg = "") {
    // Cập nhật tổng cộng
    document.getElementById("totalPrice").textContent =
        newTotal.toLocaleString("vi-VN") + " đ";

    // Cập nhật giảm giá
    let discountAmount = document.getElementById("discountAmount");
    if (discount > 0) {
        discountAmount.textContent =
            "-" + discount.toLocaleString("vi-VN") + " đ";
        discountAmount.style.color = "#28a745";
    } else {
        discountAmount.textContent = "0 đ";
        discountAmount.style.color = "#111"; // Màu mặc định
    }
}

document.getElementById("promoApplyBtn").addEventListener("click", function () {
    let code = document.getElementById("promoCodeInput").value.trim();
    let tongTien =
        typeof totalPrice !== "undefined"
            ? totalPrice
            : typeof window !== "undefined" && window.totalPrice
            ? window.totalPrice
            : 0;
    let errorDiv = document.getElementById("promo-error");
    let successDiv = document.getElementById("promo-success");
    errorDiv.textContent = "";
    successDiv.textContent = "";

    if (!code) {
        errorDiv.textContent = "Vui lòng nhập mã khuyến mãi!";
        return;
    }

    fetch(khuyenMaiApi, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                .value,
            Accept: "application/json",
        },
        body: JSON.stringify({ ma_khuyen_mai: code, tong_tien: tongTien }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.status) {
                appliedDiscount = Number(data.so_tien_giam) || 0;
                appliedCode = code;
                finalTotal = Number(data.tong_tien_sau_giam) || 0;
                updateTotalDisplay(finalTotal, appliedDiscount);
                updatePopupDiscountAndTotal(finalTotal, appliedDiscount);
                errorDiv.textContent = "";
                successDiv.textContent = data.message;
            } else {
                appliedDiscount = 0;
                appliedCode = "";
                finalTotal = totalPrice;
                updateTotalDisplay(tongTien, 0);
                updatePopupDiscountAndTotal(tongTien, 0);
                errorDiv.textContent = data.message;
                successDiv.textContent = "";
            }
        });
});

function updatePopupDiscountAndTotal(newTotal, discount) {
    if (typeof newTotal !== "number") {
        if (typeof newTotal === "string") {
            newTotal = Number(newTotal.replace(/[^\d]/g, ""));
        } else if (typeof newTotal === "object" && newTotal.textContent) {
            // Nếu lỡ truyền element thì lấy textContent
            newTotal = Number(newTotal.textContent.replace(/[^\d]/g, ""));
        } else {
            newTotal = 0;
        }
    }
    // Cập nhật giảm giá
    if (discount > 0) {
        discountAmountPopup.textContent =
            "-" + discount.toLocaleString("vi-VN") + " đ";
        discountAmountPopup.style.color = "#28a745";
    } else {
        discountAmountPopup.textContent = "0 đ";
        discountAmountPopup.style.color = "#111";
    }
    // Cập nhật tổng cộng
    document.getElementById("totalPricePopup").textContent =
        newTotal.toLocaleString("vi-VN") + " đ";
}
function isBookingOrPaymentRoute() {
    const path = window.location.pathname;
    // Có thể bổ sung thêm các route thuộc flow của bạn nếu có nhiều step hơn
    return path.startsWith("/dat-ve") || path.startsWith("/thanh-toan");
}
function releaseAllHeldSeats() {
    var heldSeats = getAllHeldSeats();
    if (!heldSeats || heldSeats.length === 0) return;
    if (!window.bookingData || !window.bookingData.suatChieuId) return;
    if (!window.userId) return;

    const payload = JSON.stringify({
        danh_sach_ghe: heldSeats,
        suat_chieu_id: window.bookingData.suatChieuId,
        user_id: window.userId,
    });

    if (navigator.sendBeacon) {
        navigator.sendBeacon("/dat-ve/bo-giu-ghe-nhieu", payload);
    } else {
        fetch("/dat-ve/bo-giu-ghe-nhieu", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: payload,
            keepalive: true,
            credentials: "same-origin",
        });
    }
}
function setupReleaseSeatEvents() {

    // Hủy giữ ghế khi reload, đóng tab, chuyển tab
    window.addEventListener("beforeunload", function (e) {
        // Chỉ release nếu đi ra khỏi flow
        if (!isBookingOrPaymentRoute()) {
            releaseAllHeldSeats();
        }
    });
    // Khi tab ẩn đi hoặc chuyển tab khác cũng release
    window.addEventListener("visibilitychange", function () {
        if (
            document.visibilityState === "hidden" &&
            !isBookingOrPaymentRoute()
        ) {
            releaseAllHeldSeats();
        }
    });
}
document.addEventListener("DOMContentLoaded", setupReleaseSeatEvents);

