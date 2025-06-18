<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function create()
    {
        return view('backend.pages.banner.create-banner');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TieuDe' => 'required|string|max:255',
            'HinhAnh' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'Link' => 'nullable|url',
        ]);

        $path = $request->file('HinhAnh')->store('banners', 'public');

        DB::table('banners')->insert([
            'TieuDe' => $request->TieuDe,
            'HinhAnh' => $path,
            'Link' => $request->link,
        ]);

        return redirect()->route('cap-nhat-thong-tin.index')->with('success', 'Đã tạo banner mới.');
    }

    public function edit($id)
    {
        $banner = DB::table('banner')->where('id', $id)->first();
        if (!$banner) {
            abort(404);
        }

        return view('backend.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TieuDe' => 'required|string|max:255',
            'HinhAnh' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'Link' => 'nullable|url',
        ]);

        $banner = DB::table('banners')->where('id', $id)->first();
        if (!$banner) {
            abort(404);
        }

        $data = [
            'TieuDe' => $request->TieuDe,
            'Link' => $request->link,
        ];

        if ($request->hasFile('HinhAnh')) {
            // Xoá hình cũ nếu có
            Storage::disk('public')->delete($banner->HinhAnh);
            $data['HinhAnh'] = $request->file('HinhAnh')->store('banners', 'public');
        }

        DB::table('banner')->where('id', $id)->update($data);

        return redirect()->route('cap-nhat-thong-tin.index')->with('success', 'Cập nhật banner thành công!');
    }
}
