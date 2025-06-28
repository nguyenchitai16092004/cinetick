/**
 * JavaScript cho trang chi tiết hóa đơn
 * Hiển thị sơ đồ ghế với các ghế đã đặt được highlight
 * Tương thích với cấu trúc từ dat-ghe.js
 */

// Khai báo biến toàn cục cho trang detail hóa đơn
let cacHang = [];
let bookedSeatIds = [];
let rowAisles = [];
let colAisles = [];

document.addEventListener('DOMContentLoaded', function() {
    console.log('Hóa đơn detail page loaded');
    
    if (typeof hoaDonData !== 'undefined') {
        initializeInvoiceSeatLayout();
    } else {
        console.error('hoaDonData không được định nghĩa');
    }
});

/**
 * Khởi tạo sơ đồ ghế cho trang chi tiết hóa đơn
 */
function initializeInvoiceSeatLayout() {
    const seatContainer = document.getElementById('seatLayout');
    if (!seatContainer) {
        console.error('Không tìm thấy container seatLayout');
        return;
    }

    // Lấy danh sách ghế đã đặt từ vé xem phim
    const bookedSeatNames = hoaDonData.veXemPhim.map(ve => ve.TenGhe);
    console.log('Ghế đã đặt:', bookedSeatNames);

    // Kiểm tra xem có seatLayout không
    if (!hoaDonData.seatLayout || hoaDonData.seatLayout.length === 0) {
        seatContainer.innerHTML = `
            <div class="placeholder-text text-muted text-center py-5">
                <i class="bi bi-info-circle display-6"></i>
                <p class="mt-2">Không có thông tin sơ đồ ghế</p>
            </div>
        `;
        return;
    }

    // Gán dữ liệu vào biến toàn cục
    cacHang = hoaDonData.seatLayout;
    rowAisles = hoaDonData.rowAisles || [];
    colAisles = hoaDonData.colAisles || [];
    bookedSeatIds = hoaDonData.veXemPhim.map(ve => ve.ID_Ghe).filter(id => id);

    console.log('Dữ liệu lối đi:', {
        rowAisles: rowAisles,
        colAisles: colAisles,
        rowAislesType: typeof rowAisles,
        colAislesType: typeof colAisles
    });

    // Tạo sơ đồ ghế
    taoSoDoGheHoaDon();
}

/**
 * Tạo sơ đồ ghế cho trang chi tiết hóa đơn
 * Dựa trên hàm taoSoDoGhe() từ dat-ghe.js
 */
