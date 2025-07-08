<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rap;
use App\Models\PhongChieu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RapController extends Controller
{

public function index()
{
    $rapChieus = Rap::orderBy('TrangThai', 'desc')
               ->orderBy('TenRap', 'asc')
               ->get();
    
    return view('admin.pages.rap.rap', compact('rapChieus'));
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
            ], [
                'TenRap.required' => 'Vui lòng nhập tên rạp.',
                'TenRap.max' => 'Tên rạp không được vượt quá 100 ký tự.',

                'HinhAnh.image' => 'Tệp tải lên phải là hình ảnh.',
                'HinhAnh.mimes' => 'Hình ảnh chỉ chấp nhận định dạng jpeg, png, jpg hoặc gif.',
                'HinhAnh.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',

                'MoTa.required' => 'Vui lòng nhập mô tả.',

                'Hotline.string' => 'Hotline phải là chuỗi ký tự.',
                'Hotline.max' => 'Hotline không được vượt quá 20 ký tự.',
                'Hotline.regex' => 'Hotline chỉ được chứa số và có độ dài từ 5 đến 15 chữ số.',

                'DiaChi.required' => 'Vui lòng nhập địa chỉ.',
                'DiaChi.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

                'TrangThai.required' => 'Vui lòng chọn trạng thái.',
                'TrangThai.max' => 'Trạng thái không được vượt quá 50 ký tự.',
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
        ], [
            'TenRap.required' => 'Vui lòng nhập tên rạp.',
            'TenRap.max' => 'Tên rạp không được vượt quá 100 ký tự.',

            'HinhAnh.image' => 'Tệp tải lên phải là hình ảnh.',
            'HinhAnh.mimes' => 'Hình ảnh chỉ được chấp nhận định dạng jpeg, png, jpg hoặc gif.',
            'HinhAnh.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',

            'MoTa.required' => 'Vui lòng nhập mô tả cho rạp.',

            'Hotline.string' => 'Hotline phải là chuỗi ký tự.',
            'Hotline.max' => 'Hotline không được vượt quá 20 ký tự.',
            'Hotline.regex' => 'Hotline chỉ được chứa chữ số và phải từ 5 đến 15 chữ số.',

            'DiaChi.required' => 'Vui lòng nhập địa chỉ rạp.',
            'DiaChi.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

            'TrangThai.required' => 'Vui lòng chọn trạng thái.',
            'TrangThai.max' => 'Trạng thái không được vượt quá 50 ký tự.',
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
        $createdAt = $rap->created_at;
        $now = Carbon::now();
        $timeDelete = $createdAt->copy()->addMinutes(5);

        if ($timeDelete < ($now)) {
            return redirect()->route('rap.index')
                ->with('error', 'Chỉ có thể xóa rạp đã tạo trong 5 phút kể từ lúc tạo.');
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
