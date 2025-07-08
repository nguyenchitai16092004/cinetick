@extends('admin.layouts.master')
@section('title', isset($rap[0]) ? 'Chỉnh sửa Rạp' : 'Thêm mới Rạp')

@section('css')
    <style>
        :root {
            --main-purple: rgb(111, 66, 193);
            --purple-light: rgba(111, 66, 193, 0.1);
            --purple-hover: rgb(95, 56, 165);
            --purple-gradient: linear-gradient(135deg, rgb(111, 66, 193), rgb(95, 56, 165));
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', sans-serif;
        }

        .main-container {
            min-height: 100vh;
            padding: 2rem 0;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--purple-gradient);
        }

        .card-header {
            background: var(--purple-gradient);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }
        }

        .card-title {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .card-subtitle {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .form-body {
            padding: 2.5rem;
        }

        .form-group {
            margin-bottom: 1.75rem;
            position: relative;
        }

        .form-label {
            color: var(--main-purple);
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label i {
            font-size: 1rem;
        }

        .required {
            color: #e74c3c;
            margin-left: 0.25rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--main-purple);
            box-shadow: 0 0 0 0.2rem var(--purple-light);
            background: white;
            transform: translateY(-1px);
        }

        .form-control:hover,
        .form-select:hover {
            border-color: var(--main-purple);
            background: white;
        }

        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            background: #fafbfc;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6b7280;
            font-weight: 500;
        }

        .file-label:hover {
            border-color: var(--main-purple);
            background: var(--purple-light);
            color: var(--main-purple);
        }

        .file-label.has-file {
            border-color: var(--main-purple);
            background: var(--purple-light);
            color: var(--main-purple);
        }

        .file-info {
            font-size: 0.85rem;
            margin-top: 0.5rem;
            color: #6b7280;
        }

        .image-preview {
            margin-top: 1rem;
            text-align: center;
        }

        .preview-img {
            max-width: 250px;
            max-height: 150px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .preview-img:hover {
            transform: scale(1.05);
        }

        .status-select {
            position: relative;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn {
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-back {
            background: #f8fafc;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #475569;
            transform: translateY(-1px);
        }

        .btn-primary {
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(111, 66, 193, 0.4);
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-element {
            position: absolute;
            background: var(--purple-light);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 70%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            width: 40px;
            height: 40px;
            top: 40%;
            right: 10%;
            animation-delay: 4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }

            .form-body {
                padding: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('main')
    <div class="main-container">
        <div class="floating-elements">
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7">
                    <div class="form-card">
                        <div class="card-header">
                            <h1 class="card-title">
                                <i class="fas {{ isset($rap[0]) ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                                {{ isset($rap[0]) ? 'Chỉnh sửa Rạp' : 'Thêm mới Rạp' }}
                            </h1>
                            <p class="card-subtitle">
                                {{ isset($rap[0]) ? 'Cập nhật thông tin rạp chiếu phim' : 'Tạo rạp chiếu phim mới' }}
                            </p>
                        </div>

                        <div class="form-body">
                            @if (isset($rap[0]) && $rap[0]->HinhAnh)
                                <div class="image-preview">
                                    <img src="{{ asset('storage/' . $rap[0]->HinhAnh) }}" alt="Ảnh rạp hiện tại"
                                        class="preview-img" id="current-image">
                                    <div class="file-info">Ảnh hiện tại</div>
                                </div>
                            @endif
                            <form action="{{ isset($rap[0]) ? route('rap.update', $rap[0]->ID_Rap) : route('rap.store') }}"
                                method="POST" enctype="multipart/form-data" id="cinemaForm">
                                @csrf
                                @if (isset($rap[0]))
                                    @method('PUT')
                                @endif

                                <div class="form-group">
                                    <label for="TenRap" class="form-label">
                                        <i class="fas fa-film"></i>
                                        Tên Rạp
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="TenRap" name="TenRap"
                                        value="{{ old('TenRap', $rap[0]->TenRap ?? '') }}"
                                        placeholder="Nhập tên rạp chiếu phim" required>
                                    @error('TenRap')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="HinhAnh" class="form-label">
                                        <i class="fas fa-image"></i>
                                        Hình ảnh
                                        <span class="required">*</span>
                                    </label>
                                    <div class="file-upload">
                                        <input type="file" class="file-input" id="HinhAnh" name="HinhAnh"
                                            accept="image/*" onchange="previewImage(this)">
                                        <label for="HinhAnh" class="file-label" id="file-label">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span id="file-text">Chọn hình ảnh rạp</span>
                                        </label>
                                    </div>
                                    <div class="file-info">
                                        Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB
                                    </div>
                                    @error('HinhAnh')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="image-preview" id="new-image-preview" style="display: none;">
                                        <img id="preview-img" class="preview-img" alt="Xem trước">
                                        <div class="file-info">Ảnh mới</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="DiaChi" class="form-label">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Địa chỉ
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="DiaChi" name="DiaChi"
                                        value="{{ old('DiaChi', $rap[0]->DiaChi ?? '') }}" placeholder="Nhập địa chỉ rạp"
                                        required>
                                    @error('DiaChi')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="MoTa" class="form-label">
                                        <i class="fas fa-align-left"></i>
                                        Mô tả
                                        <span class="required">*</span>
                                    </label>
                                    <textarea class="form-control" id="MoTa" name="MoTa" rows="3" placeholder="Nhập mô tả về rạp" required>{{ old('MoTa', $rap[0]->MoTa ?? '') }}</textarea>
                                    @error('MoTa')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Hotline" class="form-label">
                                        <i class="fas fa-phone"></i>
                                        Hotline
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="Hotline" id="Hotline"
                                        value="{{ old('Hotline', $rap[0]->Hotline ?? '') }}" placeholder="Nhập số hotline"
                                        required>
                                    @error('Hotline')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="TrangThai" class="form-label">
                                        <i class="fas fa-toggle-on"></i>
                                        Trạng thái
                                    </label>
                                    <div class="status-select">
                                        <select class="form-select" id="TrangThai" name="TrangThai"
                                            onchange="updateStatusBadge()">
                                            <option value="1"
                                                {{ old('TrangThai', $rap[0]->TrangThai ?? '1') == '1' ? 'selected' : '' }}>
                                                Hoạt động
                                            </option>
                                            <option value="0"
                                                {{ old('TrangThai', $rap[0]->TrangThai ?? '1') == '0' ? 'selected' : '' }}>
                                                Không hoạt động
                                            </option>
                                        </select>
                                        <div class="status-badge" id="status-badge">
                                            <i class="fas fa-circle"></i>
                                            <span id="status-text">Hoạt động</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="action-buttons">
                                    <a href="{{ route('rap.index') }}" class="btn btn-back">
                                        <i class="fas fa-arrow-left"></i>
                                        Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas {{ isset($rap[0]) ? 'fa-save' : 'fa-plus' }}"></i>
                                        {{ isset($rap[0]) ? 'Cập nhật' : 'Thêm mới' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function previewImage(input) {
            const file = input.files[0];
            const label = document.getElementById('file-label');
            const text = document.getElementById('file-text');
            const preview = document.getElementById('new-image-preview');
            const previewImg = document.getElementById('preview-img');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);

                label.classList.add('has-file');
                text.textContent = file.name;
            } else {
                preview.style.display = 'none';
                label.classList.remove('has-file');
                text.textContent = 'Chọn hình ảnh rạp';
            }
        }

        function updateStatusBadge() {
            const select = document.getElementById('TrangThai');
            const badge = document.getElementById('status-badge');
            const text = document.getElementById('status-text');

            if (select.value === '1') {
                badge.className = 'status-badge status-active';
                text.textContent = 'Hoạt động';
            } else {
                badge.className = 'status-badge status-inactive';
                text.textContent = 'Không hoạt động';
            }
        }

        // Initialize status badge on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateStatusBadge();
        });

        // Form validation
        document.getElementById('cinemaForm').addEventListener('submit', function(e) {
            const requiredFields = ['TenRap', 'DiaChi', 'MoTa', 'Hotline'];
            let isValid = true;

            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    isValid = false;
                    input.focus();
                    return false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
            }
        });
    </script>
@endsection
