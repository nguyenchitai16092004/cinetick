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
use App\Models\ThongTinTrangWeb;
use App\Models\Banner;
use Illuminate\Support\Str;
use App\Models\GheDangGiu;


class PhimController extends Controller
{
    public function index()
    {
        if (session()->has('user_id')) {
            GheDangGiu::where('ID_TaiKhoan', session('user_id'))->delete();
        }
        $today = now()->toDateString();

        $raps = Rap::where('TrangThai', 1)->get();
        $phims = Phim::with('theLoai')
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        $dsPhimDangChieu = Phim::whereDate('NgayKhoiChieu', '<=', $today)
            ->whereDate('NgayKetThuc', '>=', $today)
            ->get();

        foreach ($dsPhimDangChieu as $phim) {
            $phim->avg_rating = round($phim->binhLuan()->avg('DiemDanhGia'), 1) ?: '0.0';
        }

        $dsPhimSapChieu = Phim::whereDate('NgayKhoiChieu', '>', $today)
            ->get();

        foreach ($dsPhimSapChieu as $phim) {
            $phim->avg_rating = round($phim->binhLuan()->avg('DiemDanhGia'), 1) ?: '0.0';
        }

        $tinTucs = TinTuc::where('LoaiBaiViet', 4)
            ->where('TrangThai', 1)
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        $khuyenMais = TinTuc::where('LoaiBaiViet', 1)
            ->where('TrangThai', 1)
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        $footerGioiThieu = TinTuc::where('LoaiBaiViet', 2)
            ->where('TrangThai', 1)
            ->orderBy('created_at', 'asc')
            ->get();

        $footerChinhSach = TinTuc::where('LoaiBaiViet', 3)
            ->where('TrangThai', 1)
            ->orderBy('created_at', 'asc')
            ->get();

        $thongTinTrangWeb = ThongTinTrangWeb::all();

        $banners = Banner::all();

        return view('user.pages.home', [
            'dsPhimDangChieu' => $dsPhimDangChieu,
            'dsPhimSapChieu'  => $dsPhimSapChieu,
            'raps'            => $raps,
            'phims'           => $phims,
            'mainArticle'     => $tinTucs->first(),
            'sidebarArticles' => $tinTucs->slice(1, 3),
            'khuyenMais'      => $khuyenMais,
            'footerGioiThieu' => $footerGioiThieu,
            'footerChinhSach' => $footerChinhSach,
            'thongTinTrangWeb' => $thongTinTrangWeb,
            'banners'         => $banners,
        ]);
    }
    public function phimDangChieu()
    {
        if (session()->has('user_id')) {
            GheDangGiu::where('ID_TaiKhoan', session('user_id'))->delete();
        }

        $today = now()->toDateString();
        $danhSachPhim = Phim::whereDate('NgayKhoiChieu', '<=', $today)
            ->whereDate('NgayKetThuc', '>=', $today)
            ->paginate(12);

        $title = 'Phim đang chiếu';
        return view('user.pages.phim', compact('danhSachPhim', 'title'));
    }

    public function phimSapChieu()
    {
        if (session()->has('user_id')) {
            GheDangGiu::where('ID_TaiKhoan', session('user_id'))->delete();
        }

        $today = now()->toDateString();
        $danhSachPhim = Phim::whereDate('NgayKhoiChieu', '>=', $today)
            ->paginate(12);

        $title = 'Phim sắp chiếu';
        return view('user.pages.phim', compact('danhSachPhim', 'title'));
    }

