let data_lich_trinh = [];
let conflictCheckTimeout;


document.getElementById("ID_Phim").addEventListener('change', function () {
    data_lich_trinh.forEach(item => item.times = []);
    capNhatGiaoDien();
});

document.getElementById('ID_Rap').addEventListener('change', function () {
    document.getElementById("ID_PhongChieu").disabled = false;
    getPhong();
});

document.addEventListener('DOMContentLoaded', function () {
    const phongChieuSelect = document.getElementById('ID_PhongChieu');
    const rapIdInput = document.getElementById('ID_Rap');

    // Kiểm tra xung đột khi thay đổi phim
    document.getElementById('ID_Phim').addEventListener('change', checkXungDotToanBo);

    updateTrangThaiTrong();
});



function tinhSoLuongNgay() {
    let ngayBatDau = document.getElementById('start_date').value;
    let ngayKetThuc = document.getElementById('end_date').value;

    if (ngayBatDau && !ngayKetThuc) {
        ngayKetThuc = document.getElementById('start_date').value;
    }
    else if (!ngayBatDau) {
        alert('Vui lòng ngày khởi đầu (Ngày kết thúc có thể không nhập ^^)');
        return;
    }

    if (ngayBatDau > ngayKetThuc) {
        alert('Ngày bắt đầu không thể sau ngày kết thúc');
        return;
    }

    const batDau = new Date(ngayBatDau);
    const ketThuc = new Date(ngayKetThuc);
    data_lich_trinh = [];

    for (let date = new Date(batDau); date <= ketThuc; date.setDate(date.getDate() + 1)) {
        const data = date.toISOString().split('T')[0]

        if (!data_lich_trinh.find(item => item.date === data)) {
            data_lich_trinh.push({
                date: data,
                times: []
            });
        }
    }
    capNhatGiaoDien();
}

function addTimeToDate(date) {
    const time = prompt('Nhập giờ chiếu (HH:MM):');
    if (!time) return;

    if (!/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/.test(time)) {
        alert('Định dạng giờ không hợp lệ');
        return;
    }

    const fullDateTime = new Date(`${date}T${time}:00`);

    const now = new Date();
    const nowPlus15 = new Date(now.getTime() + 15 * 60 * 1000);

    if (fullDateTime <= nowPlus15) {
        alert('Không được nhập thời gian nhỏ hơn hiện tại + 15 phút!');
        return;
    }

    const dateItem = data_lich_trinh.find(item => item.date === date);
    if (dateItem && !dateItem.times.includes(time)) {
        dateItem.times.push(time);
        dateItem.times.sort();
        capNhatGiaoDien();
        checkXungDotToanBo();
    }
}




function removeTimeFromDate(date, time) {
    const dateItem = data_lich_trinh.find(item => item.date === date);
    if (dateItem) {
        dateItem.times = dateItem.times.filter(t => t !== time);
        capNhatGiaoDien();
        checkXungDotToanBo();
    }
}



function removeDateFromSchedule(date) {
    data_lich_trinh = data_lich_trinh.filter(item => item.date !== date);
    capNhatGiaoDien();
    checkXungDotToanBo();
}



function clearToanBoNgay() {
    if (confirm('Bạn có chắc muốn xóa tất cả lịch chiếu( xuy nghĩ chắc chưa ?)')) {
        data_lich_trinh = [];
        capNhatGiaoDien();
    }
}



function capNhatGiaoDien() {
    const container = document.getElementById('schedule-container');
    const emptyState = document.getElementById('empty-schedule');

    if (data_lich_trinh.length === 0) {
        container.innerHTML = '';
        emptyState.style.display = 'block';
        updateSubmitButton();
        return;
    }

    emptyState.style.display = 'none';

    container.innerHTML = data_lich_trinh.map(item => `
        <div class="schedule-date-item mb-3 p-3 border rounded">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">
                    📅 ${formatDate(item.date)} 
                    <small class="text-muted">(${item.times.length} suất chiếu)</small>
                </h6>
                <div>
                    <button type="button" class="btn btn-sm btn-success me-1" onclick="addTimeToDate('${item.date}')">
                        <i class="fas fa-plus-circle"></i> Thêm giờ
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDateFromSchedule('${item.date}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <div class="times-container mb-2">
                ${item.times.map(time => `
                    <span class="badge bg-light border text-dark me-1 mb-1" style="font-size: 0.9em;">
                        🕐 ${time}
                        <button type="button" class="btn btn-sm p-0 ms-1"  onclick="removeTimeFromDate('${item.date}', '${time}')" style="color: #dc3545; background: none; border: none;">×</button>
                    </span>
                `).join('')}

                <!-- Nút Tự động (chỉ 1 lần) -->
                <div class="mt-2">
                    <button onclick="moModalTaoTuDong('${item.date}')" class="btn btn-sm btn-outline-primary" type="button" >
                        Tự động tạo suất chiếu
                    </button>
                </div>
            </div>

            
            <input type="hidden" name="schedule[${item.date}]" value="${item.times.join(',')}">
        </div>
    `).join('');

    updateSubmitButton();
    updateSummary();
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    const days = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];
    return `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()} (${days[date.getDay()]})`;
}

function updateSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    const totalCount = document.getElementById('total-count');
    const total = data_lich_trinh.reduce((sum, item) => sum + item.times.length, 0);

    totalCount.textContent = total;
    submitBtn.disabled = total === 0;
}

function updateSummary() {
    const summary = document.getElementById('schedule-summary');
    const content = document.getElementById('summary-content');
    const total = data_lich_trinh.reduce((sum, item) => sum + item.times.length, 0);

    if (total > 0) {
        content.innerHTML = `
                    <div>Tổng số suất chiếu: <strong>${total}</strong></div>
                    <div>Số ngày chiếu: <strong>${data_lich_trinh.length}</strong></div>
                    <div>Trung bình: <strong>${(total / data_lich_trinh.length || 0).toFixed(1)}</strong> suất/ngày</div>
                `;
        summary.classList.remove('d-none');
    } else {
        summary.classList.add('d-none');
    }
}

function updateTrangThaiTrong() {
    const emptyState = document.getElementById('empty-schedule');
    emptyState.style.display = data_lich_trinh.length === 0 ? 'block' : 'none';
}

function checkXungDotToanBo() {
    const phongChieuId = document.getElementById('ID_PhongChieu').value;
    const phimId = document.getElementById('ID_Phim').value;

    if (!phongChieuId || !phimId || data_lich_trinh.length === 0) {
        document.getElementById('conflict-summary').classList.add('d-none');
        return;
    }

    // Clear previous timeout
    if (conflictCheckTimeout) {
        clearTimeout(conflictCheckTimeout);
    }

    conflictCheckTimeout = setTimeout(() => {
        const checkPromises = [];

        data_lich_trinh.forEach(dateItem => {
            dateItem.times.forEach(time => {
                checkPromises.push(
                    $.ajax({
                        url: "{{ route('suat-chieu.check-conflict') }}",
                        method: 'POST',
                        data: {
                            phong_chieu_id: phongChieuId,
                            ngay_chieu: dateItem.date,
                            gio_chieu: time,
                            phim_id: phimId,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }
                    }).then(response => ({
                        date: dateItem.date,
                        time: time,
                        conflict: response
                    }))
                );
            });
        });

        Promise.all(checkPromises).then(results => {
            const conflicts = results.filter(result => result.conflict.has_conflict);

            const conflictSummary = document.getElementById('conflict-summary');
            const conflictList = document.getElementById('conflict-list');
            const submitBtn = document.getElementById('submitBtn');

            if (conflicts.length > 0) {
                conflictList.innerHTML = conflicts.map(conflict => `
                            <div class="mb-1">
                                📅 ${formatDate(conflict.date)} 🕐 ${conflict.time}: 
                                Xung đột với "${conflict.conflict.conflict_show.phim.TenPhim}" 
                                (${conflict.conflict.conflict_time})
                            </div>
                        `).join('');

                conflictSummary.classList.remove('d-none');
                submitBtn.disabled = true;
                submitBtn.classList.add('btn-secondary');
                submitBtn.classList.remove('btn-primary');
            } else {
                conflictSummary.classList.add('d-none');
                const total = data_lich_trinh.reduce((sum, item) => sum + item.times.length, 0);
                submitBtn.disabled = total === 0;
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-primary');
            }
        });
    }, 1000);
}

let currentDateForAdd = null;

// Hàm gọi modal khi thêm giờ cho một ngày cụ thể
function addTimeToDate(date) {
    currentDateForAdd = date;
    document.getElementById('selected-date').textContent = date;
    document.getElementById('datetime-input').value = ''; // reset input

    const modal = new bootstrap.Modal(document.getElementById('addTimeModal'));
    modal.show();
}

// Hàm xác nhận thêm giờ
function xacNhanThemGio() {
    const time = document.getElementById('datetime-input').value;
    if (!time) {
        alert('Vui lòng nhập giờ chiếu!');
        return;
    }

    // Kiểm tra định dạng HH:MM
    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(time)) {
        alert('Định dạng giờ không hợp lệ! (HH:MM)');
        return;
    }

    // ✅ Kiểm tra nếu là hôm nay và giờ nhập < giờ hiện tại + 15 phút
    const todayStr = new Date().toISOString().split('T')[0];
    const timeToCheck = new Date(`${currentDateForAdd}T${time}:00`);
    const nowPlus15 = new Date(new Date().getTime() + 15 * 60 * 1000);

    if (currentDateForAdd === todayStr && timeToCheck <= nowPlus15) {
        alert('Không được nhập giờ chiếu nhỏ hơn thời điểm hiện tại + 15 phút!');
        return;
    }

    const dateItem = data_lich_trinh.find(item => item.date === currentDateForAdd);
    if (dateItem && !dateItem.times.includes(time)) {
        dateItem.times.push(time);
        dateItem.times.sort();
        capNhatGiaoDien();
        checkXungDotToanBo();
    }

    bootstrap.Modal.getInstance(document.getElementById('addTimeModal')).hide();
}


