<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Models\ThongTin;
use App\Models\Rap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaiKhoanController extends Controller
{
    /**
     * Display a listing of accounts.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'VaiTro');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = TaiKhoan::join('thong_tin', 'tai_khoan.ID_ThongTin', '=', 'thong_tin.ID_ThongTin')
            ->select('tai_khoan.*', 'thong_tin.HoTen', 'thong_tin.Email', 'thong_tin.SDT')
            ->where('tai_khoan.VaiTro', '<', 2);

        // Tìm kiếm
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('thong_tin.HoTen', 'like', '%' . $search . '%')
                    ->orWhere('tai_khoan.TenDN', 'like', '%' . $search . '%')
                    ->orWhere('thong_tin.Email', 'like', '%' . $search . '%')
                    ->orWhere('thong_tin.SDT', 'like', '%' . $search . '%')
                    ->orWhere('tai_khoan.ID_TaiKhoan', 'like', '%' . $search . '%');
            });
        }

        // Sắp xếp
        $query->orderBy($sortBy, $sortOrder);

        $taiKhoans = $query->paginate(10)->appends($request->query());

        return view('admin.pages.tai_khoan.tai-khoan', compact('taiKhoans', 'search', 'sortBy', 'sortOrder'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create()
    {
        $raps = Rap::where('TrangThai', 1)->select('ID_Rap', 'TenRap')->get();
        return view('admin.pages.tai_khoan.create-tai-khoan', compact('raps'));
    }

    /**
     * Store a newly created account in database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenDN' => 'required|string|max:50|unique:tai_khoan,TenDN',
            'MatKhau' => 'required|string|min:6|max:100',
            'HoTen' => 'required|string|max:100',
            'GioiTinh' => 'required|boolean',
            'NgaySinh' => 'required|date|before:today',
            'Email' => 'required|email|max:100|unique:thong_tin,Email',
            'SDT' => 'required|string|max:15|regex:/^[0-9]{10,11}$/',
            'VaiTro' => 'required|integer|in:0,1',
            'TrangThai' => 'required|boolean',
            'ID_Rap' => 'required_if:VaiTro,1|exists:rap,ID_Rap',
            'Luong' => 'required_if:VaiTro,1|numeric|min:1000000|max:1000000000',
        ], [
            'TenDN.required' => 'Tên đăng nhập là bắt buộc.',
            'TenDN.unique' => 'Tên đăng nhập đã tồn tại.',
            'MatKhau.required' => 'Mật khẩu là bắt buộc.',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'HoTen.required' => 'Họ tên là bắt buộc.',
            'NgaySinh.required' => 'Ngày sinh là bắt buộc.',
            'NgaySinh.before' => 'Ngày sinh phải trước ngày hôm nay.',
            'Email.required' => 'Email là bắt buộc.',
            'Email.email' => 'Email không đúng định dạng.',
            'Email.unique' => 'Email đã tồn tại.',
            'SDT.required' => 'Số điện thoại là bắt buộc.',
            'SDT.regex' => 'Số điện thoại không đúng định dạng.',
            'ID_Rap.required_if' => 'Địa chỉ làm việc là bắt buộc đối với nhân viên.',
            'Luong.required_if' => 'Lương là bắt buộc đối với nhân viên.',
            'Luong.min' => 'Lương tối thiểu là 1.000.000 đD.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Tạo thông tin cá nhân
            $thongTin = ThongTin::create([
                'HoTen' => $request->HoTen,
                'GioiTinh' => $request->GioiTinh,
                'NgaySinh' => $request->NgaySinh,
                'Email' => $request->Email,
                'SDT' => $request->SDT,
                'Luong' => $request->VaiTro == 1 ? $request->Luong : null,
                'ID_Rap' => $request->VaiTro == 1 ? $request->ID_Rap : null,
            ]);

            // Tạo tài khoản
            TaiKhoan::create([
                'TenDN' => $request->TenDN,
                'MatKhau' => Hash::make($request->MatKhau),
                'VaiTro' => $request->VaiTro,
                'TrangThai' => $request->TrangThai,
                'ID_ThongTin' => $thongTin->ID_ThongTin,
            ]);

            DB::commit();

            return redirect()->route('tai-khoan.index')
                ->with('success', 'Tài khoản đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified account.
     */
    public function show($id)
    {
        $taiKhoan = TaiKhoan::with(['thongTin', 'thongTin.rap'])->findOrFail($id);

        // Kiểm tra quyền truy cập
        if (Auth::user()->VaiTro < 2 && Auth::user()->ID_TaiKhoan != $id) {
            return redirect()->route('tai-khoan.index')
                ->with('error', 'Bạn không có quyền xem thông tin tài khoản này.');
        }

        return view('admin.pages.tai_khoan.show-tai-khoan', compact('taiKhoan'));
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit($id)
    {
        $taiKhoan = TaiKhoan::join('thong_tin', 'thong_tin.ID_ThongTin', 'tai_khoan.ID_ThongTin')
            ->where('tai_khoan.ID_TaiKhoan', $id)
            ->select('tai_khoan.*', 'thong_tin.*')
            ->first();

        if (Auth::user()->VaiTro < 2 && Auth::user()->ID_TaiKhoan != $id) {
            return redirect()->route('tai-khoan.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa thông tin tài khoản này.');
        }

        $raps = Rap::where('TrangThai', 1)->select('ID_Rap', 'TenRap')->get();

        return view('admin.pages.tai_khoan.detail-tai-khoan', compact('taiKhoan', 'raps'));
    }

    /**
     * Update the specified account in storage.
     */
    public function update(Request $request, $id)
    {
        $taiKhoan = TaiKhoan::with('thongTin')->findOrFail($id);

        // Kiểm tra quyền truy cập - chỉ cho phép chỉnh sửa tài khoản của chính mình
        if (Auth::user()->VaiTro < 2 && Auth::user()->ID_TaiKhoan != $id) {
            return redirect()->route('tai-khoan.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa thông tin tài khoản này.');
        }

        // Validation - sửa lại email unique check
        $validator = Validator::make($request->all(), [
            'TenDN' => 'required|string|max:100|unique:tai_khoan,TenDN,' . $id . ',ID_TaiKhoan',
            'MatKhau' => 'nullable|string|min:6|max:100',
            'HoTen' => 'required|string|max:100',
            'GioiTinh' => 'required|boolean',
            'NgaySinh' => 'required|date|before:today',
            'Email' => 'required|email|max:100|unique:thong_tin,Email,' . $taiKhoan->ID_ThongTin . ',ID_ThongTin',
            'SDT' => 'required|string|max:15|regex:/^[0-9]{10,11}$/',
            'VaiTro' => 'required|integer|in:0,1,2',
            'ID_Rap' => 'required_if:VaiTro,1|nullable|exists:rap,ID_Rap',
            'Luong' => 'required_if:VaiTro,1|nullable|numeric|min:0|max:1000000000',
        ], [
            'TenDN.required' => 'Tên đăng nhập là bắt buộc.',
            'TenDN.unique' => 'Tên đăng nhập đã tồn tại.',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'HoTen.required' => 'Họ tên là bắt buộc.',
            'NgaySinh.required' => 'Ngày sinh là bắt buộc.',
            'NgaySinh.before' => 'Ngày sinh phải trước ngày hôm nay.',
            'Email.required' => 'Email là bắt buộc.',
            'Email.email' => 'Email không đúng định dạng.',
            'Email.unique' => 'Email đã tồn tại.',
            'SDT.required' => 'Số điện thoại là bắt buộc.',
            'SDT.regex' => 'Số điện thoại không đúng định dạng.',
            'ID_Rap.required_if' => 'Địa chỉ làm việc là bắt buộc đối với nhân viên.',
            'Luong.required_if' => 'Lương là bắt buộc đối với nhân viên.',
            'Luong.min' => 'Lương không được âm.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Cập nhật thông tin trong bảng tai_khoan
            $taiKhoan->TenDN = $request->TenDN;

            // Chỉ cập nhật mật khẩu nếu người dùng nhập mật khẩu mới
            if ($request->filled('MatKhau')) {
                $taiKhoan->MatKhau = Hash::make($request->MatKhau);
            }

            $taiKhoan->save();

            // Cập nhật thông tin trong bảng thong_tin
            $taiKhoan->thongTin->HoTen = $request->HoTen;
            $taiKhoan->thongTin->GioiTinh = $request->GioiTinh;
            $taiKhoan->thongTin->NgaySinh = $request->NgaySinh;
            $taiKhoan->thongTin->Email = $request->Email;
            $taiKhoan->thongTin->SDT = $request->SDT;

            $taiKhoan->thongTin->save();

            DB::commit();

            return redirect()->route('tai-khoan.index')
                ->with('success', 'Tài khoản đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())
                ->withInput();
        }
    }


    // public function destroy($id)
    // {
    //     $taiKhoan = TaiKhoan::findOrFail($id);

    //     // Kiểm tra quyền xóa (chỉ admin)
    //     if (Auth::user()->VaiTro < 2) {
    //         return redirect()->route('tai-khoan.index')
    //             ->with('error', 'Bạn không có quyền xóa tài khoản.');
    //     }

    //     // Không cho phép xóa tài khoản admin
    //     if ($taiKhoan->VaiTro == 2) {
    //         return redirect()->route('tai-khoan.index')
    //             ->with('error', 'Không thể xóa tài khoản quản trị viên.');
    //     }

    //     // Không cho phép xóa chính mình
    //     if ($taiKhoan->ID_TaiKhoan == Auth::user()->ID_TaiKhoan) {
    //         return redirect()->route('tai-khoan.index')
    //             ->with('error', 'Không thể xóa tài khoản của chính mình.');
    //     }

    //     DB::beginTransaction();

    //     try {
    //         // Xóa thông tin cá nhân trước
    //         $taiKhoan->thongTin->delete();

    //         // Xóa tài khoản
    //         $taiKhoan->delete();

    //         DB::commit();

    //         return redirect()->route('tai-khoan.index')
    //             ->with('success', 'Tài khoản đã được xóa thành công.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()
    //             ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
    //     }
    // }

    /**
     * Change account status (active/inactive).
     */
    public function changeStatus($id)
    {
        $taiKhoan = TaiKhoan::findOrFail($id);
        try {
            $taiKhoan->TrangThai = !$taiKhoan->TrangThai;
            $taiKhoan->save();

            $status = $taiKhoan->TrangThai ? 'kích hoạt' : 'vô hiệu hóa';

            return redirect()->route('tai-khoan.index')
                ->with('success', "Tài khoản đã được {$status} thành công.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}
