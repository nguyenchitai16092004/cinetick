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
        // Cũng có thể lấy từ session: $vaiTro = session('user_role');

        // Nếu là người dùng bình thường
        if ($vaiTro == 0) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập khu vực quản trị!');
        }

        // Nếu là nhân viên và đang cố gắng truy cập khu vực không được phép
        if ($vaiTro == 1 && $request->is('admin/khach-hang*')) {
            return redirect('/admin')->with('error', 'Bạn không có quyền truy cập thông tin khách hàng!');
        }

        // Cập nhật thời gian hoạt động cuối cùng
        session(['last_activity' => now()->format('Y-m-d H:i:s')]);

        return $next($request);
    }
}
