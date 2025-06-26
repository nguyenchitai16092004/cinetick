<?php
use Illuminate\Support\Facades\Broadcast;

// Ví dụ channel public (có thể để trống nếu chưa dùng private channel)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});