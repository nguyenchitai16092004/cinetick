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
    const heldSeatsByOthers = bookingData.heldSeatsByOthers || [];

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
        // Label hàng (A, B, ...)
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
            seat.dataset.row = i;
            seat.dataset.col = j;
            seat.dataset.seatId = seatData.ID_Ghe;

            // Xác định trạng thái ghế
            if (myHeldSeats && myHeldSeats.has(String(seatData.ID_Ghe))) {
                // Ghế do chính user đang giữ
                seat.textContent = seatData.TenGhe;
                seat.classList.add("held", "selected");
                seat.setAttribute("title", "Bạn đang giữ ghế này");
            } else if (
                heldSeatsByOthers &&
                heldSeatsByOthers.includes(String(seatData.ID_Ghe))
            ) {
                // Ghế đang bị người khác giữ
                seat.classList.add("waiting");
                seat.setAttribute(
                    "title",
                    "Ghế này đang có người khác giữ (đang chờ xác nhận)"
                );
                // Ẩn tên ghế, chỉ hiển thị dấu chấm động
                seat.textContent = "";
                const waitingEl = document.createElement("span");
                waitingEl.className = "seat-waiting-dots";
                waitingEl.innerHTML =
                    "<span>.</span><span>.</span><span>.</span>";
                seat.appendChild(waitingEl);
            } else if (seatData.TrangThaiGhe === 0) {
                // Ghế bảo trì/vô hiệu hóa
                seat.textContent = seatData.TenGhe;
                seat.classList.add("disabled");
                seat.setAttribute("title", "Ghế đang bảo trì hoặc bị khóa");
            } else if (
                seatData.IsBooked ||
                bookedSeats.includes(seatData.TenGhe)
            ) {
                // Ghế đã đặt
                seat.textContent = seatData.TenGhe;
                seat.classList.add("booked");
                seat.setAttribute("title", "Ghế đã được đặt");
            } else {
                // Ghế có thể chọn
                seat.textContent = seatData.TenGhe;
                seat.classList.add("available");
                if (seatData.TrangThaiGhe === 2) {
                    seat.classList.add("vip");
                    seat.setAttribute("title", "Ghế VIP");
                } else {
                    seat.classList.add("normal");
                    seat.setAttribute("title", "Ghế thường");
                }
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
            if (this.classList.contains("available") && myHeldSeats.size >= 8) {
                window.showBookingNotification(
                    "Thông báo",
                    "Bạn chỉ được chọn tối đa 8 ghế mỗi lần đặt!",
                    "warning"
                );
                return;
            }
            const ma_ghe = this.dataset.seatId;
            // Lấy thông tin ghế từ seatLayout
            let seatInfo = null;
            outer: for (let i = 0; i < bookingData.seatLayout.length; i++) {
                for (let j = 0; j < bookingData.seatLayout[i].length; j++) {
                    const seat = bookingData.seatLayout[i][j];
                    if (seat && String(seat.ID_Ghe) === String(ma_ghe)) {
                        seatInfo = seat;
                        break outer;
                    }
                }
            }
            if (!seatInfo) return;

            // Xử lý cập nhật giá ngay khi click
            let price = bookingData.ticketPrice;
            if (seatInfo.LoaiTrangThaiGhe == 2) {
                // Ghế VIP
                price = Math.round(price * 1.2);
            }
            // Có thể show thông báo hoặc cập nhật UI cho giá ghế này
            // Ví dụ: showBookingNotification("Thông tin ghế", `Bạn chọn ghế ${seatInfo.TenGhe} (${seatInfo.LoaiTrangThaiGhe == 2 ? 'VIP' : 'Thường'}), giá: ${price.toLocaleString()} đ`);

            // Tiếp tục logic giữ/nhả ghế như cũ
            if (this.classList.contains("held") && isMyHeldSeat(ma_ghe)) {
                await releaseSeat(ma_ghe);
                renderSeatLayout();
                return;
            }
            if (this.classList.contains("waiting")) {
                // Có thể hiển thị tooltip hoặc notification nếu muốn
                window.showBookingNotification(
                    "Thông báo",
                    "Ghế này đang được người khác giữ!",
                    "warning"
                );
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

        if (data.hold_until) {
            const now = Math.floor(Date.now() / 1000);
            const remaining = data.hold_until - now;
            if (remaining > 0) startSeatHoldTimer(remaining);
        } else if (!seatHoldTimerInterval) {
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
    localStorage.removeItem(`hold_until_${ma_ghe}`);
    // Chỉ ẩn timer nếu không còn ghế nào giữ
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
// Cần lưu danh sách ghế đang bị giữ bởi người khác
window.bookingData.heldSeatsByOthers =
    window.bookingData.heldSeatsByOthers || [];

function updateSeatStatus(
    seatId,
    status,
    heldUntil = null,
    byCurrentUser = false
) {
    console.log("updateSeatStatus:", seatId, status, heldUntil, byCurrentUser);
    if (status === "held") {
        if (byCurrentUser) myHeldSeats.add(String(seatId));
        else {
            myHeldSeats.delete(String(seatId));
            // Bị người khác giữ
            if (
                !window.bookingData.heldSeatsByOthers.includes(String(seatId))
            ) {
                window.bookingData.heldSeatsByOthers.push(String(seatId));
            }
        }
        if (heldUntil && byCurrentUser) startHoldTimer(seatId, heldUntil);
    } else {
        myHeldSeats.delete(String(seatId));
        // Nếu được trả lại, remove khỏi heldSeatsByOthers
        window.bookingData.heldSeatsByOthers =
            window.bookingData.heldSeatsByOthers.filter(
                (id) => id !== String(seatId)
            );
        clearHoldTimer(seatId);
    }
    renderSeatLayout();
}

function isBookingOrPaymentRoute() {
    const path = window.location.pathname;
    return path.startsWith("/dat-ve") || path.startsWith("/thanh-toan");
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
    window.selectedSeats = Array.from(myHeldSeats);
    let maxHoldUntil = 0;
    (myHeldSeats ? Array.from(myHeldSeats) : []).forEach((ma_ghe) => {
        const holdUntil = parseInt(
            localStorage.getItem(`hold_until_${ma_ghe}`)
        );
        if (holdUntil && holdUntil > maxHoldUntil) maxHoldUntil = holdUntil;
    });
    if (maxHoldUntil) {
        const now = Math.floor(Date.now() / 1000);
        const remaining = maxHoldUntil - now;
        if (remaining > 0) startSeatHoldTimer(remaining);
    }
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

            // Nếu độ tuổi là P hoặc K thì không cần hiện popup xác nhận độ tuổi
            if (age === "P" || age === "K") {
                document.getElementById("selectedSeatsInput").value =
                    selectedSeats.join(",");
                document.getElementById("form-chuyen-thanh-toan").submit();
                return;
            }

            // Hợp lệ, hiện popup xác nhận tuổi
            $.sweetModal({
                title: `<div style="margin-bottom:8px;display:flex;justify-content:center;">
                            
                        </div>
                        <span style="color: #333; text-align: center; display: block; font-weight: bold;">
                            Xác nhận mua vé cho người có độ tuổi phù hợp
                        </span>`,
                content: `<div style="color:#4080FF;font-size:15px;margin-top:8px;font-style:italic;">
                    Tôi xác nhận mua vé phim này cho người có độ tuổi từ <b>${getAgeLabel(
                        age
                    )}</b> và đồng ý cung cấp giấy tờ tuỳ thân để xác minh độ tuổi.
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
    // Khi click vào link hoặc nút quay lại
    document.querySelectorAll("a, .back-button").forEach(function (el) {
        el.addEventListener("click", function (e) {
            const href = el.getAttribute("href") || "";
            const isFlow =
                href.startsWith("/dat-ve") ||
                href.startsWith("/thanh-toan") ||
                href === ""; // back-button
            if (!isFlow) {
                e.preventDefault();
                releaseAllHeldSeats();
                setTimeout(function () {
                    if (href) window.location.href = href;
                    else window.history.back();
                }, 100);
            }
        });
    });

    function getAgeLabel(ageCode) {
        switch (ageCode) {
            case "P":
                return "Phim dành cho mọi lứa tuổi (P)";
            case "K":
                return "Phim dành cho trẻ em (K)";
            case "T13 (13+)":
                return "13 tuổi trở lên (T13)";
            case "T16 (16+)":
                return "16 tuổi trở lên (T16)";
            case "T18 (18+)":
                return "18 tuổi trở lên (T18)";
            default:
                return ageCode;
        }
    }
    // Khi reload, đóng tab, chuyển tab
    window.addEventListener("beforeunload", function () {
        if (!isBookingOrPaymentRoute()) releaseAllHeldSeats();
    });
    window.addEventListener("visibilitychange", function () {
        if (document.visibilityState === "hidden" && !isBookingOrPaymentRoute())
            releaseAllHeldSeats();
    });
});

// ==== UPDATE BOOKING SUMMARY ====
function updateBookingSummary() {
    let selectedSeats = window.selectedSeats || [];
    let normalCount = 0,
        vipCount = 0;
    let normalSeats = [],
        vipSeats = [];

    selectedSeats.forEach((idGhe) => {
        let found = false;
        for (let row of bookingData.seatLayout || []) {
            for (let seat of row) {
                if (seat && String(seat.ID_Ghe) == String(idGhe)) {
                    found = true;
                    if (seat.LoaiTrangThaiGhe == 2 || seat.TrangThaiGhe == 2) {
                        vipCount++;
                        vipSeats.push(seat.TenGhe);
                    } else {
                        normalCount++;
                        normalSeats.push(seat.TenGhe);
                    }
                    break;
                }
            }
            if (found) break;
        }
    });

    // Hiển thị số lượng loại ghế
    const seatSummaryElement = document.querySelector(".seat-summary");
    if (seatSummaryElement) {
        let text = "";
        if (normalCount > 0) text += `x${normalCount} Ghế thường`;
        if (vipCount > 0) text += (text ? ", " : "") + `x${vipCount} Ghế VIP`;
        seatSummaryElement.textContent = text;
        seatSummaryElement.style.display = text ? "block" : "none";
    }

    // Hiển thị tên ghế
    const seatNumbersElement = document.querySelector(".seat-numbers");
    if (seatNumbersElement) {
        seatNumbersElement.textContent = [...vipSeats, ...normalSeats].join(
            ", "
        );
    }

    // Tính tổng tiền
    const totalPriceElement = document.querySelector(".total-price");
    let total = 0;
    selectedSeats.forEach((idGhe) => {
        for (let row of bookingData.seatLayout || []) {
            for (let seat of row) {
                if (seat && String(seat.ID_Ghe) == String(idGhe)) {
                    let price = bookingData.ticketPrice;
                    if (seat.LoaiTrangThaiGhe == 2 || seat.TrangThaiGhe == 2)
                        price = Math.round(price * 1.2);
                    total += price;
                    break;
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

function startSeatHoldTimer(durationSeconds) {
    if (typeof durationSeconds !== "number" || durationSeconds <= 0) return;
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
            releaseAllHeldSeats();
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
function getAllHeldSeats() {
    return Array.from(myHeldSeats); // hoặc window.selectedSeats nếu sync
}
function releaseAllHeldSeats() {
    const heldSeats = getAllHeldSeats();
    if (!heldSeats || heldSeats.length === 0) return;
    if ( !window.bookingData || !window.bookingData.suatChieuId ) return;
    heldSeats.forEach((ma_ghe) =>
        localStorage.removeItem(`hold_until_${ma_ghe}`)
    );
    clearSeatHoldTimer();
    if (!window.currentUserId) return;
    const payload = JSON.stringify({
        danh_sach_ghe: heldSeats,
        suat_chieu_id: window.bookingData.suatChieuId,
        user_id: window.currentUserId,
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
