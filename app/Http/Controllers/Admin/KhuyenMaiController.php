<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KhuyenMai;

class KhuyenMaiController extends Controller
{
    public function index()
    {
        $dsKhuyenMai = KhuyenMai::all();
        return view('backend.pages.phieu_giam_gia.phieu-giam-gia', compact('dsKhuyenMai'));
    }

    public function create()
    {
        return view('backend.pages.phieu_giam_gia.create-phieu-giam-gia');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenKhuyenMai' => 'required|max:100|unique:khuyen_mai,TenKhuyenMai',
            'PhanTramGiam' => 'required|integer|min:1|max:100',
            'GiaTriToiDa' => 'required|numeric|min:0',
        ]);

        KhuyenMai::create($request->all());

        return redirect()->route('khuyen-mai.index')->with('success', 'Đã thêm khuyến mãi mới!');
    }

    public function edit($id)
    {
        $km = KhuyenMai::findOrFail($id);
        return view('backend.pages.phieu_giam_gia.detail-phieu-giam-gia', compact('km'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenKhuyenMai' => 'required|max:100|unique:khuyen_mai,TenKhuyenMai',
            'PhanTramGiam' => 'required|integer|min:1|max:100',
            'GiaTriToiDa' => 'required|numeric|min:0',
        ]);

        $km = KhuyenMai::findOrFail($id);
        $km->update($request->all());

        return redirect()->route('khuyen-mai.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        KhuyenMai::findOrFail($id)->delete();
        return redirect()->route('khuyen-mai.index')->with('success', 'Xoá thành công!');
    }
}
