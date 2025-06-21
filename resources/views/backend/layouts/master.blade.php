<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/tailwind.output.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @yield('css')

    <title>@yield('title')</title>
</head>

<body>
    <div class="modal fade" id="globalNotificationModal" tabindex="-1" aria-labelledby="globalNotificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" id="modalHeader">
                    <h5 class="modal-title" id="globalNotificationModalLabel">Thông báo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Nội dung thông báo sẽ được thêm vào đây -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="modalConfirmBtn" style="display: none;">Xác
                        nhận</button>
                </div>
            </div>
        </div>
    </div>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        @include('backend.partials.left-sidebar')
        <div class="flex flex-col flex-1 w-full">
            @include('backend.partials.header')
            @yield('main')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showNotification(title, message, type = 'info') {
            const modal = document.getElementById('globalNotificationModal');
            const modalHeader = document.getElementById('modalHeader');
            const modalTitle = document.getElementById('globalNotificationModalLabel');
            const modalBody = document.getElementById('modalBody');
            const confirmBtn = document.getElementById('modalConfirmBtn');

            // Đặt tiêu đề
            modalTitle.textContent = title;

            // Đặt nội dung
            modalBody.innerHTML = message;

            // Ẩn nút xác nhận cho thông báo thông thường
            confirmBtn.style.display = 'none';

            // Thay đổi màu header theo loại thông báo
            modalHeader.className = 'modal-header';
            switch (type) {
                case 'success':
                    modalHeader.classList.add('bg-success', 'text-white');
                    break;
                case 'error':
                    modalHeader.classList.add('bg-danger', 'text-white');
                    break;
                case 'warning':
                    modalHeader.classList.add('bg-warning', 'text-dark');
                    break;
                default:
                    modalHeader.classList.add('bg-info', 'text-white');
            }

            // Hiển thị modal
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        }

        // Hàm hiển thị thông báo xác nhận
        function showConfirmation(title, message, onConfirm, onCancel = null) {
            const modal = document.getElementById('globalNotificationModal');
            const modalHeader = document.getElementById('modalHeader');
            const modalTitle = document.getElementById('globalNotificationModalLabel');
            const modalBody = document.getElementById('modalBody');
            const confirmBtn = document.getElementById('modalConfirmBtn');

            // Đặt tiêu đề
            modalTitle.textContent = title;

            // Đặt nội dung
            modalBody.innerHTML = message;

            // Hiển thị nút xác nhận
            confirmBtn.style.display = 'inline-block';

            // Đặt màu header
            modalHeader.className = 'modal-header bg-warning text-dark';

            // Xử lý sự kiện xác nhận
            confirmBtn.onclick = function() {
                if (onConfirm) onConfirm();
                bootstrap.Modal.getInstance(modal).hide();
            };

            // Xử lý sự kiện hủy
            modal.addEventListener('hidden.bs.modal', function() {
                if (onCancel) onCancel();
            }, {
                once: true
            });

            // Hiển thị modal
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertContainer = document.getElementById("socketAlertContainer");
            const alertMessage = document.getElementById("socketAlertMessage");

            @if (session('success'))
                if (alertContainer && alertMessage) {
                    alertMessage.textContent = @json(session('success'));
                    alertContainer.classList.remove("d-none");
                    alertContainer.classList.add("alert-success");
                    setTimeout(() => alertContainer.classList.add("d-none"), 10000);
                }
            @endif

            @if (session('error'))
                if (alertContainer && alertMessage) {
                    alertMessage.textContent = @json(session('error'));
                    alertContainer.classList.remove("d-none");
                    alertContainer.classList.add("alert-danger");
                    setTimeout(() => alertContainer.classList.add("d-none"), 10000);
                }
            @endif

            @if (session('warning'))
                if (alertContainer && alertMessage) {
                    alertMessage.textContent = @json(session('warning'));
                    alertContainer.classList.remove("d-none");
                    alertContainer.classList.add("alert-warning");
                    setTimeout(() => alertContainer.classList.add("d-none"), 10000);
                }
            @endif
        });
    </script>

</body>

@yield('js')

</html>
