<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class AutController extends Controller
{
    public function index()
    {
        if (Auth::check() && (Auth::user()->VaiTro == 1 || Auth::user()->VaiTro == 2)) {
            return redirect()->route('cap-nhat-thong-tin.index')->with('success', 'Đăng nhập thành công!');
        }
        return view('admin.login');
    }

    public function dang_nhap(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'TenDN' => 'required',
                'MatKhau' => 'required',
            ],
            [
                'TenDN.required' => 'Tên đăng nhập không được để trống',
                'MatKhau.required' => 'Mật khẩu không được để trống',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Sử dụng tên cột phù hợp với bảng tai_khoan
        $credentials = [
            'TenDN' => $request->TenDN,
            'password' => $request->MatKhau,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Kiểm tra vai trò
            if ($user->VaiTro == 1 || $user->VaiTro == 2) {
                if (!$user->TrangThai) {
                    Auth::logout();
                    return redirect()->back()->with('error', 'Tài khoản đã bị vô hiệu hóa!');
                }

                // Reset số lần đăng nhập sai
                session(['login_attempts' => 0]);

                // Lưu thông tin người đăng nhập vào session
                session([
                    'user_id' => $user->ID_TaiKhoan,
                    'user_name' => $user->TenDN,
                    'user_role' => $user->VaiTro,
                    'user_fullname' => $user->thongTin->HoTen ?? 'Người dùng',
                    'user_email' => $user->thongTin->Email ?? 'Chưa cập nhật',
                    'user_phone' => $user->thongTin->SDT ?? 'Chưa cập nhật',
                    'login_time' => now()->format('Y-m-d H:i:s'),
                    'user_date' => $user->thongTin->NgaySinh ?? 'Chưa cập nhật',
                    'user_sex' => $user->thongTin->GioiTinh ?? 'Chưa cập nhật',
                    'is_logged_in' => true
                ]);
                $message = $this->getWelcomeMessage($user->VaiTro);
                if (session('user_role') == 1) {
                    return redirect()->route('hoa-don.index')->with('success', $message);
                } else {
                    return redirect()->route('cap-nhat-thong-tin.index')->with('success', $message);
                }
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Bạn không có quyền truy cập vào trang quản trị!');
            }
        }

        // Tăng số lần đăng nhập sai
        session(['login_attempts' => session('login_attempts', 0) + 1]);
        session(['last_attempt' => time()]);

        return redirect()->back()->with('error', 'Tên đăng nhập hoặc mật khẩu không chính xác!');
    }

    /**
     * Lấy thông báo chào mừng theo vai trò
     */
    private function getWelcomeMessage($vaiTro)
    {
        switch ($vaiTro) {
            case 1:
                return 'Chào mừng nhân viên! Bạn có thể tạo hóa đơn và quản lý vé xem phim.';
            case 2:
                return 'Chào mừng quản trị viên! Bạn có toàn quyền quản lý hệ thống.';
            default:
                return 'Đăng nhập thành công!';
        }
    }

    // Đăng xuất
    public function dang_xuat()
    {
        // Xóa thông tin người dùng khỏi session khi đăng xuất
        session()->forget([
            'user_id',
            'user_name',
            'user_role',
            'user_fullname',
            'user_email',
            'user_phone',
            'login_time',
            'user_date',
            'user_sex',
            'is_logged_in',
            'last_activity'
        ]);

        Auth::logout();
        return redirect('/admin')->with('success', 'Đăng xuất thành công!');
    }

    // Kiểm tra người dùng có đăng nhập không
    public function KiemTraDangNhap()
    {
        return session('is_logged_in', false) && Auth::check();
    }

    /**
     * Kiểm tra quyền truy cập theo vai trò
     */
    public function checkPermission($requiredRole)
    {
        if (!$this->KiemTraDangNhap()) {
            return false;
        }

        $userRole = session('user_role');

        // Vai trò 2 (Admin cấp cao) có thể truy cập tất cả
        if ($userRole == 2) {
            return true;
        }

        // Vai trò 1 (Nhân viên) chỉ truy cập được các chức năng được phép
        if ($userRole == 1 && $requiredRole == 1) {
            return true;
        }

        return false;
    }
}
