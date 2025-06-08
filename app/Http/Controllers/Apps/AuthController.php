<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Models\ThongTin;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\HoaDon;

class AuthController extends Controller
{
    public function DangKy()
    {
        return view('frontend.pages.dang-ky');
    }

    public function dang_ky(Request $request)
    {
        $request->validate([
            'ID_CCCD' => 'required|unique:thong_tin,ID_CCCD',
            'HoTen' => 'required|string|max:255',
            'GioiTinh' => 'required|in:1,0',
            'NgaySinh' => 'required|date',
            'Email' => 'required|email|unique:thong_tin,Email',
            'SDT' => 'required|digits:10',
            'TenDN' => 'required|string|unique:tai_khoan,TenDN',
            'MatKhau' => 'required|min:6|confirmed',
        ], [
            'ID_CCCD.unique' => 'Số CCCD này đã được sử dụng. Vui lòng nhập số CCCD khác.',
            'Email.unique' => 'Email này đã được đăng ký tài khoản. Vui lòng sử dụng email khác.',
            'TenDN.unique' => 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.',
            'MatKhau.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        ThongTin::create([
            'ID_CCCD' => $request->ID_CCCD,
            'HoTen' => $request->HoTen,
            'GioiTinh' => $request->GioiTinh,
            'NgaySinh' => $request->NgaySinh,
            'Email' => $request->Email,
            'SDT' => $request->SDT,
        ]);

        $token = Str::random(64);

        TaiKhoan::create([
            'TenDN' => $request->TenDN,
            'MatKhau' => Hash::make($request->MatKhau),
            'TrangThai' => false, // chưa kích hoạt
            'ID_CCCD' => $request->ID_CCCD,
            'VaiTro' => 0,
            'token_xac_nhan' => $token,
        ]);

        // Gửi email xác nhận
        try {
            Mail::send('emails.verify-email', [
                'token' => $token,
                'TenDN' => $request->TenDN,
            ], function ($message) use ($request) {
                $message->to($request->Email)
                    ->subject('Xác nhận tài khoản của bạn');
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withErrors(['email' => 'Không gửi được email xác nhận.']);
        }

        return redirect()->route('register.form.get')->with('success', 'Vui lòng xác nhận tài khoản đăng ký thông qua liên kết mà chúng tôi đã gửi đến email của bạn!');
    }

    public function verifyAccount($token)
    {
        $taiKhoan = TaiKhoan::where('token_xac_nhan', $token)->first();

        if (!$taiKhoan) {
            return redirect()->route('login.form')->withErrors(['login' => 'Liên kết xác nhận không hợp lệ.']);
        }

        $taiKhoan->TrangThai = true;
        $taiKhoan->token_xac_nhan = null;
        $taiKhoan->save();

        return redirect()->route('login.form')->with('success', 'Tài khoản đã được xác nhận. Bạn có thể đăng nhập!');
    }

    public function DangNhap()
    {
        return view('frontend.pages.dang-nhap');
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'TenDN' => $request->TenDN,
            'password' => $request->MatKhau,
        ];

        if (!Auth::attempt($credentials)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Sai tài khoản hoặc mật khẩu'], 401);
            }
            return back()->withErrors(['login' => 'Sai tài khoản hoặc mật khẩu']);
        }

        $user = Auth::user();

        if ($user->TrangThai == 0) {
            Auth::logout();
            return back()->withErrors(['login' => 'Tài khoản chưa được xác nhận. Vui lòng kiểm tra Email và xác nhận tài khoản trước khi đăng nhập!']);
        }
        // Lưu session
        session([
            'user_id' => $user->ID_TaiKhoan,
            'user_name' => $user->TenDN,
            'cccd_id' => $user->ID_CCCD,
            'user_role' => $user->VaiTro,
            'user_fullname' => $user->thongTin->HoTen ?? 'Người dùng',
            'user_email' => $user->thongTin->Email ?? 'Chưa cập nhật',
            'user_phone' => $user->thongTin->SDT ?? 'Chưa cập nhật',
            'login_time' => now()->format('Y-m-d H:i:s'),
            'user_date' => $user->thongTin->NgaySinh ?? 'Chưa cập nhật',
            'user_sex' => $user->thongTin->GioiTinh ?? 'Chưa cập nhật',
            'is_logged_in' => true
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
    }


    public function dang_xuat()
    {
        session()->flush();
        return redirect()->route('login.form')->with('success', 'Bạn đã đăng xuất!');
    }

    public function doi_mat_khau(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $userId = session('user_id');
        if (!$userId) {
            return response("1"); // Phiên đăng nhập hết hạn
        }

        $user = TaiKhoan::find($userId);
        if (!$user || !Hash::check($data['OldPass'], $user->MatKhau)) {
            return response("2"); // Mật khẩu cũ không đúng
        }

        if ($data['NewPass'] !== $data['ReNewPass']) {
            return response("3"); // Mật khẩu nhập lại không khớp
        }

        $user->MatKhau = Hash::make($data['NewPass']);
        $user->save();

        return response("true");
    }

    public function quen_mat_khau(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return response('Vui lòng nhập email.', 400);
        }

        // Tìm thông tin người dùng theo email
        $thongTin = ThongTin::where('Email', $email)->first();
        if (!$thongTin) {
            return response('Email không tồn tại trong hệ thống.', 404);
        }

        $taiKhoan = TaiKhoan::where('ID_CCCD', $thongTin->ID_CCCD)->first();
        if (!$taiKhoan) {
            return response('Không tìm thấy tài khoản cho email này.', 404);
        }

        $newPassword = Str::random(8);

        $taiKhoan->MatKhau = Hash::make($newPassword);
        $taiKhoan->save();

        try {
            Mail::send('emails.reset-password', [
                'user' => $thongTin,
                'newPassword' => $newPassword
            ], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Mật khẩu mới từ CineTick');
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response('Không gửi được email. Vui lòng thử lại sau.', 500);
        }

        return response("true");
    }

    public function showUpdateInfo()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login.form');
        }
        $taiKhoan = TaiKhoan::find($userId);
        $thongTin = $taiKhoan->thongTin ?? null;

        return view('frontend.pages.thong-tin-tai-khoan.cap-nhat-thong-tin', compact('taiKhoan', 'thongTin'));
    }

    public function updateInfo(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login.form');
        }
        $taiKhoan = \App\Models\TaiKhoan::find($userId);
        $thongTin = $taiKhoan->thongTin ?? null;

        $request->validate([
            'HoTen' => 'required|string|max:255',
            'NgaySinh' => 'required|date_format:d/m/Y',
            'SDT' => 'required|digits:10',
            'GioiTinh' => 'required|in:1,0',
        ]);

        // Chỉ cập nhật các trường cho phép
        $thongTin->HoTen = $request->HoTen;
        $thongTin->NgaySinh = \Carbon\Carbon::createFromFormat('d/m/Y', $request->NgaySinh)->format('Y-m-d');
        $thongTin->SDT = $request->SDT;
        $thongTin->GioiTinh = $request->GioiTinh;
        $thongTin->save();

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
    public function dsHoaDonNguoiDung()
    {
        $userId = session('user_id');
        $hoaDons = HoaDon::where('ID_TaiKhoan', $userId)
            ->orderByDesc('created_at')
            ->get();

        $taiKhoan = TaiKhoan::find($userId);
        $thongTin = $taiKhoan->thongTin ?? null;

        return view('frontend.pages.thong-tin-tai-khoan.info', compact('hoaDons', 'thongTin'));
    }
    
}