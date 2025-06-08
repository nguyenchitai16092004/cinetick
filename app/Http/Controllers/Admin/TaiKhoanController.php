<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Models\ThongTin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaiKhoanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'VaiTro');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = TaiKhoan::join('thong_tin', 'tai_khoan.ID_CCCD', '=', 'thong_tin.ID_CCCD')
            ->select('tai_khoan.*', 'thong_tin.HoTen', 'thong_tin.Email', 'thong_tin.SDT');

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

        return view('backend.pages.tai_khoan.tai-khoan', compact('taiKhoans', 'search', 'sortBy', 'sortOrder'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create()
    {
        return view('backend.pages.tai_khoan.create-tai-khoan');
    }

    /**
     * Store a newly created account in database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ID_CCCD' => 'required|numeric|unique:thong_tin,ID_CCCD',
            'HoTen' => 'required|string|max:100',
            'GioiTinh' => 'required|boolean',
            'NgaySinh' => 'required|date',
            'Email' => 'required|email|max:100',
            'SDT' => 'required|string|max:15',
            'TenDN' => 'required|string|max:50|unique:tai_khoan,TenDN',
            'MatKhau' => 'required|string|min:6|max:100',
            'VaiTro' => 'required|integer|min:0|max:2',
            'TrangThai' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Bắt đầu transaction để đảm bảo tính nhất quán dữ liệu
        DB::beginTransaction();

        try {
            // Tạo thông tin người dùng
            $thongTin = new ThongTin();
            $thongTin->ID_CCCD = $request->ID_CCCD;
            $thongTin->HoTen = $request->HoTen;
            $thongTin->GioiTinh = $request->GioiTinh;
            $thongTin->NgaySinh = $request->NgaySinh;
            $thongTin->Email = $request->Email;
            $thongTin->SDT = $request->SDT;
            $thongTin->save();

            // Tạo tài khoản
            $taiKhoan = new TaiKhoan();
            $taiKhoan->TenDN = $request->TenDN;
            $taiKhoan->MatKhau = Hash::make($request->MatKhau);
            $taiKhoan->VaiTro = $request->VaiTro;
            $taiKhoan->TrangThai = $request->TrangThai;
            $taiKhoan->ID_CCCD = $request->ID_CCCD;
            $taiKhoan->save();

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

    /**
     * Show the form for editing the specified account.
     */
    public function edit($id)
    {
        $taiKhoan = TaiKhoan::join('thong_tin', 'tai_khoan.ID_CCCD', '=', 'thong_tin.ID_CCCD')
            ->select('tai_khoan.*', 'thong_tin.*')
            ->where('tai_khoan.ID_TaiKhoan', $id)
            ->firstOrFail();

        return view('backend.pages.tai_khoan.detail-tai-khoan', compact('taiKhoan'));
    }

    /**
     * Update the specified account in storage.
     */
    public function update(Request $request, $id)
    {
        $taiKhoan = TaiKhoan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'HoTen' => 'required|string|max:100',
            'GioiTinh' => 'required|boolean',
            'NgaySinh' => 'required|date',
            'Email' => 'required|email|max:100',
            'SDT' => 'required|string|max:15',
            'TenDN' => 'required|string|max:50|unique:tai_khoan,TenDN,' . $id . ',ID_TaiKhoan',
            'MatKhau' => 'nullable|string|min:6|max:100',
            'VaiTro' => 'required|integer|min:0|max:2',
            'TrangThai' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Cập nhật thông tin người dùng
            $thongTin = ThongTin::where('ID_CCCD', $taiKhoan->ID_CCCD)->firstOrFail();
            $thongTin->HoTen = $request->HoTen;
            $thongTin->GioiTinh = $request->GioiTinh;
            $thongTin->NgaySinh = $request->NgaySinh;
            $thongTin->Email = $request->Email;
            $thongTin->SDT = $request->SDT;
            $thongTin->save();

            // Cập nhật tài khoản
            $taiKhoan->TenDN = $request->TenDN;
            if (!empty($request->MatKhau)) {
                $taiKhoan->MatKhau = Hash::make($request->MatKhau);
            }
            $taiKhoan->VaiTro = $request->VaiTro;
            $taiKhoan->TrangThai = $request->TrangThai;
            $taiKhoan->save();

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

    /**
     * Show confirmation before account deletion.
     */
    public function Delete($id)
    {
        $taiKhoan = TaiKhoan::join('thong_tin', 'tai_khoan.ID_CCCD', '=', 'thong_tin.ID_CCCD')
            ->select('tai_khoan.ID_TaiKhoan', 'thong_tin.HoTen', 'tai_khoan.TenDN')
            ->where('tai_khoan.ID_TaiKhoan', $id)
            ->firstOrFail();

        return view('backend.pages.tai-khoan.confirm-delete', compact('taiKhoan'));
    }

    /**
     * Remove the specified account from storage.
     */
    public function destroy($id)
    {
        $taiKhoan = TaiKhoan::findOrFail($id);
        $idCCCD = $taiKhoan->ID_CCCD;

        DB::beginTransaction();

        try {
            // Xóa tài khoản sẽ tự động xóa thông tin người dùng do có constraint cascade
            $taiKhoan->delete();

            DB::commit();

            return redirect()->route('tai-khoan.index')
                ->with('success', 'Tài khoản đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Change account status (active/inactive).
     */
    public function changeStatus($id)
    {
        $taiKhoan = TaiKhoan::findOrFail($id);
        $taiKhoan->TrangThai = !$taiKhoan->TrangThai;
        $taiKhoan->save();

        return redirect()->route('tai-khoan.index')
            ->with('success', 'Trạng thái tài khoản đã được thay đổi thành công.');
    }
}
