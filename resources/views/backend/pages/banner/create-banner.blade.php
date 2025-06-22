@extends('backend.layouts.master')
@section('title', 'Tạo Banner - 2TCINEMA')

@section('main')
    <section class="h-full overflow-y-auto px-6 py-8">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Tạo Banner Mới</h2>

            <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Hình ảnh -->
                <div class="mb-4">
                    <label for="HinhAnh" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hình
                        ảnh</label>
                    <input type="file" id="HinhAnh" name="HinhAnh" class="w-full">
                    @error('HinhAnh')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tiêu đề -->
                <div class="mb-4">
                    <label for="TieuDe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu
                        đề</label>
                    <input type="text" id="TieuDe" name="TieuDe"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white"
                        value="{{ old('TieuDe') }}">
                    @error('TieuDe')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Mô tả -->
                <div class="mb-4">
                    <label for="MoTa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mô
                        tả</label>
                    <textarea id="MoTa" name="MoTa" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white"
                        rows="3">{{ old('MoTa') }}</textarea>
                    @error('MoTa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Loại đường dẫn -->
                <div class="mb-4">
                    <label for="DuongDan" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Loại đường dẫn
                    </label>
                    <select name="DuongDan" id="DuongDan" onchange="loadDuLieuBangType()"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-800 dark:text-white">
                        <option value="">-- Chọn loại đường dẫn --</option>
                        <option value="/phim/chi-tiet-phim/"
                            {{ old('DuongDan') == '/phim/chi-tiet-phim/' ? 'selected' : '' }}>Chi tiết phim</option>
                        <option value="/goc-dien-anh/" {{ old('DuongDan') == '/goc-dien-anh/' ? 'selected' : '' }}>Chi tiết
                            tin tức</option>
                    </select>
                    @error('DuongDan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link cụ thể -->
                <div class="mb-4">
                    <label for="link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Liên kết (link cụ thể)
                    </label>
                    <select name="link" id="link"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-800 dark:text-white"
                        disabled>
                        <option value="">-- Chọn liên kết cụ thể --</option>
                    </select>
                    @error('link')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <a href="{{ route('cap-nhat-thong-tin.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded mr-2 hover:bg-gray-600">Quay lại</a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Tạo
                        mới</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('js')
    <script>
        function loadDuLieuBangType() {
            const selectedType = document.getElementById("DuongDan").value;
            const linkSelect = document.getElementById("link");

            if (!selectedType) {
                linkSelect.innerHTML = '<option value="">-- Chọn liên kết cụ thể --</option>';
                linkSelect.disabled = true;
                return;
            }

            console.log("Đang tải dữ liệu cho loại:", selectedType);

            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Show loading
            linkSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
            linkSelect.disabled = true;

            $.ajax({
                url: "{{ route('banner.get-data-by-type') }}",
                method: 'POST',
                data: {
                    type: selectedType
                },
                success: function(data) {
                    let html = '<option value="">-- Chọn liên kết cụ thể --</option>';

                    if (data.length > 0) {
                        data.forEach(item => {
                            html += `<option value="${item.slug}">${item.name}</option>`;
                        });
                        linkSelect.disabled = false;
                    } else {
                        html += '<option value="">-- Không có dữ liệu --</option>';
                    }

                    linkSelect.innerHTML = html;
                },
                error: function(xhr) {
                    console.error('Lỗi AJAX:', xhr.responseText);
                    linkSelect.innerHTML = '<option value="">-- Lỗi khi tải dữ liệu --</option>';
                    alert('Lỗi khi tải dữ liệu!');
                }
            });
        }

        // Load data on page load if there's old input
        document.addEventListener('DOMContentLoaded', function() {
            const duongDanSelect = document.getElementById('DuongDan');
            if (duongDanSelect.value) {
                loadDuLieuBangType();
            }
        });
    </script>
@endsection
