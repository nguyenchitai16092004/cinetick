const bookingData = window.bookingData || {};
const suatChieuId = bookingData.suatChieuId || window.suatChieuId || null;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: "f544d2c03f1c3b9a7b80",
    cluster: "ap1",
    forceTLS: true,
});

const currentUserId = window.currentUserId ?? null;
window.csrfToken =
    window.csrfToken ||
    document.querySelector('meta[name="csrf-token"]')?.content;

let holdTimers = {};
let myHeldSeats = new Set((window.myHeldSeats || []).map(String));

function renderSeatLayout() {
    const seats = bookingData.seatLayout || [];
    const rowAisles = bookingData.rowAisles || [];
    const colAisles = bookingData.colAisles || [];
    const bookedSeats = bookingData.bookedSeats || [];
    const seatContainer = document.getElementById("seatLayout");
    if (!seatContainer) return;

    if (!seats || seats.length === 0) {
        seatContainer.innerHTML =
            '<div class="placeholder-text text-muted text-center py-5">Không có thông tin về sơ đồ ghế</div>';
        return;
    }

    seatContainer.innerHTML = "";
    seatContainer.className = "seat-container";

    const rowCount = seats.length;
    const colCount = seats[0] ? seats[0].length : 0;

    // Tính số cột cho grid-template-columns (label + aisle + seat)
    let gridTemplateColumns = "auto";
    let totalCols = 1;
    for (let j = 0; j < colCount; j++) {
        if (colAisles.includes(j)) {
            gridTemplateColumns += " 15px";
            totalCols++;
        }
        gridTemplateColumns += " 35px";
        totalCols++;
    }
    seatContainer.style.display = "grid";
    seatContainer.style.gridTemplateColumns = gridTemplateColumns;

    // Render từng hàng
    for (let i = 0; i < rowCount; i++) {
        // Label hàng
        const rowLabel = document.createElement("div");
        rowLabel.className = "row-label";
        rowLabel.textContent = String.fromCharCode(65 + i);
        seatContainer.appendChild(rowLabel);

        // Render từng ghế trong hàng
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
            seat.dataset.seatId = seatData.ID_Ghe;

            // Trạng thái ghế
            if (myHeldSeats && myHeldSeats.has(String(seatData.ID_Ghe))) {
                seat.classList.add("held", "selected");
                seat.setAttribute("title", "Bạn đang giữ ghế này");
            } else if (seatData.TrangThaiGhe === 0) {
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

        // Thêm aisle giữa các hàng (nếu có)
        if (rowAisles.includes(i + 1)) {
            const aisleRow = document.createElement("div");
            aisleRow.className = "aisle aisle-row";
            aisleRow.style.gridColumn = `1 / span ${totalCols}`;
            aisleRow.style.height = "15px";
            aisleRow.style.backgroundColor = "transparent";
            seatContainer.appendChild(aisleRow);
        }
    }
    bindSeatClickEvents();
}
function bindSeatClickEvents() {
    document.querySelectorAll(".seat").forEach((seatEl) => {
        seatEl.onclick = null;
        seatEl.addEventListener("click", async function () {
            const ma_ghe = this.dataset.seatId;
            if (this.classList.contains("held") && isMyHeldSeat(ma_ghe)) {
                await releaseSeat(ma_ghe);
                renderSeatLayout();
                return;
            }
            if (this.classList.contains("available")) {
                await holdSeat(ma_ghe);
                renderSeatLayout();
                return;
            }
        });
    });
}

function isMyHeldSeat(ma_ghe) {
    return myHeldSeats.has(String(ma_ghe));
}
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
        myHeldSeats.add(String(ma_ghe));
        window.selectedSeats = Array.from(myHeldSeats);
        updateBookingSummary();
        if (!seatHoldTimerInterval) {
            startSeatHoldTimer();
        }
        return true;
    } catch (err) {
        window.showBookingNotification("Lỗi", "Không thể giữ ghế", "warning");
        return false;
    }
}

