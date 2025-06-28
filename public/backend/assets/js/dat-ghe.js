// Khai báo biến toàn cục
let cacHang = [];
let cauHinhGhe = {};
let seats = [];
let rowAisles = [];
let colAisles = [];
let seatCount = 0;

// Khởi tạo input ngày với ngày hôm nay
document.addEventListener('DOMContentLoaded', function () {
    const hienTai = new Date();

    // Chuyển sang múi giờ Việt Nam
    const offsetVN = 60 * 60 * 1000;
    const thoiGianVN = new Date(hienTai.getTime() + offsetVN);

    // Format lại theo yyyy-MM-dd
    const nam = thoiGianVN.getFullYear();
    const thang = String(thoiGianVN.getMonth() + 1).padStart(2, '0');
    const ngay = String(thoiGianVN.getDate()).padStart(2, '0');
    const homNayVN = `${nam}-${thang}-${ngay}`;

    const inputNgay = document.getElementById('selectedDate');
    if (inputNgay) {
        inputNgay.min = homNayVN;
        inputNgay.value = homNayVN;
        trangThaiDatVe.ngay = homNayVN;
    }

    khoiTaoSuKien();
    capNhatTienTrinh();
});

// Chọn rạp
function chonRap(theRap) {
    // Xóa class selected khỏi tất cả thẻ rạp
    document.querySelectorAll('.cinema-card').forEach(r => r.classList.remove('selected'));

    // Thêm class selected cho thẻ được chọn
    theRap.classList.add('selected');

    // Lấy dữ liệu rạp
    const tenRap = theRap.querySelector('h6');
    const diaChiRap = theRap.querySelector('p');

    trangThaiDatVe.rap = {
        id: theRap.dataset.cinemaId,
        ten: tenRap ? tenRap.textContent : '',
        diaChi: diaChiRap ? diaChiRap.textContent : ''
    };

    console.log('Rạp đã chọn:', trangThaiDatVe.rap);

    capNhatTomTatBuoc1();
    capNhatNutTiepTheo(1);
}

