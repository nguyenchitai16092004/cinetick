<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThongTinTrangWeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $thongTin = ThongTinTrangWeb::first();
        return view('backend.pages.home', compact('thongTin'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'Logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Hotline' => 'nullable|string|max:15|regex:/^[0-9]{9,15}$/',
            'Zalo' => 'nullable|string|max:50',
            'Facebook' => 'nullable|string|max:100',
            'Instagram' => 'nullable|string|max:100',
            'Email' => 'nullable|email|max:100',
            'DiaChi' => 'nullable|string|max:255',
        ]);

        // Lấy bản ghi đầu tiên, nếu chưa có thì tạo bản ghi rỗng
        $thongTin = ThongTinTrangWeb::first();
        if (!$thongTin) {
            $thongTin = ThongTinTrangWeb::create([]); // tạo dòng rỗng
        }

        $data = $request->only(['Zalo','Hotline', 'Facebook', 'Instagram', 'Email', 'DiaChi']);

        if ($request->hasFile('Logo')) {
            if ($thongTin->Logo) {
                Storage::disk('public')->delete($thongTin->Logo);
            }
            $path = $request->file('Logo')->store('logos', 'public');
            $data['Logo'] = $path;
        }

        $thongTin->update($data);

        return redirect()->route('cap-nhat-thong-tin.index')->with('success', 'Cập nhật thông tin trang web thành công.');
    }
}