async function releaseSeat(ma_ghe) {
    if (!suatChieuId) {
        console.error("releaseSeat: suatChieuId is undefined!");
        return;
    }
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
    myHeldSeats.delete(String(ma_ghe));
    window.selectedSeats = Array.from(myHeldSeats);
    updateBookingSummary();
    // === Thêm đoạn sau để ẩn timer nếu không còn ghế ===
    if (myHeldSeats.size === 0) {
        clearSeatHoldTimer();
    }
}

// Realtime
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

function updateSeatStatus(
    seatId,
    status,
    heldUntil = null,
    byCurrentUser = false
) {
    if (status === "held") {
        if (byCurrentUser) myHeldSeats.add(String(seatId));
        else myHeldSeats.delete(String(seatId));
        if (heldUntil && byCurrentUser) startHoldTimer(seatId, heldUntil);
    } else {
        myHeldSeats.delete(String(seatId));
        clearHoldTimer(seatId);
    }
    renderSeatLayout();
}

function isBookingOrPaymentRoute() {
    const path = window.location.pathname;
    return (
        /^\/dat-ve\/[^\/]+\/[^\/]+\/[^\/]+$/.test(path) ||
        path === "/dat-ve/thanh-toan"
    );
}

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

// ==== CHECK LOGIC CHỌN GHẾ TRỐNG ====
// Chỉ kiểm tra nếu bạn thực sự cần (có thể bỏ qua nếu rắc rối)
function isValidSeatSelectionAll(seatArray) {
    if (!seatArray || seatArray.length === 0) return true;

    // Group by row, map ghế ID_Ghe -> TenGhe
    const seatsByRow = {};
    seatArray.forEach((id_ghe) => {
        let tenGhe = null;
        outer: for (let i = 0; i < bookingData.seatLayout.length; i++) {
            for (let j = 0; j < bookingData.seatLayout[i].length; j++) {
                const seat = bookingData.seatLayout[i][j];
                if (seat && String(seat.ID_Ghe) === String(id_ghe)) {
                    tenGhe = seat.TenGhe;
                    break outer;
                }
            }
        }
        if (!tenGhe) return; // skip nếu không tìm thấy
        const row = tenGhe.charAt(0);
        const col = parseInt(tenGhe.slice(1));
        if (!seatsByRow[row]) seatsByRow[row] = [];
        seatsByRow[row].push(col);
    });

    // Kiểm tra từng hàng:
    for (const row in seatsByRow) {
        const cols = seatsByRow[row].sort((a, b) => a - b);

        // Lấy tất cả số ghế có thể chọn (không phải bảo trì/đã đặt)
        let allCols = [];
        for (let i = 0; i < bookingData.seatLayout.length; i++) {
            for (let j = 0; j < bookingData.seatLayout[i].length; j++) {
                const seat = bookingData.seatLayout[i][j];
                if (
                    seat &&
                    seat.TenGhe &&
                    seat.TenGhe.charAt(0) === row &&
                    seat.TrangThaiGhe !== 0 && // != bảo trì
                    !seat.IsBooked
                ) {
                    allCols.push(parseInt(seat.TenGhe.slice(1)));
                }
            }
        }
        allCols = allCols.sort((a, b) => a - b);
        if (allCols.length === 0) continue;
        const minCol = Math.min(...allCols);
        const maxCol = Math.max(...allCols);

        // 1. Không được bỏ 1 ghế ở giữa

        for (let i = 0; i < cols.length - 1; i++) {
            if (cols[i + 1] - cols[i] === 2) {
                // Nếu có 1 ghế trống giữa 2 ghế chọn, kiểm tra nó có bị bỏ trống không
                const middleCol = cols[i] + 1;
                let middleID = null;
                outer: for (let m = 0; m < bookingData.seatLayout.length; m++) {
                    for (let n = 0; n < bookingData.seatLayout[m].length; n++) {
                        const seat = bookingData.seatLayout[m][n];
                        if (seat && seat.TenGhe === row + middleCol) {
                            middleID = seat.ID_Ghe;
                            break outer;
                        }
                    }
                }
                if (middleID && !seatArray.includes(String(middleID))) {
                    return {
                        valid: false,
                        reason: `Không được để trống ghế giữa như ghế ${row}${middleCol} giữa hai ghế đã chọn.`,
                    };
                }
            }
        }
        // 2. Không được bỏ ghế lẻ ở rìa (chỉ hợp lệ nếu phía rìa còn >=2 ghế không chọn)
        // Bỏ ghế rìa bên trái
        const leftmostSelected = Math.min(...cols);
        if (
            leftmostSelected > minCol &&
            leftmostSelected - minCol === 1 &&
            !cols.includes(minCol)
        ) {
            // Kiểm tra phía trái còn mấy ghế: nếu chỉ còn 1 ghế rìa bị bỏ thì lỗi
            let leftBlank = [];
            for (let c = minCol; c < leftmostSelected; c++) {
                if (!cols.includes(c)) leftBlank.push(c);
            }
            if (leftBlank.length === 1) {
                return {
                    valid: false,
                    reason: `Không được để trống ghế rìa bên trái như ghế ${row}${minCol}.`,
                };
            }
        }

        // Bỏ ghế rìa bên phải
        const rightmostSelected = Math.max(...cols);
        if (
            rightmostSelected < maxCol &&
            maxCol - rightmostSelected === 1 &&
            !cols.includes(maxCol)
        ) {
            let rightBlank = [];
            for (let c = rightmostSelected + 1; c <= maxCol; c++) {
                if (!cols.includes(c)) rightBlank.push(c);
            }
            if (rightBlank.length === 1) {
                return {
                    valid: false,
                    reason: `Không được để trống ghế rìa bên phải như ghế ${row}${maxCol}.`,
                };
            }
        }
    }
    // Hợp lệ
    return { valid: true };
}
window.bookingApp = window.bookingApp || {};
window.bookingApp.isValidSeatSelectionAll = isValidSeatSelectionAll;

