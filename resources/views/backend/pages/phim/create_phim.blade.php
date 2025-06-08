@extends('backend.layouts.master')
@section('title', 'Tạo Phim')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thêm phim mới</h3>
                        <div class="card-tools">
                            <a href="{{ route('phim.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('phim.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                {{-- Cột trái --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="TenPhim">Tên phim <span class="text-danger">*</span></label>
                                        <input type="text" name="TenPhim" id="TenPhim" class="form-control"
                                            value="{{ old('TenPhim') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="ID_TheLoaiPhim">Thể loại phim <span class="text-danger">*</span></label>
                                        <select name="ID_TheLoaiPhim[]" id="ID_TheLoaiPhim" class="form-control" multiple
                                            required>
                                            @foreach ($theLoais as $theLoai)
                                                <option value="{{ $theLoai->ID_TheLoaiPhim }}"
                                                    {{ collect(old('ID_TheLoaiPhim'))->contains($theLoai->ID_TheLoaiPhim) ? 'selected' : '' }}>
                                                    {{ $theLoai->TenTheLoai }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="ThoiLuong">Thời lượng (phút) <span class="text-danger">*</span></label>
                                        <input type="number" name="ThoiLuong" id="ThoiLuong" class="form-control"
                                            value="{{ old('ThoiLuong') }}" required min="1">
                                    </div>

                                    <div class="form-group">
                                        <label for="NgayKhoiChieu">Ngày khởi chiếu <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="NgayKhoiChieu" id="NgayKhoiChieu" class="form-control"
                                            value="{{ old('NgayKhoiChieu') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="NgayKetThuc">Ngày kết thúc</label>
                                        <input type="date" name="NgayKetThuc" id="NgayKetThuc" class="form-control"
                                            value="{{ old('NgayKetThuc') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="Trailer">Trailer URL</label>
                                        <input type="url" name="Trailer" id="Trailer" class="form-control"
                                            value="{{ old('Trailer') }}">
                                    </div>
                                </div>

                                {{-- Cột phải --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="DaoDien">Đạo diễn <span class="text-danger">*</span></label>
                                        <input type="text" name="DaoDien" id="DaoDien" class="form-control"
                                            value="{{ old('DaoDien') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="DienVien">Diễn viên <span class="text-danger">*</span></label>
                                        <input type="text" name="DienVien" id="DienVien" class="form-control"
                                            value="{{ old('DienVien') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="DoHoa">Đồ họa <span class="text-danger">*</span></label>
                                        <select name="DoHoa" id="DoHoa" class="form-control" required>
                                            <option value="">-- Chọn đồ họa --</option>
                                            <option value="2D" {{ old('DoHoa') == '2D' ? 'selected' : '' }}>2D</option>
                                            <option value="3D" {{ old('DoHoa') == '3D' ? 'selected' : '' }}>3D</option>
                                        </select>
                                    </div>

                                    <label for="QuocGia">Quốc gia <span class="text-danger">*</span></label>
                                    <select name="QuocGia" id="QuocGia" class="form-control" required>
                                        <option value="">-- Chọn quốc gia --</option>
                                        <option value="Việt Nam">Việt Nam</option>
                                        <option value="Hoa Kỳ">Hoa Kỳ</option>
                                        <option value="Tây Ban Nha">Tây Ban Nha</option>
                                        <option value="Pháp">Pháp</option>
                                        <option value="Đức">Đức</option>
                                        <option value="Ý">Ý</option>
                                        <option value="Nhật Bản">Nhật Bản</option>
                                        <option value="Hàn Quốc">Hàn Quốc</option>
                                        <option value="Trung Quốc">Trung Quốc</option>
                                        <option value="Nga">Nga</option>
                                    </select>

                                    <div class="form-group">
                                        <label for="DoTuoi">Độ tuổi <span class="text-danger">*</span></label>
                                        <select name="DoTuoi" id="DoTuoi" class="form-control" required>
                                            <option value="Loại P">Loại P - Cho mọi lứa tuổi</option>
                                            <option value="Loại K">Loại K - Dưới 13 tuổi (cần giám hộ)</option>
                                            <option value="Loại T13 (13+)">Loại T13 (13+)</option>
                                            <option value="Loại T16 (16+)">Loại T16 (16+)</option>
                                            <option value="Loại T18 (18+)">Loại T18 (18+)</option>
                                            <option value="Loại C">Loại C - Không được phổ biến</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="HinhAnh">Hình ảnh</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="HinhAnh"
                                                name="HinhAnh" accept="image/*">
                                            <label class="custom-file-label" for="HinhAnh">Chọn ảnh</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Full width --}}
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="MoTaPhim">Mô tả phim <span class="text-danger">*</span></label>
                                        <textarea name="MoTaPhim" id="MoTaPhim" class="form-control" rows="5" required>{{ old('MoTaPhim') }}</textarea>
                                    </div>

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Lưu phim
                                        </button>
                                    </div>
                                </div>
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
        $(document).ready(function() {
            // Hiển thị tên file khi chọn ảnh
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            // Thêm CKEDITOR cho phần mô tả phim
            if (typeof CKEDITOR !== 'undefined') {
                CKEDITOR.replace('MoTaPhim');
            }
        });
    </script>
@endsection
