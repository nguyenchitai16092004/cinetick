@extends('backend.layouts.master')
@section('title', 'Tạo Phòng Chiếu')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/seat_layout.css') }}">
@endsection

@section('main')
    <div class="container mt-4">
        <h2 class="text-center text-primary fw-bold mb-4">Quản lý Phòng Chiếu</h2>
        <div class="row">
            {{-- Cột sơ đồ ghế --}}
            <div class="col-md-7 mb-4">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-primary mb-3">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Sơ đồ ghế ngồi
                        </h5>
                        <div id="seatLayout" class="seat-container">
                            <div class="placeholder-text text-muted text-center py-5">
                                Chọn thông tin phòng để tạo sơ đồ ghế
                            </div>
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
                            <small id="seatCount" class="text-muted">Số ghế đã chọn: 0</small>
                            <button id="selectAllBtn" onclick="selectAllSeats()" type="button"
                                class="btn btn-outline-primary btn-sm d-none">
                                <i class="bi bi-check-all me-1"></i> Chọn tất cả
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột tạo phòng --}}
            <div class="col-md-5">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-success mb-3">
                            <i class="bi bi-plus-square me-1"></i> Tạo Phòng Chiếu
                        </h5>
                        <form id="roomForm" method="POST" action="{{ route('phong-chieu.store') }}">
                            @csrf

                            {{-- Tên phòng --}}
                            <div class="mb-3">
                                <label class="form-label">Tên phòng chiếu</label>
                                <input type="text" class="form-control form-control-sm" name="roomName"
                                    value="{{ old('roomName') }}" required>
                                @error('roomName')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Rạp --}}
                            <div class="mb-3">
                                <label class="form-label">Rạp chiếu</label>
                                <select class="form-select form-select-sm" name="ID_Rap" required>
                                    <option value="" disabled selected>Chọn rạp</option>
                                    @foreach ($raps as $rap)
                                        <option value="{{ $rap->ID_Rap }}"
                                            {{ old('ID_Rap') == $rap->ID_Rap ? 'selected' : '' }}>
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
                                    <option value="" disabled selected>Chọn loại phòng</option>
                                    <option value="0" {{ old('LoaiPhong') == '0' ? 'selected' : '' }}>
                                        Phòng thường
                                    </option>
                                    <option value="1" {{ old('LoaiPhong') == '1' ? 'selected' : '' }}>
                                        Phòng VIP
                                    </option>
                                </select>
                                @error('LoaiPhong')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                {{-- Số hàng ghế --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số hàng ghế</label>
                                    <select class="form-select form-select-sm" id="rowCount" name="rowCount" required>
                                        <option value="" disabled selected>Chọn số hàng</option>
                                        @for ($i = 5; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('rowCount') == $i ? 'selected' : '' }}>{{ $i }}</option>
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
                                        <option value="" disabled selected>Chọn số ghế</option>
                                        @foreach ([6, 7, 8, 9, 10] as $col)
                                            <option value="{{ $col }}"
                                                {{ old('colCount') == $col ? 'selected' : '' }}>{{ $col }}
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
                                    <option disabled>Chọn số hàng trước</option>
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
                                    <option disabled>Chọn số cột trước</option>
                                </select>
                                <small class="text-muted">Giữ Ctrl để chọn nhiều cột</small>
                                @error('colAisles')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Hidden input cho sơ đồ ghế --}}
                            <input type="hidden" name="seatLayout" id="seatLayoutInput" value="{{ old('seatLayout') }}">

                            {{-- Hiển thị lỗi --}}
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            {{-- Nút --}}
                            <div class="d-flex justify-content-center gap-2 mt-4">
                                <button type="button" id="previewBtn" onclick="previewSeats()"
                                    class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i> Xem sơ đồ
                                </button>
                                <button type="submit" id="submitBtn" class="btn btn-success btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Tạo phòng
                                </button>
                                <button type="button" id="clearAislesBtn" onclick="clearAisles()"
                                    class="btn btn-outline-danger btn-sm" style="display: none;">
                                    <i class="bi bi-x-circle me-1"></i> Hủy đường
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Hướng dẫn sử dụng --}}
                <div class="card shadow rounded-4 mt-3">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center text-info mb-3">
                            <i class="bi bi-info-circle me-1"></i> Hướng dẫn
                        </h5>
                        <ul class="small mb-0 ps-3">
                            <li>Nhập thông tin phòng chiếu và click <strong>Xem sơ đồ</strong> để tạo mẫu</li>
                            <li>Click vào ghế để chuyển đổi: <strong>Thường → VIP → Không hoạt động</strong></li>
                            <li>Thêm đường đi (lối đi) bằng cách chọn vị trí trong danh sách</li>
                            <li>Phòng thường: Các ghế cơ bản, Phòng VIP: Các ghế cao cấp hơn</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('backend/assets/js/seat.js') }}" defer></script>
@endsection