function taoSoDoGheHoaDon() {
    const containerGhe = document.getElementById('seatLayout');
    
    // Xóa nội dung cũ
    containerGhe.innerHTML = '';
    
    // Tính toán tổng số cột (bao gồm lối đi)
    const soCot = cacHang[0]?.length || 0;
    
    // Tính toán grid columns với lối đi
    let gridColumns = 'auto'; // Cột đầu cho label hàng
    for (let j = 0; j < soCot; j++) {
        // Thêm lối đi dọc TRƯỚC ghế (nếu có)
        if (colAisles && colAisles.includes(j) && j > 0) {
            gridColumns += ' 15px'; // Lối đi dọc
        }
        gridColumns += ' 35px'; // Ghế
    }
    
    // Thiết lập grid layout cho container
    containerGhe.style.display = 'grid';
    containerGhe.style.gridTemplateColumns = gridColumns;
    containerGhe.style.gap = '8px';
    containerGhe.style.justifyContent = 'center';
    containerGhe.style.alignItems = 'center';
    
    console.log('Grid columns:', gridColumns);
    console.log('Số cột ghế:', soCot);
    console.log('Row aisles:', rowAisles);
    console.log('Col aisles:', colAisles);
    
    // Tạo sơ đồ ghế theo từng hàng
    cacHang.forEach((hang, indexHang) => {
        // Label hàng
        const labelHang = document.createElement('div');
        labelHang.className = 'row-label';
        labelHang.textContent = String.fromCharCode(65 + indexHang);
        containerGhe.appendChild(labelHang);
        
        hang.forEach((duLieuGhe, indexCot) => {
            // Thêm lối đi dọc TRƯỚC ghế (nếu cần)
            if (colAisles && colAisles.includes(indexCot) && indexCot > 0) {
                const loiDi = document.createElement('div');
                loiDi.className = 'aisle aisle-col';
                loiDi.style.width = '15px';
                loiDi.style.height = '35px';
                loiDi.style.borderRadius = '3px';
                containerGhe.appendChild(loiDi);
                console.log(`Đã thêm lối đi dọc sau cột ${indexCot}`);
            }
            
            // Tạo ghế
            const phanTuGhe = document.createElement('div');
            
            // Lấy ID_Ghe và TenGhe từ dữ liệu ghế hiện tại
            const idGhe = duLieuGhe?.ID_Ghe || duLieuGhe?.id_ghe || '';
            const tenGhe = duLieuGhe?.TenGhe || duLieuGhe?.ten_ghe || '';
            
            phanTuGhe.className = 'seat';
            phanTuGhe.dataset.seatId = idGhe;
            phanTuGhe.dataset.row = indexHang;
            phanTuGhe.dataset.col = indexCot;
            phanTuGhe.textContent = tenGhe;
            
            // Xử lý trạng thái ghế dựa vào dữ liệu từ server
            if (duLieuGhe && typeof duLieuGhe === 'object') {
                // Ghế có dữ liệu từ database
                const trangThai = duLieuGhe.TrangThaiGhe || duLieuGhe.trang_thai || 1;
                const isBookedInThisInvoice = duLieuGhe.IsBooked || 
                    bookedSeatIds.includes(Number(idGhe)) || 
                    hoaDonData.veXemPhim.some(ve => ve.TenGhe === tenGhe || ve.ID_Ghe === Number(idGhe));
                
                // Thiết lập class dựa vào trạng thái
                if (trangThai === 0) {
                    phanTuGhe.ckiemTraGheNgoiasskiemTraGheNgoiist.add('seat-disabled');
                    phanTuGhe.title = `${tenGhe} - Không hoạt động`;
                } else if (isBookedInThisInvoice) {
                    phanTuGhe.classList.add('seat-booked');
                    phanTuGhe.title = `${tenGhe} - Đã đặt trong hóa đơn này`;
                    
                    // Thêm thông tin vé vào tooltip
                    const veInfo = hoaDonData.veXemPhim.find(ve => ve.TenGhe === tenGhe || ve.ID_Ghe === Number(idGhe));
                    if (veInfo) {
                        phanTuGhe.title += ` - Giá: ${parseInt(veInfo.GiaVe).toLocaleString()} VND`;
                    }
                } else if (trangThai === 2) {
                    phanTuGhe.classList.add('seat-vip');
                    phanTuGhe.title = `${tenGhe} - Ghế VIP`;
                } else if (trangThai === 1) {
                    phanTuGhe.classList.add('seat-normal');
                    phanTuGhe.title = `${tenGhe} - Ghế thường`;
                }
                
                // Gắn dữ liệu ghế vào element
                phanTuGhe.dataset.gheData = JSON.stringify(duLieuGhe);
            } else if (duLieuGhe === 0 || duLieuGhe === null) {
                // Ghế bị vô hiệu hóa
                phanTuGhe.classList.add('seat-disabled');
                phanTuGhe.title = `${tenGhe} - Không hoạt động`;
            } else {
                // Ghế mặc định
                phanTuGhe.classList.add('seat-normal');
                phanTuGhe.title = `${tenGhe} - Ghế thường`;
            }
            
            containerGhe.appendChild(phanTuGhe);
        });
        
        // Thêm lối đi ngang SAU khi hoàn thành một hàng (nếu cần)
        if (rowAisles && rowAisles.includes(indexHang)) {
            const tongCotGrid = 1 + soCot + (colAisles ? colAisles.filter(col => col > 0 && col < soCot).length : 0);
            
            const loiDiNgang = document.createElement('div');
            loiDiNgang.className = 'aisle aisle-row';
            loiDiNgang.style.gridColumn = `1 / span ${tongCotGrid}`;
            loiDiNgang.style.height = '15px';
            loiDiNgang.style.borderRadius = '3px';
            loiDiNgang.style.margin = '8px 0';
            containerGhe.appendChild(loiDiNgang);
            console.log(`Đã thêm lối đi ngang sau hàng ${String.fromCharCode(65 + indexHang)}`);
        }
    });
    
    console.log('Đã render sơ đồ ghế hóa đơn thành công');
    console.log('Ghế đã đặt:', bookedSeatIds);
}

/**
 * Hàm hỗ trợ format số tiền
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

/**
 * Hàm hỗ trợ format ngày tháng
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}