<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra cả Auth và session
        if (!Auth::check() || !session('is_logged_in', false)) {
            // Xóa session nếu có
            session()->forget([
                'user_id',
                'user_name',
                'user_role',
                'user_fullname',
                'user_email',
                'login_time',
                'is_logged_in'
            ]);
            return redirect('/admin')->with('error', 'Bạn cần đăng nhập để truy cập!');
        }

        $vaiTro = Auth::user()->VaiTro;

        // Nếu là người dùng bình thường (vai trò 0)
        if ($vaiTro == 0) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập khu vực quản trị!');
        }

        // Kiểm tra trạng thái tài khoản
        if (!Auth::user()->TrangThai) {
            Auth::logout();
            session()->forget([
                'user_id',
                'user_name',
                'user_role',
                'user_fullname',
                'user_email',
                'login_time',
                'is_logged_in'
            ]);
            return redirect('/admin')->with('error', 'Tài khoản đã bị vô hiệu hóa!');
        }

        // Cập nhật thời gian hoạt động cuối cùng
        session(['last_activity' => now()->format('Y-m-d H:i:s')]);

        return $next($request);
    }
}