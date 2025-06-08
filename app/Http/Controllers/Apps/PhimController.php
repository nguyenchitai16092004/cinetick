<?php

namespace App\Http\Controllers\Apps;

use App\Models\Phim;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use App\Models\Rap;

class PhimController extends Controller
{
    public function index()
    {
        $today = now()->toDateString(); // Khai báo ngày hôm nay
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $raps = Rap::all();
        $phims = Phim::with('theLoai')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);


        // PHIM ĐANG CHIẾU: Ngày khởi chiếu <= hôm nay và ngày kết thúc >= hôm nay
        $dsPhimDangChieu = Phim::whereDate('NgayKhoiChieu', '<=', $today)
            ->whereDate('NgayKetThuc', '>=', $today)
            ->get();

        // PHIM SẮP CHIẾU: Ngày khởi chiếu > hôm nay và TrangThai = 0
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
        $dsPhimSapChieu = Phim::whereDate('NgayKhoiChieu', '>', $today)
            ->where('TrangThai', 0)
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
}