<!-- Notification Popup Overlay -->
<div id="notificationOverlay" class="notification-overlay">
    <div class="notification-popup">
        <button class="notification-close" onclick="closeNotification()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12" />
            </svg>
        </button>

        <div class="notification-content">
            <div class="notification-icon-wrapper">
                <div class="notification-icon" id="notificationIcon">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <h3 class="notification-title" id="notificationTitle">Thành công</h3>
            <p class="notification-message" id="notificationMessage">
                Thao tác đã được thực hiện thành công!
            </p>
        </div>

        <div class="notification-actions">
            <button class="btn-ok" onclick="closeNotification()">Đồng ý</button>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    .notification-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .notification-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .notification-overlay.hide {
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .notification-overlay.hide .notification-popup {
        transform: scale(0.9) translateY(20px);
        opacity: 0;
    }

    .notification-popup {
        background: white;
        border-radius: 16px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        padding: 32px 24px 24px;
        max-width: 420px;
        width: 90%;
        margin: 20px;
        position: relative;
        transform: scale(0.9) translateY(20px);
        transition: transform 0.3s ease, opacity 0.3s ease;
        text-align: center;
        opacity: 1;
    }

    .notification-overlay.show .notification-popup {
        transform: scale(1) translateY(0);
    }

    .notification-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: #f3f4f6;
        border: none;
        border-radius: 8px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #6b7280;
        transition: all 0.2s ease;
    }

    .notification-close:hover {
        background: #e5e7eb;
        color: #374151;
    }

    .notification-content {
        display: contents;
        margin-bottom: 24px;
        padding-right: 20px;
    }

    .notification-icon-wrapper {
        margin-bottom: 16px;
    }

    .notification-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 28px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .notification-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transform: rotate(45deg);
        animation: shine 2s infinite;
    }

    @keyframes shine {
        0% {
            transform: translateX(-100%) translateY(-100%) rotate(45deg);
        }

        100% {
            transform: translateX(100%) translateY(100%) rotate(45deg);
        }
    }

    .notification-icon.success {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .notification-icon.error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .notification-icon.warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .notification-icon.info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .notification-title {
        font-size: 22px;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 12px 0;
        line-height: 1.3;
    }

    .notification-message {
        font-size: 15px;
        color: #4b5563;
        margin: 0;
        line-height: 1.6;
        text-align: center;
    }

    .notification-actions {
        display: flex;
        justify-content: center;
    }

    .btn-ok {
        padding: 12px 32px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        min-width: 100px;
        background: #3b82f6;
        color: white;
    }

    .btn-ok:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .btn-ok.success {
        background: #10b981;
    }

    .btn-ok.success:hover {
        background: #059669;
    }

    .btn-ok.error {
        background: #ef4444;
    }

    .btn-ok.error:hover {
        background: #dc2626;
    }

    .btn-ok.warning {
        background: #f59e0b;
    }

    .btn-ok.warning:hover {
        background: #d97706;
    }

    /* Dark mode */
    .dark .notification-popup {
        background: #1f2937;
        color: white;
    }

    .dark .notification-title {
        color: white;
    }

    .dark .notification-message {
        color: #d1d5db;
    }

    .dark .notification-close {
        background: #374151;
        color: #9ca3af;
    }

    .dark .notification-close:hover {
        background: #4b5563;
        color: #d1d5db;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .notification-popup {
            margin: 10px;
            padding: 28px 20px 20px;
        }

        .notification-icon {
            width: 56px;
            height: 56px;
            font-size: 24px;
        }

        .notification-title {
            font-size: 20px;
        }

        .btn-ok {
            width: 100%;
        }
    }
</style>

<!-- Script -->
<script>
    function showNotification(type, message, title = null, duration = 5000) {
        const types = {
            success: {
                title: title || 'Thành công',
                icon: 'fas fa-check',
                class: 'success'
            },
            error: {
                title: title || 'Lỗi',
                icon: 'fas fa-times',
                class: 'error'
            },
            warning: {
                title: title || 'Cảnh báo',
                icon: 'fas fa-exclamation-triangle',
                class: 'warning'
            },
            info: {
                title: title || 'Thông tin',
                icon: 'fas fa-info',
                class: 'info'
            }
        };

        const config = types[type] || types.info;

        document.getElementById('notificationTitle').textContent = config.title;
        document.getElementById('notificationMessage').innerHTML = message;

        const iconElement = document.getElementById('notificationIcon');
        iconElement.innerHTML = `<i class="${config.icon}"></i>`;
        iconElement.className = `notification-icon ${config.class}`;

        const btnElement = document.querySelector('.btn-ok');
        btnElement.className = `btn-ok ${config.class}`;

        const overlay = document.getElementById('notificationOverlay');
        overlay.classList.add('show');
        overlay.classList.remove('hide');
        document.body.style.overflow = 'hidden';

        if (duration > 0) {
            setTimeout(() => {
                closeNotification();
            }, duration);
        }
    }

    function confirmHoanTien(form) {
        showWarning(
            'Bạn có chắc chắn muốn hoàn tiền cho hóa đơn này? Hành động này không thể hoàn tác!',
            'Xác nhận hoàn tiền',
            0 // 0: không tự động tắt
        );
        
        const btn = document.querySelector('.notification-overlay.show .btn-ok');
        if (btn) {
            btn.onclick = function() {
                closeNotification();
                form.submit();
            };
        }
    }

    function closeNotification() {
        const overlay = document.getElementById('notificationOverlay');
        overlay.classList.remove('show');
        overlay.classList.add('hide');
        document.body.style.overflow = '';
        setTimeout(() => {
            overlay.classList.remove('hide');
        }, 300);
    }

    document.getElementById('notificationOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeNotification();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('notificationOverlay').classList.contains('show')) {
            closeNotification();
        }
    });

    function showSuccess(message, title = null, duration = 5000) {
        showNotification('success', message, title, duration);
    }

    function showError(message, title = null, duration = 5000) {
        showNotification('error', message, title, duration);
    }

    function showWarning(message, title = null, duration = 5000) {
        showNotification('warning', message, title, duration);
    }

    function showInfo(message, title = null, duration = 5000) {
        showNotification('info', message, title, duration);
    }

    // Laravel flash message support
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            showSuccess(`{!! session('success') !!}`);
        @endif
        @if (session('error'))
            showError(`{!! session('error') !!}`);
        @endif
        @if (session('warning'))
            showWarning(`{!! session('warning') !!}`);
        @endif
        @if (session('info'))
            showInfo(`{!! session('info') !!}`);
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                showError(`{!! $error !!}`);
            @endforeach
        @endif
    });

    window.showNotification = showNotification;
    window.showSuccess = showSuccess;
    window.showError = showError;
    window.showWarning = showWarning;
    window.showInfo = showInfo;
    window.notify = {
        success: showSuccess,
        error: showError,
        warning: showWarning,
        info: showInfo
    };
</script>

<!-- Font Awesome (if needed) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
