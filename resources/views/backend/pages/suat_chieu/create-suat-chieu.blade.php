@extends('backend.layouts.master')
@section('title', 'Tạo suất chiếu hàng loạt')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/suat-chieu.css') }}">
@endsection

@section('main')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0">Tạo suất chiếu hàng loạt ( Vui lòng chọn ngày chiếu trước mới chọn được phim )</h5>
                            <a href="{{ route('suat-chieu.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('suat-chieu.store') }}" method="POST" id="bulkSuatChieuForm">
                            @csrf

                            <!-- Thông tin chung -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="ID_Phim">Phim <span class="text-danger">*</span></label>
                                        <select name="ID_Phim" id="ID_Phim" class="form-control" required>
                                            <option value="">-- Chọn phim --</option>
                                        </select>
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
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="GiaVe">Giá vé (VNĐ) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="GiaVe" name="GiaVe"
                                            value="45000" min="0" step="1000" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="ID_PhongChieu">Phòng chiếu <span class="text-danger">*</span></label>
                                        <select name="ID_PhongChieu" id="ID_PhongChieu" class="form-control" required disabled>
                                            <option value="">-- Chọn phòng chiếu --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label>Khoảng thời gian</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="date" class="form-control" id="start_date"
                                                min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+3 months')) }}"
                                                placeholder="Từ ngày">
                                        </div>
                                        <div class="col-6">
                                            <input type="date" class="form-control" id="end_date"
                                                min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+3 months')) }}"
                                                placeholder="Đến ngày">
                                        </div>
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

                            <!-- Xung đột và tổng kết -->
                            <div id="conflict-summary" class="alert alert-warning d-none">
                                <strong>Cảnh báo xung đột lịch chiếu!</strong>
                                <div id="conflict-list"></div>
                            </div>
                            <div id="schedule-summary" class="alert alert-info d-none">
                                <strong>Tổng kết:</strong>
                                <div id="summary-content"></div>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
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
        function getMovie() {
            const selectedDate = document.getElementById("start_date").value;
            if (!selectedDate) return;

            console.log("Gửi request lọc phim cho ngày:", selectedDate);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('suat-chieu.loc-phim-theo-ngay') }}",
                method: 'POST',
                data: {
                    date: selectedDate
                },
                success: function(data) {
                    let html = '<option value="">-- Chọn phim --</option>';
                    data.forEach(phim => {
                        html +=
                            `<option value="${phim.ID_Phim}" data-duration="${phim.ThoiLuong || 120}">${phim.TenPhim}</option>`;
                    });
                    $('#ID_Phim').html(html).prop('disabled', false);
                },
                error: function(xhr) {
                    alert('Lỗi khi lọc phim!');
                    console.error(xhr.responseText);
                }
            });
        }

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
                    id_rap : selectedRap
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
