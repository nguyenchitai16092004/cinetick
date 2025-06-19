<?php

namespace App\Http\Controllers\Apps;

use App\Models\Phim;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use App\Models\Rap;
use Illuminate\Http\Request;
use App\Models\SuatChieu;
use App\Models\BinhLuan;
use App\Models\HoaDon;
use Illuminate\Support\Facades\Log;
use App\Models\VeXemPhim;
use App\Models\TinTuc;

class PhimController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $raps = Rap::where('TrangThai', 1)->get();
        $phims = Phim::with('theLoai')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $dsPhimDangChieu = Phim::whereDate('NgayKhoiChieu', '<=', $today)
            ->whereDate('NgayKetThuc', '>=', $today)
            ->get();

        $dsPhimSapChieu = Phim::whereDate('NgayKhoiChieu', '>', $today)
            ->get();

        $tinTucs = TinTuc::where('LoaiBaiViet', 0)
            ->where('TrangThai', 1)
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('frontend.pages.home', [
            'dsPhimDangChieu' => $dsPhimDangChieu,
            'dsPhimSapChieu'  => $dsPhimSapChieu,
            'raps'            => $raps,
            'phims'           => $phims,
            'mainArticle'     => $tinTucs->first(),
            'sidebarArticles' => $tinTucs->slice(1, 3),
        ]);
    }
    public function phimDangChieu()
    {
        $today = now()->toDateString();
        $danhSachPhim = Phim::whereDate('NgayKhoiChieu', '<=', $today)
            ->whereDate('NgayKetThuc', '>=', $today)
            ->get();

        $title = 'Phim đang chiếu';
        return view('frontend.pages.phim', compact('danhSachPhim', 'title'));
    }

    public function phimSapChieu()
    {
        $today = now()->toDateString();
        $danhSachPhim = Phim::whereDate('NgayKhoiChieu', '>=', $today)
            ->get();

        $title = 'Phim sắp chiếu';
        return view('frontend.pages.phim', compact('danhSachPhim', 'title'));
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
        // Chỉ lấy rạp có TrangThai == 1
        $rap = Rap::where('ID_Rap', $id_rap)->where('TrangThai', 1)->first();
        if (!$rap) {
            return response()->json(['error' => 'Rạp không tồn tại hoặc đã ngừng hoạt động!'], 404);
        }
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

    public function ajaxCanRatePhim(Request $request)
    {
        if (!session()->has('user_id')) {
            return response()->json(['allow' => false, 'message' => 'Bạn cần đăng nhập để đánh giá phim!']);
        }
        $userId = session('user_id');
        $idPhim = $request->input('id_phim');
        $phim = Phim::find($idPhim);

        // Kiểm tra đã đánh giá chưa
        $exist = BinhLuan::where('ID_Phim', $idPhim)->where('ID_TaiKhoan', $userId)->exists();
        if ($exist) {
            return response()->json(['allow' => false, 'message' => 'Bạn đã đánh giá phim này rồi!']);
        }

        // Kiểm tra vé xem phim này
        $veList = VeXemPhim::whereHas('hoaDon', function ($q) use ($userId) {
            $q->where('ID_TaiKhoan', $userId)
                ->where('TrangThaiXacNhanHoaDon', 1)
                ->where('TrangThaiXacNhanThanhToan', 1);
        })
            ->whereHas('suatChieu', function ($q) use ($idPhim) {
                $q->where('ID_Phim', $idPhim);
            })->get();

        if ($veList->isEmpty()) {
            return response()->json([
                'allow' => false,
                'message' => "Để có thể đánh giá phim này, bạn vui lòng trải nghiệm phim " . ($phim ? $phim->TenPhim : '') . " tại rạp CineTick nhé!"
            ]);
        }

        // Kiểm tra đã xem xong phim chưa
        $now = now();
        $isWatched = false;
        foreach ($veList as $ve) {
            $suat = $ve->suatChieu;
            if (!$suat) continue;

            $gioChieu = strlen($suat->GioChieu) === 5 ? $suat->GioChieu . ':00' : $suat->GioChieu;
            $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $suat->NgayChieu . ' ' . $gioChieu)
                ->addMinutes($suat->phim->ThoiLuong ?? 0);
            
            Log::info('Thời gian bình luận:', [
                'now' => $now->toDateTimeString(),
                'showTime' => $suat->NgayChieu . ' ' . $gioChieu,
                'movieDuration' => $suat->phim->ThoiLuong ?? 0,
                'endTime' => $endTime->toDateTimeString(),
                'canComment' => $now->greaterThanOrEqualTo($endTime)
            ]);

            if ($now->greaterThanOrEqualTo($endTime)) {
                $isWatched = true;
                break;
            }
        }

        if (!$isWatched) {
            return response()->json([
                'allow' => false,
                'message' => 'Cảm ơn bạn đã đã mua vé xem phim tại CineTick! Để có thể đánh giá phim này, bạn vui lòng trải nghiệm phim ' . ($phim ? $phim->TenPhim : '') . ' tại rạp. Chức năng đánh giá sẽ hoạt động ngay sau khi bạn xem phim xong. Chúc bạn có trải nghiệm thú vị, vui vẻ tại CineTick!'
            ]);
        }

        return response()->json(['allow' => true]);
    }
    public function ajaxSendRating(Request $request)
    {
        if (!session()->has('user_id')) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để đánh giá phim!']);
        }
        $userId = session('user_id');
        $idPhim = $request->input('id_phim');
        $diem = floatval($request->input('diem'));
        if ($diem < 1 || $diem > 10) {
            return response()->json(['success' => false, 'message' => 'Điểm không hợp lệ!']);
        }
        $exist = BinhLuan::where('ID_Phim', $idPhim)->where('ID_TaiKhoan', $userId)->exists();
        if ($exist) {
            return response()->json(['success' => false, 'message' => 'Bạn đã đánh giá phim này rồi!']);
        }
        // Lưu đánh giá
        BinhLuan::create([
            'ID_Phim' => $idPhim,
            'ID_TaiKhoan' => $userId,
            'DiemDanhGia' => $diem,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['success' => true, 'message' => 'Đánh giá thành công!']);
    }

    public function ajaxGetRating(Request $request)
    {
        $idPhim = $request->input('id_phim');
        $avg = BinhLuan::where('ID_Phim', $idPhim)->avg('DiemDanhGia');
        $count = BinhLuan::where('ID_Phim', $idPhim)->count();
        $avg = $avg ? number_format($avg, 1) : '0.0';
        return response()->json(['avg' => $avg, 'count' => $count]);
    }
}