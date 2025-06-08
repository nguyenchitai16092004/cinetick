@extends('backend.layouts.master')
@section('title', 'Chi tiết Phòng Chiếu')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/seat_layout.css') }}">
@endsection

@section('main')
    <div class="container mt-4">
        <h2 class="text-center text-primary fw-bold mb-4">Chi tiết Phòng Chiếu</h2>
        <div class="row">
            {{-- Cột sơ đồ ghế --}}
            <div class="col-md-7 mb-4">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-primary mb-3">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Sơ đồ ghế ngồi
                        </h5>
                        <div id="seatLayout" class="seat-container">
                            @if (isset($seatLayout) && count($seatLayout) > 0)
                                {{-- Sơ đồ ghế được render bằng JavaScript --}}
                            @else
                                <div class="placeholder-text text-muted text-center py-5">
                                    Không có thông tin về sơ đồ ghế
                                </div>
                            @endif
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
                                <div class="legend-box legend-disabled"></div>
                                <span>Không hoạt động</span>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <small id="seatCount" class="text-muted">Số ghế: {{ $phongChieu->SoLuongGhe }}</small>
                            <button id="selectAllBtn" onclick="selectAllSeats()" type="button"
                                class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-check-all me-1"></i> Chọn tất cả
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột thông tin phòng --}}
            <div class="col-md-5">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-success mb-3">
                            <i class="bi bi-info-circle me-1"></i> Thông tin phòng chiếu
                        </h5>
                        <form id="roomForm" method="POST"
                            action="{{ route('phong-chieu.update', $phongChieu->ID_PhongChieu) }}">
                            @csrf
                            @method('PUT')

                            {{-- Tên phòng --}}
                            <div class="mb-3">
                                <label class="form-label">Tên phòng chiếu</label>
                                <input type="text" class="form-control form-control-sm" name="roomName"
                                    value="{{ $phongChieu->TenPhongChieu }}" required>
                                @error('roomName')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Rạp --}}
                            <div class="mb-3">
                                <label class="form-label">Rạp chiếu</label>
                                <select class="form-select form-select-sm" name="ID_Rap" required>
                                    <option value="" disabled>Chọn rạp</option>
                                    @foreach ($raps as $rap)
                                        <option value="{{ $rap->ID_Rap }}"
                                            {{ $phongChieu->ID_Rap == $rap->ID_Rap ? 'selected' : '' }}>
                                            {{ $rap->TenRap }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ID_Rap')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Loại phòng --}}
                            <div class="mb-3">
                                <label class="form-label">Loại phòng</label>
                                <select class="form-select form-select-sm" name="LoaiPhong" required>
                                    <option value="" disabled>Chọn loại phòng</option>
                                    <option value="0" {{ $phongChieu->LoaiPhong == 0 ? 'selected' : '' }}>Phòng thường</option>
                                    <option value="1" {{ $phongChieu->LoaiPhong == 1 ? 'selected' : '' }}>Phòng VIP</option>
                                </select>
                                @error('LoaiPhong')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Trạng thái --}}
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select form-select-sm" name="TrangThai" required>
                                    <option value="1" {{ $phongChieu->TrangThai == 1 ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ $phongChieu->TrangThai == 0 ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                                @error('TrangThai')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                {{-- Số hàng ghế --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số hàng ghế</label>
                                    <select class="form-select form-select-sm" id="rowCount" name="rowCount" required>
                                        <option value="" disabled>Chọn số hàng</option>
                                        @for ($i = 5; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $rowCount == $i ? 'selected' : '' }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('rowCount')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Số ghế mỗi hàng --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số ghế mỗi hàng</label>
                                    <select class="form-select form-select-sm" id="colCount" name="colCount" required>
                                        <option value="" disabled>Chọn số ghế</option>
                                        @foreach ([6, 8, 10, 12, 14, 16] as $col)
                                            <option value="{{ $col }}" {{ $colCount == $col ? 'selected' : '' }}>
                                                {{ $col }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('colCount')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Đường đi hàng --}}
                            <div class="mb-3">
                                <label class="form-label">Đường đi giữa các hàng</label>
                                <select class="form-select form-select-sm" id="rowAisles" name="rowAisles[]" multiple>
                                    @for ($i = 1; $i < $rowCount; $i++)
                                        <option value="{{ $i }}"
                                            {{ in_array($i, json_decode($phongChieu->HangLoiDi ?: '[]')) ? 'selected' : '' }}>
                                            Sau hàng {{ chr(64 + $i) }}
                                        </option>
                                    @endfor
                                </select>
                                <small class="text-muted">Giữ Ctrl để chọn nhiều hàng</small>
                                @error('rowAisles')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Đường đi cột --}}
                            <div class="mb-3">
                                <label class="form-label">Đường đi giữa các cột</label>
                                <select class="form-select form-select-sm" id="colAisles" name="colAisles[]" multiple>
                                    @for ($i = 1; $i < $colCount; $i++)
                                        <option value="{{ $i }}"
                                            {{ in_array($i, json_decode($phongChieu->CotLoiDi ?: '[]')) ? 'selected' : '' }}>
                                            Sau cột {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <small class="text-muted">Giữ Ctrl để chọn nhiều cột</small>
                                @error('colAisles')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nút xóa lối đi --}}
                            <div class="mb-3">
                                <button type="button" id="clearAislesBtn" onclick="clearAisles()" 
                                    class="btn btn-outline-warning btn-sm"
                                    style="display: {{ (json_decode($phongChieu->HangLoiDi ?: '[]')) ? 'inline-block' : 'none' }};">
                                    <i class="bi bi-eraser me-1"></i> Xóa lối đi
                                </button>
                            </div>

                            {{-- Hidden input cho sơ đồ ghế --}}
                            <input type="hidden" name="seatLayout" id="seatLayoutInput"
                                value="{{ json_encode($seatLayout) }}">

                            {{-- Hiển thị lỗi --}}
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            {{-- Hiển thị thành công --}}
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            {{-- Nút --}}
                            <div class="d-flex justify-content-center gap-2 mt-4">
                                <button type="button" id="previewBtn" onclick="previewSeats()"
                                    class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i> Xem sơ đồ
                                </button>
                                <button type="submit" id="submitBtn" class="btn btn-success btn-sm">
                                    <i class="bi bi-save me-1"></i> Cập nhật phòng
                                </button>
                                <a href="{{ route('phong-chieu.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Khởi tạo dữ liệu từ server
        let seats = @json($seatLayout);
        let rowAisles = @json(json_decode($phongChieu->HangLoiDi ?: '[]'));
        let colAisles = @json(json_decode($phongChieu->CotLoiDi ?: '[]'));
        let seatCount = {{ $phongChieu->SoLuongGhe }};

        // Enhanced Room Management JavaScript
        // Supports both creation and detail views

        // Khởi tạo biến toàn cục nếu chưa có
        seats = seats || [];
        rowAisles = rowAisles || [];
        colAisles = colAisles || [];
        seatCount = seatCount || 0;
        let isDetailView = true; // Đặt true cho trang detail
        
        console.log('Detail view initialized with:', {
            seats: seats,
            rowAisles: rowAisles,
            colAisles: colAisles,
            seatCount: seatCount,
            isDetailView: isDetailView
        });
    </script>
    <script src="{{ asset('backend/assets/js/seat.js') }}" defer></script>
@endsection