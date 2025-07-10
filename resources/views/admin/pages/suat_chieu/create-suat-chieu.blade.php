@extends('admin.layouts.master')
@section('title', 'Tạo suất chiếu hàng loạt')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/suat-chieu.css') }}">
@endsection

@section('main')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Modal thêm giờ chiếu -->
    <div class="modal fade" id="addTimeModal" tabindex="-1" aria-labelledby="addTimeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm giờ chiếu cho ngày <span id="selected-date"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="time" id="datetime-input" class="form-control" step="900" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="button" class="btn btn-primary" onclick="xacNhanThemGio()">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal tạo suất chiếu tự động -->
    <div class="modal fade" id="autoScheduleModal" tabindex="-1" aria-labelledby="autoScheduleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo tự động suất chiếu cho ngày <span id="auto-date"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="start-time" class="form-label">Giờ bắt đầu</label>
                        <input type="time" id="start-time" class="form-control" step="900" />
                    </div>
                    <div class="mb-3">
                        <label for="end-time" class="form-label">Giờ kết thúc</label>
                        <input type="time" id="end-time" class="form-control" step="900" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="button" class="btn btn-primary" onclick="taoSuatChieuTuDong()">Tạo suất chiếu</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0">Tạo suất chiếu hàng loạt ( Vui lòng chọn ngày chiếu trước mới chọn được phim
                                )</h5>
                            <a href="{{ route('suat-chieu.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('suat-chieu.store') }}" method="POST" id="bulkSuatChieuForm">
                            @csrf

                            <!-- Thông tin chung -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label>Khoảng thời gian bắt đầu (*) - kết thúc</label>
                                        <div class="row">
                                            <div class="col-6">
                                                <input type="date" class="form-control" id="start_date"
                                                    min="{{ date('Y-m-d') }}"
                                                    max="{{ date('Y-m-d', strtotime('+3 months')) }}" placeholder="Từ ngày">
                                            </div>
                                            <div class="col-6">

                                                <input type="date" class="form-control" id="end_date"
                                                    min="{{ date('Y-m-d') }}"
                                                    max="{{ date('Y-m-d', strtotime('+3 months')) }}"
                                                    placeholder="Đến ngày">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="ID_Rap">Rạp <span class="text-danger">*</span></label>
                                        <select name="ID_Rap" id="ID_Rap" class="form-control" required>
                                            <option value="">-- Chọn rạp --</option>
                                            @foreach ($raps as $rap)
                                                <option value="{{ $rap->ID_Rap }}">
                                                    ({{ $rap->TenRap }} : {{ $rap->DiaChi }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="ID_PhongChieu">Phòng chiếu <span class="text-danger">*</span></label>
                                        <select name="ID_PhongChieu" id="ID_PhongChieu" class="form-control" required
                                            disabled>
                                            <option value="">-- Chọn phòng chiếu --</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="ID_Phim">Phim <span class="text-danger">*</span></label>
                                        <select name="ID_Phim" id="ID_Phim" class="form-control" required>
                                            <option value="">-- Chọn phim --</option>
                                            @foreach ($phims as $phim)
                                                <option value="{{ $phim->ID_Phim }}"
                                                    data-duration="{{ $phim->ThoiLuong }}"
                                                    data-ngaykhoichieu="{{ $phim->NgayKhoiChieu }}">
                                                    {{ $phim->TenPhim }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="GiaVe">Giá vé (đ) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="GiaVe" name="GiaVe"
                                            value="45000" min="0" step="1000" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Chọn ngày và giờ chiếu -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Lịch chiếu chi tiết</h6>
                                    <small class="text-muted">Chọn ngày và giờ chiếu cho từng ngày</small>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="tinhSoLuongNgay()">
                                                Tạo từ khoảng thời gian
                                            </button>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-outline-warning btn-sm"
                                                onclick="clearToanBoNgay()">
                                                Xóa tất cả
                                            </button>
                                        </div>
                                    </div>

                                    <div id="schedule-container">
                                        <!-- Các ngày và giờ chiếu sẽ được thêm vào đây -->
                                    </div>

                                    <div class="text-center mt-3" id="empty-schedule" style="display: none;">
                                        <p class="text-muted">Chưa có lịch chiếu nào. Vui lòng thêm ngày chiếu.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg" onclick="kiemTraSuatChieuSom()"
                                    id="submitBtn" disabled>
                                    <i class="fas fa-save"></i> Tạo tất cả suất chiếu (<span id="total-count">0</span>)
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('backend/assets/js/suat-chieu.js') }}" defer></script>
    <script>
        function getPhong() {
            const selectedRap = document.getElementById("ID_Rap").value;
            if (!selectedRap) return;

            console.log("Gửi request lọc phòng :", selectedRap);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('suat-chieu.loc-phong') }}",
                method: 'POST',
                data: {
                    ID_Rap: selectedRap
                },
                success: function(data) {
                    let html = '<option value="">-- Chọn phòng --</option>';
                    data.forEach(phong => {
                        html +=
                            `<option value="${phong.ID_PhongChieu}">${phong.TenPhongChieu}</option>`;
                    });
                    $('#ID_PhongChieu').html(html).prop('disabled', false);
                },
                error: function(xhr) {
                    alert('Lỗi khi lọc phòng !');
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endsection
