<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rap;
use App\Models\PhongChieu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RapController extends Controller
{
    public function index()
    {
        $raps = Rap::all();
        return view('admin.pages.rap.rap', compact('raps'));
    }

    public function create()
    {
        return view('admin.pages.rap.quan-ly-rap');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'TenRap' => 'required|max:100',
                'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'MoTa' => 'required',
                'Hotline' => 'nullable|string|max:20|regex:/^[0-9]{5,15}$/',
                'DiaChi' => 'required|max:255',
                'TrangThai' => 'required|max:50',
            ]);

            $hinhAnhPath = null;
            if ($request->hasFile('HinhAnh')) {
                $file = $request->file('HinhAnh');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('rap', $fileName, 'public');
                $hinhAnhPath = 'rap/' . $fileName;
            }

            Rap::create([
                'TenRap' => $request->TenRap,
                'HinhAnh' => $hinhAnhPath,
                'Slug' => Str::slug($request->TenRap),
                'MoTa' => $request->MoTa,
                'Hotline' => $request->Hotline,
                'DiaChi' => $request->DiaChi,
                'TrangThai' => $request->TrangThai,
            ]);

            return redirect()->route('rap.index')->with('success', 'Rạp đã được thêm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }


    public function edit($id)
    {
        $rap = Rap::where('ID_Rap', '=', $id)->get();
        return view('admin.pages.rap.quan-ly-rap', compact('rap'));
    }

    public function update(Request $request, $id)
    {
        $rap = Rap::where('ID_Rap', '=', $id)->first();

        $request->validate([
            'TenRap' => 'required|max:100',
            'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'MoTa' => 'required',
            'Hotline' => 'nullable|string|max:20|regex:/^[0-9]{5,15}$/',
            'DiaChi' => 'required|max:255',
            'TrangThai' => 'required|max:50',
        ]);

        $hinhAnhPath = $rap->HinhAnh;
        if ($request->hasFile('HinhAnh')) {
            if ($rap->HinhAnh) {
                Storage::disk('public')->delete($rap->HinhAnh);
            }
            $file = $request->file('HinhAnh');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('rap', $fileName, 'public');
            $hinhAnhPath = 'rap/' . $fileName;
        }

        $data = $request->only([
            'TenRap',
            'MoTa',
            'Hotline',
            'DiaChi',
            'TrangThai'
        ]);
        $data['Slug'] = Str::slug($request->TenRap);
        $data['HinhAnh'] = $hinhAnhPath;

        $rap->update($data);

        return redirect()->route('rap.index')->with('success', 'Rạp đã sửa thành công');
    }

    public function destroy($id)
    {
        $rap = Rap::where('ID_Rap', $id)->first();

        if (!$rap) {
            return redirect()->route('rap.index')->with('error', 'Không tìm thấy rạp để xóa');
        }
        $soPhongChieu = PhongChieu::where('ID_Rap', $id)->count();
        if ($soPhongChieu > 0) {
            return redirect()->route('rap.index')->with('error', 'Bạn phải xóa những phòng liên quan đến rạp này mới xóa được');
        }

        if ($rap->HinhAnh && Storage::disk('public')->exists($rap->HinhAnh)) {
            Storage::disk('public')->delete($rap->HinhAnh);
        }

        $rap->delete();

        return redirect()->route('rap.index')->with('success', 'Rạp đã được xóa thành công');
    }
}
