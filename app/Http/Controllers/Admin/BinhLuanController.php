<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phim;
use App\Models\BinhLuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BinhLuanController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách phim cho dropdown
        $phims = Phim::orderBy('TenPhim')->get();

        // Query bình luận có join phim và tài khoản
        $query = DB::table('binh_luan')
            ->join('tai_khoan', 'binh_luan.ID_TaiKhoan', '=', 'tai_khoan.ID_TaiKhoan')
            ->join('phim', 'binh_luan.ID_Phim', '=', 'phim.ID_Phim')
            ->select(
                'binh_luan.*',
                'tai_khoan.TenDN',
                'phim.TenPhim'
            );

        // Lọc theo phim
        if ($request->filled('phim_id')) {
            $query->where('binh_luan.ID_Phim', $request->phim_id);
        }

        // Lọc theo từ khóa trong nội dung
        if ($request->filled('keyword')) {
            $query->where('binh_luan.NoiDung', 'LIKE', '%' . $request->keyword . '%');
        }

        // Lọc theo trạng thái (nếu có)
        if ($request->filled('status')) {
            $query->where('binh_luan.TrangThai', $request->status);
        }

        // Phân trang
        $binhLuans = $query->orderByDesc('binh_luan.created_at')->paginate(15);

        // Thêm query parameters vào pagination links
        $binhLuans->appends($request->query());

        // Trả về view
        return view('backend.pages.binh_luan.binh-luan', compact('binhLuans', 'phims'));
    }

    public function show($id)
    {
        $binhLuan = DB::table('binh_luan')
            ->join('tai_khoan', 'binh_luan.ID_TaiKhoan', '=', 'tai_khoan.ID_TaiKhoan')
            ->join('phim', 'binh_luan.ID_Phim', '=', 'phim.ID_Phim')
            ->select(
                'binh_luan.*',
                'tai_khoan.TenDN',
                'tai_khoan.Email',
                'phim.TenPhim'
            )
            ->where('binh_luan.ID_BinhLuan', $id)
            ->first();

        if (!$binhLuan) {
            return redirect()->route('binh-luan.index')
                ->with('error', 'Không tìm thấy bình luận!');
        }

        return view('backend.pages.binh_luan.show', compact('binhLuan'));
    }

    public function updateStatus($id)
    {
        try {
            $binhLuan = BinhLuan::findOrFail($id);

            // Toggle trạng thái (giả sử có trường TrangThai: 1=hiện, 0=ẩn)
            $newStatus = $binhLuan->TrangThai == 1 ? 0 : 1;
            $binhLuan->update(['TrangThai' => $newStatus]);

            $statusText = $newStatus == 1 ? 'hiển thị' : 'ẩn';

            return redirect()->back()
                ->with('success', "Đã chuyển bình luận thành trạng thái {$statusText}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái!');
        }
    }

    public function destroy($id)
    {
        try {
            $binhLuan = BinhLuan::find($id);

            if (!$binhLuan) {
                Log::warning("Không tìm thấy bình luận với ID: $id");
                return redirect()->route('binh-luan.index')
                    ->with('error', 'Không tìm thấy bình luận!');
            }

            Log::info("Xóa bình luận: ", $binhLuan->toArray()); // Ghi log trước khi xóa

            $binhLuan->delete();

            Log::info("Đã xóa bình luận với ID: $id");

            return redirect()->route('binh-luan.index')
                ->with('success', 'Xóa bình luận thành công!');
        } catch (\Exception $e) {
            Log::error("Lỗi khi xóa bình luận với ID $id: " . $e->getMessage());
            return redirect()->route('binh-luan.index')
                ->with('error', 'Có lỗi xảy ra khi xóa bình luận!');
        }
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('selected_comments', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một bình luận để xóa!');
        }

        try {
            BinhLuan::whereIn('ID_BinhLuan', $ids)->delete();

            return redirect()->route('binh-luan.index')
                ->with('success', 'Xóa ' . count($ids) . ' bình luận thành công!');
        } catch (\Exception $e) {
            return redirect()->route('binh-luan.index')
                ->with('error', 'Có lỗi xảy ra khi xóa bình luận!');
        }
    }

    public function export(Request $request)
    {
        $query = DB::table('binh_luan')
            ->join('tai_khoan', 'binh_luan.ID_TaiKhoan', '=', 'tai_khoan.ID_TaiKhoan')
            ->join('phim', 'binh_luan.ID_Phim', '=', 'phim.ID_Phim')
            ->select(
                'binh_luan.*',
                'tai_khoan.TenDN',
                'phim.TenPhim'
            );

        // Áp dụng các bộ lọc tương tự như index
        if ($request->filled('phim_id')) {
            $query->where('binh_luan.ID_Phim', $request->phim_id);
        }

        if ($request->filled('keyword')) {
            $query->where('binh_luan.NoiDung', 'LIKE', '%' . $request->keyword . '%');
        }

        $binhLuans = $query->orderByDesc('binh_luan.created_at')->get();

        // Export Excel (cần cài đặt thêm package)
        $filename = 'binh_luan_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($binhLuans) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
           
            fputcsv($file, [
                'ID',
                'Tên phim',
                'Người dùng',
                'Nội dung',
                'Điểm đánh giá',
                'Ngày tạo'
            ]);

            // Data
            foreach ($binhLuans as $bl) {
                fputcsv($file, [
                    $bl->ID_BinhLuan,
                    $bl->TenPhim,
                    $bl->TenDN,
                    $bl->NoiDung,
                    $bl->DiemDanhGia ?? 'Không có',
                    $bl->created_at
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
