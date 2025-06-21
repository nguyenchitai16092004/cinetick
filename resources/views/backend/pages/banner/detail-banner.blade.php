@extends('backend.layouts.master')
@section('title', 'Sửa Banner - 2TCINEMA')

@section('main')
<section class="h-full overflow-y-auto px-6 py-8">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Sửa Banner</h2>

        <form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tiêu đề -->
            <div class="mb-4">
                <label for="TieuDe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề</label>
                <input type="text" id="TieuDe" name="TieuDe"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white"
                    value="{{ old('TieuDe', $banner->TieuDe) }}">
                @error('TieuDe')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hình ảnh hiện tại -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hình ảnh hiện tại:</label>
                <img src="{{ asset('storage/' . $banner->HinhAnh) }}" class="h-40 rounded" alt="Current Banner">
            </div>

            <!-- Upload hình ảnh mới -->
            <div class="mb-4">
                <label for="HinhAnh" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hình ảnh mới (nếu muốn thay):</label>
                <input type="file" id="HinhAnh" name="HinhAnh" class="w-full">
                @error('HinhAnh')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Link -->
            <div class="mb-4">
                <label for="link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Liên kết (link)</label>
                <input type="text" id="link" name="link"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white"
                    value="{{ old('link', $banner->link) }}">
                @error('link')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <a href="{{ route('dashboard.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded mr-2 hover:bg-gray-600">Quay lại</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Cập nhật</button>
            </div>
        </form>
    </div>
</section>
@endsection
