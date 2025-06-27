<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('ghe.{suatChieuId}', function ($user, $suatChieuId) {
    return [
        'user_id' => $user->ID_TaiKhoan,
        'user_name' => $user->TenDN,
    ];
});