@extends('admin.layouts.master')
@section('title', 'Chỉnh sửa suất chiếu')

@section('main')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Chỉnh sửa suất chiếu</h5>
                        <a href="{{ route('suat-chieu.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Alert for schedule conflicts -->
                        <div id="conflict-alert" class="alert alert-warning d-none">
                            <strong>Cảnh báo xung đột lịch chiếu!</strong>
                            <div id="conflict-message"></div>
                        </div>

                        <form action="{{ route('suat-chieu.update', $suatChieu->ID_SuatChieu) }}" method="POST"
                            id="suatChieuForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label for="NgayChieu">Ngày chiếu <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('NgayChieu') is-invalid @enderror"
                                    id="NgayChieu" name="NgayChieu" value="{{ old('NgayChieu', $suatChieu->NgayChieu) }}"
                                    min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+3 months')) }}" required>
                                @error('NgayChieu')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Chỉ có thể chỉnh sửa suất chiếu trong vòng 3 tháng
                                    tới</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="ID_Phim">Phim <span class="text-danger">*</span></label>
                                <select name="ID_Phim" id="ID_Phim"
                                    class="form-control @error('ID_Phim') is-invalid @enderror" required>
                                    <option value="">-- Chọn phim --</option>
                                    {{-- Option sẽ được load qua JS --}}
                                </select>
                                @error('ID_Phim')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Chỉ hiển thị phim còn trong thời gian chiếu</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="ID_Rap">Rạp <span class="text-danger">*</span></label>
                                        <select name="ID_Rap" id="ID_Rap" class="form-control" required>
                                            <option value="">-- Chọn rạp --</option>
                                            @foreach ($raps as $rap)
                                                <option
                                                    value="{{ $rap->ID_Rap }}"{{ $suatChieu->ID_Rap == $rap->ID_Rap ? 'selected' : '' }}>
                                                    {{ $rap->TenRap }} : {{ $rap->DiaChi }}</option>
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
                            <div class="form-group mb-3">
                                <label for="GioChieu">Giờ chiếu <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('GioChieu') is-invalid @enderror"
                                    id="GioChieu" name="GioChieu" value="{{ old('GioChieu', $suatChieu->GioChieu) }}"
                                    required>
                                @error('GioChieu')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <div id="time-suggestions" class="mt-2"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="GiaVe">Giá vé (đ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('GiaVe') is-invalid @enderror"
                                    id="GiaVe" name="GiaVe" value="{{ old('GiaVe', $suatChieu->GiaVe) }}"
                                    min="0" step="1000" required>
                                @error('GiaVe')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save"></i> Cập nhật suất chiếu
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-redo"></i> Làm mới
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let conflictCheckTimeout;
        const currentSuatChieuId = {{ $suatChieu->ID_SuatChieu }};

        document.getElementById('ID_Rap').addEventListener('change', function() {
            document.getElementById("ID_PhongChieu").disabled = false;
            getPhong();
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Khóa dropdown phim khi chưa chọn ngày chiếu
            getPhong()
            document.getElementById('ID_Phim').disabled = true;

            // Lấy các phần tử
            var phongChieuSelect = document.getElementById('ID_PhongChieu');
            var rapIdInput = document.getElementById('ID_Rap');
            var ngayChieu = document.getElementById("NgayChieu");
            var gioChieu = document.getElementById("GioChieu");
            var phimSelect = document.getElementById("ID_Phim");

            // Load phim khi có ngày chiếu
            if (ngayChieu.value) {
                document.getElementById("ID_Phim").disabled = false;
                getMovie();
            }

            // Gán lại ID_Rap khi đã chọn phòng
            if (phongChieuSelect.value) {
                var selectedOption = phongChieuSelect.options[phongChieuSelect.selectedIndex];
                var rapId = selectedOption.getAttribute('data-rap-id');
                rapIdInput.value = rapId;
            }

            // AJAX lấy danh sách phim khi chọn ngày chiếu
            ngayChieu.addEventListener('change', function() {
                const selectedDate = this.value;
                if (!selectedDate) return;
                getMovie();
            });

            // Kiểm tra xung đột khi thay đổi giờ chiếu hoặc phim
            gioChieu.addEventListener('change', checkLichTrinhXungDot);
            phimSelect.addEventListener('change', checkLichTrinhXungDot);
        });

        function getMovie() {
            const selectedDate = document.getElementById("NgayChieu").value;

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
                    const currentPhimId = {{ old('ID_Phim', $suatChieu->ID_Phim) }};

                    data.forEach(phim => {
                        const selected = phim.ID_Phim == currentPhimId ? 'selected' : '';
                        html +=
                            `<option value="${phim.ID_Phim}" data-duration="${phim.ThoiLuong || 120}" ${selected}>${phim.TenPhim}</option>`;
                    });
                    $('#ID_Phim').html(html).prop('disabled', false);

                    if (currentPhimId) {
                        checkLichTrinhXungDot();
                    }
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
                    ID_Rap: selectedRap
                },
                success: function(data) {
                    let html = '<option value="">-- Chọn phòng --</option>';
                    data.forEach(phong => {
                        const phongSelect = {{ old('ID_PhongChieu', $suatChieu->ID_PhongChieu) }};
                        html +=
                            `<option value="${phong.ID_PhongChieu}" ${ phong.ID_PhongChieu == phongSelect ? 'selected' : '' }>${phong.TenPhongChieu}</option>`;
                    });
                    $('#ID_PhongChieu').html(html).prop('disabled', false);
                },
                error: function(xhr) {
                    alert('Lỗi khi lọc phòng !');
                    console.error(xhr.responseText);
                }
            });
        }

        function checkLichTrinhXungDot() {
            const phongChieuId = document.getElementById('ID_PhongChieu').value;
            const ngayChieu = document.getElementById('NgayChieu').value;
            const gioChieu = document.getElementById('GioChieu').value;
            const phimId = document.getElementById('ID_Phim').value;

            // Clear previous timeout
            if (conflictCheckTimeout) {
                clearTimeout(conflictCheckTimeout);
            }

            // Debounce the check
            conflictCheckTimeout = setTimeout(() => {
                if (phongChieuId && ngayChieu && gioChieu && phimId) {
                    $.ajax({
                        url: "{{ route('suat-chieu.check-conflict') }}",
                        method: 'POST',
                        data: {
                            phong_chieu_id: phongChieuId,
                            ngay_chieu: ngayChieu,
                            gio_chieu: gioChieu,
                            phim_id: phimId,
                            exclude_id: currentSuatChieuId, // Loại trừ suất chiếu hiện tại
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            const alertDiv = document.getElementById('conflict-alert');
                            const messageDiv = document.getElementById('conflict-message');
                            const submitBtn = document.getElementById('submitBtn');

                            if (response.has_conflict) {
                                alertDiv.classList.remove('d-none');
                                messageDiv.innerHTML = `
                                    <p>Phòng chiếu đã có suất chiếu "<strong>${response.conflict_show.phim.TenPhim}</strong>" 
                                    từ <strong>${response.conflict_time}</strong></p>
                                    <p>Suất chiếu được sửa sẽ từ <strong>${response.new_time}</strong></p>
                                    <p>Vui lòng chọn giờ chiếu khác!</p>
                                `;
                                submitBtn.disabled = true;
                                submitBtn.classList.add('btn-secondary');
                                submitBtn.classList.remove('btn-primary');
                            } else {
                                alertDiv.classList.add('d-none');
                                submitBtn.disabled = false;
                                submitBtn.classList.remove('btn-secondary');
                                submitBtn.classList.add('btn-primary');

                                // Show suggested times
                                showTimeSuggestions();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error checking conflict:', xhr.responseText);
                        }
                    });
                } else {
                    // Hide conflict alert if not all fields are filled
                    document.getElementById('conflict-alert').classList.add('d-none');
                    document.getElementById('submitBtn').disabled = false;
                }
            }, 500);
        }

        function showTimeSuggestions() {
            // Placeholder for time suggestions functionality
            // This would be implemented similar to the create page if needed
            document.getElementById('time-suggestions').innerHTML = '';
        }

        function selectTime(time) {
            document.getElementById('GioChieu').value = time;
            checkLichTrinhXungDot();
        }

        function resetForm() {
            if (confirm('Bạn có chắc chắn muốn làm mới form? Tất cả thay đổi sẽ bị mất.')) {
                location.reload();
            }
        }
    </script>

    <style>
        .time-suggestion:hover {
            background-color: #007bff !important;
            color: white !important;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .text-danger {
            color: #dc3545 !important;
        }
    </style>
@endsection
