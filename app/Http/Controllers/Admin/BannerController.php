<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Phim;
use App\Models\TinTuc;

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
            'DuongDan' => 'required|string',
            'link' => 'required|string',
        ]);

        $path = $request->file('HinhAnh')->store('banners', 'public');

        // Tạo đường dẫn đầy đủ bằng cách kết hợp DuongDan và link
        $fullLink = $request->DuongDan . $request->link;

        DB::table('banners')->insert([
            'TieuDe' => $request->TieuDe,
            'HinhAnh' => $path,
            'Link' => $fullLink,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('cap-nhat-thong-tin.index')->with('success', 'Đã tạo banner mới.');
    }

    public function layDuLieuBangType(Request $request)
    {
        $type = $request->input('type');
        $data = [];

        if ($type === '/phim/chi-tiet-phim/') {
            $phims = DB::table('phim')->select('ID_Phim', 'TenPhim', 'Slug')->get();

            foreach ($phims as $phim) {
                $data[] = [
                        'ID_Phim' => $phim->ID_Phim,
                    'name' => $phim->TenPhim,
                    'slug' => $phim->Slug
                ];
            }
        } elseif ($type === '/goc-dien-anh/') {
            $tintucs = DB::table('tin_tuc')->select('ID_TinTuc', 'TieuDe', 'Slug')->where('TrangThai', 1)->get();

            foreach ($tintucs as $tintuc) {
                $data[] = [
                    'ID_TinTuc' => $tintuc->ID_TinTuc,
                    'name' => $tintuc->TieuDe,
                    'slug' => $tintuc->Slug
                ];
            }
        }

        return response()->json($data);
    }

    public function edit($id)
    {
        $banner = DB::table('banners')->where('id', $id)->first();
        if (!$banner) {
            abort(404);
        }

        return view('backend.pages.banner.detail-banner', compact('banner'));
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
            'Link' => $request->Link,
            'updated_at' => now(),
        ];

        if ($request->hasFile('HinhAnh')) {
            // Xoá hình cũ nếu có
            if ($banner->HinhAnh && Storage::disk('public')->exists($banner->HinhAnh)) {
                Storage::disk('public')->delete($banner->HinhAnh);
            }
            $data['HinhAnh'] = $request->file('HinhAnh')->store('banners', 'public');
        }

        DB::table('banners')->where('id', $id)->update($data);

        return redirect()->route('cap-nhat-thong-tin.index')->with('success', 'Cập nhật banner thành công!');
    }
}
