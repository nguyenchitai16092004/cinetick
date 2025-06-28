<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\PhongChieu;
use App\Models\GheNgoi;
use App\Models\Phim;
use App\Models\Rap;
use App\Models\SuatChieu;
use App\Models\VeXemPhim;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\AdminPayOSController;

use function Termwind\parse;

class HoaDonController extends Controller
{
    public function index(Request $request)
    {
        $query = HoaDon::query()->with('taiKhoan');

        // Lọc theo ngày tạo
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('NgayTao', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('NgayTao', '<=', $request->end_date);
        }

        // Lọc theo ID tài khoản
        if ($request->has('id_tai_khoan') && $request->id_tai_khoan) {
            $query->where('ID_TaiKhoan', $request->id_tai_khoan);
        }

        // Lọc theo phương thức thanh toán
        if ($request->has('pttt') && $request->pttt) {
            $query->where('PTTT', 'like', '%' . $request->pttt . '%');
        }

        // Lọc theo khoảng tổng tiền
        if ($request->has('min_amount') && $request->min_amount) {
            $query->where('TongTien', '>=', $request->min_amount);
        }

        if ($request->has('max_amount') && $request->max_amount) {
            $query->where('TongTien', '<=', $request->max_amount);
        }

        $hoaDons = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.pages.hoa_don.hoa-don', compact('hoaDons'));
    }

    /**
     * Hiển thị form tạo hóa đơn mới
     */
    public function create()
    {
        $raps = Rap::all();
        return view('admin.pages.hoa_don.create-hoa-don',  compact('raps'));
    }

