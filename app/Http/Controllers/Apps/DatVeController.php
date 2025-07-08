<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuatChieu;
use App\Models\PhongChieu;
use App\Models\Rap;
use App\Models\GheNgoi;
use App\Models\VeXemPhim;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Events\GheDuocGiu;
use Carbon\Carbon;
use App\Models\GheDangGiu;
use App\Models\Phim;


class DatVeController extends Controller
{
    public function showBySlug($phimSlug, $ngay, $gio)
    {
        Log::info('ShowBySlug', ['session_user' => session('user_id')]);
        try {
            // 1. Kiểm tra tham số truyền vào
            if (empty($phimSlug) || empty($ngay) || empty($gio)) {
                abort(400, 'Thiếu tham số bắt buộc');
            }

            // 2. Kiểm tra đăng nhập, nếu chưa thì chuyển về trang đăng nhập
            if (!session()->has('user_id')) {
                return redirect()->route('login.form')->with('error', 'Vui lòng đăng nhập để đặt vé!');
            }

            // 3. Lấy thông tin phim theo slug
            $phim = Phim::where('Slug', $phimSlug)->firstOrFail();

            // 4. Lấy thông tin suất chiếu theo phim, ngày, giờ
            $suatChieu = SuatChieu::with(['phim', 'rap', 'phongChieu'])
                ->where('ID_Phim', $phim->ID_Phim)
                ->where('NgayChieu', $ngay)
                ->where('GioChieu', $gio)
                ->firstOrFail();

            // ===== 5. Kiểm tra ẩn suất chiếu trước 15 phút =====
            $gioChieu = strlen($suatChieu->GioChieu) === 5 ? $suatChieu->GioChieu . ':00' : $suatChieu->GioChieu;
            $suatDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $suatChieu->NgayChieu . ' ' . $gioChieu);
            $now = now();
            // Nếu đã vào khoảng 15 phút trước giờ chiếu thì không cho đặt vé online
            if ($now->greaterThanOrEqualTo($suatDateTime->subMinutes(15))) {
                return view('user.pages.dat-ve', [
                    'showPopup' => true,
                    'popupMessage' => 'Vé online dành cho suất chiếu này đã hết!'
                ]);
            }
            // ===== Kết thúc kiểm tra ẩn suất chiếu =====

            // 6. Lấy thông tin phòng chiếu và rạp
            $phongChieu = PhongChieu::with('rap')->findOrFail($suatChieu->ID_PhongChieu);

            // 7. Lấy danh sách rạp đang hoạt động
            $raps = Rap::where('TrangThai', 1)->get();

            // 8. Lấy danh sách ghế trong phòng chiếu
            $ghengoi = GheNgoi::where('ID_PhongChieu', $phongChieu->ID_PhongChieu)
                ->get(['ID_Ghe', 'TenGhe', 'LoaiTrangThaiGhe']);

            // 9. Lấy danh sách ghế đã được đặt cho suất chiếu này
            $bookedSeats = VeXemPhim::where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
                ->pluck('TenGhe')
                ->toArray();

            // 10. Tính số hàng, số cột của sơ đồ ghế
            $rowCount = 0;
            $colCount = 0;
            foreach ($ghengoi as $ghe) {
                if (preg_match('/([A-Z])(\d+)/', $ghe->TenGhe, $matches)) {
                    $row = ord(strtoupper($matches[1])) - 64; // A->1, B->2,...
                    $col = (int)$matches[2];
                    $rowCount = max($rowCount, $row);
                    $colCount = max($colCount, $col);
                }
            }

            // 11. Xóa các ghế giữ đã hết hạn
            GheDangGiu::where('hold_until', '<', now())->delete();

            // 12. Tạo mảng sơ đồ ghế với trạng thái từng ghế
            $seatLayout = [];
            for ($i = 0; $i < $rowCount; $i++) {
                $row = [];
                for ($j = 0; $j < $colCount; $j++) {
                    $tenGhe = chr(65 + $i) . ($j + 1);
                    $ghe = $ghengoi->firstWhere('TenGhe', $tenGhe);

                    // Xác định trạng thái ghế
                    $trangThaiGhe = 0; // Mặc định: ghế bị vô hiệu hóa
                    if ($ghe) {
                        if (in_array($tenGhe, $bookedSeats)) {
                            $trangThaiGhe = 3; // Đã đặt
                        } else {
                            $trangThaiGhe = $ghe->LoaiTrangThaiGhe ?? 1; // Loại ghế hoặc thường
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

            // 13. Lấy thông tin lối đi giữa các hàng/cột
            $rowAisles = array_map('intval', json_decode($phongChieu->HangLoiDi ?? '[]', true) ?: []);
            $colAisles = array_map('intval', json_decode($phongChieu->CotLoiDi ?? '[]', true) ?: []);

            // 14. Lấy danh sách ghế đang được giữ bởi user hiện tại
            $myHeldSeats = array_map('strval', GheDangGiu::where('ID_TaiKhoan', session('user_id'))
                ->where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
                ->where('hold_until', '>', now())
                ->pluck('ID_Ghe')
                ->toArray());

            Log::info('ShowBySlug', [
                'session_user' => session('user_id'),
                'myHeldSeats' => $myHeldSeats
            ]);

            // 15. Lấy danh sách ghế đang được giữ bởi người khác
            $allHeldSeats = GheDangGiu::where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
                ->where('hold_until', '>', now())
                ->pluck('ID_Ghe')
                ->map(function ($id) {
                    return (string)$id;
                })
                ->toArray();
            $heldSeatsByOthers = array_values(array_diff($allHeldSeats, $myHeldSeats));

            // 16. Lấy thời gian giữ ghế của user hiện tại (nếu có)
            $holdUntilMap = GheDangGiu::where('ID_TaiKhoan', session('user_id'))
                ->where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
                ->where('hold_until', '>', now())
                ->get(['ID_Ghe', 'hold_until'])
                ->pluck('hold_until', 'ID_Ghe')
                ->map(function ($hold_until) {
                    return \Carbon\Carbon::parse($hold_until)->timestamp;
                })->toArray();

            // 17. Trả về view đặt vé với đầy đủ dữ liệu
            return view('user.pages.dat-ve', compact(
                'suatChieu',
                'phongChieu',
                'raps',
                'ghengoi',
                'rowCount',
                'colCount',
                'seatLayout',
                'rowAisles',
                'colAisles',
                'bookedSeats',
                'myHeldSeats',
                'heldSeatsByOthers',
                'holdUntilMap'
            ));
        } catch (\Exception $e) {
            // Ghi log lỗi để debug
            Log::error('Error in showBySlug: ' . $e->getMessage());
            abort(404, 'Không tìm thấy tài nguyên');
        }
    }

    public function thanhToan(Request $request)
    {
        try {
            // 1. Validate dữ liệu đầu vào
            $request->validate([
                'selectedSeats' => 'required|string',
                'suatChieuId' => 'required|integer|exists:suatchieus,ID_SuatChieu'
            ]);
            $selectedSeats = array_filter(explode(',', $request->input('selectedSeats', '')));
            $suatChieuId = $request->input('suatChieuId'); // Sửa lại đúng tên biến
            $userId = session('user_id');

            // 2. Nếu selectedSeats là TenGhe thì chuyển sang ID_Ghe
            if (!empty($selectedSeats) && !is_numeric($selectedSeats[0])) {
                $selectedSeats = GheNgoi::whereIn('TenGhe', $selectedSeats)->pluck('ID_Ghe')->toArray();
            }

            // 3. Lấy thông tin suất chiếu
            $suatChieu = SuatChieu::with(['phim', 'rap', 'phongChieu'])
                ->findOrFail($suatChieuId);

            // ===== 4. Kiểm tra thời gian suất chiếu, nếu đã vào 15 phút trước giờ chiếu thì hiện popup =====
            $gioChieu = strlen($suatChieu->GioChieu) === 5 ? $suatChieu->GioChieu . ':00' : $suatChieu->GioChieu;
            $suatDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $suatChieu->NgayChieu . ' ' . $gioChieu);
            $now = now();
            if ($now->greaterThanOrEqualTo($suatDateTime->subMinutes(15))) {
                // Trả về view thanh toán với popup thông báo
                return view('user.pages.thanh-toan', [
                    'showPopup' => true,
                    'popupMessage' => 'Vé online dành cho suất chiếu này đã hết!',
                    // Truyền các biến khác nếu cần cho view không lỗi
                    'selectedSeats' => [],
                    'suatChieu' => $suatChieu,
                    'seatDetails' => [],
                    'totalPrice' => 0,
                    'myHeldSeats' => [],
                    'bookingTimeLeft' => 0,
                ]);
            }
            // ===== Kết thúc kiểm tra thời gian =====

            // 5. Lấy danh sách ghế đang được giữ bởi user hiện tại
            $myHeldSeats = GheDangGiu::where('ID_TaiKhoan', $userId)
                ->where('ID_SuatChieu', $suatChieuId)
                ->where('hold_until', '>', now())
                ->get();

            // 6. Tính thời gian giữ ghế còn lại
            if ($myHeldSeats->count() > 0) {
                $maxHoldUntil = $myHeldSeats->max('hold_until');
                $maxHoldUntilTimestamp = is_numeric($maxHoldUntil) ? $maxHoldUntil : strtotime($maxHoldUntil);
                $bookingTimeLeft = max(0, $maxHoldUntilTimestamp - time());
            } else {
                $bookingTimeLeft = 0;
            }

            // 7. Chuyển selectedSeats (ID_Ghe) sang TenGhe
            $gheNgoi = GheNgoi::whereIn('ID_Ghe', $selectedSeats)->get();
            $selectedSeatNames = $gheNgoi->pluck('TenGhe')->toArray();

            // 8. Kiểm tra các ghế có còn được giữ bởi user không
            $danhSachGheDangGiu = GheDangGiu::whereIn('ID_Ghe', $selectedSeats)
                ->where('ID_TaiKhoan', $userId)
                ->where('ID_SuatChieu', $suatChieuId)
                ->where('hold_until', '>', now())
                ->get();
            if ($danhSachGheDangGiu->count() !== count($selectedSeats)) {
                return redirect()->back()->with('error', 'Một số ghế bạn chọn đã bị mất quyền giữ, vui lòng chọn lại!');
            }

            // 9. Kiểm tra các ghế đã bị đặt chưa
            $bookedSeats = VeXemPhim::where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
                ->whereIn('TenGhe', $selectedSeatNames)
                ->pluck('TenGhe')
                ->toArray();

            if (!empty($bookedSeats)) {
                return redirect()->back()->with('error', 'Một số ghế đã được đặt: ' . implode(', ', $bookedSeats));
            }

            // 10. Lấy lại danh sách ghế theo tên để build seatDetails
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

            // 11. Lưu thời gian bắt đầu giữ ghế vào session nếu chưa có
            if (!session()->has('hold_start')) {
                session(['hold_start' => now()]);
            }

            // 12. Lấy lại danh sách ghế đang giữ (dạng mảng ID_Ghe dạng chuỗi)
            $myHeldSeats = array_map('strval', GheDangGiu::where('ID_TaiKhoan', session('user_id'))
                ->where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
                ->where('hold_until', '>', now())
                ->pluck('ID_Ghe')
                ->toArray());

            // 13. Trả về view thanh toán với đầy đủ dữ liệu
            return view('user.pages.thanh-toan', [
                'selectedSeats' => $selectedSeatNames,
                'suatChieu' => $suatChieu,
                'seatDetails' => $seatDetails,
                'totalPrice' => $totalPrice,
                'myHeldSeats' => $myHeldSeats,
                'bookingTimeLeft' => $bookingTimeLeft,
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
            ->where('ID_SuatChieu', $suatChieuId)
            ->where('hold_until', '>', now())
            ->get();

        if ($myHeldSeats->count() > 0) {
            $maxHoldUntil = $myHeldSeats->max('hold_until');
            $maxHoldUntilTimestamp = is_numeric($maxHoldUntil) ? $maxHoldUntil : strtotime($maxHoldUntil);
            $bookingTimeLeft = max(0, $maxHoldUntilTimestamp - time());
        } else {
            $bookingTimeLeft = 0;
        }

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
        return view('user.pages.thanh-toan', [
            'suatChieu' => $suatChieu,
            'selectedSeats' => $selectedSeatNames,
            'seatDetails' => $seatDetails,
            'totalPrice' => $totalPrice,
            'myHeldSeats' => $myHeldSeats, 
            'bookingTimeLeft' => $bookingTimeLeft,
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
        Log::info('Đã broadcast GheDuocGiu', [
            'ma_ghe' => $ma_ghe,
            'suat_chieu_id' => $suat_chieu_id,
            'user_id' => $user_id,
        ]);
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

        // Thêm dòng này để broadcast realtime event "release"
        broadcast(new GheDuocGiu($ma_ghe, $suat_chieu_id, $user_id, null, 'release'))->toOthers();

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