<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\PhongChieu;
use App\Models\TaiKhoan;
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


class HoaDonController extends Controller
{
    public function index(Request $request)
    {
        if (session('user_role') == '2') {
            $query = DB::table('hoa_don')
                ->join('tai_khoan', 'hoa_don.ID_TaiKhoan', '=', 'tai_khoan.ID_TaiKhoan')
                ->join('thong_tin', 'tai_khoan.ID_ThongTin', '=', 'thong_tin.ID_ThongTin')
                ->select(
                'hoa_don.ID_HoaDon',
                'hoa_don.created_at',
                'hoa_don.PTTT',
                'hoa_don.TongTien',
                'hoa_don.TrangThaiXacNhanHoaDon', 
                'thong_tin.HoTen'
            );
        } else {
            $ID_Rap = session('user_id') ? TaiKhoan::join('thong_tin', 'thong_tin.ID_ThongTin', 'tai_khoan.ID_ThongTin')
                ->where('tai_khoan.ID_TaiKhoan', session('user_id'))
                ->value('thong_tin.ID_Rap') : null;
            $query = DB::table('hoa_don')
                ->join('tai_khoan', 'hoa_don.ID_TaiKhoan', '=', 'tai_khoan.ID_TaiKhoan')
                ->join('thong_tin', 'tai_khoan.ID_ThongTin', '=', 'thong_tin.ID_ThongTin')
                ->join('ve_xem_phim', 'hoa_don.ID_HoaDon', '=', 've_xem_phim.ID_HoaDon')
                ->join('suat_chieu', 've_xem_phim.ID_SuatChieu', '=', 'suat_chieu.ID_SuatChieu')
                ->where('suat_chieu.ID_Rap', $ID_Rap)
                ->select(
                'hoa_don.ID_HoaDon',
                'hoa_don.created_at',
                'hoa_don.PTTT',
                'hoa_don.TongTien',
                'hoa_don.TrangThaiXacNhanHoaDon', 
                'thong_tin.HoTen'
            );
        }


        // Lọc theo ngày tạo
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('hoa_don.created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('hoa_don.created_at', '<=', $request->end_date);
        }
        // Sắp xếp lại
        $hoaDons = $query->orderBy('hoa_don.created_at', 'desc')->paginate(10);

        return view('admin.pages.hoa_don.hoa-don', compact('hoaDons'));
    }


