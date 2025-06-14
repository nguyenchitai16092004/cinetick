<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TheLoaiPhim;
use Illuminate\Support\Str;

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
            'TenTheLoai' => 'required|string|max:100',
        ]);

        $slug =  Str::slug($request->TenTheLoai);

        if (TheLoaiPhim::where('Slug', $slug)->exists()) {
            return back()->with('error', 'Thể loại này đã có rồi làm lại đi ');
        }

        TheLoaiPhim::create([
            'TenTheLoai' => $request->TenTheLoai,
            'Slug' => $slug
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
            'TenTheLoai' => 'required|string|max:100',
        ]);

        $slug =  Str::slug($request->TenTheLoai);

        if (TheLoaiPhim::where('Slug', $slug)->exists()) {
            return back()->with('error', 'Thể loại này đã có rồi làm lại đi ');
        }

        $theloai = TheLoaiPhim::findOrFail($id);
        $theloai->update([
            'TenTheLoai' => $request->TenTheLoai,
            'Slug' => $slug,
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