// Cập nhật tóm tắt bước 1
function capNhatTomTatBuoc1() {
    const divTomTat = document.getElementById('step1Summary');
    if (!divTomTat) return;

    if (trangThaiDatVe.rap && trangThaiDatVe.ngay) {
        const ngayDinhDang = new Date(trangThaiDatVe.ngay).toLocaleDateString('vi-VN', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        divTomTat.innerHTML = `
            <div class="selected-info">
                <p><strong>Rạp:</strong> ${trangThaiDatVe.rap.ten}</p>
                <p><strong>Địa chỉ:</strong> ${trangThaiDatVe.rap.diaChi}</p>
                <p><strong>Ngày:</strong> ${ngayDinhDang}</p>
            </div>
        `;
    } else {
        divTomTat.innerHTML = '<p class="text-muted">Chưa chọn rạp và ngày</p>';
    }
}

// Cập nhật trạng thái nút tiếp theo
function capNhatNutTiepTheo(buoc) {
    const nutTiepTheo = document.getElementById(`btnNext${buoc}`);
    if (!nutTiepTheo) return;

    switch (buoc) {
        case 1:
            nutTiepTheo.disabled = !(trangThaiDatVe.rap && trangThaiDatVe.ngay);
            break;
        case 2:
            nutTiepTheo.disabled = !(trangThaiDatVe.phim && trangThaiDatVe.suatChieu);
            break;
        case 3:
            nutTiepTheo.disabled = trangThaiDatVe.gheNgoi.length === 0;
            break;
        default:
            break;
    }
}

// Cập nhật thanh tiến trình
function capNhatTienTrinh() {
    const duongTienTrinh = document.getElementById('progressLine');
    const cacBuoc = document.querySelectorAll('.step-item');

    if (!duongTienTrinh || !cacBuoc.length) return;

    // Xóa class active khỏi tất cả các bước
    cacBuoc.forEach(buoc => buoc.classList.remove('active'));

    // Thêm class active cho bước hiện tại và các bước trước đó
    for (let i = 0; i < trangThaiDatVe.buoc; i++) {
        if (cacBuoc[i]) {
            cacBuoc[i].classList.add('active');
        }
    }

    // Cập nhật độ rộng thanh tiến trình
    const phanTramTienTrinh = ((trangThaiDatVe.buoc - 1) / (cacBuoc.length - 1)) * 100;
    duongTienTrinh.style.width = phanTramTienTrinh + '%';
}

// Chuyển đến bước cụ thể
function chuyenDenBuoc(soBuoc) {
    // Ẩn tất cả các bước
    document.querySelectorAll('.form-step').forEach(buoc => {
        buoc.classList.remove('active');
    });

    // Hiện bước mục tiêu
    const buocMucTieu = document.getElementById(`step${soBuoc}`);
    if (buocMucTieu) {
        buocMucTieu.classList.add('active');
        trangThaiDatVe.buoc = soBuoc;
        capNhatTienTrinh();

        // Tải nội dung cho bước cụ thể
        if (soBuoc === 2) {
            taiDanhSachPhim();
        } else if (soBuoc === 3) {
            taoSoDoGhe();
        } else if (soBuoc === 4) {
            capNhatTomTatCuoiCung();
        } else if (soBuoc === 5) {
            capNhatTomTatThanhToan();
        }
    }
}

// Chọn phim
function chonPhim(idPhim, phanTuSuatChieu) {
    // Tìm phim
    const phim = danhSachPhim.find(p => p.id === parseInt(idPhim));
    if (!phim) return;

    // Bỏ chọn tất cả các phim đã chọn trước đó
    document.querySelectorAll('[data-movie-id].selected').forEach(p => {
        p.classList.remove('selected');
    });

    // Gán class selected cho phim hiện tại
    const thePhim = document.querySelector(`[data-movie-id="${idPhim}"]`);
    if (thePhim) thePhim.classList.add('selected');

    // Bỏ chọn các suất chiếu đang được chọn trước đó
    document.querySelectorAll('.showtime-btn.selected').forEach(s => {
        s.classList.remove('selected');
    });

    // Gán lại suất chiếu nếu có
    trangThaiDatVe.phim = phim;
    if (phanTuSuatChieu) {
        phanTuSuatChieu.classList.add('selected');
        trangThaiDatVe.suatChieu = phanTuSuatChieu.textContent;
        trangThaiDatVe.ID_SuatChieu = phanTuSuatChieu.dataset.idSuatChieu;
        trangThaiDatVe.phim.giaVe = parseInt(phanTuSuatChieu.dataset.giaVe);
    }

    // Cập nhật bước tiếp theo
    capNhatTomTatBuoc2();
    capNhatNutTiepTheo(2);
}

// Tải danh sách phim cho bước 2
function taiDanhSachPhim() {
    const danhSachPhimElement = document.getElementById('movieList');
    if (!danhSachPhimElement) return;

    danhSachPhimElement.innerHTML = '';

    danhSachPhim.forEach(phim => {
        const thePhim = document.createElement('div');
        thePhim.className = 'movie-card';
        thePhim.dataset.movieId = phim.id;

        let htmlSuatChieu = phim.suatChieu.map(sc =>
            `<div class="showtime-btn" data-showtime="${sc.gio}" data-id-phong="${sc.phong}" data-id-suat-chieu="${sc.suatChieu}" data-gia-ve="${sc.gia_ve}">${sc.gio}</div>`).join('');

        thePhim.innerHTML = `
            <div class="d-flex gap-3 align-items-start">
                <img src="/storage/${phim.poster}" class="movie-poster" alt="${phim.tieuDe}">
                <div style="width:100%">
                    <h5>${phim.tieuDe}</h5>
                    <p class="mb-1"><strong>Độ tuổi:</strong> ${phim.doTuoi}</p>
                    <p class="mb-1"><strong>Thời lượng:</strong> ${phim.thoiLuong}</p>
                    <div class="showtime-grid">
                        ${htmlSuatChieu}
                    </div>
                </div>
            </div>
        `;

        // Gắn sự kiện khi chọn giờ chiếu
        thePhim.querySelectorAll('.showtime-btn').forEach(nut => {
            nut.addEventListener('click', () => chonPhim(phim.id, nut));
        });

        danhSachPhimElement.appendChild(thePhim);
    });
}

// Cập nhật tóm tắt bước 2
function capNhatTomTatBuoc2() {
    const divTomTat = document.getElementById('step2Summary');
    if (!divTomTat || !trangThaiDatVe.phim) return;

    divTomTat.innerHTML = `
        <div class="selected-info">
            <p><strong>Phim:</strong> ${trangThaiDatVe.phim.tieuDe}</p>
            <p><strong>Độ tuổi:</strong> ${trangThaiDatVe.phim.doTuoi}</p>
            <p><strong>Thời lượng:</strong> ${trangThaiDatVe.phim.thoiLuong}</p>
            <p><strong>Suất chiếu:</strong> ${trangThaiDatVe.suatChieu}</p>
            <p><strong>Giá vé:</strong> ${trangThaiDatVe.phim.giaVe.toLocaleString()} VNĐ</p>
        </div>
    `;
}

/**
 * Hàm chọn ghế đơn giản - không kiểm tra quy định ngay lập tức
 */
function chonGheTheoData(phanTuGhe, duLieuGhe) {
    const idGhe = phanTuGhe.dataset.seatId;
    const laGheVip = phanTuGhe.classList.contains('vip');
    const giaCoBan = trangThaiDatVe.phim ? trangThaiDatVe.phim.giaVe : 80000;
    const giaGhe = laGheVip ? Math.round(giaCoBan * 1.2) : giaCoBan;

    // Chỉ kiểm tra giới hạn số ghế (tối đa 8 ghế)
    if (!phanTuGhe.classList.contains('selected') && trangThaiDatVe.gheNgoi.length >= 8) {
        showGlobalNotification('Giới hạn ghế', 'Bạn chỉ có thể chọn tối đa 8 ghế trong một lần đặt!', 'warning');
        return;
    }

    // Thực hiện thao tác chọn/bỏ chọn ghế mà không kiểm tra quy định
    if (phanTuGhe.classList.contains('selected')) {
        // Bỏ chọn ghế
        phanTuGhe.classList.remove('selected');
        trangThaiDatVe.gheNgoi = trangThaiDatVe.gheNgoi.filter(ghe => ghe.id !== idGhe);
        
        // Thông báo nhẹ bằng console log thay vì popup
        console.log(`Đã bỏ chọn ghế ${duLieuGhe?.TenGhe || idGhe}`);
    } else {
        // Chọn ghế
        phanTuGhe.classList.add('selected');
        trangThaiDatVe.gheNgoi.push({
            id: idGhe,
            idThucTe: duLieuGhe?.ID_Ghe || duLieuGhe?.id || null,
            tenGhe: duLieuGhe?.TenGhe || idGhe,
            loai: laGheVip ? 'VIP' : 'Thường',
            gia: giaGhe,
            hang: parseInt(phanTuGhe.dataset.row),
            cot: parseInt(phanTuGhe.dataset.col)
        });
        
        // Thông báo nhẹ bằng console log thay vì popup
        console.log(`Đã chọn ghế ${duLieuGhe?.TenGhe || idGhe} (${laGheVip ? 'VIP' : 'Thường'})`);
    }

    capNhatTomTatGhe();
    capNhatNutTiepTheo(3);
}

// Cập nhật tóm tắt ghế
function capNhatTomTatGhe() {
    const divTomTat = document.getElementById('step3Summary');
    const divTongTien = document.getElementById('totalPrice');

    if (!divTomTat || !divTongTien) return;

    if (trangThaiDatVe.gheNgoi.length > 0) {
        let htmlTomTat = '<div class="selected-seats">';
        let tongTien = 0;

        // Sắp xếp ghế theo thứ tự A1, A2, B1, B2...
        trangThaiDatVe.gheNgoi.sort((a, b) => {
            const hangA = a.id.charAt(0);
            const hangB = b.id.charAt(0);
            const soA = parseInt(a.id.substring(1));
            const soB = parseInt(b.id.substring(1));

            if (hangA !== hangB) {
                return hangA.localeCompare(hangB);
            }
            return soA - soB;
        });

        trangThaiDatVe.gheNgoi.forEach(ghe => {
            htmlTomTat += `<p>Ghế ${ghe.tenGhe || ghe.id} (${ghe.loai}): ${ghe.gia.toLocaleString()} VNĐ</p>`;
            tongTien += ghe.gia;
        });

        htmlTomTat += '</div>';
        divTomTat.innerHTML = htmlTomTat;
        divTongTien.textContent = tongTien.toLocaleString() + ' VNĐ';
        trangThaiDatVe.tongTien = tongTien;
    } else {
        divTomTat.innerHTML = '<p class="text-muted">Chưa chọn ghế</p>';
        divTongTien.textContent = '0 VNĐ';
        trangThaiDatVe.tongTien = 0;
    }
}

// Cập nhật tóm tắt cuối cùng cho bước 4
function capNhatTomTatCuoiCung() {
    const divTomTat = document.getElementById('finalSummary');
    if (!divTomTat) return;

    const ngayDinhDang = new Date(trangThaiDatVe.ngay).toLocaleDateString('vi-VN', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    divTomTat.innerHTML = `
        <div class="final-summary">
            <div class="row">
                <div class="col-6"><strong>Rạp:</strong></div>
                <div class="col-6">${trangThaiDatVe.rap.ten}</div>
            </div>
            <div class="row">
                <div class="col-6"><strong>Ngày:</strong></div>
                <div class="col-6">${ngayDinhDang}</div>
            </div>
            <div class="row">
                <div class="col-6"><strong>Phim:</strong></div>
                <div class="col-6">${trangThaiDatVe.phim.tieuDe}</div>
            </div>
            <div class="row">
                <div class="col-6"><strong>Suất chiếu:</strong></div>
                <div class="col-6">${trangThaiDatVe.suatChieu}</div>
            </div>
            <div class="row">
                <div class="col-6"><strong>Ghế:</strong></div>
                <div class="col-6">${trangThaiDatVe.gheNgoi.map(ghe => ghe.tenGhe).join(", ")}</div>
            </div>
            <div class="row">
                <div class="col-6"><strong>Số tiền giảm:</strong></div>
                <div class="col-6">${trangThaiDatVe.giamGia.toLocaleString('vi-VN')}VNĐ</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6"><strong>Tổng tiền:</strong></div>
                <div class="col-6 text-success"><strong>${trangThaiDatVe.tongTien.toLocaleString()} VNĐ</strong></div>
            </div>
        </div>
    `;
}

// Cập nhật tóm tắt thanh toán cho bước 5
function capNhatTomTatThanhToan() {
    const divTomTat = document.getElementById('paymentSummary');
    if (!divTomTat) return;

    divTomTat.innerHTML = `
        <div class="payment-summary">
            <div class="d-flex justify-content-between mb-2">
                <span>Số lượng vé:</span>
                <span>${trangThaiDatVe.gheNgoi.length}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Tổng tiền:</span>
                <span>${trangThaiDatVe.tongTien.toLocaleString()} VNĐ</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <strong>Thanh toán:</strong>
                <strong class="text-success">${trangThaiDatVe.tongTien.toLocaleString()} VNĐ</strong>
            </div>
        </div>
    `;
}

/**
 * Tạo sơ đồ ghế với cùng cấu trúc như trang detail
 */
function taoSoDoGhe() {
    const containerGhe = document.getElementById('seatLayout');
    
    // Xóa nội dung cũ
    containerGhe.innerHTML = '';
    containerGhe.className = 'seat-container';
    
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
                containerGhe.appendChild(loiDi);
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
                const trangThai = duLieuGhe.TrangThaiGhe;
                
                // Thiết lập class dựa vào trạng thái
                if (trangThai === 0) {
                    phanTuGhe.classList.add('disabled');
                    phanTuGhe.title = `${tenGhe} - Không hoạt động`;
                } else if (danhSachGheDaDat && danhSachGheDaDat.includes(Number(phanTuGhe.dataset.seatId))) {
                    phanTuGhe.classList.add('booked');
                    phanTuGhe.title = `${tenGhe} - Đã được đặt`;
                } else if (trangThai === 2) {
                    phanTuGhe.classList.add('vip', 'available');
                    phanTuGhe.title = `${tenGhe} - Ghế VIP (+20% giá vé)`;
                } else if (trangThai === 1) {
                    phanTuGhe.classList.add('normal', 'available');
                    phanTuGhe.title = `${tenGhe} - Ghế thường`;
                }
                
                // Gắn dữ liệu ghế vào element
                phanTuGhe.dataset.gheData = JSON.stringify(duLieuGhe);
            } else if (duLieuGhe === 0 || duLieuGhe === null) {
                // Ghế bị vô hiệu hóa
                phanTuGhe.classList.add('disabled');
                phanTuGhe.title = `${tenGhe} - Không hoạt động`;
            } else {
                // Ghế mặc định
                phanTuGhe.classList.add('normal', 'available');
                phanTuGhe.title = `${tenGhe} - Ghế thường`;
            }
            
            // Sự kiện click chỉ cho ghế available
            if (phanTuGhe.classList.contains('available')) {
                phanTuGhe.addEventListener('click', function () {
                    chonGheTheoData(this, duLieuGhe);
                });
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
            loiDiNgang.style.margin = '8px 0';
            containerGhe.appendChild(loiDiNgang);
        }
    });
    
    // Khôi phục trạng thái ghế đã chọn sau khi render (nếu có)
    if (typeof khoiPhucTrangThaiGheDaChon === 'function') {
        khoiPhucTrangThaiGheDaChon();
    }
    
    console.log('Đã render sơ đồ ghế thành công');
}