    /**
     * Lưu hóa đơn mới vào database
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'TongTien' => 'required|numeric|min:0',
                'SoTienGiam' => 'nullable|numeric|min:0',
                'PTTT' => 'required|string|max:50',
                'ID_SuatChieu' => 'required|integer|min:1',
                'SoLuongVe' => 'required|integer|min:1',
                'TrangThaiXacNhanHoaDon' => 'required|in:0,1,2',
                'TrangThaiXacNhanThanhToan' => 'required|in:0,1',
                'DanhSachGhe' => 'required|array|min:1', // danh sách ghế được gửi lên từ client
                'DiaChi' => 'required|string|max:255',
                'TenPhim' => 'required|string|max:255',
            ]);

            $idTaiKhoan = session('user_id');
            if (!$idTaiKhoan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không xác định được tài khoản người dùng.'
                ], 401);
            }

            DB::beginTransaction();

            // Tạo mã hóa đơn ngẫu nhiên
            $maHoaDon = 'HD' . mt_rand(10000, 99999);

            // Tạo hóa đơn
            $hoaDon = new HoaDon();
            $hoaDon->ID_HoaDon = $maHoaDon;
            $hoaDon->TongTien = $request->TongTien;
            $hoaDon->SoTienGiam = $request->SoTienGiam ?? 0;
            $hoaDon->PTTT = $request->PTTT;
            $hoaDon->ID_TaiKhoan = $idTaiKhoan;
            $hoaDon->SoLuongVe = $request->SoLuongVe;
            $hoaDon->TrangThaiXacNhanHoaDon = $request->TrangThaiXacNhanHoaDon;
            $hoaDon->TrangThaiXacNhanThanhToan = $request->TrangThaiXacNhanThanhToan;
            $hoaDon->save();

            // Duyệt từng ghế để tạo vé xem phim
            foreach ($request->DanhSachGhe as $ghe) {
                DB::table('ve_xem_phim')->insert([
                    'TenGhe'        => $ghe['tenGhe'] ?? 'Không rõ',
                    'TenPhim'       => $request->TenPhim  ?? 'Không rõ',
                    'NgayXem'       => $ghe['NgayXem'] ?? now()->toDateString(),
                    'DiaChi'        => $request->DiaChi ?? 'Không rõ',
                    'GiaVe'         => $ghe['gia'] ?? 0,
                    'TrangThai'     => 0,
                    'ID_SuatChieu'  => (int) $request->ID_SuatChieu ?? null,
                    'ID_HoaDon'     => $hoaDon->ID_HoaDon,
                    'ID_Ghe'        => $ghe['id'] ?? null,
                    'created_at'    => now(),
                    'updated_at'    => now()
                ]);
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã tạo hóa đơn thành công!',
                'maHoaDon' => $hoaDon->ID_HoaDon
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Hiển thị chi tiết hóa đơn và danh sách vé
     */
    public function show($id)
    {
        try {
            // Lấy thông tin hóa đơn với các quan hệ cần thiết
            $hoaDon = HoaDon::with([
                'taiKhoan',
                'veXemPhim' => function ($query) {
                    $query->with(['suatChieu.phongChieu', 'gheNgoi']);
                }
            ])->findOrFail($id);

            // Lấy danh sách vé xem phim
            $veXemPhim = $hoaDon->veXemPhim;

            if ($veXemPhim->isEmpty()) {
                return view('admin.pages.hoa_don.detail-hoa-don', compact('hoaDon', 'veXemPhim'))
                    ->with('message', 'Hóa đơn này không có vé nào.');
            }

            // Lấy thông tin suất chiếu từ vé đầu tiên (giả sử tất cả vé cùng suất chiếu)
            $suatChieu = $veXemPhim->first()->suatChieu;

            if (!$suatChieu || !$suatChieu->phongChieu) {
                return view('admin.pages.hoa_don.detail-hoa-don', compact('hoaDon', 'veXemPhim'))
                    ->with('message', 'Không tìm thấy thông tin phòng chiếu.');
            }

            $phongChieu = $suatChieu->phongChieu;

            // Lấy tất cả ghế trong phòng theo thứ tự
            $gheList = GheNgoi::where('ID_PhongChieu', $phongChieu->ID_PhongChieu)
                ->orderByRaw("SUBSTRING(TenGhe, 1, 1), CAST(SUBSTRING(TenGhe, 2) AS UNSIGNED)")
                ->get();

            if ($gheList->isEmpty()) {
                return view('admin.pages.hoa_don.detail-hoa-don', compact('hoaDon', 'veXemPhim'))
                    ->with('message', 'Không tìm thấy thông tin ghế trong phòng chiếu.');
            }

            // Tính số hàng và cột từ dữ liệu thực tế
            $rowCount = 0;
            $colCount = 0;

            foreach ($gheList as $ghe) {
                if (preg_match('/([A-Z])(\d+)/', $ghe->TenGhe, $matches)) {
                    $row = ord($matches[1]) - 64;
                    $col = (int)$matches[2];
                    $rowCount = max($rowCount, $row);
                    $colCount = max($colCount, $col);
                }
            }

            // Tạo sơ đồ ghế theo cấu trúc tương thích với dat-ghe.js
            $seatLayout = [];
            for ($i = 0; $i < $rowCount; $i++) {
                $row = [];
                for ($j = 0; $j < $colCount; $j++) {
                    $tenGhe = chr(65 + $i) . ($j + 1);
                    $ghe = $gheList->firstWhere('TenGhe', $tenGhe);

                    if ($ghe) {
                        $row[] = [
                            'ID_Ghe' => $ghe->ID_Ghe,
                            'TenGhe' => $ghe->TenGhe,
                            'TrangThaiGhe' => $ghe->LoaiTrangThaiGhe, // 0: disabled, 1: normal, 2: vip
                            'IsBooked' => false // Sẽ được xử lý riêng
                        ];
                    } else {
                        // Ghế không tồn tại hoặc bị vô hiệu hóa
                        $row[] = [
                            'ID_Ghe' => null,
                            'TenGhe' => $tenGhe,
                            'TrangThaiGhe' => 0,
                            'IsBooked' => false
                        ];
                    }
                }
                $seatLayout[] = $row;
            }

            // Lấy thông tin lối đi và đảm bảo dữ liệu là mảng
            $hangLoiDiRaw = $phongChieu->HangLoiDi ?? '[]';
            $cotLoiDiRaw = $phongChieu->CotLoiDi ?? '[]';

            $rowAisles = json_decode($hangLoiDiRaw, true);
            $colAisles = json_decode($cotLoiDiRaw, true);

            // Đảm bảo dữ liệu là mảng và chứa số nguyên
            $rowAisles = is_array($rowAisles) ? array_map('intval', $rowAisles) : [];
            $colAisles = is_array($colAisles) ? array_map('intval', $colAisles) : [];

            // Debug log để kiểm tra dữ liệu lối đi
            Log::info('Dữ liệu lối đi từ database:', [
                'HangLoiDi_raw' => $hangLoiDiRaw,
                'CotLoiDi_raw' => $cotLoiDiRaw,
                'rowAisles_parsed' => $rowAisles,
                'colAisles_parsed' => $colAisles,
                'phong_chieu_id' => $phongChieu->ID_PhongChieu
            ]);

            // Lấy danh sách ghế đã đặt trong hóa đơn này
            $bookedSeats = $veXemPhim->pluck('TenGhe')->toArray();
            $bookedSeatIds = $veXemPhim->pluck('ID_Ghe')->filter()->toArray();

            // Đánh dấu ghế đã đặt trong seatLayout
            foreach ($seatLayout as &$row) {
                foreach ($row as &$seat) {
                    if ($seat['ID_Ghe'] && in_array($seat['ID_Ghe'], $bookedSeatIds)) {
                        $seat['IsBooked'] = true;
                    }
                }
            }

            return view('admin.pages.hoa_don.detail-hoa-don', compact(
                'hoaDon',
                'veXemPhim',
                'seatLayout',
                'rowAisles',
                'colAisles',
                'bookedSeats',
                'bookedSeatIds'
            ));
        } catch (\Exception $e) {
            Log::error('Error in HoaDonController@show: ' . $e->getMessage());

            return redirect()->route('hoa-don.index')
                ->with('error', 'Có lỗi xảy ra khi tải chi tiết hóa đơn: ' . $e->getMessage());
        }
    }