// ==== SỰ KIỆN TIẾP TỤC ====
document.addEventListener("DOMContentLoaded", function () {
    renderSeatLayout();
    if (typeof updateBookingSummary === "function") {
        updateBookingSummary();
    }

    const btnContinue = document.getElementById("btn-continue");
    if (btnContinue) {
        btnContinue.disabled = (window.selectedSeats || []).length === 0;
        btnContinue.addEventListener("click", function (e) {
            let selectedSeatsArr = Array.from(window.selectedSeats || []);
            if (selectedSeatsArr.length === 0) {
                showNotification(
                    "Thông báo",
                    "Vui lòng chọn ít nhất 1 ghế!",
                    "warning"
                );
                return;
            }
            const check = isValidSeatSelectionAll(selectedSeatsArr);
            if (!check.valid) {
                showBookingNotification(
                    "Thông báo",
                    check.reason ||
                        "Việc chọn vị trí ghế của bạn không được để trống 1 ghế ở bên trái, giữa hoặc bên phải trên cùng hàng ghế mà bạn vừa chọn.",
                    "warning"
                );
                return;
            }
            // Hợp lệ, hiện popup xác nhận tuổi
            $.sweetModal({
                title: `<div style=" margin-bottom:8px;display:flex;justify-content:center;"><span style="background:#ff9800;color:#fff;padding:3px 7px;border-radius:6px;font-weight:100;">${age}</span> </div>
                <span style="color: #333; text-align: center; display: block; font-weight: bold;">Xác nhận mua vé cho người có độ tuổi phù hợp</span>`,
                content: `<div style="color:#4080FF;font-size:15px;margin-top:8px;font-style:italic;">
                Tôi xác nhận mua vé phim này cho người có độ tuổi từ <b>${age} tuổi trở lên</b> và đồng ý cung cấp giấy tờ tuỳ thân để xác minh độ tuổi.
                </div>`,
                icon: $.sweetModal.ICON_INFO,
                theme: $.sweetModal.THEME_DARK,
                buttons: {
                    "Từ chối": {
                        classes: "grayB",
                        action: function () {},
                    },
                    "Xác nhận": {
                        classes: "orangeB",
                        action: function () {
                            document.getElementById(
                                "selectedSeatsInput"
                            ).value = selectedSeats.join(",");
                            document
                                .getElementById("form-chuyen-thanh-toan")
                                .submit();
                        },
                    },
                },
            });
        });
    }
});