function khoiPhucTrangThaiGheDaChon() {
    if (!trangThaiDatVe.gheNgoi || trangThaiDatVe.gheNgoi.length === 0) {
        return;
    }

    // Đánh dấu lại các ghế đã chọn trước đó
    trangThaiDatVe.gheNgoi.forEach(gheInfo => {
        const phanTuGhe = document.querySelector(`[data-seat-id="${gheInfo.id}"]`);
        if (phanTuGhe && phanTuGhe.classList.contains('available')) {
            phanTuGhe.classList.add('selected');
        }
    });

    // Cập nhật tóm tắt và tổng tiền
    capNhatTomTatGhe();
    capNhatNutTiepTheo(3);
}

/**
 * Hàm kiểm tra ghế chỉ khi ấn nút "Tiếp theo"
 */
function kiemTraGheNgoi(seatArray) {
    const kiemTra = kiemTraGheHopLe(seatArray);
    
    if (kiemTra.valid) {
        // Nếu hợp lệ, chuyển đến bước tiếp theo
        console.log('Ghế hợp lệ, chuyển sang bước 4');
        return chuyenDenBuoc(4);
    } else {
        // Nếu không hợp lệ, hiển thị thông báo bằng hệ thống global
        showGlobalNotification('Quy định chọn ghế', kiemTra.message, 'error');
        return false;
    }
}

