<!DOCTYPE html>
<html lang="en">


<head>
    <base href="<?php echo e(url('user')); ?>/">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <link rel="icon"
        href="<?php echo e($thongTinTrangWeb->Icon ? asset('storage/' . $thongTinTrangWeb->Icon) : asset('images/no-image.jpg')); ?>"
        type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lexend+Deca:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:wght@300;400;500&display=swap"
        rel="stylesheet">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetmodal/dist/min/jquery.sweet-modal.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">



</head>

<body>
    <div class="loader-overlay">
        <div class="loader"></div>
    </div>

    <?php echo $__env->make('user.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldContent('main'); ?>
    <?php echo $__env->make('user.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetmodal/dist/min/jquery.sweet-modal.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="<?php echo e(asset('user/Scripts/Style88a8.js')); ?>"></script>
    <script src="<?php echo e(asset('user/Content/js/booking-release-global.js')); ?>"></script>
    <script src="Content/js/main.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/user/layouts/master.blade.php ENDPATH**/ ?>