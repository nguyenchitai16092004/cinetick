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
                'SoLuong' => 'required|numeric|min:0',
                'NgayKetThuc' => 'date|nullable',
            ], [
                'MaKhuyenMai.unique' => 'Mã khuyến mãi đã tồn tại.',
                'MaKhuyenMai.max' => 'Mã khuyến mãi không được vượt quá 100 ký tự.',
                'PhanTramGiam.required' => 'Vui lòng nhập phần trăm giảm.',
                'PhanTramGiam.integer' => 'Phần trăm giảm phải là số nguyên.',
                'PhanTramGiam.min' => 'Phần trăm giảm tối thiểu là 1%.',
                'PhanTramGiam.max' => 'Phần trăm giảm tối đa là 100%.',
                'SoLuong.min' => 'Không được số âm',
                'GiamToiDa.required' => 'Vui lòng nhập giá trị giảm tối đa.',
                'GiamToiDa.numeric' => 'Giảm tối đa phải là số.',
                'GiamToiDa.min' => 'Giảm tối đa không được âm.',
                'NgayKetThuc.date' => 'Ngày kết thúc không hợp lệ.',
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
                'SoLuong' => $request->SoLuong,
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
                'MaKhuyenMai' => 'nullable|max:100|unique:khuyen_mai,MaKhuyenMai,' . $id . ',ID_KhuyenMai',
                'DieuKienToiThieu' => 'nullable|numeric|min:0',
                'PhanTramGiam' => 'required|integer|min:1|max:100',
                'GiamToiDa' => 'required|numeric|min:0',
                'SoLuong' => 'required|numeric|min:0',
                'NgayKetThuc' => 'date|nullable',
            ], [
                'MaKhuyenMai.unique' => 'Mã khuyến mãi đã tồn tại.',
                'MaKhuyenMai.max' => 'Mã khuyến mãi không được vượt quá 100 ký tự.',
                'DieuKienToiThieu.numeric' => 'Điều kiện tối thiểu phải là số.',
                'DieuKienToiThieu.min' => 'Điều kiện tối thiểu không được âm.',
                'PhanTramGiam.required' => 'Vui lòng nhập phần trăm giảm.',
                'PhanTramGiam.integer' => 'Phần trăm giảm phải là số nguyên.',
                'PhanTramGiam.min' => 'Phần trăm giảm phải lớn hơn 0.',
                'PhanTramGiam.max' => 'Phần trăm giảm tối đa là 100%.',
                'SoLuong.min' => 'Không được số âm',
                'GiamToiDa.required' => 'Vui lòng nhập giá trị giảm tối đa.',
                'GiamToiDa.numeric' => 'Giảm tối đa phải là số.',
                'GiamToiDa.min' => 'Giảm tối đa không được âm.',
                'NgayKetThuc.date' => 'Ngày kết thúc không hợp lệ.',
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
                'SoLuong' => $request->SoLuong,
                'NgayKetThuc' => $request->NgayKetThuc,
            ]);

            return redirect()->route('khuyen-mai.index')->with('success', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function toggleStatus($id)
    {
        $km = KhuyenMai::findOrFail($id);
        $km->TrangThai = $km->TrangThai == 1 ? 0 : 1;
        $km->save();

        $message = $km->TrangThai == 1 ? 'Khôi phục khuyến mãi thành công!' : 'Đã ngưng áp dụng khuyến mãi!';
        return redirect()->route('khuyen-mai.index')->with('success', $message);
    }
}