/**
 * Hàm kiểm tra tính hợp lệ của việc chọn ghế
 */
function kiemTraGheHopLe(seatArray) {
    if (!Array.isArray(seatArray) || seatArray.length === 0) {
        return { valid: true };
    }

    // Ép toàn bộ seatArray sang string để so sánh an toàn
    const stringSeatArray = seatArray.map(String);

    // Nhóm ghế theo hàng
    const seatsByRow = {};
    stringSeatArray.forEach((id_ghe) => {
        let tenGhe = null;

        // Tìm tên ghế từ cacHang
        outer: for (let i = 0; i < cacHang.length; i++) {
            for (let j = 0; j < cacHang[i].length; j++) {
                const seat = cacHang[i][j];
                if (seat && String(seat.ID_Ghe) === id_ghe) {
                    tenGhe = seat.TenGhe;
                    break outer;
                }
            }
        }

        if (!tenGhe) return;

        const row = tenGhe.charAt(0);
        const col = parseInt(tenGhe.slice(1));
        if (!seatsByRow[row]) seatsByRow[row] = [];
        seatsByRow[row].push(col);
    });

    // Kiểm tra từng hàng
    for (const row in seatsByRow) {
        const cols = seatsByRow[row].sort((a, b) => a - b);

        // 1. Kiểm tra ghế bị bỏ trống giữa
        for (let i = 0; i < cols.length - 1; i++) {
            if (cols[i + 1] - cols[i] === 2) {
                const middleCol = cols[i] + 1;

                // Tìm ID ghế ở giữa
                let middleID = null;
                outer: for (let m = 0; m < cacHang.length; m++) {
                    for (let n = 0; n < cacHang[m].length; n++) {
                        const seat = cacHang[m][n];
                        if (seat && seat.TenGhe === row + middleCol) {
                            middleID = seat.ID_Ghe;
                            break outer;
                        }
                    }
                }

                // Kiểm tra ghế giữa có khả dụng không
                const middleSeat = cacHang.find(hang =>
                    hang.find(ghe => ghe && ghe.TenGhe === row + middleCol)
                )?.find(ghe => ghe && ghe.TenGhe === row + middleCol);

                if (middleSeat && middleSeat.TrangThaiGhe !== 0 &&
                    !danhSachGheDaDat.includes(Number(middleID)) &&
                    !stringSeatArray.includes(String(middleID))) {
                    return {
                        valid: false,
                        message: `Không được để trống ghế giữa như ghế ${row}${middleCol}. Vui lòng chọn ghế liền kề hoặc chọn thêm ghế ${row}${middleCol}.`
                    };
                }
            }
        }

        // 2. Lấy tất cả ghế khả dụng trong hàng này
        let availableCols = [];
        for (let i = 0; i < cacHang.length; i++) {
            for (let j = 0; j < cacHang[i].length; j++) {
                const seat = cacHang[i][j];
                if (seat &&
                    seat.TenGhe &&
                    seat.TenGhe.charAt(0) === row &&
                    seat.TrangThaiGhe !== 0 && // ghế không bị khóa
                    !danhSachGheDaDat.includes(Number(seat.ID_Ghe))) {
                    availableCols.push(parseInt(seat.TenGhe.slice(1)));
                }
            }
        }

        if (availableCols.length === 0) continue;
        availableCols.sort((a, b) => a - b);

        const minAvailableCol = Math.min(...availableCols);
        const maxAvailableCol = Math.max(...availableCols);
        const selectedCols = cols.sort((a, b) => a - b);
        const leftmostSelected = Math.min(...selectedCols);
        const rightmostSelected = Math.max(...selectedCols);

        // 3. Kiểm tra ghế trống bên trái
        if (leftmostSelected > minAvailableCol) {
            let consecutiveEmptyLeft = 0;
            for (let col = leftmostSelected - 1; col >= minAvailableCol; col--) {
                if (availableCols.includes(col) && !selectedCols.includes(col)) {
                    consecutiveEmptyLeft++;
                } else {
                    break;
                }
            }

            // Nếu chỉ có đúng 1 ghế trống liên tiếp bên trái -> vi phạm
            if (consecutiveEmptyLeft === 1) {
                const emptyLeftCol = leftmostSelected - 1;
                return {
                    valid: false,
                    message: `Không được để trống 1 ghế rìa bên trái như ghế ${row}${emptyLeftCol}. Vui lòng chọn ghế khác hoặc chọn thêm ghế ${row}${emptyLeftCol}.`
                };
            }
        }

        // 4. Kiểm tra ghế trống bên phải
        if (rightmostSelected < maxAvailableCol) {
            let consecutiveEmptyRight = 0;
            for (let col = rightmostSelected + 1; col <= maxAvailableCol; col++) {
                if (availableCols.includes(col) && !selectedCols.includes(col)) {
                    consecutiveEmptyRight++;
                } else {
                    break;
                }
            }

            // Nếu chỉ có đúng 1 ghế trống liên tiếp bên phải -> vi phạm
            if (consecutiveEmptyRight === 1) {
                const emptyRightCol = rightmostSelected + 1;
                return {
                    valid: false,
                    message: `Không được để trống 1 ghế rìa bên phải như ghế ${row}${emptyRightCol}. Vui lòng chọn ghế khác hoặc chọn thêm ghế ${row}${emptyRightCol}.`
                };
            }
        }
    }

    return { valid: true };
}

