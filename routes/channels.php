<?php

use Illuminate\Support\Facades\Broadcast;

// Nếu bạn dùng PresenceChannel (Echo.join), dùng như dưới
Broadcast::channel('ghe.{suatChieuId}', function ($user, $suatChieuId) {
    // Nếu muốn xác thực, có thể kiểm tra quyền ở đây.
    return true; // Cho phép tất cả user join channel này (test realtime)
});

// Nếu sau này dùng PrivateChannel thì cũng tương tự (thay PresenceChannel bằng PrivateChannel)