<?php

namespace App\Http\Controllers\Admin;

use App\Models\Phim;
use App\Models\TheLoaiPhim;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AdminPhimController extends Controller
{
    public function index()
    {
        $phims = Phim::with('theLoai')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('backend.pages.phim.phim', compact('phims'));
    }

    public function create()
    {
        $theLoais = TheLoaiPhim::all();
        return view('backend.pages.phim.create_phim', compact('theLoais'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenPhim' => 'required|max:100|unique:phim,TenPhim',
            'DaoDien' => 'required|max:100',
            'DienVien' => 'required|max:255',
            'ThoiLuong' => 'required|integer|min:1|max:180',
            'NgayKhoiChieu' => 'required|date',
            'NgayKetThuc' => 'nullable|date|after_or_equal:NgayKhoiChieu',
            'MoTaPhim' => 'required',
            'Trailer' => 'nullable|url|max:255',
            'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'DoTuoi' => 'required|max:100',
            'DoHoa' => 'required|max:50',
            'QuocGia' => 'required|max:100',
            'ID_TheLoaiPhim' => 'required|array|min:1',
            'ID_TheLoaiPhim.*' => 'exists:the_loai_phim,ID_TheLoaiPhim',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hinhAnhPath = null;
        if ($request->hasFile('HinhAnh')) {
            $file = $request->file('HinhAnh');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('phim', $fileName, 'public');
            $hinhAnhPath = 'phim/' . $fileName;
        }

        $phim = Phim::create([
            'TenPhim' => $request->TenPhim,
            'Slug' => Str::slug($request->TenPhim),
            'DaoDien' => $request->DaoDien,
            'DienVien' => $request->DienVien,
            'ThoiLuong' => $request->ThoiLuong,
            'NgayKhoiChieu' => $request->NgayKhoiChieu,
            'NgayKetThuc' => $request->NgayKetThuc,
            'MoTaPhim' => $request->MoTaPhim,
            'Trailer' => $request->Trailer,
            'HinhAnh' => $hinhAnhPath,
            'DoTuoi' => $request->DoTuoi,
            'DoHoa' => $request->DoHoa,
            'QuocGia' => $request->QuocGia,
            'TrangThai' => $request->TrangThai,
        ]);

        $phim->theLoai()->attach($request->ID_TheLoaiPhim);

        return redirect()->route('phim.index')->with('success', 'Thêm phim mới thành công!');
    }

    public function show($id)
    {
        $phim = Phim::with('theLoai')->findOrFail($id);
        $theLoais = TheLoaiPhim::all();
        return view('backend.pages.phim.detail_phim', compact('phim', 'theLoais'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'TenPhim' => 'required|max:100',
            'DaoDien' => 'required|max:100',
            'DienVien' => 'required|max:255',
            'ThoiLuong' => 'required|integer|min:1|max:180',
            'NgayKhoiChieu' => 'required|date',
            'NgayKetThuc' => 'nullable|date|after_or_equal:NgayKhoiChieu',
            'MoTaPhim' => 'required',
            'Trailer' => 'nullable|url|max:255',
            'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'DoTuoi' => 'required|max:100',
            'DoHoa' => 'required|max:50',
            'QuocGia' => 'required|max:100',
            'ID_TheLoaiPhim' => 'required|array|min:1',
            'ID_TheLoaiPhim.*' => 'exists:the_loai_phim,ID_TheLoaiPhim',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $phim = Phim::findOrFail($id);

        $hinhAnhPath = $phim->HinhAnh;
        if ($request->hasFile('HinhAnh')) {
            if ($phim->HinhAnh) {
                Storage::delete('public/' . $phim->HinhAnh);
            }
            $file = $request->file('HinhAnh');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('phim', $fileName, 'public');
            $hinhAnhPath = 'phim/' . $fileName;
        }

        $phim->update([
            'TenPhim' => $request->TenPhim,
            'Slug' => Str::slug($request->TenPhim),
            'DaoDien' => $request->DaoDien,
            'DienVien' => $request->DienVien,
            'ThoiLuong' => $request->ThoiLuong,
            'NgayKhoiChieu' => $request->NgayKhoiChieu,
            'NgayKetThuc' => $request->NgayKetThuc,
            'MoTaPhim' => $request->MoTaPhim,
            'Trailer' => $request->Trailer,
            'HinhAnh' => $hinhAnhPath,
            'DoTuoi' => $request->DoTuoi,
            'DoHoa' => $request->DoHoa,
            'QuocGia' => $request->QuocGia,
            'TrangThai' => $request->TrangThai,
        ]);

        $phim->theLoai()->sync($request->ID_TheLoaiPhim);

        return redirect()->route('phim.index')->with('success', 'Cập nhật phim thành công!');
    }

    public function destroy($id)
    {
        $phim = Phim::findOrFail($id);
        if ($phim->HinhAnh) {
            Storage::delete('public/' . $phim->HinhAnh);
        }
        $phim->theLoai()->detach();
        $phim->delete();

        return redirect()->route('phim.index')->with('success', 'Xóa phim thành công!');
    }
}