    /**
     * Hiển thị form chỉnh sửa hóa đơn
     */
    public function edit($id)
    {
        $hoaDon = HoaDon::findOrFail($id);
        return view('admin.pages.hoa_don.detail-hoa-don', compact('hoaDon'));
    }

    /**
     * Cập nhật thông tin hóa đơn
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'ID_TaiKhoan' => 'required|exists:tai_khoan,ID_TaiKhoan',
            'TongTien' => 'required|numeric|min:0',
            'PTTT' => 'required|string|max:50',
        ]);

        $hoaDon = HoaDon::findOrFail($id);
        $hoaDon->TongTien = $request->TongTien;
        $hoaDon->PTTT = $request->PTTT;
        $hoaDon->ID_TaiKhoan = $request->ID_TaiKhoan;
        $hoaDon->save();

        return redirect()->route('admin.hoadon.index')
            ->with('success', 'Hóa đơn đã được cập nhật thành công!');
    }

    /**
     * Xóa hóa đơn
     */
    public function destroy($id)
    {
        // Bắt đầu một transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();

        try {
            $hoaDon = HoaDon::findOrFail($id);

            // Xóa tất cả các vé xem phim liên quan
            VeXemPhim::where('ID_HoaDon', $id)->delete();

            // Xóa hóa đơn
            $hoaDon->delete();

            DB::commit();
            return redirect()->route('admin.hoadon.index')
                ->with('success', 'Hóa đơn và các vé liên quan đã được xóa thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.hoadon.index')
                ->with('error', 'Không thể xóa hóa đơn: ' . $e->getMessage());
        }
    }

    /**
     * Xuất báo cáo hóa đơn
     */
    public function exportReport(Request $request)
    {
        $query = HoaDon::query()->with('taiKhoan');

        $hoaDons = $query->orderBy('NgayTao', 'desc')->get();

        return redirect()->route('admin.hoadon.index')
            ->with('success', 'Báo cáo đã được xuất thành công!');
    }

