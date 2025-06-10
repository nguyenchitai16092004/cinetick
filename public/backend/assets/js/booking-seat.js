// Enhanced Booking Seat Management JavaScript
// Specifically designed for ticket booking page

// Hàm bỏ giữ ghế (release) dùng ID_Ghe
async function releaseSeat(ma_ghe) {
    // Lấy suatChieuId từ window.bookingData hoặc window.suatChieuId
    const suat_chieu_id =
        window.bookingData?.suatChieuId || window.suatChieuId || null;
    if (!suat_chieu_id) {
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
            suat_chieu_id,
        }),
    });
}

document.addEventListener("DOMContentLoaded", function () {
    console.log("Booking seat script initialized");

    // Initialize booking data from server
    const bookingData = window.bookingData || {};
    let seats = bookingData.seatLayout || [];
    let rowAisles = bookingData.rowAisles || [];
    let colAisles = bookingData.colAisles || [];
    let bookedSeats = bookingData.bookedSeats || [];
    let suatChieuId = bookingData.suatChieuId || null;
    let ticketPrice = bookingData.ticketPrice || 0;
    let vipSurcharge = bookingData.vipSurcharge || 20000;

    // DOM Elements
    const seatContainer = document.getElementById("seatLayout");
    const seatNumbersElement = document.querySelector(".seat-numbers");
    const totalPriceElement = document.querySelector(".total-price");
    const seatSummaryElement = document.querySelector(".seat-summary");
    const ticketPriceElement = document.querySelector(".ticket-price");
    const btnContinue = document.getElementById("btn-continue");
    const timeButtons = document.querySelectorAll(".time-btn");
    const showtimeInfo = document.querySelector(".showtime-info");

    // Booking state
    const selectedSeats = new Set();
    const maxSeats = 8;
    let isProcessing = false;

    // --- Helper: lấy TenGhe từ ID_Ghe (nếu cần)
    function getTenGheById(id_ghe) {
        for (let i = 0; i < seats.length; i++) {
            for (let j = 0; j < seats[i].length; j++) {
                const seat = seats[i][j];
                if (seat && seat.ID_Ghe == id_ghe) return seat.TenGhe;
            }
        }
        return id_ghe;
    }

    // Seat availability check function (dummy)
    function checkSeatAvailability() {
        // Bạn nên bổ sung AJAX check trạng thái ghế ở đây nếu muốn realtime hơn
        console.log("Checking seat availability...");
    }

    // Render seat layout (dùng ID_Ghe cho data-seat-id)
    function renderSeatLayout() {
        if (!seats || seats.length === 0) {
            seatContainer.innerHTML =
                '<div class="placeholder-text text-muted text-center py-5">Không có thông tin về sơ đồ ghế</div>';
            return;
        }

        seatContainer.innerHTML = "";
        seatContainer.className = "seat-container";

        const rowCount = seats.length;
        const colCount = seats[0] ? seats[0].length : 0;
        let totalCols = 1;
        let gridTemplateColumns = "auto";
        for (let j = 0; j < colCount; j++) {
            if (colAisles.includes(j)) {
                gridTemplateColumns += " 15px";
                totalCols++;
            }
            gridTemplateColumns += " 35px";
            totalCols++;
        }
        seatContainer.style.gridTemplateColumns = gridTemplateColumns;

        for (let i = 0; i < rowCount; i++) {
            // Row label
            const rowLabel = document.createElement("div");
            rowLabel.className = "row-label";
            rowLabel.textContent = String.fromCharCode(65 + i);
            seatContainer.appendChild(rowLabel);

            // Seats in row
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
                seat.dataset.seatId = seatData.ID_Ghe; // Sử dụng ID_Ghe!

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
                // Gán sự kiện click
                seat.onclick = async function () {
                    if (
                        isProcessing ||
                        seat.classList.contains("held") ||
                        seat.classList.contains("booked") ||
                        seat.classList.contains("disabled")
                    )
                        return;

                    const seatId = this.dataset.seatId;
                    if (seat.classList.contains("selected")) {
                        seat.classList.remove("selected");
                        selectedSeats.delete(seatId);
                        await releaseSeat(seatId);
                        updateBookingSummary();
                        return;
                    }

                    // Giữ ghế
                    const res = await fetch("/dat-ve/giu-ghe", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": window.csrfToken,
                        },
                        body: JSON.stringify({
                            ma_ghe: seatId,
                            suat_chieu_id: suatChieuId,
                        }),
                    });
                    const data = await res.json();
                    if (!data.success) {
                        showNotification(
                            "Thông báo",
                            data.error || "Không thể giữ ghế",
                            "warning"
                        );
                        return;
                    }
                    handleSeatClick(seat);
                };
                seatContainer.appendChild(seat);
            }
            // Row aisle after row
            if (rowAisles.includes(i + 1)) {
                const aisleRow = document.createElement("div");
                aisleRow.className = "aisle aisle-row";
                aisleRow.style.gridColumn = `1 / span ${totalCols}`;
                aisleRow.style.height = "15px";
                aisleRow.style.backgroundColor = "transparent";
                seatContainer.appendChild(aisleRow);
            }
        }
    }

    // Handle seat click (UI)
    function handleSeatClick(seatElement) {
        if (isProcessing) return;
        const seatId = seatElement.dataset.seatId;
        if (seatElement.classList.contains("selected")) {
            seatElement.classList.remove("selected");
            selectedSeats.delete(seatId);
        } else {
            if (selectedSeats.size >= maxSeats) {
                showNotification(
                    "Cảnh báo",
                    `Bạn chỉ được chọn tối đa ${maxSeats} ghế.`,
                    "warning"
                );
                return;
            }
            seatElement.classList.add("selected");
            selectedSeats.add(seatId);
        }
        updateBookingSummary();
    }

    // Update booking summary
    function updateBookingSummary() {
        const seatArray = Array.from(selectedSeats).sort();
        if (seatNumbersElement) {
            seatNumbersElement.textContent =
                seatArray.length > 0
                    ? seatArray.map(getTenGheById).join(", ")
                    : "";
        }
        let totalPrice = 0,
            normalCount = 0,
            vipCount = 0;
        seatArray.forEach((seatId) => {
            const seatElement = document.querySelector(
                `[data-seat-id="${seatId}"]`
            );
            if (seatElement) {
                if (seatElement.classList.contains("vip")) {
                    totalPrice += ticketPrice * 1.2;
                    vipCount++;
                } else {
                    totalPrice += ticketPrice;
                    normalCount++;
                }
            }
        });
        if (seatSummaryElement) {
            let summaryText = "";
            if (normalCount > 0) summaryText += `x${normalCount} Ghế thường`;
            if (vipCount > 0) {
                if (summaryText) summaryText += ", ";
                summaryText += `x${vipCount} Ghế VIP`;
            }
            seatSummaryElement.textContent = summaryText;
            seatSummaryElement.style.display = summaryText ? "block" : "none";
        }
        if (totalPriceElement) {
            totalPriceElement.textContent =
                totalPrice.toLocaleString("vi-VN") + " VNĐ";
        }
        if (btnContinue) {
            btnContinue.disabled = selectedSeats.size === 0;
        }
        window.selectedSeats = seatArray;
        console.log("Updated selectedSeats:", window.selectedSeats);
    }

    // Handle showtime change
    function handleShowtimeChange() {
        timeButtons.forEach((button) => {
            button.addEventListener("click", function () {
                if (isProcessing) return;
                timeButtons.forEach((btn) => btn.classList.remove("active"));
                button.classList.add("active");
                const gioChieu = button.getAttribute("data-gio");
                const suatChieuIdNew = button.getAttribute("data-id");
                if (showtimeInfo) {
                    const ngayChieu = showtimeInfo.textContent.split(" - ")[1];
                    showtimeInfo.innerHTML = `<span>Suất: ${gioChieu}</span> - ${ngayChieu}`;
                }
                if (suatChieuIdNew && suatChieuIdNew !== suatChieuId) {
                    isProcessing = true;
                    const currentUrl = new URL(window.location.href);
                    const pathParts = currentUrl.pathname.split("/");
                    pathParts[pathParts.length - 1] = gioChieu.replace(
                        ":",
                        "-"
                    );
                    window.location.href = pathParts.join("/");
                }
            });
        });
    }

    // Notification function
    function showNotification(title, message, type = "info") {
        console.log("Showing notification:", { title, message, type });
        if (typeof $ !== "undefined" && $.sweetModal) {
            $.sweetModal({
                content: message,
                title: title,
                icon:
                    type === "warning"
                        ? $.sweetModal.ICON_WARNING
                        : $.sweetModal.ICON_INFO,
                theme: $.sweetModal.THEME_DARK,
                buttons: {
                    OK: {
                        classes: "redB",
                    },
                },
            });
        } else if (typeof showBookingNotification === "function") {
            showBookingNotification(title, message, type);
        } else {
            alert(title + ": " + message);
        }
    }

    // Initialize everything
    function init() {
        renderSeatLayout();
        handleShowtimeChange();
        updateBookingSummary();
        setInterval(checkSeatAvailability, 30000);
    }

    window.bookingApp = {
        selectedSeats,
        renderSeatLayout,
        updateBookingSummary,
        checkSeatAvailability,
        showNotification,
    };

    init();
});
