import Echo from "laravel-echo";
window.Pusher = require("pusher-js");

// Echo config
window.Echo = new Echo({
    broadcaster: "pusher",
    key: "f544d2c03f1c3b9a7b80",
    cluster: "ap1",
    forceTLS: true,
});

// Dữ liệu từ blade
const bookingData = window.bookingData || {};
const suatChieuId = bookingData.suatChieuId;
const currentUserId = window.currentUserId ?? null;
window.csrfToken =
    window.csrfToken ||
    document.querySelector('meta[name="csrf-token"]')?.content;

let holdTimers = {};
let myHeldSeats = new Set();

// RENDER GHẾ (dùng ID_Ghe cho data-seat-id)
function renderSeatLayout() {
    const seats = bookingData.seatLayout || [];
    const rowAisles = bookingData.rowAisles || [];
    const colAisles = bookingData.colAisles || [];
    const bookedSeats = bookingData.bookedSeats || [];
    const seatContainer = document.getElementById("seatLayout");
    if (!seatContainer) return;

    seatContainer.innerHTML = "";
    seatContainer.className = "seat-container";

    const rowCount = seats.length;
    const colCount = seats[0] ? seats[0].length : 0;

    for (let i = 0; i < rowCount; i++) {
        const rowLabel = document.createElement("div");
        rowLabel.className = "row-label";
        rowLabel.textContent = String.fromCharCode(65 + i);
        seatContainer.appendChild(rowLabel);

        for (let j = 0; j < colCount; j++) {
            if (colAisles.includes(j)) {
                const aisle = document.createElement("div");
                aisle.className = "aisle aisle-col";
                aisle.style.width = "15px";
                aisle.style.height = "35px";
                seatContainer.appendChild(aisle);
            }

            const seatData = seats[i][j];
            if (!seatData) {
                const emptySeat = document.createElement("div");
                emptySeat.className = "seat empty";
                seatContainer.appendChild(emptySeat);
                continue;
            }

            const seat = document.createElement("div");
            seat.className = "seat";
            seat.textContent = seatData.TenGhe;
            seat.dataset.row = i;
            seat.dataset.col = j;
            seat.dataset.seatId = seatData.ID_Ghe; // <-- Quan trọng: luôn là ID_Ghe

            if (seatData.TrangThaiGhe === 0) {
                seat.classList.add("disabled");
            } else if (
                seatData.IsBooked ||
                bookedSeats.includes(seatData.TenGhe)
            ) {
                seat.classList.add("booked");
            } else {
                seat.classList.add("available");
                if (seatData.TrangThaiGhe === 2) seat.classList.add("vip");
                else seat.classList.add("normal");
            }

            seatContainer.appendChild(seat);
        }

        if (rowAisles.includes(i + 1)) {
            const aisleRow = document.createElement("div");
            aisleRow.className = "aisle aisle-row";
            aisleRow.style.gridColumn = `1 / span ${colCount + 1}`;
            aisleRow.style.height = "15px";
            aisleRow.style.backgroundColor = "transparent";
            seatContainer.appendChild(aisleRow);
        }
    }
}

// Gán sự kiện click duy nhất cho mỗi seat
function bindSeatClickEvents() {
    document.querySelectorAll(".seat").forEach((seatEl) => {
        seatEl.onclick = null;
        seatEl.addEventListener("click", async function () {
            const ma_ghe = this.dataset.seatId;
            // Nếu ghế đang do mình giữ
            if (this.classList.contains("held") && isMyHeldSeat(ma_ghe)) {
                await releaseSeat(ma_ghe);
                return;
            }
            // Nếu là ghế trống, giữ ghế
            if (this.classList.contains("available")) {
                await holdSeat(ma_ghe);
                return;
            }
            // Nếu ghế đã đặt hoặc người khác giữ thì không làm gì
        });
    });
}

// Kiểm tra ghế này có phải mình giữ không
function isMyHeldSeat(ma_ghe) {
    return myHeldSeats.has(ma_ghe);
}

// API giữ ghế
async function holdSeat(ma_ghe) {
    try {
        const res = await fetch("/dat-ve/giu-ghe", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.csrfToken,
            },
            credentials: "same-origin",
            body: JSON.stringify({
                ma_ghe,
                suat_chieu_id: suatChieuId,
            }),
        });
        const data = await res.json();
        if (!data.success) {
            window.showBookingNotification(
                "Thông báo",
                data.error || "Không thể giữ ghế",
                "warning"
            );
            return false;
        }
        return true;
    } catch (err) {
        window.showBookingNotification("Lỗi", "Không thể giữ ghế", "warning");
        return false;
    }
}