    public function filterMovieByDate(Request $request)
    {
        try {
            $date = $request->date;
            $ID_Rap = (int)$request->ID_Rap;

            $phims = Phim::join('suat_chieu', 'phim.ID_Phim', '=', 'suat_chieu.ID_Phim')
                ->where('ID_Rap', $ID_Rap)
                ->where('suat_chieu.NgayChieu', $date)
                ->select('phim.*', 'suat_chieu.GioChieu', 'suat_chieu.ID_PhongChieu', 'suat_chieu.ID_SuatChieu', 'suat_chieu.GiaVe')
                ->get()
                ->groupBy('ID_Phim')
                ->map(function ($group) {
                    $phim = $group->first();

                    // Tạo danh sách suất chiếu gồm giờ và phòng
                    $suatChieus = $group->map(function ($item) {
                        return [
                            'gio' => Carbon::parse($item->GioChieu)->format('H:i'),
                            'phong' => $item->ID_PhongChieu,
                            'id' => $item->ID_SuatChieu,
                            'gia_ve' => (int)$item->GiaVe,
                        ];
                    })
                        ->unique(fn($item) => $item['gio'] . '-' . $item['phong']) // Tránh trùng giờ+phòng
                        ->values();

                    return [
                        'ID_Phim'       => $phim->ID_Phim,
                        'TenPhim'       => $phim->TenPhim,
                        'ThoiLuong'     => $phim->ThoiLuong,
                        'DoTuoi'        => $phim->DoTuoi,
                        'HinhAnh'       => $phim->HinhAnh,
                        'SuatChieu'     => $suatChieus,
                    ];
                })
                ->values();

            return response()->json($phims);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    public function layTheoId(Request $request)
    {
        $id = $request->id_phong;
        $idSuatChieu = $request->ID_SuatChieu;

        $gheList = PhongChieu::where('phong_chieu.ID_PhongChieu', $id)
            ->join('ghe_ngoi', 'ghe_ngoi.ID_PhongChieu', '=', 'phong_chieu.ID_PhongChieu')
            ->select('phong_chieu.*', 'ghe_ngoi.*')
            ->get();

        $gheDaDat = SuatChieu::join("ve_xem_phim", 've_xem_phim.ID_SuatChieu', '=', 'suat_chieu.ID_SuatChieu')
            ->where('ve_xem_phim.ID_SuatChieu', $idSuatChieu)
            ->select('ve_xem_phim.ID_Ghe')
            ->get();


        if ($gheList->isEmpty()) {
            return response()->json(['error' => 'Không tìm thấy phòng chiếu hoặc ghế ngồi'], 404);
        }

        // Lấy thông tin phòng từ 1 dòng bất kỳ
        $phongInfo = $gheList->first();

        // Tính số hàng và cột
        $rowCount = 0;
        $colCount = 0;

        foreach ($gheList as $ghe) {
            if (preg_match('/([A-Z])(\d+)/', $ghe->TenGhe, $matches)) {
                $row = ord($matches[1]) - 64;
                $col = (int)$matches[2];
                $rowCount = max($rowCount, $row);
                $colCount = max($colCount, $col);
            }
        }

        // Tạo sơ đồ ghế
        $seatLayout = [];
        for ($i = 0; $i < $rowCount; $i++) {
            $row = [];
            for ($j = 0; $j < $colCount; $j++) {
                $tenGhe = chr(65 + $i) . ($j + 1);
                $ghe = $gheList->firstWhere('TenGhe', $tenGhe);

                $row[] = [
                    'TenGhe'       => $tenGhe,
                    'ID_Ghe'   => $ghe->ID_Ghe ?? null,
                    'TrangThaiGhe' => $ghe->LoaiTrangThaiGhe ?? 0
                ];
            }
            $seatLayout[] = $row;
        }

        $rowAisles = json_decode($phongInfo->HangLoiDi ?? '[]');
        $colAisles = json_decode($phongInfo->CotLoiDi ?? '[]');

        return response()->json([
            'GheDaDat'      => $gheDaDat->pluck('ID_Ghe'),
            'ID_PhongChieu' => $phongInfo->ID_PhongChieu,
            'TenPhong'   => $phongInfo->TenPhong,
            'seatLayout' => $seatLayout,
            'rowAisles'  => $rowAisles,
            'colAisles'  => $colAisles
        ]);
    }

    public function kiemTra(Request $request)
    {
        // Chuyển mã khuyến mãi sang chữ in hoa
        $request->merge([
            'MaKhuyenMai' => strtoupper($request->input('MaKhuyenMai'))
        ]);

        $request->validate([
            'MaKhuyenMai' => 'required|max:100',
        ]);

        $ma = $request->MaKhuyenMai;

        $khuyenMai = KhuyenMai::where('MaKhuyenMai', $ma)->first();

        if (!$khuyenMai) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại.',
            ]);
        }

