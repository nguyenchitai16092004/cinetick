@extends('backend.layouts.master')
@section('title', 'Quản lý Rạp')
@section('main')

    <section class="h-full overflow-y-auto">
        <div class="container px-6 mx-auto">
            <!-- Tiêu đề và nút thêm -->
            <div class="flex justify-between items-center py-6">
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Danh sách Rạp</h2>
                <a href="{{ route('rap.create') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
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
                                <th class="px-4 py-3">Địa Chỉ</th>
                                <th class="px-4 py-3">Trạng Thái</th>
                                <th class="px-4 py-3">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($raps as $rap)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $rap->TenRap }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $rap->DiaChi }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                        @if ($rap->TrangThai == 0)
                                            Bảo trì
                                        @elseif ($rap->TrangThai == 1)
                                            Hoạt động
                                        @else
                                            Bảo trì
                                        @endif
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

                                        <form action="{{ route('rap.destroy', $rap->ID_Rap) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                                Xoá
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($raps->isEmpty())
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                        Không có dữ liệu.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection
