<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuatChieu;
use App\Models\PhongChieu;
use App\Models\Rap;
use App\Models\GheNgoi;
use App\Models\VeXemPhim;
use App\Models\HeldSeat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Events\GheDuocGiu;
use Carbon\Carbon;
use App\Models\GheDangGiu;
use App\Models\HoaDon;
use App\Models\Phim;


class DatVeController extends Controller
{
    public function showBySlug($phimSlug, $ngay, $gio)
    {
        Log::info('ShowBySlug', ['session_user' => session('user_id')]);
        try {
            // Validate input parameters
            if (empty($phimSlug) || empty($ngay) || empty($gio)) {
                abort(400, 'Missing required parameters');
            }

            if (!session()->has('user_id')) {
                // Redirect về trang đăng nhập, có thể truyền kèm return url
                return redirect()->route('login.form')->with('error', 'Vui lòng đăng nhập để đặt vé!');
            }
            // Get movie by slug
            $phim = Phim::where('Slug', $phimSlug)->firstOrFail();

            // Get showtime with related data
            $suatChieu = SuatChieu::with(['phim', 'rap', 'phongChieu'])
                ->where('ID_Phim', $phim->ID_Phim)
                ->where('NgayChieu', $ngay)
                ->where('GioChieu', $gio)
                ->firstOrFail();

            // Get cinema room and related cinema
            $phongChieu = PhongChieu::with('rap')->findOrFail($suatChieu->ID_PhongChieu);

            // Get all cinemas
            $raps = Rap::all();

            // Get seats for the room
            $ghengoi = GheNgoi::where('ID_PhongChieu', $phongChieu->ID_PhongChieu)
                ->get(['ID_Ghe', 'TenGhe', 'LoaiTrangThaiGhe']);

            // Get booked seats for this showtime
            $bookedSeats = VeXemPhim::where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
                ->pluck('TenGhe')
                ->toArray();

            // Calculate seat layout dimensions
            $rowCount = 0;
            $colCount = 0;

            foreach ($ghengoi as $ghe) {
                if (preg_match('/([A-Z])(\d+)/', $ghe->TenGhe, $matches)) {
                    $row = ord(strtoupper($matches[1])) - 64; // Convert A->1, B->2, etc.
                    $col = (int)$matches[2];
                    $rowCount = max($rowCount, $row);
                    $colCount = max($colCount, $col);
                }
            }
            GheDangGiu::where('hold_until', '<', now())->delete();
            // Generate seat layout array with booking status
            $seatLayout = [];
            for ($i = 0; $i < $rowCount; $i++) {
                $row = [];
                for ($j = 0; $j < $colCount; $j++) {
                    $tenGhe = chr(65 + $i) . ($j + 1);
                    $ghe = $ghengoi->firstWhere('TenGhe', $tenGhe);

                    // Determine seat status
                    $trangThaiGhe = 0; // Default: disabled
                    if ($ghe) {
                        if (in_array($tenGhe, $bookedSeats)) {
                            $trangThaiGhe = 3; // Booked
                        } else {
                            $trangThaiGhe = $ghe->LoaiTrangThaiGhe ?? 1; // Use seat type or default normal
                        }
                    }
                    $row[] = [
                        'TenGhe' => $tenGhe,
                        'TrangThaiGhe' => $trangThaiGhe,
                        'ID_Ghe' => $ghe ? $ghe->ID_Ghe : null,
                        'IsBooked' => in_array($tenGhe, $bookedSeats)
                    ];
                }
                $seatLayout[] = $row;
            }

            // Parse aisle information
            $rowAisles = array_map('intval', json_decode($phongChieu->HangLoiDi ?? '[]', true) ?: []);
            $colAisles = array_map('intval', json_decode($phongChieu->CotLoiDi ?? '[]', true) ?: []);
            $myHeldSeats = GheDangGiu::where('ID_TaiKhoan', session('user_id'))
                ->where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
                ->where('hold_until', '>', now())
                ->pluck('ID_Ghe')
                ->toArray();
                
            Log::info('ShowBySlug', [
                'session_user' => session('user_id'),
                'myHeldSeats' => $myHeldSeats
            ]);
            
            // Get same-day showtimes
            $suatChieuCungNgay = SuatChieu::where('NgayChieu', $suatChieu->NgayChieu)
                ->where('ID_Rap', $suatChieu->ID_Rap)
                ->where('ID_Phim', $suatChieu->ID_Phim)
                ->orderBy('GioChieu', 'asc')
                ->get(['ID_SuatChieu', 'GioChieu']);

            return view('frontend.pages.dat-ve', compact(
                'suatChieu',
                'suatChieuCungNgay',
                'phongChieu',
                'raps',
                'ghengoi',
                'rowCount',
                'colCount',
                'seatLayout',
                'rowAisles',
                'colAisles',
                'bookedSeats',
                'myHeldSeats'
            ));
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in showBySlug: ' . $e->getMessage());
            abort(404, 'Resource not found');
        }
    }

