@extends('admin.layouts.master')
@section('title', 'Quản lý bình luận')

@section('main')
    <style>
        .btn-purple {
            background-color: #6f42c1;
            color: white;
        }

        .btn-purple:hover {
            background-color: #5a32a3;
            color: white;
        }

        .card-header.bg-purple {
            background-color: #6f42c1;
            color: white;
        }

        .table thead th {
            background-color: #e9d8fd;
            color: #4b0082;
        }

        .table-hover tbody tr:hover {
            background-color: #f3e5f5;
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">💬 Quản lý bình luận</h3>
                    </div>
                    <div class="card-body">
                        {{-- Form lọc --}}
                        <form method="GET" action="{{ route('binh-luan.index') }}" class="mb-4">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label for="phim_id" class="form-label">Chọn phim:</label>
                                    <select name="phim_id" id="phim_id" class="form-select">
                                        <option value="">-- Tất cả phim --</option>
                                        @foreach ($phims as $phim)
                                            <option value="{{ $phim->ID_Phim }}"
                                                {{ request('phim_id') == $phim->ID_Phim ? 'selected' : '' }}>
                                                {{ $phim->TenPhim }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-info me-2">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('binh-luan.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Làm mới
                                    </a>
                                </div>
                            </div>
                        </form>

                        {{-- Form xóa nhiều --}}
                        <form id="bulk-delete-form" method="POST" action="{{ route('binh-luan.destroy-multiple') }}">
                            @csrf
                            @method('DELETE')

                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="button" class="btn btn-danger btn-sm" id="delete-selected" disabled>
                                        <i class="fas fa-trash"></i> Xóa đã chọn
                                    </button>
                                </div>
                                <span class="text-muted">
                                    Tổng cộng: <strong>{{ $binhLuans->total() }}</strong> bình luận
                                </span>
                            </div>

                            {{-- Bảng dữ liệu --}}
                            @if ($binhLuans->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle text-center">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">
                                                    <input type="checkbox" id="select-all">
                                                </th>
                                                <th width="80">ID</th>
                                                <th>Phim</th>
                                                <th>Người dùng</th>
                                                <th width="100">Điểm</th>
                                                <th width="120">Ngày tạo</th>
                                                <th width="150">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($binhLuans as $bl)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="selected_comments[]"
                                                            value="{{ $bl->ID_BinhLuan }}" class="comment-checkbox">
                                                    </td>
                                                    <td>{{ $bl->ID_BinhLuan }}</td>
                                                    <td class="text-start">
                                                        <strong>{{ $bl->TenPhim }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $bl->TenDN }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($bl->DiemDanhGia)
                                                            <span class="badge bg-warning text-dark">
                                                                {{ $bl->DiemDanhGia }}/10
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small>{{ date('d/m/Y H:i', strtotime($bl->created_at)) }}</small>
                                                    </td>
                                                    <td>
                                                        {{-- Xóa --}}
                                                        <form method="POST"
                                                            action="{{ route('binh-luan.destroy', $bl->ID_BinhLuan) }}"
                                                            class="d-inline"
                                                            onsubmit="return confirm('Xác nhận xóa bình luận này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Xóa">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Phân trang --}}
                                <div class="mt-3 d-flex justify-content-center">
                                    {{ $binhLuans->links() }}
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle"></i>
                                    Không tìm thấy bình luận nào phù hợp với điều kiện lọc.
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chọn tất cả checkbox
            const selectAllCheckbox = document.getElementById('select-all');
            const commentCheckboxes = document.querySelectorAll('.comment-checkbox');
            const deleteSelectedBtn = document.getElementById('delete-selected');

            selectAllCheckbox.addEventListener('change', function() {
                commentCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateDeleteButton();
            });

            commentCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateDeleteButton();
                    updateSelectAllCheckbox();
                });
            });

            function updateDeleteButton() {
                const checkedBoxes = document.querySelectorAll('.comment-checkbox:checked');
                deleteSelectedBtn.disabled = checkedBoxes.length === 0;
            }

            function updateSelectAllCheckbox() {
                const checkedBoxes = document.querySelectorAll('.comment-checkbox:checked');
                selectAllCheckbox.checked = checkedBoxes.length === commentCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < commentCheckboxes
                    .length;
            }

            // Xóa nhiều
            deleteSelectedBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.comment-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    alert('Vui lòng chọn ít nhất một bình luận để xóa!');
                    return;
                }

                if (confirm(`Xác nhận xóa ${checkedBoxes.length} bình luận đã chọn?`)) {
                    document.getElementById('bulk-delete-form').submit();
                }
            });

            // Toggle nội dung dài
            document.querySelectorAll('.toggle-content').forEach(button => {
                button.addEventListener('click', function() {
                    const commentContent = this.closest('.comment-content');
                    const shortContent = commentContent.querySelector('.short-content');
                    const fullContent = commentContent.querySelector('.full-content');

                    if (fullContent.style.display === 'none') {
                        shortContent.style.display = 'none';
                        fullContent.style.display = 'block';
                        this.textContent = 'Thu gọn';
                    } else {
                        shortContent.style.display = 'block';
                        fullContent.style.display = 'none';
                        this.textContent = 'Xem thêm';
                    }
                });
            });
        });
    </script>
@endsection
