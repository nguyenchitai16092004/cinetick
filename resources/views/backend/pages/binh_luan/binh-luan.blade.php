@extends('backend.layouts.master')
@section('title', 'Quản lý bình luận')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Quản lý bình luận</h3>
                        <div class="card-tools">
                            <a href="{{ route('binh-luan.export', request()->query()) }}" 
                               class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </a>
                        </div>
                    </div>

                    {{-- Form lọc --}}
                    <div class="card-body">
                        <form method="GET" action="{{ route('binh-luan.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="phim_id">Chọn phim:</label>
                                    <select name="phim_id" id="phim_id" class="form-control">
                                        <option value="">-- Tất cả phim --</option>
                                        @foreach ($phims as $phim)
                                            <option value="{{ $phim->ID_Phim }}"
                                                {{ request('phim_id') == $phim->ID_Phim ? 'selected' : '' }}>
                                                {{ $phim->TenPhim }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="keyword">Từ khóa:</label>
                                    <input type="text" name="keyword" id="keyword" class="form-control" 
                                           value="{{ request('keyword') }}" placeholder="Tìm trong nội dung...">
                                </div>  

                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('binh-luan.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Làm mới
                                    </a>
                                </div>
                            </div>
                        </form>

                        {{-- Thông báo --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Form xóa nhiều --}}
                        <form id="bulk-delete-form" method="POST" action="{{ route('binh-luan.destroy-multiple') }}">
                            @csrf
                            @method('DELETE')
                            
                            <div class="mb-3">
                                <button type="button" class="btn btn-danger btn-sm" id="delete-selected" disabled>
                                    <i class="fas fa-trash"></i> Xóa đã chọn
                                </button>
                                <span class="ml-2 text-muted">
                                    Tổng cộng: <strong>{{ $binhLuans->total() }}</strong> bình luận
                                </span>
                            </div>

                            {{-- Bảng dữ liệu --}}
                            @if($binhLuans->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th width="50">
                                                    <input type="checkbox" id="select-all">
                                                </th>
                                                <th width="80">ID</th>
                                                <th>Phim</th>
                                                <th>Người dùng</th>
                                                <th>Nội dung</th>
                                                <th width="100">Điểm</th>
                                                <th width="120">Ngày tạo</th>
                                                <th width="150">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($binhLuans as $bl)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="selected_comments[]" 
                                                               value="{{ $bl->ID_BinhLuan }}" class="comment-checkbox">
                                                    </td>
                                                    <td>{{ $bl->ID_BinhLuan }}</td>
                                                    <td>
                                                        <strong>{{ $bl->TenPhim }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info text-black">{{ $bl->TenDN }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="comment-content" style="max-width: 300px;">
                                                            @if(strlen($bl->NoiDung) > 100)
                                                                <span class="short-content">
                                                                    {{ substr($bl->NoiDung, 0, 100) }}...
                                                                </span>
                                                                <span class="full-content" style="display: none;">
                                                                    {{ $bl->NoiDung }}
                                                                </span>
                                                                <br>
                                                                <button type="button" class="btn btn-link btn-sm p-0 toggle-content">
                                                                    Xem thêm
                                                                </button>
                                                            @else
                                                                {{ $bl->NoiDung }}
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="text-center ">
                                                        @if($bl->DiemDanhGia)
                                                            <span class="badge badge-warning text-black">
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
                                                        <div class="btn-group" role="group">
                                                            {{-- Xem chi tiết --}}
                                                            <a href="{{ route('binh-luan.show', $bl->ID_BinhLuan) }}" 
                                                               class="btn btn-sm btn-info" title="Xem chi tiết">
                                                                <i class="fas fa-eye"></i>
                                                            </a>

                                                            {{-- Xóa --}}
                                                            <form method="POST" action="{{ route('binh-luan.destroy', $bl->ID_BinhLuan) }}" 
                                                                  style="display: inline;" 
                                                                  onsubmit="return confirm('Xác nhận xóa bình luận này?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Phân trang --}}
                                <div class="d-flex justify-content-center">
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
                selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < commentCheckboxes.length;
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