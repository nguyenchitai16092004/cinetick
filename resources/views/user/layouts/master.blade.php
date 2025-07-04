<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{{ url('user') }}/">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link rel="icon"
        href="{{ $thongTinTrangWeb->Icon ? asset('storage/' . $thongTinTrangWeb->Icon) : asset('images/no-image.jpg') }}"
        type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lexend+Deca:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:wght@300;400;500&display=swap"rel="stylesheet">

    <!-- External CSS -->
    <link href="Content/Stylebf25.css" rel="stylesheet" />
    <link href="Content/css/main.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

    <!-- Font Awesome & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- SweetModal -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetmodal/dist/min/jquery.sweet-modal.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/sweetmodal@2.0.0-beta.2/dist/min/jquery.sweet-modal.min.css" />

    <!-- Notification System CSS -->
    <style>
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            width: 100%;
        }

        .notification {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-left: 4px solid;
            display: flex;
            align-items: center;
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        .notification.hide {
            transform: translateX(100%);
            opacity: 0;
        }

        .notification::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(100%);
            }
        }

        .notification-icon {
            font-size: 24px;
            margin-right: 15px;
            min-width: 30px;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
            color: #2d3748;
        }

        .notification-message {
            font-size: 14px;
            color: #4a5568;
            line-height: 1.4;
        }

        .notification-close {
            background: none;
            border: none;
            font-size: 18px;
            color: #a0aec0;
            cursor: pointer;
            padding: 5px;
            margin-left: 10px;
            border-radius: 50%;
            transition: all 0.2s;
        }

        .notification-close:hover {
            background: rgba(0, 0, 0, 0.1);
            color: #2d3748;
        }

        .notification-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            animation: progress 5s linear forwards;
        }

        @keyframes progress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        /* Notification Types */
        .notification.success {
            border-left-color: #48bb78;
        }

        .notification.success .notification-icon {
            color: #48bb78;
        }

        .notification.success .notification-progress {
            background: #48bb78;
        }

        .notification.error {
            border-left-color: #f56565;
        }

        .notification.error .notification-icon {
            color: #f56565;
        }

        .notification.error .notification-progress {
            background: #f56565;
        }

        .notification.warning {
            border-left-color: #ed8936;
        }

        .notification.warning .notification-icon {
            color: #ed8936;
        }

        .notification.warning .notification-progress {
            background: #ed8936;
        }

        .notification.info {
            border-left-color: #4299e1;
        }

        .notification.info .notification-icon {
            color: #4299e1;
        }

        .notification.info .notification-progress {
            background: #4299e1;
        }

        @media (max-width: 768px) {
            .notification-container {
                left: 20px;
                right: 20px;
                max-width: none;
            }
        }
    </style>
</head>

<body>
    <!-- Loader -->
    <div class="loader-overlay">
        <div class="loader"></div>
    </div>

    <!-- Header -->
    @include('user.partials.header')

    <!-- Main Content -->
    @yield('main')

    <!-- Notification Container -->
    <div id="notification-container" class="notification-container"></div>

    <!-- Footer -->
    @include('user.partials.footer')

    <!-- JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetmodal/dist/min/jquery.sweet-modal.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetmodal@2.0.0-beta.2/dist/min/jquery.sweet-modal.min.js"></script>
    <script src="{{ asset('user/Content/js/booking-release-global.js') }}"></script>
    <script src="Content/js/main.js"></script>

    <!-- Notification System JavaScript -->
    <script>
        let notificationId = 0;

        function showNotification(type = 'info', title = '', message = '', duration = 5000) {
            const container = document.getElementById('notification-container');
            if (!container) {
                console.error('Notification container not found');
                return;
            }

            const id = ++notificationId;

            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-times-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };

            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.id = `notification-${id}`;

            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="${icons[type] || icons.info}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${title}</div>
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close" onclick="closeNotification(${id})">
                    <i class="fas fa-times"></i>
                </button>
                <div class="notification-progress"></div>
            `;

            container.appendChild(notification);

            // Trigger animation
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            // Auto close
            const timeoutId = setTimeout(() => {
                closeNotification(id);
            }, duration);

            // Store timeout ID for manual close
            notification.dataset.timeoutId = timeoutId;
        }

        function closeNotification(id) {
            const notification = document.getElementById(`notification-${id}`);
            if (!notification) return;

            // Clear timeout
            const timeoutId = notification.dataset.timeoutId;
            if (timeoutId) {
                clearTimeout(timeoutId);
            }

            notification.classList.add('hide');

            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 400);
        }

        // Close notification when pressing ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const notifications = document.querySelectorAll('.notification.show');
                notifications.forEach(notification => {
                    const id = notification.id.replace('notification-', '');
                    closeNotification(parseInt(id));
                });
            }
        });
    </script>

    <!-- Hiển thị thông báo từ session -->
    @if (session('notification'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification(
                    @json(session('notification.type')),
                    @json(session('notification.title')),
                    @json(session('notification.message'))
                );
            });
        </script>
    @endif

    <!-- Hiển thị thông báo success cũ (để tương thích) -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('success', 'Thành công!', @json(session('success')));
            });
        </script>
    @endif

    <!-- Hiển thị lỗi validation -->
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @foreach ($errors->all() as $error)
                    showNotification('error', 'Lỗi!', @json($error));
                @endforeach
            });
        </script>
    @endif

</body>

</html>
