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
        return view('admin.pages.banner.create-banner');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TieuDeChinh' => 'required|string|max:100',
            'TieuDePhu' => 'required|string|max:100',
            'MoTa' => 'required|string|max:255',
            'HinhAnh' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'DuongDan' => 'required|string',
            'link' => 'required|string',
        ], [
            'TieuDeChinh.required' => 'Vui lòng nhập tiêu đề chính.',
            'TieuDeChinh.string' => 'Tiêu đề chính phải là chuỗi ký tự.',
            'TieuDeChinh.max' => 'Tiêu đề chính không được vượt quá 100 ký tự.',

            'TieuDePhu.required' => 'Vui lòng nhập tiêu đề phụ.',
            'TieuDePhu.string' => 'Tiêu đề phụ phải là chuỗi ký tự.',
            'TieuDePhu.max' => 'Tiêu đề phụ không được vượt quá 100 ký tự.',

            'MoTa.required' => 'Vui lòng nhập mô tả.',
            'MoTa.string' => 'Mô tả phải là chuỗi ký tự.',
            'MoTa.max' => 'Mô tả không được vượt quá 255 ký tự.',

            'HinhAnh.required' => 'Vui lòng chọn hình ảnh.',
            'HinhAnh.image' => 'Tệp tải lên phải là hình ảnh.',
            'HinhAnh.mimes' => 'Hình ảnh chỉ chấp nhận định dạng jpg, jpeg hoặc png.',
            'HinhAnh.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',

            'DuongDan.required' => 'Vui lòng nhập đường dẫn.',
            'DuongDan.string' => 'Đường dẫn phải là chuỗi ký tự.',

            'link.required' => 'Vui lòng nhập liên kết.',
            'link.string' => 'Liên kết phải là chuỗi ký tự.',
        ]);


        $path = $request->file('HinhAnh')->store('banners', 'public');

        // Tạo đường dẫn đầy đủ bằng cách kết hợp DuongDan và link
        $fullLink = $request->DuongDan . $request->link;

        DB::table('banners')->insert([
            'TieuDeChinh' => $request->TieuDeChinh,
            'TieuDePhu' => $request->TieuDePhu,
            'MoTa' => $request->MoTa,
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
            $tintucs = DB::table('tin_tuc')
                ->select('ID_TinTuc', 'TieuDe', 'Slug')
                ->whereIn('LoaiBaiViet', [1, 4])
                ->where('TrangThai', 1)
                ->get();

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
        return view('admin.pages.banner.detail-banner', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'TieuDeChinh' => 'required|string|max:100',
                'TieuDePhu' => 'required|string|max:100',
                'MoTa' => 'required|string|max:255',
                'HinhAnh' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'DuongDan' => 'nullable|string',
                'link' => 'nullable|string',
            ], [
                'TieuDeChinh.required' => 'Vui lòng nhập tiêu đề chính.',
                'TieuDeChinh.string' => 'Tiêu đề chính phải là chuỗi ký tự.',
                'TieuDeChinh.max' => 'Tiêu đề chính không được vượt quá 100 ký tự.',

                'TieuDePhu.required' => 'Vui lòng nhập tiêu đề phụ.',
                'TieuDePhu.string' => 'Tiêu đề phụ phải là chuỗi ký tự.',
                'TieuDePhu.max' => 'Tiêu đề phụ không được vượt quá 100 ký tự.',

                'MoTa.required' => 'Vui lòng nhập mô tả.',
                'MoTa.string' => 'Mô tả phải là chuỗi ký tự.',
                'MoTa.max' => 'Mô tả không được vượt quá 255 ký tự.',

                'HinhAnh.image' => 'Tệp tải lên phải là hình ảnh.',
                'HinhAnh.mimes' => 'Hình ảnh chỉ được chấp nhận định dạng jpg, png hoặc jpeg.',
                'HinhAnh.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',

                'DuongDan.string' => 'Đường dẫn không hợp lệ.',
                'link.string' => 'Liên kết không hợp lệ.',
            ]);


            $banner = DB::table('banners')->where('id', $id)->first();
            if (!$banner) {
                abort(404);
            }

            $data = [
                'TieuDeChinh' => $request->TieuDeChinh,
                'TieuDePhu' => $request->TieuDePhu,
                'MoTa' => $request->MoTa,
                'Link' => $request->DuongDan . $request->link,
                'updated_at' => now(),
            ];

            if ($request->hasFile('HinhAnh')) {
                if ($banner->HinhAnh && Storage::disk('public')->exists($banner->HinhAnh)) {
                    Storage::disk('public')->delete($banner->HinhAnh);
                }

                $data['HinhAnh'] = $request->file('HinhAnh')->store('banners', 'public');
            }

            DB::table('banners')->where('id', $id)->update($data);

            return redirect()->route('cap-nhat-thong-tin.index')->with('success', 'Cập nhật banner thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $banner = DB::table('banners')->where('id', $id)->first();

            if (!$banner) {
                return redirect()->back()->with('error', 'Không tìm thấy banner!');
            }

            // Xóa ảnh khỏi storage nếu tồn tại
            if ($banner->HinhAnh && Storage::disk('public')->exists($banner->HinhAnh)) {
                Storage::disk('public')->delete($banner->HinhAnh);
            }

            // Xóa dữ liệu trong database
            DB::table('banners')->where('id', $id)->delete();

            return redirect()->route('cap-nhat-thong-tin.index')->with('success', 'Xóa banner thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}
