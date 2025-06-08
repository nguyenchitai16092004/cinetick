<!DOCTYPE html>
<html lang="en">

<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <base href="{{ url('frontend') }}/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('frontend/Content/img/lgCineTick.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lexend+Deca:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- External CSS -->
    <link href="Content/Stylebf25.css" rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Owl Carousel CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- jQuery -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetmodal/dist/min/jquery.sweet-modal.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<script src="https://cdn.jsdelivr.net/npm/sweetmodal/dist/min/jquery.sweet-modal.min.js"></script>

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="{{ asset('frontend/Scripts/Style88a8.js') }}"></script>

</head>

<body>


    @include('frontend.partials.header')
    @yield('main')
    @include('frontend.partials.footer')


</body>


</html>
