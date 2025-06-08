<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        @include('backend.partials.left-sidebar')
        <div class="flex flex-col flex-1 w-full">
            @include('backend.partials.header')
            @yield('main')
        </div>
    </div>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        @if (session('success'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right"
            };
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right"
            };
            toastr.error("{{ session('error') }}");
        @endif
    </script>

</body>

<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<script src="{{ asset('backend/assets/js/init-alpine.js') }}" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" defer></script>
<script src="{{ asset('backend/assets/js/charts-lines.js') }}" defer></script>
<script src="{{ asset('backend/assets/js/charts-pie.js') }}" defer></script>
@yield('js')

</html>
