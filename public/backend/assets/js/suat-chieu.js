let data_lich_trinh = [];
let conflictCheckTimeout;


document.getElementById("ID_Phim").disabled = true;
document.getElementById('start_date').addEventListener('change', function () {
    const value = this.value;
    if (value) {
        document.getElementById("ID_Phim").disabled = false;
        getMovie();
    }
});

document.getElementById('ID_Rap').addEventListener('change', function () {
    document.getElementById("ID_PhongChieu").disabled = false;
    getPhong();
});

document.addEventListener('DOMContentLoaded', function () {
    const phongChieuSelect = document.getElementById('ID_PhongChieu');
    const rapIdInput = document.getElementById('ID_Rap');

    // Cập nhật ID_Rap khi chọn phòng chiếu
    phongChieuSelect.addEventListener('change', function () {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const rapId = selectedOption.getAttribute('data-rap-id');
            rapIdInput.value = rapId;
        } else {
            rapIdInput.value = '';
        }
        checkXungDotToanBo();
    });

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
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDateFromSchedule('${item.date}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    
                    <div class="times-container">
                        ${item.times.map(time => `
                                <span class="badge badge-secondary mr-1 mb-1 text-dark" style="font-size: 0.9em;">
                                    🕐 ${time}
                                    <button type="button" class="btn btn-sm p-0 ml-1" onclick="removeTimeFromDate('${item.date}', '${time}')" style="color: dark; background: none; border: none;">
                                        ×
                                    </button>
                                </span>
                            `).join('')}
                        
                        <button type="button" class="btn btn-sm btn-outline-primary ml-1" onclick="addTimeToDate('${item.date}')">
                            <i class="fas fa-plus"></i> Thêm giờ
                        </button>
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



