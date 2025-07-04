@extends('admin.layouts.master')
@section('title', 'Quản lý Hóa Đơn')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/booking_seat.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/hoa-don.css') }}">
@endsection

@section('main')
    <div class="container-fluid">
        <div class="container">
            <!-- Progress Bar -->
            <div class="progress-bar-container">
                <div class="progress-steps">
                    <div class="progress-line" id="progressLine"></div>
                    <div class="step-item active" data-step="1">
                        <div class="step-circle">1</div>
                        <div class="step-title">Chọn Rạp & Ngày</div>
                    </div>
                    <div class="step-item" data-step="2">
                        <div class="step-circle">2</div>
                        <div class="step-title">Chọn Phim</div>
                    </div>
                    <div class="step-item" data-step="3">
                        <div class="step-circle">3</div>
                        <div class="step-title">Chọn Ghế</div>
                    </div>
                    <div class="step-item" data-step="4">
                        <div class="step-circle">4</div>
                        <div class="step-title">Xác Nhận</div>
                    </div>
                    <div class="step-item" data-step="5">
                        <div class="step-circle">5</div>
                        <div class="step-title">Thanh Toán</div>
                    </div>
                </div>
            </div>

            <!-- Step Content -->
            <div class="step-content">
                <!-- Step 1: Chọn Rạp & Ngày -->
                <div class="form-step active" id="step1">
                    <h3 class="mb-4"><i class="fas fa-building me-2"></i>Chọn Rạp Chiếu & Ngày</h3>

                    <div class="row">
                        <div class="col-md-8">
                            <div id="cinemaList">
                                @foreach ($raps as $rap)
                                    <div class="cinema-card" data-cinema-id="{{ $rap->ID_Rap }}">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-film me-3 text-primary" style="font-size: 2rem;"></i>
                                            <div>
                                                <h6 class="mb-1">{{ $rap->TenRap }}</h6>
                                                <p class="text-muted mb-0">{{ $rap->DiaChi }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h5 class="mb-3">Chọn Ngày:</h5>
                            <input type="date" class="form-control" id="selectedDate" min="" />

                            <div class="summary-card mt-4">
                                <h6><i class="fas fa-info-circle me-2"></i>Thông Tin Đã Chọn</h6>
                                <div id="step1Summary">
                                    <p class="text-muted">Chưa chọn rạp và ngày</p>
                                </div>
                            </div>
                            <button type="button" class="btn-custom btn-primary-custom" id="btnNext1" disabled>
                                Tiếp Theo <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Chọn Phim -->
                <div class="form-step" id="step2">
                    <h3 class="mb-4"><i class="fas fa-video me-2"></i>Chọn Phim</h3>

                    <div class="row">
                        <div class="col-md-8">
                            <div id="movieList">
                                <!-- Movies will be loaded here -->
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="summary-card">
                                <h6><i class="fas fa-info-circle me-2"></i>Thông Tin Đã Chọn</h6>
                                <div id="step2Summary"></div>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group-custom">
                        <button type="button" class="btn-custom btn-secondary-custom" id="btnBack2">
                            <i class="fas fa-arrow-left me-1"></i> Quay Lại
                        </button>
                        <button type="button" class="btn-custom btn-primary-custom" id="btnNext2" disabled>
                            Tiếp Theo <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Chọn Ghế -->
                <div class="form-step" id="step3">
                    <h3 class="mb-4"><i class="fas fa-couch me-2"></i>Chọn Ghế Ngồi</h3>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="left-panel">
                                <div class="booking-note-pro" style="display: flex; align-items: center; gap: 10px;">
                                    <div class="booking-note-icon-tooltip" style="position: relative;">
                                        <i class="fa-solid fa-circle-info"
                                            style="font-size: 1.5rem; color: #1976d2; cursor: pointer;"></i>
                                        <div class="booking-note-tooltip">
                                            <div><b>Số ghế:</b> <span id="tongSoGhe">-- ghế/phòng</span></div>
                                            <div><b>Tối đa:</b> 8 ghế/lần đặt</div>
                                            <div><b>Ghế VIP:</b> +20% giá vé so với ghế thường</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="screen-wrapper"
                                    style="display: flex; flex-direction: column; align-items: center;">
                                    <img src="{{ asset('frontend/Content/img/img-screen.png') }}" alt="Screen"
                                        class="screen-image">
                                    <div class="screen-text">Màn hình</div>
                                </div>
                                <div id="seatLayout" class="seat-container">
                                    {{-- Sơ đồ ghế được render bằng JavaScript --}}
                                </div>
                                <div class="seat-legend mt-3">
                                    <div class="legend-item">
                                        <div class="legend-box legend-normal"></div>
                                        <span>Ghế thường</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-box legend-vip"></div>
                                        <span>Ghế VIP</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-box choosing"></div>
                                        <span>Ghế đang chọn</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-box legend-booked booked"></div>
                                        <span>Ghế đã đặt</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-box legend-disabled"></div>
                                        <span>Ghế đang bảo trì</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="summary-card">
                                <h6><i class="fas fa-ticket-alt me-2"></i>Thông Tin Vé</h6>
                                <div id="step3Summary"></div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Tổng tiền:</strong>
                                    <strong class="text-success" id="totalPrice">0 VNĐ</strong>
                                </div>
                            </div>
                            <div class="btn-group-custom">
                                <button type="button" class="btn-custom btn-secondary-custom" id="btnBack3">
                                    <i class="fas fa-arrow-left me-1"></i> Quay Lại
                                </button>
                                <button type="button" class="btn-custom btn-primary-custom" id="btnNext3" disabled>
                                    Tiếp Theo <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Xác Nhận -->
                <div class="form-step" id="step4">
                    <h3 class="mb-4"><i class="fas fa-check-circle me-2"></i>Xác Nhận Thông Tin</h3>

                    <div class="row">
                        <!-- Cột bên trái -->
                        <div class="col-md-8">
                            <!-- Tóm tắt hóa đơn -->
                            <div class="ticket-preview mb-4">
                                <h5 class="mb-3">Chi Tiết Hóa Đơn</h5>
                                <div id="finalSummary"></div>
                            </div>

                            <!-- Nhập khuyến mãi -->
                            <div class="mt-4">
                                <h6 class="mb-3">Nhập Khuyến Mãi</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="promoCode"
                                            placeholder="Nhập mã khuyến mãi">
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-primary" id="applyPromo">Áp dụng</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Chỉnh sửa -->
                            <div class="summary-card mt-4">
                                <h6>Chỉnh Sửa Nhanh</h6>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="editCinema">
                                        <i class="fas fa-edit me-1"></i> Sửa Rạp & Ngày
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="editMovie">
                                        <i class="fas fa-edit me-1"></i> Sửa Phim
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="editSeats">
                                        <i class="fas fa-edit me-1"></i> Sửa Ghế
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Cột bên phải -->
                        <div class="col-md-4">
                            <div class="payment-method card mb-4">
                                <div class="card-body">
                                    <h5 class="mb-3 text-center">Phương Thức Thanh Toán</h5>

                                    <div class="mb-3 text-center">
                                        <i class="fas fa-money-bill-wave fa-3x text-success mb-2"></i>
                                        <div>
                                            <input type="radio" name="paymentMethod" value="Tiền mặt"
                                                class="form-check-input me-2">
                                            <label>Tiền mặt</label>
                                        </div>
                                        <p class="text-muted small">Khách hàng thanh toán trực tiếp</p>
                                    </div>

                                    <hr>

                                    <div class="mb-3 text-center">
                                        <i class="fas fa-credit-card fa-3x text-primary mb-2"></i>
                                        <div>
                                            <input type="radio" name="paymentMethod" value="Chuyển khoản"
                                                class="form-check-input me-2">
                                            <label>Thẻ</label>
                                        </div>
                                        <p class="text-muted small">Thanh toán qua thẻ ATM/Credit</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Nút chuyển bước -->
                            <div class="btn-group-custom mt-4">
                                <button type="button" class="btn-custom btn-secondary-custom" id="btnBack4">
                                    <i class="fas fa-arrow-left me-1"></i> Quay Lại
                                </button>
                                <button type="button" class="btn-custom btn-success-custom" id="btnNext4">
                                    Thanh Toán <i class="fas fa-credit-card ms-1"></i>
                                </button>
                            </div>
                            {{-- popup thanh toán chuyển khoản --}}
                            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="paymentModalLabel"><i
                                                    class="fas fa-credit-card me-2"></i> Thanh Toán</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <!-- Chi tiết hóa đơn -->
                                                <div class="col-md-7">
                                                    <h6><i class="fas fa-film me-2"></i>Chi tiết vé</h6>
                                                    <div id="paymentSummaryModal"></div>
                                                </div>

                                                <!-- Phương thức thanh toán -->
                                                <div class="col-md-5">
                                                    <h6><i class="fas fa-wallet me-2"></i>Phương thức thanh toán</h6>
                                                    <div class="m-3" class="d-flex" style="display: flex">
                                                        <input type="radio" id="payOS" name="paymentMethod"
                                                            checked>
                                                        <label for="payOS" class="d-flex w-100">
                                                            <img src="/frontend/Content/img/payos.jpg" alt="PayOS"
                                                                style="height: 24px;"> PayOS
                                                        </label>
                                                    </div>
                                                    <button class="btn btn-warning w-100 mt-3" id="btnModalConfirm">
                                                        Thanh Toán
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const rowCountSelect = document.getElementById('rowCount');
        const colCountSelect = document.getElementById('colCount');
        const rowAislesSelect = document.getElementById('rowAisles');
        const colAislesSelect = document.getElementById('colAisles');
        const seatContainer = document.getElementById('seatLayout');
        const seatLayoutInput = document.getElementById('seatLayoutInput');
        const clearAislesBtn = document.getElementById('clearAislesBtn');
        const seatCountSpan = document.getElementById('seatCount');
        const taiKhoan = {!! json_encode($taiKhoan) !!};

        document.addEventListener('DOMContentLoaded', function() {
            if (taiKhoan && taiKhoan.ID_Rap) {
                trangThaiDatVe.rap = {
                    id: taiKhoan.ID_Rap,
                    ten: taiKhoan.TenRap || '',
                    diaChi: taiKhoan.DiaChi || ''
                };
                layDuLieuPhim();
            }
        });
        // Trạng thái ứng dụng
        let trangThaiDatVe = {
            buoc: 1,
            rap: null,
            ngay: null,
            phim: null,
            ID_SuatChieu: null,
            suatChieu: null,
            idPhongChieu: null,
            gheNgoi: [],
            khachHang: {},
            phuongThucThanhToan: null,
            giamGia: 0,
            tongTien: 0,
            giaVe: 0,
        };
        let danhSachGheDaDat = [];

        function layDuLieuPhim() {
            const ngayDaChon = document.getElementById("selectedDate").value;

            let ID_Rap = null;

            if (trangThaiDatVe.rap) {
                ID_Rap = trangThaiDatVe.rap.id;
            } else if (taiKhoan && taiKhoan.ID_Rap) {
                ID_Rap = taiKhoan.ID_Rap;
                trangThaiDatVe.rap = {
                    id: taiKhoan.ID_Rap,
                    ten: taiKhoan.TenRap || '',
                    diaChi: taiKhoan.DiaChi || ''
                };
            }

            if (!ngayDaChon && !ID_Rap) return;

            console.log("Gửi yêu cầu lọc phim cho ngày:", ngayDaChon);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('hoa-don.suat-chieu.loc-phim-theo-ngay') }}",
                method: 'POST',
                data: {
                    date: ngayDaChon,
                    ID_Rap: ID_Rap,
                },
                success: function(data) {
                    if (!Array.isArray(data)) {
                        alert('Phản hồi từ máy chủ không hợp lệ!');
                        console.error('Dữ liệu trả về:', data);
                        return;
                    }

                    danhSachPhim = data.map(phim => ({
                        id: phim.ID_Phim,
                        tieuDe: phim.TenPhim,
                        thoiLuong: phim.ThoiLuong,
                        doTuoi: phim.DoTuoi || 'Không rõ',
                        poster: phim.HinhAnh ||
                            'https://via.placeholder.com/80x120/cccccc/ffffff?text=?',
                        suatChieu: phim.SuatChieu.map(sc => ({
                            suatChieu: sc.id,
                            gio: sc.gio,
                            phong: sc.phong,
                            gia_ve: sc.gia_ve,
                        })),
                    }));

                    console.log("Dữ liệu danh sách phim mới:", danhSachPhim);
                    taiDanhSachPhim();
                    chuyenDenBuoc(2);
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi tải dữ liệu phim:", error);
                    alert('Có lỗi xảy ra khi tải danh sách phim. Vui lòng thử lại!');
                }
            });
        }



        $('#applyPromo').click(function() {
            const code = $('#promoCode').val().trim();
            $('#promoMessage').text(''); // Xóa thông báo cũ

            if (code === "") {
                $('#promoMessage').text("Vui lòng nhập mã khuyến mãi.");
                return;
            }

            $.ajax({
                url: "{{ route('hoa-don.khuyen-mai.kiem-tra') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    MaKhuyenMai: code
                },
                success: function(res) {
                    if (res.success) {
                        $('#promoMessage').removeClass('text-danger').addClass('text-success').text(res
                            .message);
                        const phanTramGiam = res.khuyenMai.PhanTramGiam;
                        const giamToiDa = res.khuyenMai.GiamToiDa;
                        const dieuKienToiThieu = res.khuyenMai.DieuKienToiThieu;

                        let khuyenMai = (trangThaiDatVe.tongTien * phanTramGiam) / 100;

                        if (khuyenMai > giamToiDa) khuyenMai = giamToiDa;

                        if (trangThaiDatVe.tongTien >= dieuKienToiThieu) {
                            trangThaiDatVe.giamGia = khuyenMai;
                            trangThaiDatVe.tongTien -= khuyenMai;
                        } else {
                            trangThaiDatVe.giamGia = 0;
                        }
                        capNhatTomTatCuoiCung();
                    } else {
                        $('#promoMessage').removeClass('text-success').addClass('text-danger').text(res
                            .message);
                    }
                },
                error: function() {
                    $('#promoMessage').removeClass('text-success').addClass('text-danger').text(
                        "Lỗi khi kiểm tra mã khuyến mãi.");
                }
            });

        });


        function xuatPhong() {
            const nutDaChon = document.querySelector('.showtime-btn.selected');
            if (!nutDaChon) {
                alert("Vui lòng chọn suất chiếu trước khi tiếp tục.");
                return;
            }
            danhSachGheDaDat = []
            const idPhong = nutDaChon.dataset.idPhong;
            trangThaiDatVe.idPhongChieu = idPhong;

            $.ajax({
                url: "{{ route('hoa-don.suat-chieu.lay-phong') }}",
                method: 'POST',
                data: {
                    id_phong: idPhong,
                    ngay_chieu: trangThaiDatVe.ngay,
                    gio_chieu: nutDaChon.dataset.showtime,
                    ID_SuatChieu: trangThaiDatVe.ID_SuatChieu,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log('Dữ liệu phòng chiếu:', data);

                    // Gán dữ liệu vào biến toàn cục
                    cacHang = data.seatLayout || [];
                    cauHinhGhe = {
                        rows: cacHang.length,
                        cols: cacHang[0]?.length || 0
                    };
                    rowAisles = (data.rowAisles || []).map(Number);
                    colAisles = (data.colAisles || []).map(Number);

                    // Lấy danh sách ghế đã đặt (nếu có)
                    danhSachGheDaDat = data.GheDaDat || [];

                    // Cập nhật thông tin tooltip
                    updateSeatInfo(data);

                    // Render sơ đồ ghế
                    taoSoDoGhe();
                    chuyenDenBuoc(3);
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi khi lấy phòng chiếu:', error);
                    alert('Không thể tải thông tin phòng chiếu.');
                }
            });
        }

        document.getElementById('btnModalConfirm').addEventListener('click', function() {
            // Thu thập dữ liệu từ trang (hoặc từ biến toàn cục)
            const data = {
                _token: "{{ csrf_token() }}", // Bắt buộc trong Laravel
                ID_SuatChieu: trangThaiDatVe.ID_SuatChieu,
                selectedSeats: trangThaiDatVe.gheNgoi.map(ghe => ghe.tenGhe).join(','),
                seatDetails: JSON.stringify(trangThaiDatVe.chiTietGhe || []),
                paymentMethod: 'PAYOS',
                so_tien_giam: trangThaiDatVe.giamGia || 0,
                tong_tien: trangThaiDatVe.tongTien,
                ma_khuyen_mai: trangThaiDatVe.khuyenMai || ''
            };

            // Gửi AJAX đến controller
            $.ajax({
                url: "{{ route('hoa-don.payment') }}",
                method: "POST",
                data: data,
                success: function(res) {
                    if (res.checkoutUrl) {
                        // Chuyển hướng tới link thanh toán PayOS
                        window.location.href = res.checkoutUrl;
                    } else {
                        alert("Tạo liên kết thanh toán không thành công.");
                        console.log(res);
                    }
                }
            });
        });


        // Hàm cập nhật thông tin ghế trong tooltip
        function updateSeatInfo(phongData) {
            const tongSoGheElement = document.getElementById('tongSoGhe');
            if (tongSoGheElement && phongData.SoLuongGhe) {
                tongSoGheElement.textContent = `${phongData.SoLuongGhe} ghế/phòng`;
            } else if (tongSoGheElement && cacHang) {
                // Tính tổng số ghế từ seatLayout nếu không có SoLuongGhe
                let tongGhe = 0;
                cacHang.forEach(hang => {
                    hang.forEach(ghe => {
                        if (ghe && ghe !== 0) {
                            tongGhe++;
                        }
                    });
                });
                tongSoGheElement.textContent = `${tongGhe} ghế/phòng`;
            }
        }

        // CSS cho tooltip (thêm vào head hoặc file CSS)
        const tooltipStyle = `
            <style>
                .booking-note-tooltip {
                    position: absolute;
                    bottom: 100%;
                    left: 50%;
                    transform: translateX(-50%);
                    background: rgba(0, 0, 0, 0.9);
                    color: white;
                    padding: 12px;
                    border-radius: 8px;
                    font-size: 14px;
                    white-space: nowrap;
                    z-index: 1000;
                    opacity: 0;
                    visibility: hidden;
                    transition: opacity 0.3s, visibility 0.3s;
                    margin-bottom: 5px;
                }
                
                .booking-note-tooltip::after {
                    content: '';
                    position: absolute;
                    top: 100%;
                    left: 50%;
                    transform: translateX(-50%);
                    border: 5px solid transparent;
                    border-top-color: rgba(0, 0, 0, 0.9);
                }
                
                .booking-note-tooltip div {
                    margin: 2px 0;
                }
                
                .booking-note-icon-tooltip:hover .booking-note-tooltip {
                    opacity: 1;
                    visibility: visible;
                }
            </style>
        `;

        // Thêm CSS vào head
        if (!document.querySelector('#booking-tooltip-style')) {
            const styleElement = document.createElement('div');
            styleElement.innerHTML = tooltipStyle;
            styleElement.id = 'booking-tooltip-style';
            document.head.appendChild(styleElement.children[0]);
        }
        if (!result.valid) {
            document.getElementById("thongBao").innerText = result.reason;
        }
    </script>
    <script src="{{ asset('backend/assets/js/dat-ghe.js') }}" defer></script>
    <script src="{{ asset('backend/assets/js/booking-seat.js') }}" defer></script>
@endsection
