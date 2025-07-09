<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect('/admin')->with('error', 'Bạn cần đăng nhập để truy cập!');
        }

        $userRole = Auth::user()->VaiTro;
        $requiredRole = (int) $role;

        // Kiểm tra vai trò
        switch ($requiredRole) {
            case 1: // Nhân viên
                if ($userRole != 1) {
                    return redirect('/admin/home')->with('error', 'Bạn không có quyền truy cập chức năng này!');
                }
                break;
            
            case 2: // Admin cấp cao
                if ($userRole != 2) {
                    return redirect('/admin/hoa-don')->with('error', 'Chỉ admin cấp cao mới có quyền truy cập!');
                }
                break;
            
            default:
                return redirect('/admin/home')->with('error', 'Vai trò không hợp lệ!');
        }

        return $next($request);
    }
}