    /**
     * Hiển thị form tạo hóa đơn mới
     */
    public function create()
    {
        $raps = Rap::all();
        $taiKhoan = TaiKhoan::join('thong_tin', 'thong_tin.ID_ThongTin', 'tai_khoan.ID_ThongTin')
            ->where('tai_khoan.ID_TaiKhoan', session('user_id'))
            ->join('rap', 'rap.ID_Rap', 'thong_tin.ID_Rap')
            ->select('rap.*')
            ->first();
        return view('admin.pages.hoa_don.create-hoa-don',  compact('raps', 'taiKhoan'));
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
                'DanhSachGhe' => 'required|array|min:1',
                'DiaChi' => 'required|string|max:255',
                'TenPhim' => 'required|string|max:255',
            ], [
                'TongTien.required' => 'Vui lòng nhập tổng tiền.',
                'TongTien.numeric' => 'Tổng tiền phải là số.',
                'TongTien.min' => 'Tổng tiền không được âm.',

                'SoTienGiam.numeric' => 'Số tiền giảm phải là số.',
                'SoTienGiam.min' => 'Số tiền giảm không được âm.',

                'PTTT.required' => 'Vui lòng chọn phương thức thanh toán.',
                'PTTT.string' => 'Phương thức thanh toán không hợp lệ.',
                'PTTT.max' => 'Phương thức thanh toán không được vượt quá 50 ký tự.',

                'ID_SuatChieu.required' => 'Vui lòng chọn suất chiếu.',
                'ID_SuatChieu.integer' => 'Suất chiếu không hợp lệ.',
                'ID_SuatChieu.min' => 'Suất chiếu không hợp lệ.',

                'SoLuongVe.required' => 'Vui lòng nhập số lượng vé.',
                'SoLuongVe.integer' => 'Số lượng vé phải là số nguyên.',
                'SoLuongVe.min' => 'Số lượng vé phải lớn hơn 0.',

                'TrangThaiXacNhanHoaDon.required' => 'Vui lòng chọn trạng thái hóa đơn.',
                'TrangThaiXacNhanHoaDon.in' => 'Trạng thái hóa đơn không hợp lệ.',

                'TrangThaiXacNhanThanhToan.required' => 'Vui lòng chọn trạng thái thanh toán.',
                'TrangThaiXacNhanThanhToan.in' => 'Trạng thái thanh toán không hợp lệ.',

                'DanhSachGhe.required' => 'Vui lòng chọn ít nhất một ghế.',
                'DanhSachGhe.array' => 'Danh sách ghế không hợp lệ.',
                'DanhSachGhe.min' => 'Vui lòng chọn ít nhất một ghế.',

                'DiaChi.required' => 'Vui lòng nhập địa chỉ.',
                'DiaChi.string' => 'Địa chỉ không hợp lệ.',
                'DiaChi.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

                'TenPhim.required' => 'Vui lòng nhập tên phim.',
                'TenPhim.string' => 'Tên phim không hợp lệ.',
                'TenPhim.max' => 'Tên phim không được vượt quá 255 ký tự.',
            ]);


            $idTaiKhoan = session('user_id');
            if (!$idTaiKhoan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không xác định được tài khoản người dùng.'
                ], 401);
            }

            DB::beginTransaction();

            do {
                $maHoaDon = 'HD' . mt_rand(10000, 99999);
                $exists = HoaDon::where('ID_HoaDon', $maHoaDon)->exists();
            } while ($exists);

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
                DB::table('ghe_dang_giu')
                    ->where('ID_Ghe', $ghe['id'])
                    ->where('ID_SuatChieu', (int) $request->ID_SuatChieu)
                    ->where('ID_TaiKhoan', $idTaiKhoan)
                    ->delete();
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

        return redirect()->route('hoa-don.index')
            ->with('success', 'Hóa đơn đã được cập nhật thành công!');
    }

    /**
     * Xóa hóa đơn
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $hoaDon = HoaDon::findOrFail($id);

            $hoaDon->TrangThaiXacNhanHoaDon = 0; //hòa tiền
            $hoaDon->save();

            
            VeXemPhim::where('ID_HoaDon', $id)->update(['TrangThai' => 2]); // hoàn tiền

            DB::commit();
            return redirect()->route('hoa-don.index')
                ->with('success', 'Hóa đơn này đã được chuyển về trạng thái hòan tiền!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('hoa-don.index')
                ->with('error', 'Không thể cập nhật trạng thái hóa đơn: ' . $e->getMessage());
        }
    }

    /**
     * Xuất báo cáo hóa đơn
     */
    public function exportReport(Request $request)
    {
        $query = HoaDon::query()->with('taiKhoan');

        $hoaDons = $query->orderBy('create_at', 'desc')->get();

        return redirect()->route('hoa-don.index')
            ->with('success', 'Báo cáo đã được xuất thành công!');
    }

    public function filterMovieByDate(Request $request)
    {
        try {
            $taiKhoan = TaiKhoan::join('thong_tin', 'thong_tin.ID_ThongTin', 'tai_khoan.ID_ThongTin')
                ->where('tai_khoan.ID_TaiKhoan', session('user_id'))
                ->select('thong_tin.ID_Rap')
                ->first();
            if (!$taiKhoan->ID_Rap) {
                $date = $request->date;
                $ID_Rap = (int)$request->ID_Rap;
            } else {
                $date = $request->date ?? Carbon::now()->toDateString();
                $ID_Rap = (int)$taiKhoan->ID_Rap;
            }

            $phims = Phim::join('suat_chieu', 'phim.ID_Phim', '=', 'suat_chieu.ID_Phim')
                ->where('ID_Rap', $ID_Rap)
                ->where('suat_chieu.NgayChieu', $date)
                ->select(
                    'phim.*',
                    'suat_chieu.GioChieu',
                    'suat_chieu.ID_PhongChieu',
                    'suat_chieu.ID_SuatChieu',
                    'suat_chieu.GiaVe',
                    'suat_chieu.NgayChieu'
                )
                ->get()
                ->groupBy('ID_Phim')
                ->map(function ($group) {
                    $phim = $group->first();

                    // Lọc các suất chiếu có giờ lớn hơn hiện tại
                    $suatChieus = $group->filter(function ($item) {
                        // Tạo đối tượng datetime từ ngày và giờ chiếu
                        $datetime = Carbon::parse($item->NgayChieu . ' ' . $item->GioChieu);
                        return $datetime->greaterThan(Carbon::now());
                    })->map(function ($item) {
                        return [
                            'gio' => Carbon::parse($item->GioChieu)->format('H:i'),
                            'phong' => $item->ID_PhongChieu,
                            'id' => $item->ID_SuatChieu,
                            'gia_ve' => (int)$item->GiaVe,
                        ];
                    })
                        ->unique(fn($item) => $item['gio'] . '-' . $item['phong'])
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
                ->filter(fn($phim) => $phim['SuatChieu']->isNotEmpty()) // Loại bỏ phim không còn suất nào hợp lệ
                ->values();

            return response()->json($phims);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    public function layTheoId(Request $request)
    {
        try {
            $id = $request->id_phong;
            $idSuatChieu = $request->ID_SuatChieu;

            $gheList = PhongChieu::where('phong_chieu.ID_PhongChieu', $id)
                ->join('ghe_ngoi', 'ghe_ngoi.ID_PhongChieu', '=', 'phong_chieu.ID_PhongChieu')
                ->select('phong_chieu.*', 'ghe_ngoi.*')
                ->get();

            // Lấy ghế đã đặt vé
            $gheDaDat = SuatChieu::join("ve_xem_phim", 've_xem_phim.ID_SuatChieu', '=', 'suat_chieu.ID_SuatChieu')
                ->where('ve_xem_phim.ID_SuatChieu', $idSuatChieu)
                ->where('ve_xem_phim.TrangThai', '!=', 2) // Không tính vé đã hủy
                ->select('ve_xem_phim.ID_Ghe')
                ->get();

            // ✅ THÊM: Lấy ghế đang được giữ bởi người khác
            $ID_TaiKhoan = session('user_id');
            $gheDangGiu = DB::table('ghe_dang_giu')
                ->where('ID_SuatChieu', $idSuatChieu)
                ->where('hold_until', '>', now())
                ->where('ID_TaiKhoan', '!=', $ID_TaiKhoan) // Không tính ghế mình đang giữ
                ->select('ID_Ghe')
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
                        'ID_Ghe'       => $ghe->ID_Ghe ?? null,
                        'TrangThaiGhe' => $ghe->LoaiTrangThaiGhe ?? 0
                    ];
                }
                $seatLayout[] = $row;
            }

            $rowAisles = json_decode($phongInfo->HangLoiDi ?? '[]');
            $colAisles = json_decode($phongInfo->CotLoiDi ?? '[]');

            // ✅ THÊM: Hợp nhất ghế đã đặt và ghế đang giữ
            $allUnavailableSeats = $gheDaDat->pluck('ID_Ghe')
                ->merge($gheDangGiu->pluck('ID_Ghe'))
                ->unique()
                ->values();

            return response()->json([
                'GheDaDat'      => $allUnavailableSeats, // Bao gồm cả ghế đã đặt và đang giữ
                'ID_PhongChieu' => $phongInfo->ID_PhongChieu,
                'TenPhong'      => $phongInfo->TenPhong,
                'SoLuongGhe'    => $gheList->count(),
                'seatLayout'    => $seatLayout,
                'rowAisles'     => $rowAisles,
                'colAisles'     => $colAisles
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy thông tin phòng chiếu: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi lấy thông tin phòng chiếu'], 500);
        }
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

    public function datGheTam(Request $request)
    {
        try {
            $request->validate([
                'ID_Ghe' => 'required|integer',
                'ID_SuatChieu' => 'required|integer',
            ]);

            $ID_TaiKhoan = session('user_id');
            if (!$ID_TaiKhoan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.'
                ], 401);
            }

            $now = now();
            $holdMinutes = 10;
            $ID_Ghe = (int)$request->ID_Ghe;
            $ID_SuatChieu = (int)$request->ID_SuatChieu;

            // Kiểm tra ghế có tồn tại không
            $gheExists = GheNgoi::find($ID_Ghe);
            if (!$gheExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ghế không tồn tại trong hệ thống'
                ], 404);
            }

            // Kiểm tra suất chiếu có tồn tại không
            $suatChieuExists = SuatChieu::find($ID_SuatChieu);
            if (!$suatChieuExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Suất chiếu không tồn tại'
                ], 404);
            }

            // Kiểm tra ghế đã được đặt vé chưa
            $isBooked = VeXemPhim::where('ID_Ghe', $ID_Ghe)
                ->where('ID_SuatChieu', $ID_SuatChieu)
                ->where('TrangThai', '!=', 2) // Không phải vé đã hủy
                ->exists();

            if ($isBooked) {
                return response()->json([
                    'success' => false,
                    'message' => "Ghế {$gheExists->TenGhe} đã được đặt vé"
                ], 409);
            }

            // Kiểm tra ghế có đang được giữ bởi người khác không
            $isHeldByOther = DB::table('ghe_dang_giu')
                ->where('ID_Ghe', $ID_Ghe)
                ->where('ID_SuatChieu', $ID_SuatChieu)
                ->where('ID_TaiKhoan', '!=', $ID_TaiKhoan)
                ->where('hold_until', '>', $now)
                ->exists();

            if ($isHeldByOther) {
                return response()->json([
                    'success' => false,
                    'message' => "Ghế {$gheExists->TenGhe} đang được giữ bởi người khác"
                ], 409);
            }

            // Xóa bản ghi giữ ghế cũ của người dùng hiện tại (nếu có)
            DB::table('ghe_dang_giu')
                ->where('ID_Ghe', $ID_Ghe)
                ->where('ID_SuatChieu', $ID_SuatChieu)
                ->where('ID_TaiKhoan', $ID_TaiKhoan)
                ->delete();

            // Tạo bản ghi giữ ghế mới
            $inserted = DB::table('ghe_dang_giu')->insert([
                'ID_Ghe' => $ID_Ghe,
                'ID_SuatChieu' => $ID_SuatChieu,
                'ID_TaiKhoan' => $ID_TaiKhoan,
                'hold_until' => $now->addMinutes($holdMinutes),
                'created_at' => $now,
                'updated_at' => $now
            ]);

            if (!$inserted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể giữ ghế, vui lòng thử lại'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => "Đã giữ ghế {$gheExists->TenGhe} thành công",
                'data' => [
                    'ID_Ghe' => $ID_Ghe,
                    'TenGhe' => $gheExists->TenGhe,
                    'hold_until' => $now->addMinutes($holdMinutes)->toISOString()
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Lỗi khi đặt ghế tạm thời: ' . $e->getMessage(), [
                'ID_Ghe' => $request->ID_Ghe ?? null,
                'ID_SuatChieu' => $request->ID_SuatChieu ?? null,
                'ID_TaiKhoan' => $ID_TaiKhoan ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi giữ ghế, vui lòng thử lại'
            ], 500);
        }
    }

    public function huyGiuGhe(Request $request)
    {
        try {
            $request->validate([
                'ID_Ghe' => 'required|integer',
                'ID_SuatChieu' => 'required|integer',
            ]);

            $ID_TaiKhoan = session('user_id');
            if (!$ID_TaiKhoan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phiên đăng nhập đã hết hạn'
                ], 401);
            }

            $ID_Ghe = (int)$request->ID_Ghe;
            $ID_SuatChieu = (int)$request->ID_SuatChieu;

            // Kiểm tra ghế có tồn tại không
            $ghe = GheNgoi::find($ID_Ghe);
            if (!$ghe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ghế không tồn tại'
                ], 404);
            }

            // Xóa bản ghi giữ ghế nếu đúng người dùng
            $deleted = DB::table('ghe_dang_giu')
                ->where('ID_Ghe', $ID_Ghe)
                ->where('ID_SuatChieu', $ID_SuatChieu)
                ->where('ID_TaiKhoan', $ID_TaiKhoan)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => "Đã hủy giữ ghế {$ghe->TenGhe}",
                    'data' => [
                        'ID_Ghe' => $ID_Ghe,
                        'TenGhe' => $ghe->TenGhe
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy ghế đang giữ hoặc bạn không có quyền hủy'
                ], 404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Lỗi khi hủy giữ ghế: ' . $e->getMessage(), [
                'ID_Ghe' => $request->ID_Ghe ?? null,
                'ID_SuatChieu' => $request->ID_SuatChieu ?? null,
                'ID_TaiKhoan' => $ID_TaiKhoan ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy giữ ghế'
            ], 500);
        }
    }
}