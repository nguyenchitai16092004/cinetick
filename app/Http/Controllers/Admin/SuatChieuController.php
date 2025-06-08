<?php

namespace App\Http\Controllers\Admin;

use App\Models\Phim;
use App\Models\PhongChieu;
use App\Models\SuatChieu;
use App\Http\Controllers\Controller;
use App\Models\Rap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuatChieuController extends Controller
{
    public function index()
    {
        $suatChieus = SuatChieu::with(['phim', 'phongChieu.rap'])->latest()->paginate(10);
        return view('backend.pages.suat_chieu.suat-chieu', compact('suatChieus'));
    }

    /**
     * Hiển thị form tạo mới suất chiếu
     */
    public function create()
    {
        $phims = Phim::where('TrangThai', 1)->get();
        $phongChieus = PhongChieu::join('rap', 'phong_chieu.ID_Rap', '=', 'rap.ID_Rap')
            ->select('phong_chieu.TenPhongChieu', 'phong_chieu.ID_PhongChieu', 'rap.DiaChi', 'rap.TenRap' , 'phong_chieu.ID_Rap')
            ->get();
        return view('backend.pages.suat_chieu.create-suat-chieu', compact('phims', 'phongChieus'));
    }

    /**
     * Lưu thông tin suất chiếu mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'GioChieu' => 'required',
            'NgayChieu' => 'required|date',
            'GiaVe' => 'required|numeric|min:0',
            'ID_PhongChieu' => 'required|exists:phong_chieu,ID_PhongChieu',
            'ID_Phim' => 'required|exists:phim,ID_Phim',
            'ID_Rap' => 'required|exists:rap,ID_Rap',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Kiểm tra phim có TrangThai = 1 không
        $phim = Phim::find($request->ID_Phim);
        if ($phim->TrangThai != 1) {
            return redirect()->back()
                ->withErrors(['ID_Phim' => 'Chỉ được chọn phim có trạng thái đang chiếu'])
                ->withInput();
        }

        // Kiểm tra xem phòng chiếu có trùng lịch không
        $existingSuatChieu = SuatChieu::where('ID_PhongChieu', $request->ID_PhongChieu)
            ->where('NgayChieu', $request->NgayChieu)
            ->where('GioChieu', $request->GioChieu)
            ->first();

        if ($existingSuatChieu) {
            return redirect()->back()
                ->withErrors(['clash' => 'Phòng chiếu đã được đặt vào giờ này'])
                ->withInput();
        }

        SuatChieu::create($request->all());

        return redirect()->route('suat-chieu.index')
            ->with('success', 'Thêm suất chiếu thành công');
    }

    /**
     * Hiển thị thông tin chi tiết suất chiếu
     */
    public function show($id)
    {
        $suatChieu = SuatChieu::with(['phim', 'phongChieu'])->findOrFail($id);
        return view('suat-chieu.show', compact('suatChieu'));
    }

    /**
     * Hiển thị form chỉnh sửa suất chiếu
     */
    public function edit($id)
    {
        $suatChieu = SuatChieu::findOrFail($id);
        // Chỉ lấy những phim có TrangThai = 1
        $phims = Phim::where('TrangThai', 1)->get();
        $phongChieus = PhongChieu::join('rap', 'phong_chieu.ID_Rap', '=', 'rap.ID_Rap')
            ->select('phong_chieu.TenPhongChieu', 'phong_chieu.ID_PhongChieu', 'rap.DiaChi' , 'rap.TenRap' , 'phong_chieu.ID_Rap')
            ->get();
        return view('backend.pages.suat_chieu.detail-suat-chieu', compact('suatChieu', 'phims', 'phongChieus'));
    }

    /**
     * Cập nhật thông tin suất chiếu
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'GioChieu' => 'required',
            'NgayChieu' => 'required|date',
            'GiaVe' => 'required|numeric|min:0',
            'ID_PhongChieu' => 'required|exists:phong_chieu,ID_PhongChieu',
            'ID_Phim' => 'required|exists:phim,ID_Phim',
            'ID_Rap' => 'required|exists:rap,ID_Rap',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Kiểm tra phim có TrangThai = 1 không
        $phim = Phim::find($request->ID_Phim);
        if ($phim->TrangThai != 1) {
            return redirect()->back()
                ->withErrors(['ID_Phim' => 'Chỉ được chọn phim có trạng thái đang chiếu'])
                ->withInput();
        }

        // Kiểm tra xem phòng chiếu có trùng lịch không (ngoại trừ chính nó)
        $existingSuatChieu = SuatChieu::where('ID_PhongChieu', $request->ID_PhongChieu)
            ->where('NgayChieu', $request->NgayChieu)
            ->where('GioChieu', $request->GioChieu)
            ->where('ID_SuatChieu', '!=', $id)
            ->first();

        if ($existingSuatChieu) {
            return redirect()->back()
                ->withErrors(['clash' => 'Phòng chiếu đã được đặt vào giờ này'])
                ->withInput();
        }

        $suatChieu = SuatChieu::findOrFail($id);
        $suatChieu->update($request->all());

        return redirect()->route('suat-chieu.index')
            ->with('success', 'Cập nhật suất chiếu thành công');
    }

    /**
     * Xóa suất chiếu
     */
    public function destroy($id)
    {
        $suatChieu = SuatChieu::findOrFail($id);
        $suatChieu->delete();

        return redirect()->route('suat-chieu.index')
            ->with('success', 'Xóa suất chiếu thành công');
    }

    /**
     * Lọc suất chiếu theo ngày
     */
    public function filterByDate(Request $request)
    {
        $date = $request->date;
        $suatChieus = SuatChieu::with(['phim', 'phongChieu'])
            ->whereDate('NgayChieu', $date)
            ->latest()
            ->paginate(10);

        return view('backend.pages.suat_chieu.suat-chieu', compact('suatChieus', 'date'));
    }

    /**
     * Lọc suất chiếu theo phim
     */
    public function filterByPhim(Request $request)
    {
        $phimId = $request->phim_id;
        $suatChieus = SuatChieu::with(['phim', 'phongChieu'])
            ->where('ID_Phim', $phimId)
            ->latest()
            ->paginate(10);

        $selectedPhim = Phim::find($phimId);

        return view('backend.pages.suat_chieu.suat-chieu', compact('suatChieus', 'selectedPhim'));
    }
}