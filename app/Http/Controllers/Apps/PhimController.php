<?php

namespace App\Http\Controllers\Apps;

use App\Models\Phim;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use App\Models\Rap;
use Illuminate\Http\Request;    
use App\Models\SuatChieu;

class PhimController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $raps = Rap::all();
        $phims = Phim::with('theLoai')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $dsPhimDangChieu = Phim::join('suat_chieu' , 'suat_chieu.ID_Phim' , '=' , 'phim.ID_Phim')
            ->whereDate('suat_chieu.NgayChieu' , $today)
            ->whereDate('NgayKhoiChieu', '<=', $today)
            ->whereDate('NgayKetThuc', '>=', $today)
            ->get();

        $dsPhimSapChieu = Phim::whereDate('NgayKhoiChieu', '>=', $today)
            ->get();


        return view('frontend.pages.home', compact(
            'dsPhimDangChieu',
            'dsPhimSapChieu',
            'raps',
            'phims'
        ));
    }
    public function phimDangChieu()
    {
        $today = now()->toDateString();
        $dsPhimDangChieu = Phim::whereDate('NgayKhoiChieu', '<=', $today)
            ->whereDate('NgayKetThuc', '>=', $today)
            ->get();

        return view('frontend.pages.phim-dang-chieu', compact('dsPhimDangChieu'));
    }
    public function phimSapChieu()
    {
        $today = now()->toDateString();
        $dsPhimSapChieu = Phim::whereDate('NgayKhoiChieu', '>=', $today)
            ->get();

        return view('frontend.pages.phim-sap-chieu', compact('dsPhimSapChieu'));
    }

    public function chiTiet($slug)
    {
        $phim = Phim::where('Slug', $slug)->firstOrFail();
        $now = now();

        $suatChieu = $phim->suatChieu()
            ->with('rap')
            ->get()
            ->filter(function ($suat) use ($now) {
                $gioChieu = strlen($suat->GioChieu) === 5 ? $suat->GioChieu . ':00' : $suat->GioChieu;
                $suatDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $suat->NgayChieu . ' ' . $gioChieu);
                return $suatDateTime->greaterThan($now);
            })
            ->sortBy([
                ['NgayChieu', 'asc'],
                ['GioChieu', 'asc'],
            ])
            ->groupBy(function ($item) {
                return $item->NgayChieu . '|' . $item->rap->DiaChi;
            });

        return view('frontend.pages.chi-tiet-phim', compact('phim', 'suatChieu'));
    }
    public function ajaxPhimTheoRap(Request $request)
    {
        $id_rap = $request->input('id_rap');
        $phims = SuatChieu::where('ID_Rap', $id_rap)
            ->where('NgayChieu', '>=', now()->toDateString())
            ->with('phim')
            ->get()
            ->groupBy('ID_Phim')
            ->map(function ($suatChieuGroup) {
                $phim = optional($suatChieuGroup->first()->phim);
                if ($phim) {
                    return [
                        'ID_Phim' => $phim->ID_Phim,
                        'TenPhim' => $phim->TenPhim,
                        'Slug'    => $phim->Slug,
                    ];
                }
                return null;
            })
            ->filter()
            ->values();

        if ($phims->isEmpty()) {
            return response()->json(['error' => 'Hiện tại các thông tin rạp chiếu đang được cập nhật!'], 200);
        }
        return response()->json($phims, 200);
    }
    public function ajaxNgayChieuTheoRapPhim(Request $request)
    {
        $id_rap = $request->input('id_rap');
        $id_phim = $request->input('id_phim');
        $dates = SuatChieu::where([
            ['ID_Rap', $id_rap],
            ['ID_Phim', $id_phim]
        ])
            ->where('NgayChieu', '>=', now()->toDateString())
            ->pluck('NgayChieu')
            ->unique()
            ->sort()
            ->values();

        return response()->json($dates, 200);
    }
    public function ajaxSuatChieuTheoRapPhimNgay(Request $request)
    {
        $id_rap = $request->input('id_rap');
        $id_phim = $request->input('id_phim');
        $ngay = $request->input('ngay');
        $suatChieu = SuatChieu::where([
            ['ID_Rap', $id_rap],
            ['ID_Phim', $id_phim],
            ['NgayChieu', $ngay]
        ])
            ->orderBy('GioChieu')
            ->get();

        return response()->json($suatChieu, 200);
    }
}