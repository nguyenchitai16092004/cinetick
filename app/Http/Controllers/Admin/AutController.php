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
        return view('backend.login');
    }

    // Xử lý đăng nhập
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

        // Hạn chế số lần đăng nhập sai
        if (session('login_attempts', 0) >= 5) {
            if (time() - session('last_attempt', 0) < 1800) { // 30 phút
                return redirect()->back()->with('error', 'Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau 30 phút.');
            }
            session(['login_attempts' => 0]);
        }

        // Sử dụng tên cột phù hợp với bảng tai_khoan
        $credentials = [
            'TenDN' => $request->TenDN,
            'password' => $request->MatKhau,
        ];

        if (Auth::attempt($credentials)) {
            if (Auth::user()->VaiTro == 1 || Auth::user()->VaiTro == 2) {
                if (!Auth::user()->TrangThai) {
                    Auth::logout();
                    return redirect()->back()->with('error', 'Tài khoản đã bị vô hiệu hóa!');
                }

                // Reset số lần đăng nhập sai
                session(['login_attempts' => 0]);

                // Lưu thông tin người đăng nhập vào session
                $user = Auth::user();
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

                return redirect()->route('cap-nhat-thong-tin.index')->with('success', 'Đăng nhập thành công!');
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

    // Đăng xuất
    public function dang_xuat()
    {
        // Xóa thông tin người dùng khỏi session
        session()->forget([
            'user_id',
            'user_name',
            'user_role',
            'user_fullname',
            'user_email',
            'login_time',
            'is_logged_in'
        ]);

        // Hoặc có thể xóa toàn bộ session
        // session()->flush();

        Auth::logout();
        return redirect('/admin')->with('success', 'Đăng xuất thành công!');
    }

    // Kiểm tra người dùng có đăng nhập không
    public function isLoggedIn()
    {
        return session('is_logged_in', false) && Auth::check();
    }
}
