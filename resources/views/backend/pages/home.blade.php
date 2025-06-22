@extends('backend.layouts.master')
@section('title', 'Hello Admin - 2TCINEMA')
@section('main')
    <section class="h-full overflow-y-auto">
        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Dashboard
            </h2>
            <!-- CTA -->
            <a class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple"
                href="https://github.com/estevanmaito/windmill-dashboard">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    <span>Star this project on GitHub</span>
                </div>
                <span>View more &RightArrow;</span>
            </a>
            <!-- Cards -->
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                    <div
                        class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                            Total clients
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            6389
                        </p>
                    </div>
                </div>
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                    <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                            Account balance
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            $ 46,760.89
                        </p>
                    </div>
                </div>
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                    <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                            New sales
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            376
                        </p>
                    </div>
                </div>
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                    <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                            Pending contacts
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            35
                        </p>
                    </div>
                </div>
            </div>

            <!-- Banner Section -->
            <div class="flex justify-between items-center my-6">
                <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Danh sách Banner</h2>
                <a href="{{ route('banner.create') }}"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg shadow hover:bg-purple-700">
                    + Tạo Banner
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle shadow-sm">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 160px;">Hình ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Mô tả</th>
                            <th style="width: 250px;">Liên kết</th>
                            <th style="width: 140px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banners as $banner)
                            <tr>
                                <td class="text-center">
                                    <img src="{{ asset('storage/' . $banner->HinhAnh) }}" alt="Banner"
                                        class="img-thumbnail" style="max-height: 80px;">
                                </td>
                                <td class="fw-bold text-dark align-middle">
                                    {{ $banner->TieuDe }}
                                </td>
                                <td class="fw-bold text-dark align-middle">
                                    {{ $banner->MoTa     }}
                                </td>
                                <td class="text-truncate align-middle">
                                    <a href="{{ $banner->Link }}" class="text-decoration-none text-primary" target="_blank">
                                        {{ $banner->Link }}
                                    </a>
                                </td>
                                <td class="text-center align-middle">
                                    <form action="{{ route('banner.edit', $banner->id) }}" method="GET" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('banner.destroy', $banner->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa banner này không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Chưa có banner nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>



            <div class="container mt-4">
                <h2>Thông Tin Trang Web</h2>

                <form action="{{ route('thong-tin-trang-web.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <!-- Logo hiện tại -->
                    <div class="mb-3">
                        <label for="Logo" class="form-label">Logo hiện tại:</label><br>
                        @if ($thongTin && $thongTin->Logo)
                            <img src="{{ asset('storage/' . $thongTin->Logo) }}" alt="Logo" style="height: 100px;">
                        @else
                            <p>Chưa có logo</p>
                        @endif
                    </div>

                    <!-- Upload Logo mới -->
                    <div class="mb-3">
                        <label for="Logo" class="form-label">Logo mới (nếu có):</label>
                        <input type="file" class="form-control" name="Logo" id="Logo">
                        @error('Logo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tên đơn vị -->
                    <div class="mb-3">
                        <label for="TenDonVi" class="form-label">Tên đơn vị:</label>
                        <input type="text" class="form-control" name="TenDonVi" id="TenDonVi"
                            value="{{ old('TenDonVi', $thongTin->TenDonVi ?? '') }}">
                        @error('TenDonVi')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tên website -->
                    <div class="mb-3">
                        <label for="TenWebsite" class="form-label">Tên Website:</label>
                        <input type="text" class="form-control" name="TenWebsite" id="TenWebsite"
                            value="{{ old('TenWebsite', $thongTin->TenWebsite ?? '') }}">
                        @error('TenWebsite')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Zalo -->
                    <div class="mb-3">
                        <label for="Zalo" class="form-label">Zalo:</label>
                        <input type="text" class="form-control" name="Zalo" id="Zalo"
                            value="{{ old('Zalo', $thongTin->Zalo ?? '') }}">
                        @error('Zalo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hotline -->
                    <div class="mb-3">
                        <label for="Hotline" class="form-label">Hotline:</label>
                        <input type="text" class="form-control" name="Hotline" id="Hotline"
                            value="{{ old('Hotline', $thongTin->Hotline ?? '') }}">
                        @error('Hotline')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Facebook -->
                    <div class="mb-3">
                        <label for="Facebook" class="form-label">Facebook:</label>
                        <input type="text" class="form-control" name="Facebook" id="Facebook"
                            value="{{ old('Facebook', $thongTin->Facebook ?? '') }}">
                        @error('Facebook')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Instagram -->
                    <div class="mb-3">
                        <label for="Instagram" class="form-label">Instagram:</label>
                        <input type="text" class="form-control" name="Instagram" id="Instagram"
                            value="{{ old('Instagram', $thongTin->Instagram ?? '') }}">
                        @error('Instagram')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Youtube -->
                    <div class="mb-3">
                        <label for="Youtube" class="form-label">Youtube:</label>
                        <input type="text" class="form-control" name="Youtube" id="Youtube"
                            value="{{ old('Youtube', $thongTin->Youtube ?? '') }}">
                        @error('Youtube')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="Email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="Email" id="Email"
                            value="{{ old('Email', $thongTin->Email ?? '') }}">
                        @error('Email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Địa chỉ -->
                    <div class="mb-3">
                        <label for="DiaChi" class="form-label">Địa chỉ:</label>
                        <textarea class="form-control" name="DiaChi" id="DiaChi" rows="3">{{ old('DiaChi', $thongTin->DiaChi ?? '') }}</textarea>
                        @error('DiaChi')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </section>
@stop