/**
 * Hàm sử dụng hệ thống thông báo global từ master.blade.php
 */
function showGlobalNotification(title, message, type = 'info') {
    // Sử dụng hàm global từ master.blade.php
    if (typeof window.showNotification === 'function') {
        window.showNotification(title, message, type);
    } else if (typeof showNotification === 'function') {
        showNotification(title, message, type);
    } else {
        // Fallback về alert nếu không có hệ thống global
        alert(`${title}: ${message}`);
    }
}

// Hoàn tất đặt vé
function hoanTatDatVe() {
    console.log('Đặt vé hoàn tất:', trangThaiDatVe);
    showGlobalNotification('Thành công', 'Đặt vé thành công! Hóa đơn sẽ được in ra.', 'success');
}

// Khởi tạo các sự kiện
function khoiTaoSuKien() {
    // Gán hàm showNotification vào window để có thể sử dụng global
    if (typeof showNotification !== 'undefined') {
        window.showNotification = showNotification;
    }

    // Sự kiện bước 1
    document.querySelectorAll('.cinema-card').forEach(theRap => {
        theRap.addEventListener('click', function() {
            chonRap(this);
        });
    });

    const inputNgay = document.getElementById('selectedDate');
    if (inputNgay) {
        inputNgay.addEventListener('change', function() {
            trangThaiDatVe.ngay = this.value;
            capNhatTomTatBuoc1();
            capNhatNutTiepTheo(1);
        });
    }

    // Các nút điều hướng
    const nutTiepTheo1 = document.getElementById('btnNext1');
    if (nutTiepTheo1) nutTiepTheo1.addEventListener('click', () => {
        layDuLieuPhim();
    });

    const nutQuayLai2 = document.getElementById('btnBack2');
    if (nutQuayLai2) nutQuayLai2.addEventListener('click', () => chuyenDenBuoc(1));

    const nutTiepTheo2 = document.getElementById('btnNext2');
    if (nutTiepTheo2) nutTiepTheo2.addEventListener('click', () => {
        xuatPhong();
    });

    const nutQuayLai3 = document.getElementById('btnBack3');
    if (nutQuayLai3) nutQuayLai3.addEventListener('click', () => chuyenDenBuoc(2));

    const nutTiepTheo3 = document.getElementById('btnNext3');
    if (nutTiepTheo3) nutTiepTheo3.addEventListener('click', () => {
        // Kiểm tra quy định ghế trước khi chuyển bước
        const selectedSeatIds = trangThaiDatVe.gheNgoi.map(ghe => ghe.id);
        kiemTraGheNgoi(selectedSeatIds);
    });

    const nutQuayLai4 = document.getElementById('btnBack4');
    if (nutQuayLai4) nutQuayLai4.addEventListener('click', () => chuyenDenBuoc(3));

    const nutTiepTheo4 = document.getElementById('btnNext4');
    if (nutTiepTheo4) {
        nutTiepTheo4.addEventListener('click', () => {
            const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked');

            if (!selectedMethod) {
                // Sử dụng thông báo global thay vì alert
                if (typeof showGlobalNotification === 'function') {
                    showGlobalNotification('Lỗi', 'Vui lòng chọn phương thức thanh toán.', 'warning');
                } else {
                    alert("Vui lòng chọn phương thức thanh toán.");
                }
                return;
            }

            const value = selectedMethod.value;
            trangThaiDatVe.phuongThucThanhToan = value;

            if (value === "Tiền mặt") {
                // Sử dụng confirm hoặc modal tùy theo có hệ thống global không
                let xacNhan;
                if (typeof showConfirmation === 'function') {
                    showConfirmation(
                        'Xác nhận thanh toán',
                        'Bạn có chắc chắn đã thu tiền từ khách hàng?',
                        function() {
                            // Callback khi xác nhận
                            xuLyThanhToanTienMat();
                        }
                    );
                    return;
                } else {
                    xacNhan = confirm("Xác nhận đã thu tiền từ khách hàng?");
                    if (!xacNhan) return;
                    xuLyThanhToanTienMat();
                }

                return; // Dừng lại ở đây nếu là tiền mặt
            }

            // ✅ Nếu là chuyển khoản → mở popup modal
            const summaryContent = document.getElementById('finalSummary').innerHTML;
            document.getElementById('paymentSummaryModal').innerHTML = summaryContent;

            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        });
    }

    const suaRap = document.getElementById('editCinema');
    if (suaRap) suaRap.addEventListener('click', () => chuyenDenBuoc(1));

    const suaPhim = document.getElementById('editMovie');
    if (suaPhim) suaPhim.addEventListener('click', () => chuyenDenBuoc(2));

    const suaGhe = document.getElementById('editSeats');
    if (suaGhe) suaGhe.addEventListener('click', () => chuyenDenBuoc(3));

    const nutHoanTat = document.getElementById('btnComplete');
    if (nutHoanTat) nutHoanTat.addEventListener('click', hoanTatDatVe);
}