// API bỏ giữ ghế
async function releaseSeat(ma_ghe) {
    try {
        await fetch("/dat-ve/bo-giu-ghe", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.csrfToken,
            },
            body: JSON.stringify({
                ma_ghe,
                suat_chieu_id: suatChieuId,
            }),
        });
    } catch (err) {
        window.showBookingNotification(
            "Lỗi",
            "Không thể hủy giữ ghế",
            "warning"
        );
    }
}

// Cập nhật trạng thái ghế (realtime)
function updateSeatStatus(
    seatId,
    status,
    heldUntil = null,
    byCurrentUser = false
) {
    const seatEl = document.querySelector(`[data-seat-id="${seatId}"]`);
    if (!seatEl) return;
    seatEl.classList.remove("available", "held", "booked");
    if (status === "held") {
        seatEl.classList.add("held");
        seatEl.setAttribute(
            "title",
            byCurrentUser ? "Bạn đang giữ ghế này" : "Ghế đang được giữ"
        );
        if (byCurrentUser) myHeldSeats.add(seatId);
        else myHeldSeats.delete(seatId);
        if (heldUntil && byCurrentUser) startHoldTimer(seatId, heldUntil);
    } else if (status === "booked") {
        seatEl.classList.add("booked");
        seatEl.setAttribute("title", "Ghế đã đặt");
        myHeldSeats.delete(seatId);
        clearHoldTimer(seatId);
    } else {
        seatEl.classList.add("available");
        seatEl.setAttribute("title", "Ghế trống");
        myHeldSeats.delete(seatId);
        clearHoldTimer(seatId);
    }
}

// Bắt đầu timer giữ ghế
function startHoldTimer(seatId, heldUntil) {
    clearHoldTimer(seatId);
    holdTimers[seatId] = setInterval(() => {
        const now = Math.floor(Date.now() / 1000);
        if (now >= heldUntil) {
            clearHoldTimer(seatId);
            releaseSeat(seatId);
            if (window.location.pathname.includes("thanh-toan")) {
                window.location.href = "/";
            }
        }
    }, 1000);
}
function clearHoldTimer(seatId) {
    if (holdTimers[seatId]) {
        clearInterval(holdTimers[seatId]);
        delete holdTimers[seatId];
    }
}

// Lắng nghe realtime
if (suatChieuId) {
    window.Echo.join(`ghe.${suatChieuId}`).listen("GheDuocGiu", (e) => {
        updateSeatStatus(
            e.ma_ghe,
            e.type === "hold"
                ? "held"
                : e.type === "booked"
                ? "booked"
                : "available",
            e.hold_until,
            e.user_id === currentUserId
        );
    });
}

// Thoát trang: gửi release bằng sendBeacon
window.addEventListener("beforeunload", function () {
    if (myHeldSeats.size > 0) {
        for (const seatId of myHeldSeats) {
            const suat_chieu_id = suatChieuId;
            // sendBeacon chỉ support content-type là text/plain, backend nên chấp nhận luôn dạng này!
            navigator.sendBeacon(
                "/dat-ve/bo-giu-ghe",
                new Blob(
                    [
                        JSON.stringify({
                            ma_ghe: seatId,
                            suat_chieu_id,
                        }),
                    ],
                    { type: "application/json" }
                )
            );
        }
    }
});

// NOTIFICATION FALLBACK
window.showBookingNotification =
    window.showBookingNotification ||
    function (title, message, type = "info") {
        alert(`${title}: ${message}`);
    };

// Khi bấm "Tiếp tục":
document.getElementById("btn-continue").addEventListener("click", function () {
    let mySeats = Array.from(myHeldSeats);
    if (mySeats.length === 0) {
        window.showBookingNotification(
            "Thông báo",
            "Vui lòng chọn ít nhất 1 ghế!",
            "warning"
        );
        return;
    }
    document.getElementById("selectedSeatsInput").value = mySeats.join(",");
    document.getElementById("form-chuyen-thanh-toan").submit();
});

// INIT
document.addEventListener("DOMContentLoaded", function () {
    renderSeatLayout();
    bindSeatClickEvents();
});
