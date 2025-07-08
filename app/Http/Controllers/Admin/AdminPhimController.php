<?php

namespace App\Http\Controllers\Admin;

use App\Models\Phim;
use App\Models\TheLoaiPhim;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AdminPhimController extends Controller
{
    public function index()
    {
        $phims = Phim::with('theLoai')
            ->orderBy('NgayKetThuc', 'desc')
            ->paginate(5);

        return view('admin.pages.phim.phim', compact('phims'));
    }

    public function create()
    {
        $theLoais = TheLoaiPhim::all();
        return view('admin.pages.phim.create_phim', compact('theLoais'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenPhim' => 'required|max:100|unique:phim,TenPhim',
            'NhaSanXuat' => 'required|max:100',
            'DaoDien' => 'required|max:100',
            'DienVien' => 'required|max:255',
            'ThoiLuong' => 'required|integer|min:1|max:180',
            'NgayKhoiChieu' => 'required|date',
            'NgayKetThuc' => 'required|date|after_or_equal:NgayKhoiChieu',
            'MoTaPhim' => 'required',
            'Trailer' => 'nullable|url|max:255',
            'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'DoTuoi' => 'required|max:100',
            'DoHoa' => 'nullable|max:50',
            'QuocGia' => 'required|max:100',
            'ID_TheLoaiPhim' => 'required|array|min:1',
            'ID_TheLoaiPhim.*' => 'exists:the_loai_phim,ID_TheLoaiPhim',
        ], [
            'TenPhim.required' => 'Vui lòng nhập tên phim.',
            'TenPhim.max' => 'Tên phim không được vượt quá 100 ký tự.',
            'TenPhim.unique' => 'Tên phim này đã tồn tại.',
            'NhaSanXuat.required' => 'Vui lòng nhập nhà sản xuất.',
            'NhaSanXuat.max' => 'Nhà sản xuất không được vượt quá 100 ký tự.',
            'DaoDien.required' => 'Vui lòng nhập tên đạo diễn.',
            'DaoDien.max' => 'Tên đạo diễn không được vượt quá 100 ký tự.',
            'DienVien.required' => 'Vui lòng nhập danh sách diễn viên.',
            'DienVien.max' => 'Danh sách diễn viên không được vượt quá 255 ký tự.',
            'ThoiLuong.required' => 'Vui lòng nhập thời lượng phim.',
            'ThoiLuong.integer' => 'Thời lượng phim phải là số nguyên.',
            'ThoiLuong.min' => 'Thời lượng phim tối thiểu là 1 phút.',
            'ThoiLuong.max' => 'Thời lượng phim tối đa là 180 phút.',
            'NgayKhoiChieu.required' => 'Vui lòng nhập ngày khởi chiếu.',
            'NgayKhoiChieu.date' => 'Ngày khởi chiếu không hợp lệ.',
            'NgayKetThuc.required' => 'Vui lòng nhập ngày kết thúc.',
            'NgayKetThuc.date' => 'Ngày kết thúc không hợp lệ.',
            'NgayKetThuc.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày khởi chiếu.',
            'MoTaPhim.required' => 'Vui lòng nhập mô tả phim.',
            'Trailer.url' => 'Trailer phải là một đường dẫn URL hợp lệ.',
            'Trailer.max' => 'Trailer không được vượt quá 255 ký tự.',
            'HinhAnh.image' => 'Tệp tải lên phải là hình ảnh.',
            'HinhAnh.mimes' => 'Hình ảnh chỉ được chấp nhận các định dạng jpeg, png, jpg hoặc gif.',
            'HinhAnh.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'DoTuoi.required' => 'Vui lòng nhập độ tuổi phù hợp.',
            'DoTuoi.max' => 'Độ tuổi không được vượt quá 100 ký tự.',
            'DoHoa.max' => 'Đồ họa không được vượt quá 50 ký tự.',
            'QuocGia.required' => 'Vui lòng nhập quốc gia sản xuất.',
            'QuocGia.max' => 'Quốc gia không được vượt quá 100 ký tự.',
            'ID_TheLoaiPhim.required' => 'Vui lòng chọn ít nhất một thể loại phim.',
            'ID_TheLoaiPhim.array' => 'Dữ liệu thể loại phim không hợp lệ.',
            'ID_TheLoaiPhim.min' => 'Phim phải có ít nhất một thể loại.',
            'ID_TheLoaiPhim.*.exists' => 'Một hoặc nhiều thể loại phim không hợp lệ.',
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
            'NhaSanXuat' => $request->NhaSanXuat,
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
        return view('admin.pages.phim.detail_phim', compact('phim', 'theLoais'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'TenPhim' => 'required|max:100',
            'NhaSanXuat' => 'required|max:100',
            'DaoDien' => 'required|max:100',
            'DienVien' => 'required|max:255',
            'ThoiLuong' => 'required|integer|min:1|max:180',
            'NgayKhoiChieu' => 'required|date',
            'NgayKetThuc' => 'nullable|date|after_or_equal:NgayKhoiChieu',
            'MoTaPhim' => 'required',
            'Trailer' => 'nullable|url|max:255',
            'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'DoTuoi' => 'required|max:100',
            'DoHoa' => 'nullable|max:50',
            'QuocGia' => 'required|max:100',
            'ID_TheLoaiPhim' => 'required|array|min:1',
            'ID_TheLoaiPhim.*' => 'exists:the_loai_phim,ID_TheLoaiPhim',
        ], [
            'TenPhim.required' => 'Vui lòng nhập tên phim.',
            'TenPhim.max' => 'Tên phim không được vượt quá 100 ký tự.',
            'NhaSanXuat.required' => 'Vui lòng nhập nhà sản xuất.',
            'NhaSanXuat.max' => 'Nhà sản xuất không được vượt quá 100 ký tự.',
            'DaoDien.required' => 'Vui lòng nhập tên đạo diễn.',
            'DaoDien.max' => 'Tên đạo diễn không được vượt quá 100 ký tự.',
            'DienVien.required' => 'Vui lòng nhập danh sách diễn viên.',
            'DienVien.max' => 'Danh sách diễn viên không được vượt quá 255 ký tự.',
            'ThoiLuong.required' => 'Vui lòng nhập thời lượng phim.',
            'ThoiLuong.integer' => 'Thời lượng phim phải là số nguyên.',
            'ThoiLuong.min' => 'Thời lượng phim phải ít nhất 1 phút.',
            'ThoiLuong.max' => 'Thời lượng phim tối đa là 180 phút.',
            'NgayKhoiChieu.required' => 'Vui lòng nhập ngày khởi chiếu.',
            'NgayKhoiChieu.date' => 'Ngày khởi chiếu không hợp lệ.',
            'NgayKetThuc.date' => 'Ngày kết thúc không hợp lệ.',
            'NgayKetThuc.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày khởi chiếu.',
            'MoTaPhim.required' => 'Vui lòng nhập mô tả phim.',
            'Trailer.url' => 'Trailer phải là một liên kết URL hợp lệ.',
            'Trailer.max' => 'Trailer không được vượt quá 255 ký tự.',
            'HinhAnh.image' => 'Tệp tải lên phải là hình ảnh.',
            'HinhAnh.mimes' => 'Hình ảnh chỉ được chấp nhận định dạng jpeg, png, jpg hoặc gif.',
            'HinhAnh.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'DoTuoi.required' => 'Vui lòng nhập độ tuổi phù hợp.',
            'DoTuoi.max' => 'Độ tuổi không được vượt quá 100 ký tự.',
            'DoHoa.max' => 'Đồ họa không được vượt quá 50 ký tự.',
            'QuocGia.required' => 'Vui lòng nhập quốc gia sản xuất.',
            'QuocGia.max' => 'Quốc gia không được vượt quá 100 ký tự.',
            'ID_TheLoaiPhim.required' => 'Vui lòng chọn ít nhất một thể loại phim.',
            'ID_TheLoaiPhim.array' => 'Dữ liệu thể loại phim không hợp lệ.',
            'ID_TheLoaiPhim.min' => 'Phim phải có ít nhất một thể loại.',
            'ID_TheLoaiPhim.*.exists' => 'Một hoặc nhiều thể loại phim không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $phim = Phim::findOrFail($id);

        $hinhAnhPath = $phim->HinhAnh;
        if ($request->hasFile('HinhAnh')) {
            if ($phim->HinhAnh) {
                Storage::disk('public')->delete($phim->HinhAnh);
            }

            $file = $request->file('HinhAnh');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('phim', $fileName, 'public');
            $hinhAnhPath = 'phim/' . $fileName;
        }

        $phim->update([
            'TenPhim' => $request->TenPhim,
            'Slug' => Str::slug($request->TenPhim),
            'NhaSanXuat' => $request->NhaSanXuat,
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
            Storage::disk('public')->delete($phim->HinhAnh);
        }
        $phim->theLoai()->detach();
        $phim->delete();

        return redirect()->route('phim.index')->with('success', 'Xóa phim thành công!');
    }
}
