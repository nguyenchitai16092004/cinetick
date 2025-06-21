@extends('backend.layouts.master')
@section('title', 'Sửa Banner - 2TCINEMA')

@section('main')
    <section class="h-full overflow-y-auto px-6 py-8">
        <div class="max-w-6xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Sửa Banner</h2>

            <form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-4 gap-6">
                    <!-- Ảnh hiện tại -->
                    <div class="col-span-12 md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hình ảnh hiện
                            tại:</label>
                        <img src="{{ asset('storage/' . $banner->HinhAnh) }}"
                            class="rounded-lg shadow aspect-[2/3] object-cover border dark:border-gray-600 mx-auto"
                            alt="Banner hiện tại" style="width: 400px">
                    </div>

                    <!-- Form chỉnh sửa -->
                    <div class="col-span-12 md:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Tiêu đề -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề *</label>
                            <input type="text" name="TieuDe" value="{{ old('TieuDe', $banner->TieuDe) }}"
                                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
                            @error('TieuDe')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tiêu đề -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link *</label>
                            <input type="text" name="" value="{{ old('', $banner->Link) }}" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white" readonly>
                            @error('')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Loại đường dẫn -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại đường dẫn
                                *</label>
                            <select name="DuongDan" id="DuongDan" onchange="loadDuLieuBangType()"
                                class="w-full border rounded-md dark:bg-gray-800 dark:text-white">
                                <option value="">-- Chọn loại --</option>
                                <option value="/phim/chi-tiet-phim/"
                                    {{ old('DuongDan', $currentDuongDan ?? '') == '/phim/chi-tiet-phim/' ? 'selected' : '' }}>
                                    Chi tiết phim</option>
                                <option value="/goc-dien-anh/"
                                    {{ old('DuongDan', $currentDuongDan ?? '') == '/goc-dien-anh/' ? 'selected' : '' }}>Chi
                                    tiết tin tức</option>
                            </select>
                            @error('DuongDan')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Liên kết -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link cụ thể
                                *</label>
                            <select name="link" id="link"
                                class="w-full border rounded-md dark:bg-gray-800 dark:text-white" disabled>
                                <option value="">-- Chọn liên kết cụ thể --</option>
                                @if (isset($currentLink))
                                    <option value="{{ $currentLink }}" selected>{{ $currentLinkName ?? $currentLink }}
                                    </option>
                                @endif
                            </select>
                            @error('link')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hình ảnh mới -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chọn hình ảnh
                                mới</label>
                            <input type="file" name="HinhAnh" class="w-full">
                            <p class="text-xs text-gray-500 mt-1">Chọn file mới (để trống nếu không thay đổi)</p>
                            @error('HinhAnh')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end mt-6">
                    <a href="{{ route('cap-nhat-thong-tin.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded mr-2 hover:bg-gray-600">Quay lại</a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Cập
                        nhật</button>
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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
                    const currentLink = "{{ old('link', $currentLink ?? '') }}";

                    if (data.length > 0) {
                        data.forEach(item => {
                            const selected = (item.slug === currentLink) ? 'selected' : '';
                            html += `<option value="${item.slug}" ${selected}>${item.name}</option>`;
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

        document.addEventListener('DOMContentLoaded', function() {
            const duongDanSelect = document.getElementById('DuongDan');
            if (duongDanSelect.value) {
                loadDuLieuBangType();
            }
        });
    </script>
@endsection
