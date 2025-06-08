@extends('backend.layouts.master')
@section('title', isset($rap[0]) ? 'Chỉnh sửa Rạp' : 'Thêm mới Rạp')

@section('main')
<section class="px-6 py-10 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
        <h2 class="text-3xl font-semibold mb-8 text-gray-800 dark:text-white flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            {{ isset($rap[0]) ? 'Chỉnh sửa Rạp' : 'Thêm mới Rạp' }}
        </h2>

        <form action="{{ isset($rap[0]) ? route('rap.update', $rap[0]->ID_Rap) : route('rap.store') }}" method="POST">
            @csrf
            @if(isset($rap[0]))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên Rạp</label>
                    <input type="text" name="TenRap" value="{{ old('TenRap', $rap[0]->TenRap ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Địa Chỉ</label>
                    <input type="text" name="DiaChi" value="{{ old('DiaChi', $rap[0]->DiaChi ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng Thái</label>
                    <select name="TrangThai"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="1" {{ (old('TrangThai', $rap[0]->TrangThai ?? '') == '1') ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ (old('TrangThai', $rap[0]->TrangThai ?? '') == '0') ? 'selected' : '' }}>Bảo trì</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('rap.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow-sm dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                    Quay lại
                </a>

                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                    {{ isset($rap[0]) ? 'Cập nhật' : 'Thêm mới' }}
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
