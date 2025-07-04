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
    <link rel="stylesheet" href="{{ asset('backend/assets/css/layout.css') }}" />
    @yield('css')
</head>

<body>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>

    <!-- Include Notification Component -->
    @include('admin.partials.notification')

    <!-- Overlay cho mobile -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileSidebar()"></div>

    <!-- Main layout -->
    <div class="main-layout flex bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- Fixed Sidebar -->
        @include('admin.partials.left-sidebar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Fixed Header -->
            <div class="no-print">
                @include('admin.partials.header')
            </div>
            <!-- Scrollable Main Content -->
            <main class="main-content-area">
                @yield('main')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <!-- Mobile script -->
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('mobileOverlay');

            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-show');
                overlay.classList.toggle('show');
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('mobileOverlay');

            sidebar.classList.remove('mobile-show');
            overlay.classList.remove('show');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerButton = document.querySelector('button[aria-label="Menu"]');
            if (hamburgerButton) {
                hamburgerButton.addEventListener('click', toggleMobileSidebar);
            }
        });
    </script>
    @yield('js')
</body>

</html>
