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

// RENDER GHẾ
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

            const seatId = seatData.TenGhe;
            const seat = document.createElement("div");
            seat.className = "seat";
            seat.textContent = seatId;
            seat.dataset.row = i;
            seat.dataset.col = j;
            seat.dataset.seatId = seatId;

            if (seatData.TrangThaiGhe === 0) {
                seat.classList.add("disabled");
            } else if (seatData.IsBooked || bookedSeats.includes(seatId)) {
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
let myHeldSeats = new Set();

// Gán sự kiện click duy nhất
function bindSeatClickEvents() {
    document.querySelectorAll(".seat").forEach((seatEl) => {
        seatEl.onclick = null;
        seatEl.addEventListener("click", async function () {
            const ma_ghe = this.dataset.seatId;
            if (this.classList.contains("held") && isMyHeldSeat(ma_ghe)) {
                await releaseSeat(ma_ghe);
                return;
            }
            if (this.classList.contains("available")) {
                await holdSeat(ma_ghe);
                return;
            }
        });
    });
}
document.querySelectorAll(".seat.available, .seat.held").forEach((seatEl) => {
    seatEl.addEventListener("click", async function () {
        const ma_ghe = this.dataset.seatId;
        // Nếu ghế đang do chính user này giữ (tức là "held" bởi mình)
        if (this.classList.contains("held") && isMyHeldSeat(ma_ghe)) {
            // Bỏ giữ ghế
            await releaseSeat(ma_ghe);
            // Cập nhật UI nếu cần (hoặc chờ realtime)
            return;
        }
        // Nếu là ghế mới, thực hiện giữ ghế
        if (this.classList.contains("available")) {
            await holdSeat(ma_ghe);
            return;
        }
        // Nếu ghế đã đặt hoặc người khác đang giữ thì không làm gì
    });
});

// Hàm kiểm tra ghế này có phải do user hiện tại giữ không
function isMyHeldSeat(ma_ghe) {
    return myHeldSeats.has(ma_ghe);
}
// API GIỮ/BỎ GHẾ
async function holdSeat(ma_ghe) {
    console.log("Click giữ ghế:", ma_ghe);
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
// Khi bỏ chọn ghế (unselect)
async function releaseSeat(ma_ghe) {
    console.log("Ghế đã hết được giữ:", ma_ghe);
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
        showNotification("Lỗi", "Không thể hủy giữ ghế", "warning");
    }
} // REALTIME UPDATE UI (ONLY HERE)
function updateSeatStatus(
    seat,
    status,
    heldUntil = null,
    byCurrentUser = false
) {
    const seatEl = document.querySelector(`[data-seat-id="${seat}"]`);
    if (!seatEl) return;
    seatEl.classList.remove("available", "held", "booked");

    if (status === "held") {
        seatEl.classList.add("held");
        seatEl.setAttribute(
            "title",
            byCurrentUser ? "Bạn đang giữ ghế này" : "Ghế đang được giữ"
        );
        if (byCurrentUser) myHeldSeats.add(seat);
        else myHeldSeats.delete(seat);
        if (heldUntil && byCurrentUser) startHoldTimer(seat, heldUntil);
    } else if (status === "booked") {
        seatEl.classList.add("booked");
        seatEl.setAttribute("title", "Ghế đã đặt");
        myHeldSeats.delete(seat);
        clearHoldTimer(seat);
    } else {
        seatEl.classList.add("available");
        seatEl.setAttribute("title", "Ghế trống");
        myHeldSeats.delete(seat);
        clearHoldTimer(seat);
    }
}
function startHoldTimer(seat, heldUntil) {
    clearHoldTimer(seat);
    holdTimers[seat] = setInterval(() => {
        const now = Math.floor(Date.now() / 1000);
        if (now >= heldUntil) {
            clearHoldTimer(seat);
            releaseSeat(seat);
            if (window.location.pathname.includes("thanh-toan")) {
                window.location.href = "/";
            }
        }
    }, 1000);
}
function clearHoldTimer(seat) {
    if (holdTimers[seat]) {
        clearInterval(holdTimers[seat]);
        delete holdTimers[seat];
    }
}

// REALTIME LẮNG NGHE
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

// Khi chuyển trang hoặc reload
window.addEventListener("beforeunload", function () {
    if (window.myHeldSeats && window.myHeldSeats.size > 0) {
        for (const seatId of window.myHeldSeats) {
            const suat_chieu_id = window.bookingData?.suatChieuId;
            navigator.sendBeacon(
                "/dat-ve/bo-giu-ghe",
                JSON.stringify({
                    ma_ghe: seatId,
                    suat_chieu_id,
                })
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

// INIT
document.addEventListener("DOMContentLoaded", function () {
    renderSeatLayout();
    bindSeatClickEvents();
});
function getMyHeldSeats() {
    // Lấy những seat.held mà mình đang giữ (nếu backend trả về byCurrentUser)
    return Array.from(document.querySelectorAll(".seat.held")).map(
        (e) => e.dataset.seatId
    );
}
// Khi bấm "Tiếp tục":
document.getElementById("btn-continue").addEventListener("click", function () {
    let mySeats = Array.from(myHeldSeats);
    if (mySeats.length === 0) {
        showBookingNotification(
            "Thông báo",
            "Vui lòng chọn ít nhất 1 ghế!",
            "warning"
        );
        return;
    }
    document.getElementById("selectedSeatsInput").value = mySeats.join(",");
    document.getElementById("form-chuyen-thanh-toan").submit();
});
document.addEventListener("DOMContentLoaded", function () {
    renderSeatLayout();
    bindSeatClickEvents();
});
