<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KhuyenMai;
use Illuminate\Support\Str;

class KhuyenMaiController extends Controller
{
    public function index()
    {
        $dsKhuyenMai = KhuyenMai::all();
        return view('admin.pages.phieu_giam_gia.phieu-giam-gia', compact('dsKhuyenMai'));
    }

    public function create()
    {
        return view('admin.pages.phieu_giam_gia.create-phieu-giam-gia');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'MaKhuyenMai' => 'nullable|max:100|unique:khuyen_mai,MaKhuyenMai',
                'DieuKienToiThieu' => 'nullable|numeric|min:0',
                'PhanTramGiam' => 'required|integer|min:1|max:100',
                'GiamToiDa' => 'required|numeric|min:0',
                'NgayKetThuc' => 'date|nullable',
            ]);

            $maKhuyenMai = $request->MaKhuyenMai;
            if (empty($maKhuyenMai)) {
                $maKhuyenMai = strtoupper(Str::random(6));
            } else {
                $maKhuyenMai = strtoupper($maKhuyenMai);
            }

            KhuyenMai::create([
                'MaKhuyenMai' => $maKhuyenMai,
                'DieuKienToiThieu' => $request->DieuKienToiThieu,
                'PhanTramGiam' => $request->PhanTramGiam,
                'GiamToiDa' => $request->GiamToiDa,
                'NgayKetThuc' => $request->NgayKetThuc,
            ]);

            return redirect()->route('khuyen-mai.index')->with('success', 'Đã thêm khuyến mãi mới!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }


    public function edit($id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);
        return view('admin.pages.phieu_giam_gia.detail-phieu-giam-gia', compact('khuyenMai'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'MaKhuyenMai' => 'nullable|max:100',
                'DieuKienToiThieu' => 'nullable|numeric|min:0',
                'PhanTramGiam' => 'required|integer|min:1|max:100',
                'GiamToiDa' => 'required|numeric|min:0',
                'NgayKetThuc' => 'date|nullable',
            ]);

            $maKhuyenMai = $request->MaKhuyenMai;
            if (empty($maKhuyenMai)) {
                $maKhuyenMai = strtoupper(Str::random(6));
            } else {
                $maKhuyenMai = strtoupper($maKhuyenMai);
            }

            $km = KhuyenMai::findOrFail($id);
            $km->update([
                'MaKhuyenMai' => $maKhuyenMai,
                'DieuKienToiThieu' => $request->DieuKienToiThieu,
                'PhanTramGiam' => $request->PhanTramGiam,
                'GiamToiDa' => $request->GiamToiDa,
                'NgayKetThuc' => $request->NgayKetThuc,
            ]);

            return redirect()->route('khuyen-mai.index')->with('success', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        KhuyenMai::findOrFail($id)->delete();
        return redirect()->route('khuyen-mai.index')->with('success', 'Xoá thành công!');
    }
}