let currentDateForAuto = null;

function moModalTaoTuDong(date) {
    currentDateForAuto = date;
    document.getElementById('auto-date').innerText = date;
    document.getElementById('start-time').value = '';
    document.getElementById('end-time').value = '';
    new bootstrap.Modal(document.getElementById('autoScheduleModal')).show();
}

function taoSuatChieuTuDong() {
    const gioBatDau = document.getElementById('start-time').value;
    const gioKetThuc = document.getElementById('end-time').value;
    const luaChonPhim = document.querySelector('#ID_Phim option:checked');
    const thoiLuongPhim = luaChonPhim ? parseInt(luaChonPhim.getAttribute('data-duration')) : 0;

    if (!thoiLuongPhim) {
        alert('Vui lòng chọn phim trước khi thêm giờ!');
        return;
    }

    if (!gioBatDau || !gioKetThuc) {
        alert('Vui lòng nhập đầy đủ giờ bắt đầu và kết thúc!');
        return;
    }

    const [gioBD, phutBD] = gioBatDau.split(':').map(Number);
    const [gioKT, phutKT] = gioKetThuc.split(':').map(Number);

    let tongPhutBatDau = gioBD * 60 + phutBD;
    const tongPhutKetThuc = gioKT * 60 + phutKT;

    if (tongPhutBatDau >= tongPhutKetThuc) {
        alert('Giờ bắt đầu phải nhỏ hơn giờ kết thúc!');
        return;
    }

    const ngayDangChon = data_lich_trinh.find(item => item.date === currentDateForAuto);
    if (!ngayDangChon) {
        alert('Không tìm thấy ngày chiếu!');
        return;
    }

    // Làm tròn lên phút chia hết cho 5
    if (tongPhutBatDau % 5 !== 0) {
        tongPhutBatDau += 5 - (tongPhutBatDau % 5);
    }

    for (let phutChieu = tongPhutBatDau; phutChieu <= tongPhutKetThuc;) {
        const gio = String(Math.floor(phutChieu / 60)).padStart(2, '0');
        const phut = String(phutChieu % 60).padStart(2, '0');
        let gioChieu = `${gio}:${phut}`;

        const ngayHomNay = new Date().toISOString().split('T')[0];
        const thoiGianCanKiem = new Date(`${currentDateForAuto}T${gioChieu}:00`);
        const thoiGianToiThieu = new Date(new Date().getTime() + 15 * 60 * 1000);

        // Nếu là hôm nay và giờ chiếu nhỏ hơn hiện tại + 15 phút
        if (currentDateForAuto === ngayHomNay && thoiGianCanKiem < thoiGianToiThieu) {
            const tongPhutToiThieu = Math.ceil(thoiGianToiThieu.getHours() * 60 + thoiGianToiThieu.getMinutes());

            // Làm tròn đến phút chia hết cho 5
            const lamTron5Phut = tongPhutToiThieu % 5 === 0
                ? tongPhutToiThieu
                : tongPhutToiThieu + (5 - (tongPhutToiThieu % 5));

            phutChieu = lamTron5Phut;
            continue; // quay lại vòng lặp với thời gian mới
        }

        // Tạo suất chiếu nếu chưa tồn tại
        if (!ngayDangChon.times.includes(gioChieu)) {
            ngayDangChon.times.push(gioChieu);
        }

        phutChieu += thoiLuongPhim + 30;
    }

    ngayDangChon.times.sort();
    capNhatGiaoDien();
    checkXungDotToanBo();

    bootstrap.Modal.getInstance(document.getElementById('autoScheduleModal')).hide();
}

function kiemTraSuatChieuSom() {
    const luaChonPhim = document.querySelector('#ID_Phim option:checked');
    const ngayKhoiChieu = luaChonPhim ? luaChonPhim.getAttribute('data-ngaykhoichieu') : null;

    if (!ngayKhoiChieu) return true; // Không có dữ liệu thì cho qua

    for (const item of data_lich_trinh) {
        if (item.date < ngayKhoiChieu) {
            return confirm('⚠️ Hiện bạn đang tạo suất chiếu sớm hơn ngày khởi chiếu của phim. Bạn có chắc chắn muốn tiếp tục không?');
        }
    }

    return true;
}


