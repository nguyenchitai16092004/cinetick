@extends('backend.layouts.master')
@section('title', 'Liên hệ / Trợ giúp')

@section('main')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/lien-he.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <div class="container-fluid mt-4">
        <div class="card shadow">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Quản lý liên hệ khách hàng</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Điện thoại</th>
                                <th>Tiêu đề</th>
                                <th>Nội dung</th>
                                <th>Ảnh minh họa</th>
                                <th>Trạng thái</th>
                                <th>Thời gian gửi</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $contact)
                                <tr @if ($contact->TrangThai == 0) style="background:#fff7f5;" @endif>
                                    <td>{{ $contact->ID_LienHe }}</td>
                                    <td>{{ $contact->HoTenNguoiLienHe }}</td>
                                    <td>
                                        <a href="mailto:{{ $contact->Email }}">{{ $contact->Email }}</a>
                                    </td>
                                    <td>{{ $contact->SDT }}</td>
                                    <td>{{ $contact->TieuDe }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                            data-bs-target="#modalNoiDung{{ $contact->ID_LienHe }}">
                                            Xem
                                        </button>
                                        <!-- Modal nội dung -->
                                        <div class="modal fade" id="modalNoiDung{{ $contact->ID_LienHe }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Nội dung liên hệ</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ $contact->NoiDung }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($contact->AnhMinhHoa)
                                            <a href="{{ asset('storage/' . $contact->AnhMinhHoa) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $contact->AnhMinhHoa) }}"
                                                    alt="Ảnh minh họa"
                                                    style="width:48px; height:48px; object-fit:cover; border-radius:5px; border:1px solid #eee;">
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Không có</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($contact->TrangThai == 0)
                                            <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                        @else
                                            <span class="badge bg-success">Đã xử lý</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span title="{{ $contact->created_at }}">
                                            {{ \Carbon\Carbon::parse($contact->created_at)->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @if ($contact->TrangThai == 0)
                                            <form action="{{ route('lien-he.xuly', $contact->ID_LienHe) }}" method="POST"
                                                class="d-inline form-xuly">
                                                @csrf
                                                <button type="button" title="Đánh dấu đã xử lý liên hệ này?"
                                                    class="btn btn-sm btn-success btn-xuly">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('lien-he.destroy', $contact->ID_LienHe) }}" method="POST"
                                            class="d-inline form-xoa">
                                            @csrf @method('DELETE')
                                            <button type="button" title="Xóa liên hệ này?"
                                                class="btn btn-sm btn-outline-danger btn-xoa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">Chưa có liên hệ nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="pagination-wrapper">
            {{ $contacts->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/assets/js/lien-he.js') }}"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif
@endsection
