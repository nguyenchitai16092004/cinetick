<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TinTuc;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TinTucController extends Controller
{
    public function index()
    {
        $query = TinTuc::join('tai_khoan', 'tin_tuc.ID_TaiKhoan', '=', 'tai_khoan.ID_TaiKhoan')
            ->select('tin_tuc.*', 'tai_khoan.TenDN');

        if (request()->filled('loai_bai_viet')) {
            $query->where('tin_tuc.LoaiBaiViet', request('loai_bai_viet'));
        }

        $tinTucs = $query->paginate(5)->appends(request()->query());

        return view('admin.pages.tin_tuc.tin-tuc', compact('tinTucs'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        return view('admin.pages.tin_tuc.create-tin-tuc');
    }

    // Lưu tin tức mới
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TieuDe' => 'required|max:100',
            'NoiDung' => 'required',
            'LoaiBaiViet' => 'required|in:1,2,3,4',
            'TrangThai' => 'required|in:0,1',
            'AnhDaiDien' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            Log::error('Lỗi validate khi thêm tin tức:', [
                'errors' => $validator->errors()->all(),
                'user_id' => session('user_id'),
                'input' => $request->except(['_token', 'AnhDaiDien'])
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['TieuDe', 'NoiDung', 'LoaiBaiViet', 'TrangThai']);
        $data['ID_TaiKhoan'] = session('user_id');

        // Tạo slug từ tiêu đề
        $data['Slug'] = Str::slug($data['TieuDe']);

        if (!$data['ID_TaiKhoan']) {
            Log::warning('Thêm tin tức thất bại: Không có user_id trong session.');
            return redirect()->back()->with('error', 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!');
        }
        if ($request->hasFile('AnhDaiDien')) {
            $file = $request->file('AnhDaiDien');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('tin-tuc', $fileName, 'public');
            $data['AnhDaiDien'] = 'tin-tuc/' . $fileName;
        }
        TinTuc::create($data);

        Log::info('Tin tức mới đã được thêm thành công', [
            'user_id' => $data['ID_TaiKhoan'],
            'TieuDe' => $data['TieuDe'],
        ]);

        return redirect()->route('tin_tuc.index')->with('success', 'Thêm tin tức thành công!');    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $tinTuc = TinTuc::findOrFail($id);
        return view('admin.pages.tin_tuc.detail-tin-tuc', compact('tinTuc'));
    }

    // Cập nhật tin tức
    public function update(Request $request, $id)
    {
        $tinTuc = TinTuc::findOrFail($id);

        $request->validate([
            'TieuDe' => 'required|max:100',
            'NoiDung' => 'required',
            'LoaiBaiViet' => 'required|in:1,2,3,4',
            'TrangThai' => 'required|in:0,1',
            'AnhDaiDien' => 'nullable|image',
        ]);

        $data = $request->only(['TieuDe', 'NoiDung', 'LoaiBaiViet', 'TrangThai']);
        $data['ID_TaiKhoan'] = session('user_id') ?? $tinTuc->ID_TaiKhoan;

        $data['Slug'] = Str::slug($data['TieuDe']);

        if ($request->hasFile('AnhDaiDien')) {
            $file = $request->file('AnhDaiDien');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('tin-tuc', $fileName, 'public');
            $data['AnhDaiDien'] = 'tin-tuc/' . $fileName;
        }

        $tinTuc->update($data);

        return redirect()->route('tin_tuc.index')->with('success', 'Cập nhật tin tức thành công');
    }

    // Xóa tin tức
    public function destroy($id)
    {
        $tinTuc = TinTuc::findOrFail($id);
        $tinTuc->delete();

        return redirect()->route('tin_tuc.index')->with('success', 'Xóa tin tức thành công');
    }
    
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('tin-tuc', $fileName, 'public');
            $url = asset('storage/tin-tuc/' . $fileName);
            return response()->json(['location' => $url]);
        }
        return response()->json(['error' => 'No file uploaded.'], 400);
    }

}