// ==== UPDATE BOOKING SUMMARY ====
function updateBookingSummary() {
    let selectedSeats = window.selectedSeats || [];
    const seatNumbersElement = document.querySelector(".seat-numbers");
    if (seatNumbersElement) {
        // Map từ ID_Ghe sang TenGhe để hiển thị chuẩn A2, B5, v.v.
        const seatNames = selectedSeats.map((idGhe) => {
            for (let row of bookingData.seatLayout || []) {
                for (let seat of row) {
                    if (seat && String(seat.ID_Ghe) == String(idGhe)) {
                        return seat.TenGhe;
                    }
                }
            }
            return idGhe; // fallback nếu không tìm thấy
        });
        seatNumbersElement.textContent = seatNames.join(", ");
    }
    // Đếm loại ghế
    let normalCount = 0,
        vipCount = 0;
    selectedSeats.forEach((idGhe) => {
        for (let row of bookingData.seatLayout || []) {
            for (let seat of row) {
                if (seat && String(seat.ID_Ghe) == String(idGhe)) {
                    if (seat.LoaiTrangThaiGhe == 2) vipCount++;
                    else normalCount++;
                }
            }
        }
    });
    // Cập nhật seat-summary
    const seatSummaryElement = document.querySelector(".seat-summary");
    if (seatSummaryElement) {
        let text = "";
        if (normalCount > 0) text += `x${normalCount} Ghế thường`;
        if (vipCount > 0) text += (text ? ", " : "") + `x${vipCount} Ghế VIP`;
        seatSummaryElement.textContent = text;
        seatSummaryElement.style.display = text ? "block" : "none";
    }
    const totalPriceElement = document.querySelector(".total-price");
    let total = 0;
    selectedSeats.forEach((idGhe) => {
        for (let row of bookingData.seatLayout || []) {
            for (let seat of row) {
                if (seat && String(seat.ID_Ghe) == String(idGhe)) {
                    let price = bookingData.ticketPrice;
                    if (seat.LoaiTrangThaiGhe == 2)
                        price = Math.round(price * 1.2);
                    total += price;
                }
            }
        }
    });
    if (totalPriceElement) {
        totalPriceElement.textContent = total.toLocaleString("vi-VN") + " đ";
    }
    const btnContinue = document.getElementById("btn-continue");
    if (btnContinue) {
        btnContinue.disabled = selectedSeats.length === 0;
    }
}

// ====== TIMER GIỮ GHẾ (nếu cần) ======
let seatHoldTimerInterval = null;
let seatHoldTimeLeft = 0; // giây

function startSeatHoldTimer(durationSeconds = 360) {
    clearSeatHoldTimer();
    seatHoldTimeLeft = durationSeconds;
    const timerDiv = document.getElementById("seat-hold-timer");
    const timerText = document.getElementById("seat-hold-timer-text");
    if (timerDiv) timerDiv.style.display = "block";

    function updateTimer() {
        if (!timerText) return;
        let min = Math.floor(seatHoldTimeLeft / 60);
        let sec = seatHoldTimeLeft % 60;
        timerText.textContent = `${min.toString().padStart(2, "0")}:${sec
            .toString()
            .padStart(2, "0")}`;
        if (seatHoldTimeLeft <= 0) {
            clearSeatHoldTimer();
            if (timerDiv) timerDiv.style.display = "none";
            showBookingNotification(
                "Hết hạn giữ ghế",
                "Bạn đã hết thời gian giữ ghế, vui lòng chọn lại!",
                "warning"
            );
            // Optionally: call releaseHeldSeats() ở đây luôn nếu muốn giải phóng tự động
            setTimeout(function () {
                window.location.href = "/";
            }, 1500);
        }
        seatHoldTimeLeft--;
    }

    updateTimer();
    seatHoldTimerInterval = setInterval(updateTimer, 1000);
}

function clearSeatHoldTimer() {
    if (seatHoldTimerInterval) {
        clearInterval(seatHoldTimerInterval);
        seatHoldTimerInterval = null;
    }
    const timerDiv = document.getElementById("seat-hold-timer");
    if (timerDiv) timerDiv.style.display = "none";
}
