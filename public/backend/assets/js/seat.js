// Enhanced Room Management JavaScript
// Supports both creation and detail views with room type logic
// Maximum 100 seats limit for all room types

document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements
    const roomForm = document.getElementById('roomForm');
    const rowCountSelect = document.getElementById('rowCount');
    const colCountSelect = document.getElementById('colCount');
    const rowAislesSelect = document.getElementById('rowAisles');
    const colAislesSelect = document.getElementById('colAisles');
    const seatContainer = document.getElementById('seatLayout');
    const seatLayoutInput = document.getElementById('seatLayoutInput');
    const clearAislesBtn = document.getElementById('clearAislesBtn');
    const submitBtn = document.getElementById('submitBtn');
    const previewBtn = document.getElementById('previewBtn');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const seatCountSpan = document.getElementById('seatCount');
    const roomTypeSelect = document.querySelector('select[name="LoaiPhong"]');

    // Initialize data
    let seats = [];
    let rowAisles = [];
    let colAisles = [];
    let seatCount = 0;
    let isDetailView = false;
    let currentRoomType = null;
    
    // Giới hạn tối đa 100 ghế
    const MAX_SEATS = 100;

    // Try to parse initial data if available
    try {
        if (seatLayoutInput && seatLayoutInput.value) {
            // Parse seat layout from hidden input
            seats = JSON.parse(seatLayoutInput.value);
            isDetailView = seats && seats.length > 0;

            // Parse aisles data if available
            if (rowAislesSelect) {
                rowAisles = Array.from(rowAislesSelect.selectedOptions).map(opt => parseInt(opt.value));
            }
            if (colAislesSelect) {
                colAisles = Array.from(colAislesSelect.selectedOptions).map(opt => parseInt(opt.value));
            }

            // Lấy loại phòng hiện tại nếu có
            if (roomTypeSelect) {
                currentRoomType = roomTypeSelect.value;
            }

            console.log('Initial data loaded:', {
                isDetailView,
                seats,
                rowAisles,
                colAisles,
                currentRoomType
            });
        }
    } catch (e) {
        console.error('Error initializing data:', e);
        seats = [];
    }

    // Cập nhật giới hạn số hàng và cột - tối đa 100 ghế
    function updateRowAndColOptions() {
        const currentRow = rowCountSelect ? parseInt(rowCountSelect.value) || 0 : 0;
        const currentCol = colCountSelect ? parseInt(colCountSelect.value) || 0 : 0;

        // Lưu giá trị hiện tại
        const currentRowText = rowCountSelect ? rowCountSelect.value : '';
        const currentColText = colCountSelect ? colCountSelect.value : '';

        // Cập nhật options cho số hàng (5-10)
        if (rowCountSelect) {
            rowCountSelect.innerHTML = '<option value="" disabled selected>Chọn số hàng</option>';
            for (let i = 5; i <= 10; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                if (currentRowText === i.toString()) option.selected = true;
                rowCountSelect.appendChild(option);
            }
        }

        // Cập nhật options cho số cột với kiểm tra giới hạn 100 ghế
        if (colCountSelect) {
            colCountSelect.innerHTML = '<option value="" disabled selected>Chọn số ghế</option>';
            
            const selectedRows = currentRow || parseInt(rowCountSelect?.value) || 10;
            const maxColsForCurrentRows = Math.floor(MAX_SEATS / selectedRows);
            const maxCols = Math.min(10, maxColsForCurrentRows); // Tối đa 10 cột hoặc số cột cho phép với 100 ghế
            
            for (let i = 6; i <= maxCols; i++) {
                const totalSeats = selectedRows * i;
                if (totalSeats <= MAX_SEATS) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = i;
                    if (currentColText === i.toString()) option.selected = true;
                    colCountSelect.appendChild(option);
                }
            }
        }

        console.log(`Updated options - Max seats: ${MAX_SEATS}`);
    }

    // Kiểm tra giới hạn ghế trước khi render (không hiển thị thông báo)
    function validateSeatLimit(rows, cols) {
        const totalSeats = rows * cols;
        return totalSeats <= MAX_SEATS;
    }

    // Chuẩn hóa dữ liệu ghế
    function normalizeSeats(rowCount, colCount) {
        // Kiểm tra giới hạn ghế
        if (!validateSeatLimit(rowCount, colCount)) {
            return seats;
        }

        if (!Array.isArray(seats) || seats.length === 0) {
            console.warn('Invalid or empty seats data, initializing default');
            // Tạo mảng ghế mới với mỗi ghế là một đối tượng độc lập
            seats = Array(rowCount).fill().map(() =>
                Array(colCount).fill().map(() => ({
                    TrangThaiGhe: 1 // Mỗi ghế là một đối tượng mới
                }))
            );
        } else {
            // Đảm bảo đủ số hàng
            while (seats.length < rowCount) {
                seats.push(
                    Array(colCount).fill().map(() => ({
                        TrangThaiGhe: 1
                    }))
                );
            }

            // Cắt bớt hàng nếu thừa
            if (seats.length > rowCount) {
                seats = seats.slice(0, rowCount);
            }

            // Đảm bảo mỗi hàng đủ số cột
            for (let i = 0; i < rowCount; i++) {
                if (!Array.isArray(seats[i])) {
                    seats[i] = [];
                }

                // Thêm cột nếu thiếu
                while (seats[i].length < colCount) {
                    seats[i].push({
                        TrangThaiGhe: 1
                    });
                }

                // Cắt bớt cột nếu thừa
                if (seats[i].length > colCount) {
                    seats[i] = seats[i].slice(0, colCount);
                }

                // Đảm bảo mỗi phần tử là đối tượng có TrangThaiGhe
                for (let j = 0; j < colCount; j++) {
                    if (!seats[i][j] || typeof seats[i][j] !== 'object') {
                        seats[i][j] = {
                            TrangThaiGhe: 1
                        };
                    } else if (seats[i][j].TrangThaiGhe === undefined) {
                        seats[i][j].TrangThaiGhe = 1;
                    }
                }
            }
        }

        console.log('Normalized seats:', seats);
        return seats;
    }

    // Áp dụng loại phòng cho tất cả ghế
    function applyRoomTypeToAllSeats(roomType) {
        const rowCount = parseInt(rowCountSelect.value) || 0;
        const colCount = parseInt(colCountSelect.value) || 0;

        // Kiểm tra giới hạn ghế
        if (!validateSeatLimit(rowCount, colCount)) {
            return;
        }

        if (!rowCount || !colCount || !seats || seats.length === 0) {
            return;
        }

        // Đảm bảo mảng ghế đã được chuẩn hóa
        normalizeSeats(rowCount, colCount);

        let changedSeats = 0;
        
        // Áp dụng loại ghế dựa vào loại phòng
        for (let i = 0; i < rowCount; i++) {
            for (let j = 0; j < colCount; j++) {
                if (seats[i][j] && seats[i][j].TrangThaiGhe !== 0) { // Không thay đổi ghế đã bị vô hiệu hóa
                    const oldState = seats[i][j].TrangThaiGhe;
                    
                    if (roomType === '1') { // Phòng VIP
                        seats[i][j].TrangThaiGhe = 2; // Chuyển thành ghế VIP
                    } else if (roomType === '0') { // Phòng thường
                        seats[i][j].TrangThaiGhe = 1; // Chuyển thành ghế thường
                    }
                    
                    if (oldState !== seats[i][j].TrangThaiGhe) {
                        changedSeats++;
                    }
                }
            }
        }

        console.log(`Applied room type ${roomType} to ${changedSeats} seats`);
        renderCurrentSeats();
    }

    // Xử lý thay đổi loại phòng
    function handleRoomTypeChange() {
        if (!roomTypeSelect) return;

        const newRoomType = roomTypeSelect.value;
        // Cập nhật options khi thay đổi loại phòng
        updateRowAndColOptions();

        // Chỉ áp dụng nếu loại phòng thay đổi và đã có sơ đồ ghế
        if (newRoomType !== currentRoomType && seats && seats.length > 0) {
            const rowCount = parseInt(rowCountSelect.value) || 0;
            const colCount = parseInt(colCountSelect.value) || 0;

            if (rowCount > 0 && colCount > 0) {
                // Hiển thị thông báo xác nhận
                const confirmMessage = newRoomType === '1'
                    ? 'Chuyển sang phòng VIP sẽ đặt tất cả ghế thành ghế VIP. Bạn có muốn tiếp tục?'
                    : 'Chuyển sang phòng thường sẽ đặt tất cả ghế thành ghế thường. Bạn có muốn tiếp tục?';

                if (confirm(confirmMessage)) {
                    applyRoomTypeToAllSeats(newRoomType);
                    currentRoomType = newRoomType;
                } else {
                    // Khôi phục giá trị cũ nếu người dùng hủy
                    roomTypeSelect.value = currentRoomType || '';
                }
            } else {
                currentRoomType = newRoomType;
            }
        } else {
            currentRoomType = newRoomType;
        }
    }

    // Render sơ đồ ghế với kiểm tra giới hạn
    function renderSeats(rows, cols) {
        console.log('Rendering seats with rows:', rows, 'cols:', cols);
        console.log('Row aisles:', rowAisles);
        console.log('Col aisles:', colAisles);

        // Xóa nội dung cũ
        seatContainer.innerHTML = '';
        seatContainer.className = 'seat-container';

        if (!rows || !cols) {
            seatContainer.innerHTML = `
            <div class="placeholder-text text-muted text-center py-5">
                <div class="placeholder-icon">🎭</div>
                <div>Chọn thông tin phòng để tạo sơ đồ ghế</div>
                <small>Hãy chọn số hàng và số cột để bắt đầu thiết kế</small>
            </div>
        `;
            if (selectAllBtn) selectAllBtn.classList.add('d-none');
            return;
        }

        // Kiểm tra giới hạn ghế
        const totalSeats = rows * cols;
        if (totalSeats > MAX_SEATS) {
            seatContainer.innerHTML = `
            <div class="placeholder-text text-danger text-center py-5">
                <div class="placeholder-icon">⚠️</div>
                <div class="fw-bold">Vượt quá giới hạn ghế!</div>
                <small>Vui lòng giảm số hàng hoặc số cột</small>
            </div>
        `;
            if (selectAllBtn) selectAllBtn.classList.add('d-none');
            return;
        }

        // Hiển thị nút chọn tất cả với hiệu ứng
        if (selectAllBtn) {
            selectAllBtn.classList.remove('d-none');
            selectAllBtn.style.animation = 'fadeInUp 0.5s ease';
        }

        // Đếm số ghế đang hoạt động
        let activeSeats = 0;

        // Tính toán tổng số cột thực (bao gồm cả lối đi)
        const totalCols = cols + colAisles.length;

        // Cập nhật grid-template-columns để đảm bảo bố cục phù hợp
        let gridTemplateColumns = '40px'; // Cột đầu cho row label rộng hơn
        for (let j = 0; j < cols; j++) {
            // Thêm lối đi dọc nếu cần
            if (colAisles.includes(j) && j > 0) {
                gridTemplateColumns += ' 15px'; // Độ rộng lối đi dọc
            }
            gridTemplateColumns += ' 35px'; // Độ rộng ghế
        }
        seatContainer.style.gridTemplateColumns = gridTemplateColumns;

        // Add margin top for screen
        seatContainer.style.marginTop = '45px';

        // Tạo container cho mỗi hàng để dễ quản lý
        for (let i = 0; i < rows; i++) {
            // Tạo container hàng
            const rowContainer = document.createElement('div');
            rowContainer.className = 'seat-row';
            rowContainer.style.display = 'contents';
            rowContainer.style.animationDelay = `${i * 0.05}s`;

            // Thêm label hàng với hiệu ứng
            const rowLabel = document.createElement('div');
            rowLabel.className = 'row-label';
            rowLabel.textContent = String.fromCharCode(65 + i);
            rowLabel.style.animation = 'slideInLeft 0.5s ease';
            rowLabel.style.animationDelay = `${i * 0.1}s`;
            rowLabel.style.animationFillMode = 'both';
            rowContainer.appendChild(rowLabel);

            for (let j = 0; j < cols; j++) {
                // Thêm lối đi dọc nếu cần
                if (colAisles.includes(j) && j > 0) {
                    const aisle = document.createElement('div');
                    aisle.className = 'aisle aisle-col';
                    aisle.style.animation = 'fadeIn 0.3s ease';
                    aisle.style.animationDelay = `${(i * cols + j) * 0.02}s`;
                    aisle.style.animationFillMode = 'both';
                    rowContainer.appendChild(aisle);
                }

                // Tạo ghế với hiệu ứng
                const seat = document.createElement('div');
                const seatData = seats[i] && seats[i][j] ? seats[i][j] : {
                    TrangThaiGhe: 1
                };
                const trangThaiGhe = typeof seatData.TrangThaiGhe === 'number' ? seatData.TrangThaiGhe : 1;

                // Xác định class dựa vào TrangThaiGhe
                seat.className = 'seat';
                if (trangThaiGhe === 0) {
                    seat.classList.add('disabled');
                } else if (trangThaiGhe === 1) {
                    seat.classList.add('normal');
                    activeSeats++;
                } else if (trangThaiGhe === 2) {
                    seat.classList.add('vip');
                    activeSeats++;
                }

                seat.textContent = `${String.fromCharCode(65 + i)}${j + 1}`;
                seat.dataset.row = i;
                seat.dataset.col = j;

                // Thêm hiệu ứng animation cho ghế
                seat.style.animation = 'zoomIn 0.4s ease';
                seat.style.animationDelay = `${(i * cols + j) * 0.03}s`;
                seat.style.animationFillMode = 'both';

                // Xử lý sự kiện click với hiệu ứng
                seat.addEventListener('click', function () {
                    this.classList.add('state-change');
                    setTimeout(() => this.classList.remove('state-change'), 300);
                    toggleSeatStatus.call(this);
                });

                // Thêm hover effect
                seat.addEventListener('mouseenter', function () {
                    if (!this.classList.contains('disabled')) {
                        this.style.transform = 'translateY(-2px) scale(1.05)';
                    }
                });

                seat.addEventListener('mouseleave', function () {
                    if (!this.classList.contains('disabled')) {
                        this.style.transform = '';
                    }
                });

                rowContainer.appendChild(seat);
            }

            seatContainer.appendChild(rowContainer);

            // Thêm lối đi ngang nếu cần với hiệu ứng
            if (rowAisles.includes(i + 1)) {
                const aisleRow = document.createElement('div');
                aisleRow.className = 'aisle-row';
                aisleRow.style.gridColumn = `1 / span ${totalCols + 1}`;
                aisleRow.style.height = '15px';
                aisleRow.style.animation = 'expandWidth 0.5s ease';
                aisleRow.style.animationDelay = `${i * 0.1}s`;
                aisleRow.style.animationFillMode = 'both';
                seatContainer.appendChild(aisleRow);
            }
        }

        // Cập nhật số ghế với hiệu ứng
        seatCount = activeSeats;
        if (seatCountSpan) {
            seatCountSpan.style.animation = 'pulse 0.5s ease';
            seatCountSpan.textContent = `Số ghế đã chọn: ${seatCount}`;
            setTimeout(() => {
                if (seatCountSpan) seatCountSpan.style.animation = '';
            }, 500);
        }

        // Cập nhật input
        if (seatLayoutInput) {
            seatLayoutInput.value = JSON.stringify(seats);
        }

        // Thêm hiệu ứng hoàn thành
        setTimeout(() => {
            seatContainer.classList.add('render-complete');
        }, (rows * cols * 0.03 + 0.5) * 1000);
    }

    // Chuyển đổi trạng thái ghế khi click - với logic loại phòng
    function toggleSeatStatus() {
        let row = parseInt(this.dataset.row);
        let col = parseInt(this.dataset.col);

        if (!seats[row]) seats[row] = [];
        if (!seats[row][col]) {
            seats[row][col] = {
                TrangThaiGhe: 1
            };
        }

        const currentState = seats[row][col].TrangThaiGhe || 0;
        const roomType = roomTypeSelect ? roomTypeSelect.value : null;

        // Thêm hiệu ứng loading ngắn
        this.style.opacity = '0.7';
        this.style.transform = 'scale(0.95)';

        setTimeout(() => {
            // Logic chuyển đổi - cả phòng thường và VIP đều có thể có ghế VIP
            if (currentState === 1) {
                seats[row][col].TrangThaiGhe = 2; // Thường -> VIP
                this.classList.remove('disabled', 'normal');
                this.classList.add('vip');
                this.style.animation = 'tada 0.5s ease';
            } else if (currentState === 2) {
                seats[row][col].TrangThaiGhe = 0; // VIP -> Tắt
                this.classList.add('disabled');
                this.classList.remove('normal', 'vip');
                seatCount--;
                this.style.animation = 'fadeOut 0.3s ease';
            } else {
                seats[row][col].TrangThaiGhe = 1; // Tắt -> Thường
                this.classList.remove('disabled', 'vip');
                this.classList.add('normal');
                seatCount++;
                this.style.animation = 'bounceIn 0.3s ease';
            }

            // Khôi phục trạng thái visual
            this.style.opacity = '1';
            this.style.transform = '';

            // Clear animation sau khi hoàn thành
            setTimeout(() => {
                this.style.animation = '';
            }, 500);

            // Cập nhật số ghế với hiệu ứng
            if (seatCountSpan) {
                seatCountSpan.style.animation = 'pulse 0.3s ease';
                seatCountSpan.textContent = `Số ghế đã chọn: ${seatCount}`;
                setTimeout(() => {
                    if (seatCountSpan) seatCountSpan.style.animation = '';
                }, 300);
            }

            if (seatLayoutInput) {
                seatLayoutInput.value = JSON.stringify(seats);
            }

        }, 100);

        console.log(`Toggled seat [${row},${col}] to state: ${seats[row][col].TrangThaiGhe}`);
    }

    // Cập nhật tùy chọn đường đi với kiểm tra giới hạn ghế
    function updateAisleOptions() {
        const rowCount = parseInt(rowCountSelect.value) || 0;
        const colCount = parseInt(colCountSelect.value) || 0;

        // Kiểm tra xem các phần tử có tồn tại không
        if (!rowAislesSelect || !colAislesSelect) return;

        // Cập nhật tùy chọn lối đi hàng
        rowAislesSelect.innerHTML = '';
        rowAislesSelect.disabled = !rowCount;
        if (rowCount > 1) {
            for (let i = 1; i < rowCount; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `Sau hàng ${String.fromCharCode(64 + i)}`;
                option.selected = rowAisles.includes(i);
                rowAislesSelect.appendChild(option);
            }
        } else {
            const option = document.createElement('option');
            option.disabled = true;
            option.textContent = 'Chọn số hàng trước';
            rowAislesSelect.appendChild(option);
        }

        // Cập nhật tùy chọn lối đi cột
        colAislesSelect.innerHTML = '';
        colAislesSelect.disabled = !colCount;
        if (colCount > 1) {
            for (let i = 1; i < colCount; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `Sau cột ${i}`;
                option.selected = colAisles.includes(i);
                colAislesSelect.appendChild(option);
            }
        } else {
            const option = document.createElement('option');
            option.disabled = true;
            option.textContent = 'Chọn số cột trước';
            colAislesSelect.appendChild(option);
        }

        // Hiển thị hoặc ẩn nút xóa lối đi
        if (clearAislesBtn) {
            clearAislesBtn.style.display = (rowAisles.length > 0 || colAisles.length > 0) ? 'inline-block' : 'none';
        }
    }

    // Cập nhật lối đi hàng
    function handleRowAislesChange() {
        if (!rowAislesSelect) return;

        rowAisles = Array.from(rowAislesSelect.selectedOptions).map(opt => parseInt(opt.value));
        console.log('Updated row aisles:', rowAisles);
        renderCurrentSeats();

        if (clearAislesBtn) {
            clearAislesBtn.style.display = (rowAisles.length > 0 || colAisles.length > 0) ? 'inline-block' : 'none';
        }
    }

    // Cập nhật lối đi cột
    function handleColAislesChange() {
        if (!colAislesSelect) return;

        colAisles = Array.from(colAislesSelect.selectedOptions).map(opt => parseInt(opt.value));
        console.log('Updated col aisles:', colAisles);
        renderCurrentSeats();

        if (clearAislesBtn) {
            clearAislesBtn.style.display = (rowAisles.length > 0 || colAisles.length > 0) ? 'inline-block' : 'none';
        }
    }

    // Xóa tất cả lối đi
    function clearAllAisles() {
        rowAisles = [];
        colAisles = [];

        if (rowAislesSelect) {
            Array.from(rowAislesSelect.options).forEach(opt => opt.selected = false);
        }

        if (colAislesSelect) {
            Array.from(colAislesSelect.options).forEach(opt => opt.selected = false);
        }

        if (clearAislesBtn) {
            clearAislesBtn.style.display = 'none';
        }

        console.log('Cleared all aisles');
        renderCurrentSeats();
    }

    // Chọn tất cả ghế
    function selectAllSeats() {
        const rowCount = parseInt(rowCountSelect.value) || 0;
        const colCount = parseInt(colCountSelect.value) || 0;
        const roomType = roomTypeSelect ? roomTypeSelect.value : null;

        if (!validateSeatLimit(rowCount, colCount)) {
            return;
        }

        if (!rowCount || !colCount) return;

        // Đảm bảo mảng ghế đã được chuẩn hóa
        normalizeSeats(rowCount, colCount);

        // Đặt tất cả ghế dựa vào loại phòng
        for (let i = 0; i < rowCount; i++) {
            for (let j = 0; j < colCount; j++) {
                if (seats[i][j]) {
                    if (roomType === '1') { // Phòng VIP - đặt tất cả thành ghế VIP
                        seats[i][j].TrangThaiGhe = 2;
                    } else if (roomType === '0') { // Phòng thường - đặt tất cả thành ghế thường  
                        seats[i][j].TrangThaiGhe = 1;
                    } else { // Chưa chọn loại phòng - đặt tất cả thành ghế thường
                        seats[i][j].TrangThaiGhe = 1;
                    }
                }
            }
        }

        console.log('Selected all seats with room type:', roomType);
        renderCurrentSeats();
    }

    // Thay đổi số hàng/cột và render lại sơ đồ
    function handleDimensionChange() {
        const rowCount = parseInt(rowCountSelect.value) || 0;
        const colCount = parseInt(colCountSelect.value) || 0;

        // Cập nhật options cho cột khi thay đổi hàng
        updateRowAndColOptions();
        
        // Cập nhật lối đi
        updateAisleOptions();

        // Chuẩn hóa dữ liệu ghế cho kích thước mới
        if (rowCount && colCount) {
            if (validateSeatLimit(rowCount, colCount)) {
                normalizeSeats(rowCount, colCount);

                // Áp dụng loại phòng nếu đã chọn
                const roomType = roomTypeSelect ? roomTypeSelect.value : null;
                if (roomType) {
                    applyRoomTypeToAllSeats(roomType);
                } else {
                    renderSeats(rowCount, colCount);
                }
            } else {
                renderSeats(0, 0); // Hiển thị error message
            }
        } else {
            renderSeats(0, 0); // Hiển thị placeholder
        }
    }

    // Render sơ đồ với kích thước hiện tại
    function renderCurrentSeats() {
        const rowCount = parseInt(rowCountSelect.value) || 0;
        const colCount = parseInt(colCountSelect.value) || 0;

        if (rowCount && colCount && validateSeatLimit(rowCount, colCount)) {
            renderSeats(rowCount, colCount);
        }
    }

    // Xem trước sơ đồ ghế
    function previewSeatingLayout() {
        const rowCount = parseInt(rowCountSelect.value);
        const colCount = parseInt(colCountSelect.value);

        if (!rowCount || !colCount) {
            alert('Vui lòng chọn số hàng và số cột!');
            return;
        }

        if (!validateSeatLimit(rowCount, colCount)) {
            alert(`Vượt quá giới hạn ghế!`);
            return;
        }

        // Cập nhật lối đi
        if (rowAislesSelect && colAislesSelect) {
            rowAisles = Array.from(rowAislesSelect.selectedOptions).map(opt => parseInt(opt.value));
            colAisles = Array.from(colAislesSelect.selectedOptions).map(opt => parseInt(opt.value));
        }

        // Chuẩn hóa dữ liệu ghế
        normalizeSeats(rowCount, colCount);

        // Áp dụng loại phòng nếu đã chọn
        const roomType = roomTypeSelect ? roomTypeSelect.value : null;
        if (roomType) {
            applyRoomTypeToAllSeats(roomType);
        } else {
            renderSeats(rowCount, colCount);
        }

        // Kích hoạt nút xóa lối đi nếu có lối đi
        if (clearAislesBtn) {
            clearAislesBtn.style.display = (rowAisles.length > 0 || colAisles.length > 0) ? 'inline-block' : 'none';
        }

        console.log('Preview seating layout completed');
    }

    // Xử lý submit form
    function handleSubmitForm(e) {
        const totalSeats = (parseInt(rowCountSelect.value) || 0) * (parseInt(colCountSelect.value) || 0);
        
        // Kiểm tra giới hạn ghế
        if (totalSeats > MAX_SEATS) {
            e.preventDefault();
            alert(`Vượt quá giới hạn ghế!`);
            return;
        }

        // Kiểm tra số ghế
        if (seatCount === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một ghế trước khi lưu!');
            return;
        }

        // Cập nhật input trước khi submit
        if (seatLayoutInput) {
            seatLayoutInput.value = JSON.stringify(seats);
        }

        // Vô hiệu hóa nút submit để tránh submit nhiều lần
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Đang xử lý...';
        }
    }

    function previewSeats() {
        const rowCount = parseInt(rowCountSelect.value);
        const colCount = parseInt(colCountSelect.value);

        if (!rowCount || !colCount) {
            alert('Vui lòng chọn số hàng và số cột!');
            return;
        }

        if (!validateSeatLimit(rowCount, colCount)) {
            alert(`Vượt quá giới hạn ghế!`);
            return;
        }

        // Cập nhật lối đi
        rowAisles = Array.from(rowAislesSelect.selectedOptions).map(opt => parseInt(opt.value));
        colAisles = Array.from(colAislesSelect.selectedOptions).map(opt => parseInt(opt.value));

        // Chuẩn hóa dữ liệu ghế
        seats = normalizeSeats(rowCount, colCount);

        // Áp dụng loại phòng
        const roomType = roomTypeSelect ? roomTypeSelect.value : null;
        if (roomType) {
            applyRoomTypeToAllSeats(roomType);
        } else {
            renderSeats(rowCount, colCount);
        }

        // Kích hoạt nút xóa lối đi
        if (clearAislesBtn) {
            clearAislesBtn.disabled = false;
        }
    }

    // Set up event listeners
    if (rowCountSelect) {
        rowCountSelect.addEventListener('change', handleDimensionChange);
    }

    if (colCountSelect) {
        colCountSelect.addEventListener('change', handleDimensionChange);
    }

    if (rowAislesSelect) {
        rowAislesSelect.addEventListener('change', handleRowAislesChange);
    }

    if (colAislesSelect) {
        colAislesSelect.addEventListener('change', handleColAislesChange);
    }

    if (clearAislesBtn) {
        clearAislesBtn.addEventListener('click', clearAllAisles);
        // Khởi tạo trạng thái hiển thị của nút
        clearAislesBtn.style.display = (rowAisles.length > 0 || colAisles.length > 0) ? 'inline-block' : 'none';
    }

    if (previewBtn) {
        previewBtn.addEventListener('click', previewSeatingLayout);
    }

    if (roomForm) {
        roomForm.addEventListener('submit', handleSubmitForm);
    }

    // Thêm event listener cho loại phòng
    if (roomTypeSelect) {
        roomTypeSelect.addEventListener('change', handleRoomTypeChange);
        // Lưu giá trị ban đầu
        currentRoomType = roomTypeSelect.value;
    }

    // Initialize the view
    const rowCount = rowCountSelect ? parseInt(rowCountSelect.value) || 0 : 0;
    const colCount = colCountSelect ? parseInt(colCountSelect.value) || 0 : 0;

    if (isDetailView) {
        console.log('Initializing detail view');
        updateAisleOptions();

        if (rowCount && colCount) {
            console.log('Rendering initial seats');
            normalizeSeats(rowCount, colCount);
            renderSeats(rowCount, colCount);
        }
    } else {
        console.log('Initializing create view');
        updateRowAndColOptions();
        updateAisleOptions();
        renderSeats(0, 0);
    }

    if (roomTypeSelect && roomTypeSelect.value) {
        updateRowAndColOptions();
    }

    // Expose functions to global scope
    window.roomManagement = {
        renderSeats,
        normalizeSeats,
        toggleSeatStatus: function (row, col) {
            const seat = document.querySelector(`.seat[data-row="${row}"][data-col="${col}"]`);
            if (seat) toggleSeatStatus.call(seat);
        },
        selectAllSeats,
        clearAllAisles,
        updateAisleOptions,
        previewSeatingLayout: previewSeatingLayout,
        applyRoomTypeToAllSeats,
        validateSeatLimit,
        MAX_SEATS
    };

    // Global functions for onclick handlers
    window.selectAllSeats = selectAllSeats;
    window.clearAisles = clearAllAisles;
    window.previewSeats = previewSeats;
});

// Enhanced notification function with 100 seat limit context
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    };
    
    const colors = {
        success: '#27ae60',
        error: '#e74c3c',
        warning: '#f39c12',
        info: '#3498db'
    };
    
    notification.innerHTML = `
        <span style="margin-right: 8px;">${icons[type] || icons.info}</span>
        <span>${message}</span>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        background: ${colors[type] || colors.info};
        color: white;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        z-index: 10000;
        display: flex;
        align-items: center;
        transform: translateX(400px);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        max-width: 350px;
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Animate out
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 400);
    }, 3000);
}