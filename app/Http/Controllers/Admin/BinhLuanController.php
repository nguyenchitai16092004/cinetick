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
        $phims = Phim::orderBy('TenPhim')->get();
        $query = DB::table('binh_luan')
            ->join('tai_khoan', 'binh_luan.ID_TaiKhoan', '=', 'tai_khoan.ID_TaiKhoan')
            ->join('phim', 'binh_luan.ID_Phim', '=', 'phim.ID_Phim')
            ->select(
                'binh_luan.*',
                'tai_khoan.TenDN',
                'phim.TenPhim'
            );
        if ($request->filled('phim_id')) {
            $query->where('binh_luan.ID_Phim', $request->phim_id);
        }
        if ($request->filled('keyword')) {
            $query->where('binh_luan.NoiDung', 'LIKE', '%' . $request->keyword . '%');
        }
        if ($request->filled('status')) {
            $query->where('binh_luan.TrangThai', $request->status);
        }

        $binhLuans = $query->orderByDesc('binh_luan.created_at')->paginate(15);
        $binhLuans->appends($request->query());

        return view('backend.pages.binh_luan.binh-luan', compact('binhLuans', 'phims'));
    }

    public function show($id)
    {
        $binhLuan = DB::table('binh_luan')->join('tai_khoan', 'binh_luan.ID_TaiKhoan', '=', 'tai_khoan.ID_TaiKhoan')
            ->join('phim', 'binh_luan.ID_Phim', '=', 'phim.ID_Phim')
            ->select('binh_luan.*', 'tai_khoan.TenDN', 'tai_khoan.Email', 'phim.TenPhim')
            ->where('binh_luan.ID_BinhLuan', $id)->first();

        if (!$binhLuan) {
            return redirect()->route('binh-luan.index')
                ->with('error', 'Không tìm thấy bình luận nào hết ');
        }

        return view('backend.pages.binh_luan.show', compact('binhLuan'));
    }

    public function KiemTraTrangThai($id)
    {
        try {
            $binhLuan = BinhLuan::findOrFail($id);
            $trangThaiMoi = $binhLuan->TrangThai == 1 ? 0 : 1;
            $binhLuan->update(['TrangThai' => $trangThaiMoi]);

            $LoaiTrangThai = $trangThaiMoi == 1 ? 'hiển thị' : 'ẩn';

            return redirect()->back()
                ->with('success', "Đã chuyển bình luận thành trạng thái {$LoaiTrangThai}!");
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
                    ->with('error', 'Không tìm thấy bình luận nên không xóa được !');
            }

            Log::info("Xóa bình luận: ", $binhLuan->toArray());
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
}