        // Kiểm tra hạn sử dụng bằng cột NgayKetThuc
        if (strtotime($khuyenMai->NgayKetThuc) < strtotime(date('Y-m-d'))) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi đã hết hạn.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã thành công!',
            'khuyenMai' => $khuyenMai
        ]);
    }

    public function payment(Request $request)
    {
        try {
            $validated = $request->validate([
                'ID_SuatChieu' => 'required|integer|exists:suat_chieu,ID_SuatChieu',
                'selectedSeats' => 'required|string',
                'paymentMethod' => 'required|in:COD,PAYOS',
                'tong_tien' => 'required|integer',

            ]);
            if (!session()->has('user_id')) {
                return response()->json(['error' => 'Vui lòng đăng nhập để đặt vé!'], 401);
            }

            // Chuẩn hóa selectedSeats thành mảng
            $selectedSeats = array_filter(explode(',', $request->input('selectedSeats', '')));
            if (!is_array($selectedSeats) || empty($selectedSeats)) {
                return response()->json(['error' => 'Vui lòng chọn ít nhất một ghế!'], 400);
            }

            $suatChieu = SuatChieu::with(['phim', 'rap', 'phongChieu'])
                ->findOrFail($validated['ID_SuatChieu']);

            // Check đã đặt chưa
            $bookedSeats = VeXemPhim::where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
                ->whereIn('TenGhe', $selectedSeats)
                ->where('TrangThai', '!=', 2) // chỉ ghế chưa hủy
                ->pluck('TenGhe')
                ->toArray();

            if (!empty($bookedSeats)) {
                $message = 'Một số ghế đã được đặt: ' . implode(', ', $bookedSeats);
                return response()->json(['error' => $message], 409);
            }

            // Lấy danh sách ghế từ DB
            $gheNgoi = GheNgoi::where('ID_PhongChieu', $suatChieu->ID_PhongChieu)
                ->whereIn('TenGhe', $selectedSeats)
                ->get();

            // Lấy seatDetails từ request (nếu có), nếu không thì build lại từ DB
            $seatDetails = [];
            if ($request->filled('seatDetails')) {
                $decoded = json_decode($request->input('seatDetails'), true);
                if (is_array($decoded) && count($decoded) === count($selectedSeats)) {
                    $seatDetails = $decoded;
                }
            }
            if (empty($seatDetails)) {
                foreach ($selectedSeats as $seatName) {
                    $seat = $gheNgoi->firstWhere('TenGhe', $seatName);
                    if ($seat) {
                        $giaVe = (int) $suatChieu->GiaVe;
                        if ($seat->LoaiTrangThaiGhe == 2) {
                            $giaVe = (int) round($giaVe * 1.2);
                        }
                        $seatDetails[] = [
                            'ID_Ghe' => $seat->ID_Ghe,
                            'TenGhe' => $seatName,
                            'LoaiGhe' => $seat->LoaiTrangThaiGhe == 2 ? 'VIP' : 'Thường',
                            'GiaVe'  => $giaVe,
                        ];
                    }
                }
            }

            // Tính tổng tiền lại từ seatDetails
            $calculatedTotal = $request->tong_tien;
            $tenKhachHang = session('user_fullname', 'Người dùng');
            $email = session('user_email', 'no-reply@example.com');
            $orderData = [
                'ten_khach_hang' => $tenKhachHang,
                'email' => $email,
                'ID_SuatChieu'   => $validated['ID_SuatChieu'],
                'selectedSeats'  => $selectedSeats,
                'seatDetails'    => $seatDetails,
                'tong_tien'      => $calculatedTotal,
                'so_tien_giam'   => $request->input('so_tien_giam', 0),
                'ma_khuyen_mai'  => $request->input('ma_khuyen_mai', ''),
                'ten_phim'       => $suatChieu->phim->TenPhim,
                'ngay_xem'       => $suatChieu->NgayChieu,
                'gio_xem'        => $suatChieu->GioChieu,
                'ten_rap'        => $suatChieu->rap->TenRap,
                'ten_phong'      => $suatChieu->phongChieu->TenPhongChieu,
            ];

            // Log orderData để debug
            Log::info('orderData gửi sang PayOS:', $orderData);

            if ($request->paymentMethod === 'COD') {
                return $this->processCODTicket($orderData);
            }

            if ($request->paymentMethod === 'PAYOS') {
                session(['pending_payment' => $orderData]);
                $AdminPayOSController = app()->make(AdminPayOSController::class);
                $response = $AdminPayOSController->createPaymentLink($orderData);
                return $response;
            }

            return response()->json(['error' => 'Phương thức thanh toán không hợp lệ.'], 400);
        } catch (\Exception $e) {
            Log::error('Lỗi thanh toán: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra, vui lòng thử lại!'], 500);
        }
    }

    private function processCODTicket($orderData)
    {
        try {
            DB::beginTransaction();

            $maHoaDon = HoaDon::generateMaHoaDon();

            $hoaDon = HoaDon::create([
                'ID_HoaDon' => $maHoaDon,
                'TongTien' => $orderData['tong_tien'],
                'PTTT' => 'COD',
                'ID_TaiKhoan' => session('user_id'),
                'TrangThaiXacNhanHoaDon' => 0,
                'TrangThaiXacNhanThanhToan' => 0,
                'SoLuongVe' => count($orderData['selectedSeats']),
            ]);

            foreach ($orderData['seatDetails'] as $seatDetail) {
                VeXemPhim::create([
                    'TenGhe' => $seatDetail['TenGhe'],
                    'TenPhim' => $orderData['ten_phim'],
                    'NgayXem' => $orderData['ngay_xem'],
                    'DiaChi' => $orderData['ten_rap'] . ' - ' . $orderData['ten_phong'],
                    'GiaVe' => $seatDetail['GiaVe'],
                    'TrangThai' => 0,
                    'ID_SuatChieu' => $orderData['ID_SuatChieu'],
                    'ID_HoaDon' => $maHoaDon,
                    'ID_Ghe' => $seatDetail['ID_Ghe'],
                ]);
            }

            DB::commit();

            return redirect()->route('hoa-don.index')->with('success', 'Thanh toán thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing COD ticket: ' . $e->getMessage());
            throw $e;
        }
    }
}