    public function thanhToan(Request $request)
    {
        try {
            $request->validate([
                'selectedSeats' => 'required|string',
                'suatChieuId' => 'required|integer|exists:suatchieus,ID_SuatChieu'
            ]);
            $selectedSeats = array_filter(explode(',', $request->input('selectedSeats', '')));
            $suatChieuId = $request->input('ID_SuatChieu');
            $userId = session('user_id');

            if (!empty($selectedSeats) && !is_numeric($selectedSeats[0])) {
                // Nếu là TenGhe, convert sang ID_Ghe
                $selectedSeats = GheNgoi::whereIn('TenGhe', $selectedSeats)->pluck('ID_Ghe')->toArray();
            }
            $myHeldSeats = GheDangGiu::where('ID_TaiKhoan', $userId)
                ->where('ID_SuatChieu', $suatChieuId)
                ->where('hold_until', '>', now())
                ->pluck('ID_Ghe')
                ->toArray();
            // selectedSeats đang là ID_Ghe, convert sang TenGhe:
            $gheNgoi = GheNgoi::whereIn('ID_Ghe', $selectedSeats)->get();
            $selectedSeatNames = $gheNgoi->pluck('TenGhe')->toArray();

            $danhSachGheDangGiu = GheDangGiu::whereIn('ID_Ghe', $selectedSeats)
                ->where('ID_TaiKhoan', $userId)
                ->where('ID_SuatChieu', $suatChieuId)
                ->where('hold_until', '>', now())
                ->get();
            if ($danhSachGheDangGiu->count() !== count($selectedSeats)) {
                return redirect()->back()->with('error', 'Một số ghế bạn chọn đã bị mất quyền giữ, vui lòng chọn lại!');
            }
            $suatChieu = SuatChieu::with(['phim', 'rap', 'phongChieu'])
                ->findOrFail($request->input('suatChieuId'));

            // Check if seats are still available
            $bookedSeats = VeXemPhim::where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
                ->whereIn('TenGhe', $selectedSeatNames)
                ->pluck('TenGhe')
                ->toArray();

            if (!empty($bookedSeats)) {
                return redirect()->back()->with('error', 'Một số ghế đã được đặt: ' . implode(', ', $bookedSeats));
            }

            // Lấy lại danh sách ghế theo tên để build seatDetails
            $gheNgoiTheoTen = GheNgoi::where('ID_PhongChieu', $suatChieu->ID_PhongChieu)
                ->whereIn('TenGhe', $selectedSeatNames)
                ->get();

            $seatDetails = [];
            $totalPrice = 0;
            foreach ($selectedSeatNames as $seatName) {
                $seat = $gheNgoiTheoTen->firstWhere('TenGhe', $seatName);
                if ($seat) {
                    $giaVe = (int) $suatChieu->GiaVe;
                    if ($seat->LoaiTrangThaiGhe == 2) {
                        $giaVe = (int) round($giaVe * 1.2);
                    }
                    $totalPrice += $giaVe;
                    $seatDetails[] = [
                        'ID_Ghe' => $seat->ID_Ghe,
                        'TenGhe' => $seat->TenGhe,
                        'LoaiGhe' => $seat->LoaiTrangThaiGhe == 2 ? 'VIP' : 'Thường',
                        'GiaVe'  => $giaVe,
                    ];
                }
            }

            return view('frontend.pages.thanh-toan', [
                // Truyền xuống view là TenGhe chứ không phải ID_Ghe
                'selectedSeats' => $selectedSeatNames,
                'suatChieu' => $suatChieu,
                'seatDetails' => $seatDetails,
                'totalPrice' => $totalPrice,
                'myHeldSeats' => $myHeldSeats,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in thanhToan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function showThanhToan(Request $request)
    {
        $suatChieuId = $request->input('ID_SuatChieu');
        $selectedSeats = array_filter(explode(',', $request->input('selectedSeats')));

        $suatChieu = SuatChieu::with(['phim', 'rap', 'phongChieu'])->findOrFail($suatChieuId);

        // selectedSeats vẫn là ID_Ghe, convert sang TenGhe:
        $gheNgoi = GheNgoi::whereIn('ID_Ghe', $selectedSeats)->get();
        $selectedSeatNames = $gheNgoi->pluck('TenGhe')->toArray();
        GheDangGiu::where('hold_until', '<', now())->delete();
        // Lấy lại danh sách ghế để build seatDetails
        $gheNgoiTheoTen = GheNgoi::where('ID_PhongChieu', $suatChieu->ID_PhongChieu)
            ->whereIn('TenGhe', $selectedSeatNames)
            ->get();

        $myHeldSeats = GheDangGiu::where('ID_TaiKhoan', session('user_id'))
            ->where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
            ->where('hold_until', '>', now())
            ->pluck('ID_Ghe')
            ->toArray();

        $seatDetails = [];
        $totalPrice = 0;
        foreach ($selectedSeatNames as $seatName) {
            $seat = $gheNgoiTheoTen->firstWhere('TenGhe', $seatName);
            if ($seat) {
                $giaVe = (int) $suatChieu->GiaVe;
                if ($seat->LoaiTrangThaiGhe == 2) {
                    $giaVe = (int) round($giaVe * 1.2);
                }
                $totalPrice += $giaVe;
                $seatDetails[] = [
                    'ID_Ghe' => $seat->ID_Ghe,
                    'TenGhe' => $seat->TenGhe,
                    'LoaiGhe' => $seat->LoaiTrangThaiGhe == 2 ? 'VIP' : 'Thường',
                    'GiaVe'  => $giaVe,
                ];
            }
        }
        return view('frontend.pages.thanh-toan', [
            'suatChieu' => $suatChieu,
            'selectedSeats' => $selectedSeatNames,
            'seatDetails' => $seatDetails,
            'totalPrice' => $totalPrice,
            'myHeldSeats' => $myHeldSeats, // THÊM DÒNG NÀY
        ]);
    }


    public function checkSeatAvailability(Request $request)
    {
        try {
            $seats = $request->input('seats', []);
            $suatChieuId = $request->input('suatChieuId');

            if (empty($seats) || !$suatChieuId) {
                return response()->json(['success' => false, 'message' => 'Invalid parameters']);
            }

            // Check if seats are booked
            $bookedSeats = VeXemPhim::where('ID_SuatChieu', $suatChieuId)
                ->whereIn('TenGhe', $seats)
                ->pluck('TenGhe')
                ->toArray();

            $availableSeats = array_diff($seats, $bookedSeats);

            return response()->json([
                'success' => true,
                'availableSeats' => array_values($availableSeats),
                'bookedSeats' => array_values($bookedSeats)
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking seat availability: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error']);
        }
    }
/*
    public function changeShowtime(Request $request)
    {
        try {
            $suatChieuId = $request->input('suatChieuId');
            $phimSlug = $request->input('phimSlug');

            $suatChieu = SuatChieu::with(['phim'])->findOrFail($suatChieuId);

            $ngay = $suatChieu->NgayChieu;
            $gio = substr($suatChieu->GioChieu, 0, 5);

            return redirect()->route('dat-ve.show', [
                'phimSlug' => $phimSlug,
                'ngay' => $ngay,
                'gio' => $gio
            ]);
        } catch (\Exception $e) {
            Log::error('Error changing showtime: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra');
        }
    }
    public function ajaxNgayChieu(Request $request)
    {
        $idRap = $request->id_rap;
        $idPhim = $request->id_phim;
        $ngays = \App\Models\SuatChieu::where('ID_Rap', $idRap)
            ->where('ID_Phim', $idPhim)
            ->pluck('NgayChieu')->unique()->values();
        return response()->json($ngays);
    }

    public function ajaxSuatChieu(Request $request)
    {
        $idRap = $request->id_rap;
        $idPhim = $request->id_phim;
        $ngay = $request->ngay;
        $suats = \App\Models\SuatChieu::where('ID_Rap', $idRap)
            ->where('ID_Phim', $idPhim)
            ->where('NgayChieu', $ngay)
            ->get(['GioChieu', 'ID_SuatChieu', 'DinhDang']);
        $result = $suats->map(function ($s) {
            return [
                'gio' => $s->GioChieu,
                'id' => $s->ID_SuatChieu,
                'dinh_dang' => $s->DinhDang ?? '2D'
            ];
        });
        return response()->json($result);
    }
        */
    public function giuGhe(Request $request)
    {
        Log::info('giuGhe request: ', $request->all());
        Log::info('session user_id: ' . session('user_id'));
        $ma_ghe = $request->ma_ghe;
        $suat_chieu_id = $request->suat_chieu_id;
        $user_id = session('user_id'); // hoặc ID_TaiKhoan

        // Kiểm tra ghế đã được giữ/tạm giữ hoặc đã thanh toán
        $isHeld = GheDangGiu::where('ID_Ghe', $ma_ghe)
            ->where('ID_SuatChieu', $suat_chieu_id)
            ->where('hold_until', '>', now())
            ->first();
        $exists = DB::table('ve_xem_phim')
            ->join('hoa_don', 've_xem_phim.ID_HoaDon', '=', 'hoa_don.ID_HoaDon')
            ->where('ve_xem_phim.TenGhe', $ma_ghe)
            ->where('ve_xem_phim.ID_SuatChieu', $suat_chieu_id)
            ->where('hoa_don.TrangThaiXacNhanThanhToan', 1)
            ->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'error' => 'Ghế này đã được đặt!'
            ]);
        }

        if ($isHeld || $exists) {
            return response()->json(['success' => false, 'error' => 'Ghế đã có người giữ hoặc đã thanh toán!']);
        }

        $hold_until = now()->addMinutes(6);
        GheDangGiu::updateOrCreate(
            ['ID_Ghe' => $ma_ghe, 'ID_SuatChieu' => $suat_chieu_id],
            ['ID_TaiKhoan' => $user_id, 'hold_until' => $hold_until]
        );

        broadcast(new GheDuocGiu($ma_ghe, $suat_chieu_id, $user_id, $hold_until->timestamp, 'hold'))->toOthers();

        return response()->json(['success' => true, 'hold_until' => $hold_until->timestamp]);
    }
    public function boGiuGhe(Request $request)
    {
        Log::info('boGiuGhe called!');
        $ma_ghe = $request->input('ma_ghe');
        $suat_chieu_id = $request->input('suat_chieu_id');
        $user_id = session('user_id');

        Log::info('Bỏ giữ ghế:', [
            'ma_ghe'         => $ma_ghe,
            'suat_chieu_id'  => $suat_chieu_id,
            'user_id'        => $user_id,
        ]);

        $deleted = GheDangGiu::where('ID_Ghe', $ma_ghe)
            ->where('ID_SuatChieu', $suat_chieu_id)
            ->where('ID_TaiKhoan', $user_id)
            ->delete();

        Log::info('Số bản ghi đã xóa:', ['deleted' => $deleted]);

        return response()->json(['success' => true]);
    }
    public function releaseMultipleSeats(Request $request)
    {
        $userId = session('user_id'); // hoặc Session::get('user_id');
        $seatIds = $request->input('danh_sach_ghe', []);
        $suatChieuId = $request->input('suat_chieu_id');

        // Xóa chỉ những ghế mà user hiện tại giữ
        DB::table('ghe_dang_giu')
            ->where('ID_TaiKhoan', $userId)
            ->where('ID_SuatChieu', $suatChieuId)
            ->whereIn('ID_Ghe', $seatIds)
            ->delete();

        return response()->json(['success' => true]);
    }
    public function boGiuGheNhieu(Request $request)
    {
        try {
            // Lấy dữ liệu từ request
            $data = $request->json()->all() ?: $request->all();
            $danhSachGhe = $data['danh_sach_ghe'] ?? [];
            $suatChieuId = $data['suat_chieu_id'] ?? null;
            $userId = $data['user_id'] ?? session('user_id');

            // Validate dữ liệu
            if (!$danhSachGhe || !$suatChieuId || !$userId) {
                Log::warning('Invalid params for boGiuGheNhieu', [
                    'danh_sach_ghe' => $danhSachGhe,
                    'suat_chieu_id' => $suatChieuId,
                    'user_id' => $userId
                ]);
                return response()->json(['success' => false, 'message' => 'Invalid params'], 400);
            }

            // Log thông tin để debug
            Log::info('HUY_GHE', [
                'danh_sach_ghe' => $danhSachGhe,
                'suat_chieu_id' => $suatChieuId,
                'user_id' => $userId,
                'request_data' => $data
            ]);

            // Xóa các ghế được giữ bởi user hiện tại
            $deleted = DB::table('ghe_dang_giu')
                ->where('ID_TaiKhoan', $userId)
                ->where('ID_SuatChieu', $suatChieuId)
                ->whereIn('ID_Ghe', $danhSachGhe)
                ->delete();

            // Broadcast sự kiện hủy ghế
            foreach ($danhSachGhe as $maGhe) {
                broadcast(new GheDuocGiu($maGhe, $suatChieuId, $userId, null, 'release'))->toOthers();
            }

            Log::info('Đã hủy giữ ghế thành công', [
                'deleted_count' => $deleted,
                'user_id' => $userId,
                'suat_chieu_id' => $suatChieuId
            ]);

            return response()->json(['success' => true, 'deleted_count' => $deleted]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi hủy giữ ghế: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return response()->json(['success' => false, 'message' => 'Error releasing seats'], 500);
        }
    }
}