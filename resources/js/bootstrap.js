import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common[ 'X-Requested-With' ] = 'XMLHttpRequest';

import Echo from "laravel-echo";
window.Pusher = require("pusher-js");

window.Echo = new Echo({
    broadcaster: "pusher",
    key: "YOUR_PUSHER_KEY",
    cluster: "ap1", // Đúng cluster Pusher trên dashboard
    wsHost: "ws.pusherapp.com",
    wsPort: 80,
    forceTLS: false,
    encrypted: true,
    disableStats: true,
    // Nếu chạy local, KHÔNG dùng wsHost là localhost nếu dùng Pusher thật!
});