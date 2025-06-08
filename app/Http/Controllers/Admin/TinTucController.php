<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TinTuc;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TinTucController extends Controller
{
    public function index()
    {
        $tinTucs = TinTuc::join('tai_khoan', 'tin_tuc.ID_TaiKhoan', '=', 'tai_khoan.ID_TaiKhoan')
            ->select('tin_tuc.*', 'tai_khoan.TenDN')
            ->get();
        return view('backend.pages.tin_tuc.tin-tuc', compact('tinTucs'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        return view('backend.pages.tin_tuc.create-tin-tuc');
    }

    // Lưu tin tức mới
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TieuDe' => 'required|max:100',
            'NoiDung' => 'required',
            'LoaiBaiViet' => 'required|in:0,1',
            'HinhAnh' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            Log::error('Lỗi validate khi thêm tin tức:', [
                'errors' => $validator->errors()->all(),
                'user_id' => session('user_id'),
                'input' => $request->except(['_token', 'HinhAnh'])
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['TieuDe', 'NoiDung', 'LoaiBaiViet']); // bỏ _token
        $data['ID_TaiKhoan'] = session('user_id');

        if (!$data['ID_TaiKhoan']) {
            Log::warning('Thêm tin tức thất bại: Không có user_id trong session.');
            return redirect()->back()->with('error', 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!');
        }
        if ($request->hasFile('HinhAnh')) {
            $file = $request->file('HinhAnh');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('tin-tuc', $fileName, 'public'); // Lưu vào storage/app/public/tin-tuc
            $data['HinhAnh'] = 'storage/tin-tuc/' . $fileName; // Đường dẫn truy cập từ public
        }

        TinTuc::create($data);

        Log::info('Tin tức mới đã được thêm thành công', [
            'user_id' => $data['ID_TaiKhoan'],
            'TieuDe' => $data['TieuDe'],
        ]);

        return redirect()->back()->with('success', 'Thêm tin tức thành công!');
    }




    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $tinTuc = TinTuc::findOrFail($id);
        return view('backend.pages.tin_tuc.detail-tin-tuc', compact('tinTuc'));
    }

    // Cập nhật tin tức
    public function update(Request $request, $id)
    {
        $tinTuc = TinTuc::findOrFail($id);

        $request->validate([
            'TieuDe' => 'required|max:100',
            'NoiDung' => 'required',
            'LoaiBaiViet' => 'required|in:0,1',
            'HinhAnh' => 'nullable|image',
        ]);

        $data = $request->only(['TieuDe', 'NoiDung', 'LoaiBaiViet']);

        // Gán lại ID_TaiKhoan từ session hoặc giữ nguyên
        $data['ID_TaiKhoan'] = session('user_id') ?? $tinTuc->ID_TaiKhoan;

        // Xử lý ảnh mới nếu có
        if ($request->hasFile('HinhAnh')) {
            $file = $request->file('HinhAnh');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('tin-tuc', $fileName, 'public');
            $data['HinhAnh'] = 'storage/tin-tuc/' . $fileName;
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
}
