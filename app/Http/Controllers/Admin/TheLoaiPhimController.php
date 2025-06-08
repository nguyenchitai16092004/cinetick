<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TheLoaiPhim;

class TheLoaiPhimController extends Controller
{
    public function index()
    {
        $theloais = TheLoaiPhim::all();
        return view('backend.pages.the_loai.the-loai', compact('theloais'));
    }

    public function create()
    {
        return view('backend.pages.the_loai.create-the-loai');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenTheLoai' => 'required|string|max:255',
        ]);

        TheLoaiPhim::create([
            'TenTheLoai' => $request->TenTheLoai,
        ]);

        return redirect()->route('the-loai.index')->with('success', 'Thêm thể loại thành công!');
    }

    public function edit($id)
    {
        $theloai = TheLoaiPhim::findOrFail($id);
        return view('backend.pages.the_loai.detail-the-loai', compact('theloai'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenTheLoai' => 'required|string|max:255',
        ]);

        $theloai = TheLoaiPhim::findOrFail($id);
        $theloai->update([
            'TenTheLoai' => $request->TenTheLoai,
        ]);

        return redirect()->route('the-loai.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $theloai = TheLoaiPhim::findOrFail($id);
        $theloai->delete();

        return redirect()->route('the-loai.index')->with('success', 'Xoá thành công!');
    }
}
