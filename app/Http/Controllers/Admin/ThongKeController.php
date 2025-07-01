<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\VeXemPhim;
use App\Models\Phim;
use App\Models\SuatChieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        // Lấy năm hiện tại hoặc năm được chọn
        $selectedYear = $request->get('year', Carbon::now()->year);
        
        // Lấy tất cả các phim
        $phims = Phim::orderBy('TenPhim')->get();
        
        // Thống kê tổng doanh thu theo từng phim theo tháng
        $doanhThuTheoPhim = $this->getDoanhThuTheoPhim($selectedYear);
        
        // Thống kê số vé bán được theo suất chiếu theo tháng
        $soVeTheoSuatChieu = $this->getSoVeTheoSuatChieu($selectedYear);
        
        // Chuẩn bị dữ liệu cho biểu đồ
        $chartData = $this->prepareChartData($doanhThuTheoPhim, $soVeTheoSuatChieu);
        
        return view('admin.pages.thong_ke.thong-ke-doanh-thu', compact(
            'phims',
            'selectedYear',
            'doanhThuTheoPhim',
            'soVeTheoSuatChieu',
            'chartData'
        ));
    }
    
    private function getDoanhThuTheoPhim($year)
    {
        return DB::table('hoa_don as hd')
            ->join('ve_xem_phim as vxp', 'hd.ID_HoaDon', '=', 'vxp.ID_HoaDon')
            ->join('suat_chieu as sc', 'vxp.ID_SuatChieu', '=', 'sc.ID_SuatChieu')
            ->join('phim as p', 'sc.ID_Phim', '=', 'p.ID_Phim')
            ->where('hd.TrangThaiXacNhanThanhToan', 1)
            ->whereYear('hd.created_at', $year)
            ->select(
                'p.ID_Phim',
                'p.TenPhim',
                DB::raw('MONTH(hd.created_at) as thang'),
                DB::raw('SUM(vxp.GiaVe) as tong_doanh_thu'),
                DB::raw('COUNT(vxp.ID_Ve) as so_ve_ban')
            )
            ->groupBy('p.ID_Phim', 'p.TenPhim', DB::raw('MONTH(hd.created_at)'))
            ->orderBy('p.TenPhim')
            ->orderBy('thang')
            ->get()
            ->groupBy('ID_Phim');
    }
    
    private function getSoVeTheoSuatChieu($year)
    {
        return DB::table('hoa_don as hd')
            ->join('ve_xem_phim as vxp', 'hd.ID_HoaDon', '=', 'vxp.ID_HoaDon')
            ->join('suat_chieu as sc', 'vxp.ID_SuatChieu', '=', 'sc.ID_SuatChieu')
            ->join('phim as p', 'sc.ID_Phim', '=', 'p.ID_Phim')
            ->join('phong_chieu as pc', 'sc.ID_PhongChieu', '=', 'pc.ID_PhongChieu')
            ->join('rap as r', 'sc.ID_Rap', '=', 'r.ID_Rap')
            ->where('hd.TrangThaiXacNhanThanhToan', 1)
            ->whereYear('hd.created_at', $year)
            ->select(
                'sc.ID_SuatChieu',
                'p.TenPhim',
                'sc.GioChieu',
                'sc.NgayChieu',
                'pc.TenPhongChieu',
                'r.TenRap',
                DB::raw('MONTH(hd.created_at) as thang'),
                DB::raw('COUNT(vxp.ID_Ve) as so_ve_ban'),
                DB::raw('SUM(vxp.GiaVe) as doanh_thu_suat')
            )
            ->groupBy(
                'sc.ID_SuatChieu',
                'p.TenPhim',
                'sc.GioChieu',
                'sc.NgayChieu',
                'pc.TenPhongChieu',
                'r.TenRap',
                DB::raw('MONTH(hd.created_at)')
            )
            ->orderBy('p.TenPhim')
            ->orderBy('thang')
            ->orderBy('sc.NgayChieu')
            ->orderBy('sc.GioChieu')
            ->get()
            ->groupBy(['TenPhim', 'thang']);
    }
    
    private function prepareChartData($doanhThuTheoPhim, $soVeTheoSuatChieu)
    {
        $months = range(1, 12);
        $monthNames = [
            1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4',
            5 => 'Tháng 5', 6 => 'Tháng 6', 7 => 'Tháng 7', 8 => 'Tháng 8',
            9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
        ];
        
        // Dữ liệu doanh thu theo phim
        $revenueData = [];
        $colors = [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 205, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)',
            'rgba(199, 199, 199, 0.8)',
            'rgba(83, 102, 255, 0.8)',
        ];
        
        $colorIndex = 0;
        foreach ($doanhThuTheoPhim as $phimId => $data) {
            $phimName = $data->first()->TenPhim;
            $monthlyRevenue = array_fill(0, 12, 0);
            
            foreach ($data as $item) {
                $monthlyRevenue[$item->thang - 1] = (float) $item->tong_doanh_thu;
            }
            
            $revenueData[] = [
                'label' => $phimName,
                'data' => $monthlyRevenue,
                'backgroundColor' => $colors[$colorIndex % count($colors)],
                'borderColor' => str_replace('0.8', '1', $colors[$colorIndex % count($colors)]),
                'borderWidth' => 2,
                'fill' => false
            ];
            $colorIndex++;
        }
        
        return [
            'labels' => array_values($monthNames),
            'revenueData' => $revenueData,
            'months' => $months
        ];
    }
    
    public function exportExcel(Request $request)
    {
        $selectedYear = $request->get('year', Carbon::now()->year);
        
        // Lấy dữ liệu
        $doanhThuTheoPhim = $this->getDoanhThuTheoPhim($selectedYear);
        $soVeTheoSuatChieu = $this->getSoVeTheoSuatChieu($selectedYear);
        
        // Tạo tên file
        $filename = "thong_ke_doanh_thu_theo_thang_{$selectedYear}_" . date('YmdHis') . ".csv";
        
        // Tạo CSV content
        $csvContent = "Bao cao thong ke doanh thu theo thang nam {$selectedYear}\n\n";
        
        // Header cho bảng tổng kết
        $csvContent .= "Ten Phim,";
        for ($i = 1; $i <= 12; $i++) {
            $csvContent .= "Thang {$i},";
        }
        $csvContent .= "Tong Doanh Thu,Tong Ve Ban\n";
        
        // Dữ liệu từng phim
        foreach ($doanhThuTheoPhim as $phimId => $thangData) {
            $tenPhim = $thangData->first()->TenPhim;
            $tongDoanhThu = $thangData->sum('tong_doanh_thu');
            $tongVeBan = $thangData->sum('so_ve_ban');
            
            $csvContent .= "\"{$tenPhim}\",";
            
            for ($month = 1; $month <= 12; $month++) {
                $monthData = $thangData->where('thang', $month)->first();
                $doanhThuThang = $monthData ? $monthData->tong_doanh_thu : 0;
                $csvContent .= "{$doanhThuThang},";
            }
            
            $csvContent .= "{$tongDoanhThu},{$tongVeBan}\n";
        }
        
        // Trả về file CSV
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Content-Length', strlen($csvContent));
    }
    
    // API để lấy dữ liệu AJAX
    public function getDataByMonth(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        
        $data = DB::table('hoa_don as hd')
            ->join('ve_xem_phim as vxp', 'hd.ID_HoaDon', '=', 'vxp.ID_HoaDon')
            ->join('suat_chieu as sc', 'vxp.ID_SuatChieu', '=', 'sc.ID_SuatChieu')
            ->join('phim as p', 'sc.ID_Phim', '=', 'p.ID_Phim')
            ->join('phong_chieu as pc', 'sc.ID_PhongChieu', '=', 'pc.ID_PhongChieu')
            ->join('rap as r', 'sc.ID_Rap', '=', 'r.ID_Rap')
            ->where('hd.TrangThaiXacNhanThanhToan', 1)
            ->whereYear('hd.created_at', $year)
            ->whereMonth('hd.created_at', $month)
            ->select(
                'p.TenPhim',
                'sc.GioChieu',
                'sc.NgayChieu',
                'pc.TenPhongChieu',
                'r.TenRap',
                DB::raw('COUNT(vxp.ID_Ve) as so_ve_ban'),
                DB::raw('SUM(vxp.GiaVe) as doanh_thu_suat')
            )
            ->groupBy('p.TenPhim', 'sc.GioChieu', 'sc.NgayChieu', 'pc.TenPhongChieu', 'r.TenRap')
            ->orderBy('p.TenPhim')
            ->orderBy('sc.NgayChieu')
            ->orderBy('sc.GioChieu')
            ->get();
            
        return response()->json($data);
    }
    
    // So sánh dữ liệu giữa các năm
    public function compareYears(Request $request)
    {
        $years = $request->get('years', [Carbon::now()->year]);
        $compareData = [];
        
        foreach ($years as $year) {
            $yearData = $this->getDoanhThuTheoPhim($year);
            $compareData[$year] = [
                'total_revenue' => $yearData->sum(function($phim) { 
                    return $phim->sum('tong_doanh_thu'); 
                }),
                'total_tickets' => $yearData->sum(function($phim) { 
                    return $phim->sum('so_ve_ban'); 
                }),
                'movies_count' => $yearData->count(),
                'monthly_data' => []
            ];
            
            // Dữ liệu theo tháng
            for ($month = 1; $month <= 12; $month++) {
                $monthRevenue = 0;
                $monthTickets = 0;
                
                foreach ($yearData as $phimData) {
                    $monthData = $phimData->where('thang', $month)->first();
                    if ($monthData) {
                        $monthRevenue += $monthData->tong_doanh_thu;
                        $monthTickets += $monthData->so_ve_ban;
                    }
                }
                
                $compareData[$year]['monthly_data'][$month] = [
                    'revenue' => $monthRevenue,
                    'tickets' => $monthTickets
                ];
            }
        }
        
        return response()->json($compareData);
    }
}