@extends('admin.layouts.master')
@section('title', 'Liên hệ / Trợ giúp')

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

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.5rem;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .pagination li {
            margin: 0 3px;
        }

        .page-link {
            color: #2c3e50;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.4rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
            font-weight: 500;
        }

        .page-link:hover,
        .page-link:focus {
            color: #fff;
            background-color: #6f42c1;
            border-color: #6f42c1;
            box-shadow: 0 2px 8px rgba(111, 66, 193, 0.3);
            text-decoration: none;
        }

        .pagination .active .page-link {
            color: #fff;
            background-color: #6f42c1;
            border-color: #6f42c1;
            pointer-events: none;
        }

        .pagination .disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
            border-color: #dee2e6;
            pointer-events: none;
            opacity: 0.6;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="fas fa-phone-volume"></i> Quản lý liên hệ khách hàng</h3>
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
                                            <td class="fw-bold">{{ $contact->HoTenNguoiLienHe }}</td>
                                            <td>
                                                <a href="mailto:{{ $contact->Email }}" class="text-decoration-none">
                                                    {{ $contact->Email }}
                                                </a>
                                            </td>
                                            <td>{{ $contact->SDT }}</td>
                                            <td class="fw-bold">{{ $contact->TieuDe }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                                    data-bs-target="#modalNoiDung{{ $contact->ID_LienHe }}">
                                                    <i class="fas fa-eye"></i> Xem
                                                </button>
                                                <!-- Modal nội dung -->
                                                <div class="modal fade" id="modalNoiDung{{ $contact->ID_LienHe }}" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-purple text-white">
                                                                <h5 class="modal-title">Nội dung liên hệ</h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="mb-0">{{ $contact->NoiDung }}</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($contact->AnhMinhHoa)
                                                    <a href="{{ asset('storage/' . $contact->AnhMinhHoa) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $contact->AnhMinhHoa) }}"
                                                            alt="Ảnh minh họa" class="img-thumbnail"
                                                            style="width:48px; height:48px; object-fit:cover;">
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

                        {{-- Phân trang --}}
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $contacts->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/assets/js/lien-he.js') }}"></script>
@endsection