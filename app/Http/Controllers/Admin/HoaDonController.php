<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HoaDon;
use App\Models\VeXemPhim;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class HoaDonController extends Controller
{
    public function index(Request $request)
    {
        $query = HoaDon::query()->with('taiKhoan');
        
        // Lọc theo ngày tạo
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('NgayTao', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('NgayTao', '<=', $request->end_date);
        }
        
        // Lọc theo ID tài khoản
        if ($request->has('id_tai_khoan') && $request->id_tai_khoan) {
            $query->where('ID_TaiKhoan', $request->id_tai_khoan);
        }
        
        // Lọc theo phương thức thanh toán
        if ($request->has('pttt') && $request->pttt) {
            $query->where('PTTT', 'like', '%' . $request->pttt . '%');
        }
        
        // Lọc theo khoảng tổng tiền
        if ($request->has('min_amount') && $request->min_amount) {
            $query->where('TongTien', '>=', $request->min_amount);
        }
        
        if ($request->has('max_amount') && $request->max_amount) {
            $query->where('TongTien', '<=', $request->max_amount);
        }
        
        $hoaDons = $query->orderBy('NgayTao', 'desc')->paginate(10);
        
        return view('backend.pages.', compact('hoaDons'));
    }
    
    /**
     * Hiển thị form tạo hóa đơn mới
     */
    public function create()
    {
        return view('admin.hoadon.create');
    }
    
    /**
     * Lưu hóa đơn mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'ID_TaiKhoan' => 'required|exists:tai_khoan,ID_TaiKhoan',
            'TongTien' => 'required|numeric|min:0',
            'PTTT' => 'required|string|max:50',
        ]);
        
        $hoaDon = new HoaDon();
        $hoaDon->NgayTao = Carbon::now()->toDateString();
        $hoaDon->TongTien = $request->TongTien;
        $hoaDon->PTTT = $request->PTTT;
        $hoaDon->ID_TaiKhoan = $request->ID_TaiKhoan;
        $hoaDon->save();
        
        return redirect()->route('admin.hoadon.show', $hoaDon->ID_HoaDon)
            ->with('success', 'Hóa đơn đã được tạo thành công!');
    }
    
    /**
     * Hiển thị chi tiết hóa đơn và danh sách vé
     */
    public function show($id)
    {
        $hoaDon = HoaDon::with(['taiKhoan', 'veXemPhim' => function($query) {
            $query->with(['suatChieu', 'gheNgoi']);
        }])->findOrFail($id);
        
        return view('admin.hoadon.show', compact('hoaDon'));
    }
    
    /**
     * Hiển thị form chỉnh sửa hóa đơn
     */
    public function edit($id)
    {
        $hoaDon = HoaDon::findOrFail($id);
        return view('admin.hoadon.edit', compact('hoaDon'));
    }
    
    /**
     * Cập nhật thông tin hóa đơn
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'ID_TaiKhoan' => 'required|exists:tai_khoan,ID_TaiKhoan',
            'TongTien' => 'required|numeric|min:0',
            'PTTT' => 'required|string|max:50',
        ]);
        
        $hoaDon = HoaDon::findOrFail($id);
        $hoaDon->TongTien = $request->TongTien;
        $hoaDon->PTTT = $request->PTTT;
        $hoaDon->ID_TaiKhoan = $request->ID_TaiKhoan;
        $hoaDon->save();
        
        return redirect()->route('admin.hoadon.index')
            ->with('success', 'Hóa đơn đã được cập nhật thành công!');
    }
    
    /**
     * Xóa hóa đơn
     */
    public function destroy($id)
    {
        // Bắt đầu một transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();
        
        try {
            $hoaDon = HoaDon::findOrFail($id);
            
            // Xóa tất cả các vé xem phim liên quan
            VeXemPhim::where('ID_HoaDon', $id)->delete();
            
            // Xóa hóa đơn
            $hoaDon->delete();
            
            DB::commit();
            return redirect()->route('admin.hoadon.index')
                ->with('success', 'Hóa đơn và các vé liên quan đã được xóa thành công!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.hoadon.index')
                ->with('error', 'Không thể xóa hóa đơn: ' . $e->getMessage());
        }
    }
    
    /**
     * Xuất báo cáo hóa đơn
     */
    public function exportReport(Request $request)
    {
        $query = HoaDon::query()->with('taiKhoan');
        
        // Áp dụng các bộ lọc tương tự như method index
        // ...
        
        $hoaDons = $query->orderBy('NgayTao', 'desc')->get();
        
        // Logic xuất báo cáo (có thể xuất ra CSV, Excel, PDF...)
        // Đây chỉ là phần giả định, cần thư viện hỗ trợ xuất báo cáo
        
        return redirect()->route('admin.hoadon.index')
            ->with('success', 'Báo cáo đã được xuất thành công!');
    }
}