// Hàm xử lý thanh toán tiền mặt riêng biệt
function xuLyThanhToanTienMat() {
    // ✅ Gửi AJAX tạo hóa đơn
    $.ajax({
        url: '/admin/hoa-don/store',
        method: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            TongTien: trangThaiDatVe.tongTien,
            SoTienGiam: trangThaiDatVe.giamGia || 0,
            ID_SuatChieu: trangThaiDatVe.ID_SuatChieu,
            PTTT: "Tiền mặt",
            SoLuongVe: trangThaiDatVe.gheNgoi.length,
            TrangThaiXacNhanHoaDon: 1,
            TrangThaiXacNhanThanhToan: 1,
            TenPhim: trangThaiDatVe.phim.tieuDe,
            DanhSachGhe: trangThaiDatVe.gheNgoi,
            DiaChi: trangThaiDatVe.rap.diaChi,
        },
        success: function(res) {
            if (typeof showGlobalNotification === 'function') {
                showGlobalNotification('Thành công', 'Tạo hóa đơn thành công!', 'success');
            }
            
            // Chuyển hướng sau 1.5 giây
            setTimeout(() => {
                window.location.href = res.redirect_url || '/admin/hoa-don';
            }, 1500);
        },
        error: function(xhr) {
            console.error('Lỗi AJAX:', xhr.responseText);
            
            if (typeof showGlobalNotification === 'function') {
                showGlobalNotification('Lỗi', 'Lỗi khi tạo hóa đơn. Vui lòng kiểm tra lại.', 'error');
            } else {
                alert("Lỗi khi tạo hóa đơn. Vui lòng kiểm tra lại.");
            }
        }
    });
}