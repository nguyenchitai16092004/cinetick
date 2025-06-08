// Enhanced Booking Seat Management JavaScript
// Specifically designed for ticket booking page

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

    console.log("Initialized with data:", {
        seats: seats.length,
        rowAisles,
        colAisles,
        bookedSeats,
        suatChieuId,
        ticketPrice,
    });

    // Seat availability check function
    function checkSeatAvailability() {
        // Implementation for periodic seat availability check
        // This would typically make an AJAX call to check seat status
        console.log("Checking seat availability...");
    }

    // Seat validation functions
    function getSeatRowAndCol(seatId) {
        const match = seatId.match(/([A-Z])(\d+)/);
        if (!match) return null;
        return {
            row: match[1],
            col: parseInt(match[2]),
        };
    }

    function getSeatsByRow(row) {
        const rowSeats = [];
        document.querySelectorAll(".seat").forEach((seat) => {
            const seatId = seat.textContent.trim();
            if (
                seatId.startsWith(row) &&
                seat.classList.contains("available")
            ) {
                rowSeats.push({
                    id: seatId,
                    col: parseInt(seatId.slice(1)),
                    element: seat,
                });
            }
        });
        return rowSeats.sort((a, b) => a.col - b.col);
    }

    function isValidSeatSelection(newSeatId) {
        const seatInfo = getSeatRowAndCol(newSeatId);
        if (!seatInfo) return false;

        // Create temporary selection including the new seat
        const tempSelected = new Set([...selectedSeats, newSeatId]);
        const rowSeats = getSeatsByRow(seatInfo.row);

        if (rowSeats.length === 0) return false;

        // Get selected seats in this row
        const selectedInRow = rowSeats
            .filter((seat) => tempSelected.has(seat.id))
            .map((seat) => seat.col)
            .sort((a, b) => a - b);

        if (selectedInRow.length <= 1) return true;

        // Check for gaps between selected seats
        for (let i = 0; i < selectedInRow.length - 1; i++) {
            const current = selectedInRow[i];
            const next = selectedInRow[i + 1];

            if (next - current === 2) {
                // There's exactly one seat between current and next
                const middleSeatId = `${seatInfo.row}${current + 1}`;
                const middleSeat = rowSeats.find((s) => s.id === middleSeatId);

                if (middleSeat && !tempSelected.has(middleSeatId)) {
                    return false; // Gap found
                }
            }
        }

        // Check isolated seats at edges
        const allCols = rowSeats.map((seat) => seat.col).sort((a, b) => a - b);
        const minCol = Math.min(...allCols);
        const maxCol = Math.max(...allCols);

        // Check left edge isolation
        const leftmostSelected = Math.min(...selectedInRow);
        if (leftmostSelected > minCol && leftmostSelected - 1 === minCol) {
            const leftEdgeId = `${seatInfo.row}${minCol}`;
            if (!tempSelected.has(leftEdgeId)) {
                return false;
            }
        }

        // Check right edge isolation
        const rightmostSelected = Math.max(...selectedInRow);
        if (rightmostSelected < maxCol && rightmostSelected + 1 === maxCol) {
            const rightEdgeId = `${seatInfo.row}${maxCol}`;
            if (!tempSelected.has(rightEdgeId)) {
                return false;
            }
        }

        return true;
    }

    // Validate all selected seats (for final check before continue)
    function isValidSeatSelectionAll(seatArray) {
        if (!seatArray || seatArray.length === 0) return true;

        // Group seats by row
        const seatsByRow = {};
        seatArray.forEach((seatId) => {
            const row = seatId.charAt(0);
            if (!seatsByRow[row]) seatsByRow[row] = [];
            seatsByRow[row].push(parseInt(seatId.slice(1)));
        });

        // Validate each row
        for (const row in seatsByRow) {
            const cols = seatsByRow[row].sort((a, b) => a - b);

            // Check for gaps between consecutive seats
            for (let i = 0; i < cols.length - 1; i++) {
                if (cols[i + 1] - cols[i] === 2) {
                    // There's exactly one seat gap between current and next
                    const middleSeat = `${row}${cols[i] + 1}`;
                    if (!seatArray.includes(middleSeat)) {
                        console.log(`Gap found at ${middleSeat}`);
                        return false;
                    }
                }
            }

            // Check for isolated seats at edges
            const allSeatsInRow = [];
            document.querySelectorAll(".seat").forEach((seat) => {
                const seatId = seat.textContent.trim();
                if (
                    seatId.startsWith(row) &&
                    (seat.classList.contains("available") ||
                        seat.classList.contains("selected"))
                ) {
                    allSeatsInRow.push(parseInt(seatId.slice(1)));
                }
            });

            if (allSeatsInRow.length === 0) continue;

            allSeatsInRow.sort((a, b) => a - b);
            const minCol = Math.min(...allSeatsInRow);
            const maxCol = Math.max(...allSeatsInRow);

            // Check left edge isolation
            const leftmostSelected = Math.min(...cols);
            if (leftmostSelected > minCol && leftmostSelected - 1 === minCol) {
                const leftEdgeId = `${row}${minCol}`;
                if (!seatArray.includes(leftEdgeId)) {
                    console.log(`Left edge isolation at ${leftEdgeId}`);
                    return false;
                }
            }

            // Check right edge isolation
            const rightmostSelected = Math.max(...cols);
            if (
                rightmostSelected < maxCol &&
                rightmostSelected + 1 === maxCol
            ) {
                const rightEdgeId = `${row}${maxCol}`;
                if (!seatArray.includes(rightEdgeId)) {
                    console.log(`Right edge isolation at ${rightEdgeId}`);
                    return false;
                }
            }
        }

        return true;
    }

    // Render seat layout
    function renderSeatLayout() {
        console.log("Starting renderSeatLayout with seats:", seats);
        console.log("Column aisles:", colAisles);
        console.log("Row aisles:", rowAisles);

        if (!seats || seats.length === 0) {
            seatContainer.innerHTML =
                '<div class="placeholder-text text-muted text-center py-5">Không có thông tin về sơ đồ ghế</div>';
            return;
        }

        seatContainer.innerHTML = "";
        seatContainer.className = "seat-container";

        const rowCount = seats.length;
        const colCount = seats[0] ? seats[0].length : 0;

        console.log("Grid dimensions:", { rowCount, colCount });

        // Calculate grid template columns
        let gridTemplateColumns = "auto"; // Row label column
        let totalCols = 1; // Start with 1 for row label

        for (let j = 0; j < colCount; j++) {
            // Check if we need an aisle BEFORE this column
            if (colAisles.includes(j)) {
                gridTemplateColumns += " 15px"; // Aisle width
                totalCols++;
                console.log(`Adding column aisle before column ${j}`);
            }
            gridTemplateColumns += " 35px"; // Seat width
            totalCols++;
        }

        console.log("Grid template columns:", gridTemplateColumns);
        console.log("Total columns:", totalCols);
        seatContainer.style.gridTemplateColumns = gridTemplateColumns;

        // Create seat rows
        for (let i = 0; i < rowCount; i++) {
            // Row label
            const rowLabel = document.createElement("div");
            rowLabel.className = "row-label";
            rowLabel.textContent = String.fromCharCode(65 + i);
            seatContainer.appendChild(rowLabel);

            // Seats in row
            for (let j = 0; j < colCount; j++) {
                // Add column aisle if needed (BEFORE this seat)
                if (colAisles.includes(j)) {
                    const aisle = document.createElement("div");
                    aisle.className = "aisle aisle-col";
                    aisle.style.width = "15px";
                    aisle.style.height = "35px";
                    console.log(
                        `Creating column aisle before seat [${i}][${j}]`
                    );
                    seatContainer.appendChild(aisle);
                }

                // Create seat
                const seatData = seats[i][j];
                if (!seatData) {
                    console.warn(`Missing seat data at position [${i}][${j}]`);
                    // Create empty placeholder
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

                console.log(
                    `Creating seat ${seatId} with status:`,
                    seatData.TrangThaiGhe
                );

                // Set seat status
                if (seatData.TrangThaiGhe === 0) {
                    // Disabled seat
                    seat.classList.add("disabled");
                } else if (seatData.IsBooked || bookedSeats.includes(seatId)) {
                    // Booked seat
                    seat.classList.add("booked");
                } else {
                    // Available seat
                    seat.classList.add("available");

                    if (seatData.TrangThaiGhe === 2) {
                        seat.classList.add("vip");
                    } else {
                        seat.classList.add("normal");
                    }

                    // Add click handler for available seats
                    seat.addEventListener("click", function () {
                        handleSeatClick(this);
                    });
                }

                seatContainer.appendChild(seat);
            }

            // Add row aisle if needed (AFTER this row)
            if (rowAisles.includes(i + 1)) {
                const aisleRow = document.createElement("div");
                aisleRow.className = "aisle aisle-row";
                aisleRow.style.gridColumn = `1 / span ${totalCols}`;
                aisleRow.style.height = "15px";
                aisleRow.style.backgroundColor = "transparent";
                console.log(`Creating row aisle after row ${i + 1}`);
                seatContainer.appendChild(aisleRow);
            }
        }

        console.log("Seat layout rendered successfully");
    }

    // Handle seat click
    function handleSeatClick(seatElement) {
        if (isProcessing) return;

        const seatId = seatElement.dataset.seatId;

        if (seatElement.classList.contains("selected")) {
            // Deselect seat
            seatElement.classList.remove("selected");
            selectedSeats.delete(seatId);
        } else {
            // Select seat
            if (selectedSeats.size >= maxSeats) {
                showNotification(
                    "Cảnh báo",
                    `Bạn chỉ được chọn tối đa ${maxSeats} ghế.`,
                    "warning"
                );
                return;
            }

            // Add validation check here if you want immediate feedback
            // Uncomment below lines if you want validation on click
            /*
            if (!isValidSeatSelection(seatId)) {
                showNotification(
                    "Thông báo",
                    "Việc chọn vị trí ghế của bạn không được để trống 1 ghế ở bên trái, giữa hoặc bên phải trên cùng hàng ghế mà bạn vừa chọn.",
                    "warning"
                );
                return;
            }
            */

            seatElement.classList.add("selected");
            selectedSeats.add(seatId);
        }

        updateBookingSummary();
    }

    // Update booking summary
    function updateBookingSummary() {
        const seatArray = Array.from(selectedSeats).sort();

        // Update seat numbers display
        if (seatNumbersElement) {
            seatNumbersElement.textContent =
                seatArray.length > 0 ? seatArray.join(", ") : "";
        }

        // Calculate total price
        let totalPrice = 0;
        let normalCount = 0;
        let vipCount = 0;

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

        // Update summary display
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

        // Update continue button state
        if (btnContinue) {
            btnContinue.disabled = selectedSeats.size === 0;
        }

        // Cập nhật biến toàn cục để form gửi đúng ghế đã chọn
        window.selectedSeats = seatArray;

        console.log("Updated selectedSeats:", window.selectedSeats);
    }

    // Handle showtime change
    function handleShowtimeChange() {
        timeButtons.forEach((button) => {
            button.addEventListener("click", function () {
                if (isProcessing) return;

                // Update active state
                timeButtons.forEach((btn) => btn.classList.remove("active"));
                button.classList.add("active");

                // Get new showtime info
                const gioChieu = button.getAttribute("data-gio");
                const suatChieuIdNew = button.getAttribute("data-id");

                // Update display
                if (showtimeInfo) {
                    const ngayChieu = showtimeInfo.textContent.split(" - ")[1];
                    showtimeInfo.innerHTML = `<span>Suất: ${gioChieu}</span> - ${ngayChieu}`;
                }

                // Redirect to new showtime
                if (suatChieuIdNew && suatChieuIdNew !== suatChieuId) {
                    isProcessing = true;
                    const currentUrl = new URL(window.location.href);
                    const pathParts = currentUrl.pathname.split("/");

                    // Update time in URL
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

        // Try to use SweetModal if available
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
            // Use the global notification function if available
            showBookingNotification(title, message, type);
        } else {
            // Fallback to alert
            alert(title + ": " + message);
        }
    }

    // Initialize everything
    function init() {
        console.log("Initializing booking app...");
        renderSeatLayout();
        handleShowtimeChange();
        updateBookingSummary();

        // Set up periodic seat availability check
        setInterval(checkSeatAvailability, 30000); // Check every 30 seconds
    }

    // Initialize window.bookingApp object first
    window.bookingApp = {
        selectedSeats,
        renderSeatLayout,
        updateBookingSummary,
        checkSeatAvailability,
        isValidSeatSelection,
        isValidSeatSelectionAll,
        showNotification,
    };

    // Start the application
    init();

    console.log("Booking app initialized successfully");
});