    public function chiTiet($slug)
    {
        $phim = Phim::where('Slug', $slug)->firstOrFail();
        $now = now();

        // Lấy tất cả suất chiếu còn hiệu lực (chưa kết thúc và chưa vào 15 phút trước giờ chiếu)
        $suatChieu = $phim->suatChieu()
            ->with('rap', 'phim')
            ->get()
            ->filter(function ($suat) use ($now) {
                $gioChieu = strlen($suat->GioChieu) === 5 ? $suat->GioChieu . ':00' : $suat->GioChieu;
                $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $suat->NgayChieu . ' ' . $gioChieu);
                $endTime = $startTime->copy()->addMinutes($suat->phim->ThoiLuong ?? 0);

                // Ẩn suất chiếu nếu đã vào 15 phút trước giờ chiếu hoặc đã kết thúc
                return $startTime->subMinutes(15)->greaterThan($now) && $endTime->greaterThan($now);
            })
            ->sortBy([
                ['NgayChieu', 'asc'],
                ['GioChieu', 'asc'],
            ])
            ->groupBy(function ($item) {
                return $item->NgayChieu . '|' . $item->rap->DiaChi;
            });

        $phim->avg_rating = round($phim->binhLuan()->avg('DiemDanhGia'), 1) ?: '0.0';
        $phim->count_rating = $phim->binhLuan()->whereNotNull('DiemDanhGia')->count();

        return view('user.pages.chi-tiet-phim', compact('phim', 'suatChieu'));
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
        $now = now();

        $suatChieu = SuatChieu::where([
            ['ID_Rap', $id_rap],
            ['ID_Phim', $id_phim],
            ['NgayChieu', $ngay]
        ])
            ->orderBy('GioChieu')
            ->get()
            ->filter(function ($suat) use ($now) {
                $gioChieu = strlen($suat->GioChieu) === 5 ? $suat->GioChieu . ':00' : $suat->GioChieu;
                $startTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $suat->NgayChieu . ' ' . $gioChieu);
                $endTime = $startTime->copy()->addMinutes($suat->phim->ThoiLuong ?? 0);

                // Chỉ lấy suất chiếu chưa vào 15 phút trước giờ chiếu và chưa kết thúc
                return $startTime->subMinutes(15)->greaterThan($now) && $endTime->greaterThan($now);
            })
            ->values();

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

        if (is_null($avg)) {
            $avgStr = '10';
        } elseif (fmod($avg, 1) == 0.0) {
            $avgStr = (string)(int)$avg;
        } else {
            $avgStr = sprintf('%.2f', floor($avg * 100) / 100);
        }

        return response()->json(['avg' => $avgStr, 'count' => $count]);
    }

    public function timKiem(Request $request)
    {
        if (session()->has('user_id')) {
            GheDangGiu::where('ID_TaiKhoan', session('user_id'))->delete();
        }

        $keyword = trim($request->input('keyword', '')); // Dùng cho hiển thị
        $phims = collect();

        if ($keyword !== '') {
            $keywordLower = mb_strtolower($keyword); // Dùng cho so sánh chữ thường
            $slugKeyword = Str::slug($keyword);      // Dùng cho so sánh slug

            // Tìm phim
            $phims = Phim::with('theLoai')
                ->whereRaw('LOWER(TenPhim) LIKE ?', ["%{$keywordLower}%"])
                ->orWhereRaw('LOWER(Slug) LIKE ?', ["%{$slugKeyword}%"])
                ->orWhereHas('theLoai', function ($q) use ($keywordLower) {
                    $q->whereRaw('LOWER(TenTheLoai) LIKE ?', ["%{$keywordLower}%"]);
                })
                ->distinct()
                ->paginate(12);

            foreach ($phims as $phim) {
                $phim->avg_rating = round($phim->binhLuan()->avg('DiemDanhGia'), 1) ?: '0.0';
            }

            // Tìm rạp: CHỈ những rạp liên quan từ khóa hoặc slug
            $rapsSearch = Rap::withCount('phongChieu')
                ->where(function ($q) use ($keywordLower, $slugKeyword) {
                    $q->whereRaw('LOWER(TenRap) LIKE ?', ["%{$keywordLower}%"])
                        ->orWhereRaw('LOWER(Slug) LIKE ?', ["%{$slugKeyword}%"]);
                })
                ->where('TrangThai', 1)
                ->get();
        }

        return view('user.pages.tim-kiem', [
            'phims' => $phims,
            'rapsSearch' => $rapsSearch,
            'keyword' => $keyword,
        ]);
    }
}