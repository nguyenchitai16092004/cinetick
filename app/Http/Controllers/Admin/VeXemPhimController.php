<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VeXemPhim;
use App\Models\HoaDon;
use App\Models\SuatChieu;
use App\Models\GheNgoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VeXemPhimController extends Controller
{
    public function index(Request $request, $hoaDonId)
    {
        $hoaDon = HoaDon::findOrFail($hoaDonId);
        $veXemPhim = VeXemPhim::where('ID_HoaDon', $hoaDonId)
            ->with(['suatChieu', 'gheNgoi'])
            ->get();
            
        return view('admin.vexemphim.index', compact('veXemPhim', 'hoaDon'));
    }
    
    /**
     * Hiển thị form thêm vé mới vào hóa đơn
     */
    public function create($hoaDonId)
    {
        $hoaDon = HoaDon::findOrFail($hoaDonId);
        $suatChieus = SuatChieu::all(); // Giả sử model SuatChieu đã tồn tại
        $gheNgois = GheNgoi::whereNotIn('ID_Ghe', function($query) {
            $query->select('ID_Ghe')->from('ve_xem_phim');
        })->get(); // Lấy các ghế chưa được đặt
        
        return view('admin.vexemphim.create', compact('hoaDon', 'suatChieus', 'gheNgois'));
    }
    
    /**
     * Lưu vé mới vào database
     */
    public function store(Request $request, $hoaDonId)
    {
        $request->validate([
            'SoLuong' => 'required|integer|min:1',
            'TenPhim' => 'required|string|max:100',
            'NgayXem' => 'required|date',
            'DiaChi' => 'required|string|max:255',
            'GiaVe' => 'required|numeric|min:0',
            'ID_SuatChieu' => 'required|exists:suat_chieu,ID_SuatChieu',
            'ID_Ghe' => 'required|exists:ghe_ngoi,ID_Ghe|unique:ve_xem_phim,ID_Ghe',
        ]);
        
        DB::beginTransaction();
        
        try {
            $hoaDon = HoaDon::findOrFail($hoaDonId);
            
            $veXemPhim = new VeXemPhim();
            $veXemPhim->SoLuong = $request->SoLuong;
            $veXemPhim->TenPhim = $request->TenPhim;
            $veXemPhim->NgayXem = $request->NgayXem;
            $veXemPhim->DiaChi = $request->DiaChi;
            $veXemPhim->GiaVe = $request->GiaVe;
            $veXemPhim->TrangThai = true; // Mặc định vé có trạng thái active
            $veXemPhim->ID_SuatChieu = $request->ID_SuatChieu;
            $veXemPhim->ID_HoaDon = $hoaDonId;
            $veXemPhim->ID_Ghe = $request->ID_Ghe;
            $veXemPhim->save();
            
            // Cập nhật tổng tiền hóa đơn
            $hoaDon->TongTien += ($request->GiaVe * $request->SoLuong);
            $hoaDon->save();
            
            DB::commit();
            
            return redirect()->route('admin.hoadon.show', $hoaDonId)
                ->with('success', 'Vé xem phim đã được thêm thành công!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.vexemphim.create', $hoaDonId)
                ->with('error', 'Không thể thêm vé: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Hiển thị chi tiết vé xem phim
     */
    public function show($hoaDonId, $veId)
    {
        $hoaDon = HoaDon::findOrFail($hoaDonId);
        $veXemPhim = VeXemPhim::with(['suatChieu', 'gheNgoi'])
            ->where('ID_HoaDon', $hoaDonId)
            ->findOrFail($veId);
            
        return view('admin.vexemphim.show', compact('hoaDon', 'veXemPhim'));
    }
    
    /**
     * Hiển thị form chỉnh sửa vé xem phim
     */
    public function edit($hoaDonId, $veId)
    {
        $hoaDon = HoaDon::findOrFail($hoaDonId);
        $veXemPhim = VeXemPhim::where('ID_HoaDon', $hoaDonId)->findOrFail($veId);
        $suatChieus = SuatChieu::all();
        
        // Lấy danh sách ghế có thể chọn (ghế hiện tại + ghế chưa được đặt)
        $gheNgois = GheNgoi::whereNotIn('ID_Ghe', function($query) use ($veId) {
            $query->select('ID_Ghe')->from('ve_xem_phim')->where('ID_Ve', '!=', $veId);
        })->get();
        
        return view('admin.vexemphim.edit', compact('hoaDon', 'veXemPhim', 'suatChieus', 'gheNgois'));
    }
    
    /**
     * Cập nhật thông tin vé xem phim
     */
    public function update(Request $request, $hoaDonId, $veId)
    {
        $request->validate([
            'SoLuong' => 'required|integer|min:1',
            'TenPhim' => 'required|string|max:100',
            'NgayXem' => 'required|date',
            'DiaChi' => 'required|string|max:255',
            'GiaVe' => 'required|numeric|min:0',
            'TrangThai' => 'required|boolean',
            'ID_SuatChieu' => 'required|exists:suat_chieu,ID_SuatChieu',
            'ID_Ghe' => 'required|exists:ghe_ngoi,ID_Ghe|unique:ve_xem_phim,ID_Ghe,' . $veId . ',ID_Ve',
        ]);
        
        DB::beginTransaction();
        
        try {
            $hoaDon = HoaDon::findOrFail($hoaDonId);
            $veXemPhim = VeXemPhim::where('ID_HoaDon', $hoaDonId)->findOrFail($veId);
            
            // Lưu giá vé cũ để tính lại tổng tiền
            $oldTotal = $veXemPhim->GiaVe * $veXemPhim->SoLuong;
            
            $veXemPhim->SoLuong = $request->SoLuong;
            $veXemPhim->TenPhim = $request->TenPhim;
            $veXemPhim->NgayXem = $request->NgayXem;
            $veXemPhim->DiaChi = $request->DiaChi;
            $veXemPhim->GiaVe = $request->GiaVe;
            $veXemPhim->TrangThai = $request->TrangThai;
            $veXemPhim->ID_SuatChieu = $request->ID_SuatChieu;
            $veXemPhim->ID_Ghe = $request->ID_Ghe;
            $veXemPhim->save();
            
            // Tính lại tổng tiền hóa đơn
            $newTotal = $request->GiaVe * $request->SoLuong;
            $hoaDon->TongTien = $hoaDon->TongTien - $oldTotal + $newTotal;
            $hoaDon->save();
            
            DB::commit();
            
            return redirect()->route('admin.hoadon.show', $hoaDonId)
                ->with('success', 'Vé xem phim đã được cập nhật thành công!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.vexemphim.edit', [$hoaDonId, $veId])
                ->with('error', 'Không thể cập nhật vé: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Xóa vé xem phim
     */
    public function destroy($hoaDonId, $veId)
    {
        DB::beginTransaction();
        
        try {
            $hoaDon = HoaDon::findOrFail($hoaDonId);
            $veXemPhim = VeXemPhim::where('ID_HoaDon', $hoaDonId)->findOrFail($veId);
            
            // Cập nhật tổng tiền hóa đơn
            $hoaDon->TongTien -= ($veXemPhim->GiaVe * $veXemPhim->SoLuong);
            $hoaDon->save();
            
            // Xóa vé
            $veXemPhim->delete();
            
            DB::commit();
            
            return redirect()->route('admin.hoadon.show', $hoaDonId)
                ->with('success', 'Vé xem phim đã được xóa thành công!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.hoadon.show', $hoaDonId)
                ->with('error', 'Không thể xóa vé: ' . $e->getMessage());
        }
    }
    
    /**
     * Thay đổi trạng thái vé
     */
    public function changeStatus($hoaDonId, $veId)
    {
        $veXemPhim = VeXemPhim::where('ID_HoaDon', $hoaDonId)->findOrFail($veId);
        $veXemPhim->TrangThai = !$veXemPhim->TrangThai;
        $veXemPhim->save();
        
        return redirect()->route('admin.hoadon.show', $hoaDonId)
            ->with('success', 'Trạng thái vé đã được cập nhật thành công!');
    }
}
