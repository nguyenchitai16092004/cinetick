function isBookingRoute() {
    return /^\/dat-ve(\/|$)/.test(window.location.pathname);
}

function releaseHeldSeats() {
    if (
        !window.selectedSeats ||
        !window.bookingData ||
        !window.bookingData.suatChieuId
    )
        return;
    if (window.selectedSeats.length === 0) return;
    // console.log(
    //     "RELEASE SEATS",
    //     window.selectedSeats,
    //     window.bookingData.suatChieuId
    // );

    // Tạo JSON string - KHÔNG dùng FormData!
    const data = JSON.stringify({
        danh_sach_ghe: window.selectedSeats,
        suat_chieu_id: window.bookingData.suatChieuId,
    });

    // Gửi bằng sendBeacon, chỉ truyền data dạng string (JSON)
    navigator.sendBeacon("/dat-ve/bo-giu-ghe-nhieu", data);
}

document.addEventListener("DOMContentLoaded", function () {
    if (isBookingRoute()) {
        // Huỷ ghế khi click bất kỳ link rời trang (không phải anchor #/javascript)
        document.querySelectorAll("a").forEach(function (link) {
            link.addEventListener("click", function (e) {
                if (
                    this.getAttribute("href") &&
                    this.getAttribute("href")[0] !== "#" &&
                    this.getAttribute("href")[0] !== "j"
                ) {
                    if (
                        window.selectedSeats &&
                        window.selectedSeats.length > 0 &&
                        window.bookingData?.suatChieuId
                    ) {
                        e.preventDefault();
                        fetch("/dat-ve/bo-giu-ghe-nhieu", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": window.csrfToken,
                            },
                            body: JSON.stringify({
                                danh_sach_ghe: window.selectedSeats,
                                suat_chieu_id: window.bookingData.suatChieuId,
                                user_id: window.bookingData.userId,
                            }),
                        }).finally(() => {
                            window.location.href = this.href;
                        });
                    }
                }
            });
        });
        // Huỷ giữ ghế khi reload/thoát trang/tab
        window.addEventListener("beforeunload", function (e) {
            // Chỉ release nếu KHÔNG ở trang dat-ve hoặc thanh-toan
            if (
                !window.location.pathname.startsWith("/dat-ve") &&
                !window.location.pathname.startsWith("/thanh-toan")
            ) {
                window.releaseHeldSeats && window.releaseHeldSeats();
            }
        });
    }
});
