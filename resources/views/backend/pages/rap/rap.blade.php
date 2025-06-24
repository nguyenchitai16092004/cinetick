@extends('backend.layouts.master')
@section('title', 'Quản lý Rạp')
@section('main')

    <section class="h-full overflow-y-auto">
        <div class="container px-6 mx-auto">
            <!-- Tiêu đề và nút thêm -->
            <div class="flex justify-between items-center py-6">
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Danh sách Rạp</h2>
                <a href="{{ route('rap.create') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none">
                    Thêm mới
                </a>
            </div>

            <!-- Bảng hiển thị danh sách rạp -->
            <div class="w-full overflow-hidden rounded-lg shadow-xs">
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Tên Rạp</th>
                                <th class="px-4 py-3">Hình ảnh</th>
                                <th class="px-4 py-3">Hotline</th>
                                <th class="px-4 py-3">Địa Chỉ</th>
                                <th class="px-4 py-3">Trạng Thái</th>
                                <th class="px-4 py-3">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @forelse ($raps as $rap)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $rap->TenRap }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200"><img
                                            src="{{ $rap->HinhAnh ? asset('storage/' . $rap->HinhAnh) : asset('images/no-image.jpg') }}"
                                            width="80" class="img-thumbnail" alt="{{ $rap->TenRap }}"></td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $rap->Hotline }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $rap->DiaChi }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        @switch($rap->TrangThai)
                                            @case(1)
                                                Hoạt động
                                            @break

                                            @default
                                                Bảo trì
                                        @endswitch
                                    </td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('rap.edit', $rap->ID_Rap) }}" method="GET"
                                            class="inline-block" style="background: yellow">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1 text-sm font-medium bg-yellow-500 rounded hover:bg-yellow-600">
                                                Sửa
                                            </button>
                                        </form>

                                        <!-- Nút Xóa (KHÔNG gửi form trực tiếp) -->
                                        <button type="button"
                                            class="px-3 py-1 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700"
                                            onclick="moModalXoa({{ $rap->ID_Rap }}, '{{ $rap->TenRap }}')">
                                            Xoá
                                        </button>

                                        <!-- Form xóa ẩn -->
                                        <form id="form-xoa-{{ $rap->ID_Rap }}"
                                            action="{{ route('rap.destroy', $rap->ID_Rap) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                            Không có dữ liệu.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal xác nhận xoá -->
        <div class="modal fade" id="modalXacNhanXoa" tabindex="-1" aria-labelledby="modalXoaLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalXoaLabel">Xác nhận xoá rạp</h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xoá rạp <strong id="tenRapModal"></strong>?</p>
                        <p class="text-danger">Nút xoá sẽ kích hoạt sau <span id="demNguoc">5</span> giây.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="button" class="btn btn-danger" id="btnXacNhanXoa" disabled>Xoá</button>
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @section('js')
        <script>
            let idRapCanXoa = null;
            let timeoutXoa = null;
            let intervalDemNguoc = null;

            function moModalXoa(id, tenRap) {
                idRapCanXoa = id;
                document.getElementById('tenRapModal').textContent = tenRap;
                document.getElementById('btnXacNhanXoa').disabled = true;

                let giay = 5;
                document.getElementById('demNguoc').textContent = giay;

                intervalDemNguoc = setInterval(() => {
                    giay--;
                    document.getElementById('demNguoc').textContent = giay;
                    if (giay <= 0) {
                        clearInterval(intervalDemNguoc);
                        document.getElementById('btnXacNhanXoa').disabled = false;
                    }
                }, 1000);

                const modal = new bootstrap.Modal(document.getElementById('modalXacNhanXoa'));
                modal.show();
            }

            document.getElementById('btnXacNhanXoa').addEventListener('click', function() {
                if (idRapCanXoa) {
                    document.getElementById('form-xoa-' + idRapCanXoa).submit();
                }
            });
        </script>
    @endsection
