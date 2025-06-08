<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\VeXemPhim;
use App\Models\Phim;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    public function index()
    {
        // Lấy danh sách phim
        $phims = Phim::select('ID_Phim', 'TenPhim')->get();

        // ID_Phim mặc định (có thể là phim đầu tiên hoặc null)
        $selectedPhimId = request('ID_Phim', $phims->first()->ID_Phim ?? null);

        // Thống kê số vé bán được theo suất chiếu của phim được chọn
        $ticketStats = [];
        if ($selectedPhimId) {
            $ticketStats = VeXemPhim::select(
                'suat_chieu.NgayChieu',
                'suat_chieu.GioChieu',
                DB::raw('COUNT(*) as total_tickets')
            )
                ->join('suat_chieu', 've_xem_phim.ID_SuatChieu', '=', 'suat_chieu.ID_SuatChieu')
                ->where('ve_xem_phim.TrangThai', 'da_ban')
                ->where('suat_chieu.ID_Phim', $selectedPhimId)
                ->groupBy('suat_chieu.NgayChieu', 'suat_chieu.GioChieu')
                ->get();

            // Format labels cho biểu đồ (Ngày + Giờ)
            $ticketLabels = $ticketStats->map(function ($item) {
                return $item->NgayChieu . ' ' . $item->GioChieu;
            })->toArray();
            $ticketData = $ticketStats->pluck('total_tickets')->toArray();
        } else {
            $ticketLabels = [];
            $ticketData = [];
        }

        // Thống kê doanh thu theo ngày của phim được chọn
        $revenueStats = [];
        if ($selectedPhimId) {
            $revenueStats = HoaDon::select(
                DB::raw('DATE(hoa_don.NgayTao) as date'),
                DB::raw('SUM(hoa_don.TongTien) as total_revenue')
            )
                ->join('ve_xem_phim', 'hoa_don.ID_HoaDon', '=', 've_xem_phim.ID_HoaDon')
                ->join('suat_chieu', 've_xem_phim.ID_SuatChieu', '=', 'suat_chieu.ID_SuatChieu')
                ->where('suat_chieu.ID_Phim', $selectedPhimId)
                ->groupBy(DB::raw('DATE(hoa_don.NgayTao)'))
                ->orderBy('date', 'asc')
                ->get();

            $revenueLabels = $revenueStats->pluck('date')->toArray();
            $revenueData = $revenueStats->pluck('total_revenue')->toArray();
        } else {
            $revenueLabels = [];
            $revenueData = [];
        }

        return view('backend.pages.thong_ke.thong-ke-doanh-thu', [
            'phims' => $phims,
            'selectedPhimId' => $selectedPhimId,
            'ticketLabels' => $ticketLabels,
            'ticketData' => $ticketData,
            'revenueLabels' => $revenueLabels,
            'revenueData' => $revenueData,
        ]);
    }
}
