<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SuatChieu;
use App\Models\VeXemPhim;
use App\Models\GheNgoi;
use Illuminate\Support\Facades\DB;
use App\Models\HoaDon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;
use App\Http\Controllers\Apps\PayOSController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use App\Models\KhuyenMaiDaSuDung;
use Carbon\Carbon;

class ThanhToanController extends Controller
{
    /**
     * Xử lý thanh toán vé xem phim
     */
    public function payment(Request $request)
    {
        try {
            $validated = $request->validate([
                'ten_khach_hang' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'ID_SuatChieu' => 'required|integer|exists:suat_chieu,ID_SuatChieu',
                'selectedSeats' => 'required|string',
                'paymentMethod' => 'required|in:COD,PAYOS'
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
            $calculatedTotal = array_sum(array_column($seatDetails, 'GiaVe'));

            $orderData = [
                'ten_khach_hang' => $validated['ten_khach_hang'],
                'email'          => $validated['email'],
                'ID_SuatChieu'   => $validated['ID_SuatChieu'],
                'selectedSeats'  => $selectedSeats,
                'seatDetails'    => $seatDetails,
                'tong_tien'      => $calculatedTotal,
                'so_tien_giam'   => $request->input('so_tien_giam', 0),
                'tong_tien_sau_giam' => $request->input('tong_tien_sau_giam', $calculatedTotal),
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
                $payosController = app()->make(PayOSController::class);
                $response = $payosController->createPaymentLink($orderData);
                return $response;
            }

            return response()->json(['error' => 'Phương thức thanh toán không hợp lệ.'], 400);
        } catch (\Exception $e) {
            Log::error('Lỗi thanh toán: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra, vui lòng thử lại!'], 500);
        }
    }


    /**
     * Xử lý thanh toán COD cho vé xem phim
     */
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

            return redirect()->route('checkout_status', [
                'status' => 'success',
                'maHoaDon' => $hoaDon->ID_HoaDon
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing COD ticket: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendEmailForOrder($hoaDon, $veXemPhims)
    {
        // Đảm bảo $veXemPhims là collection hoặc mảng
        if (is_string($veXemPhims)) {
            // Nếu $veXemPhims là ID_HoaDon, lấy vé từ DB
            $veXemPhims = VeXemPhim::where('ID_HoaDon', $veXemPhims)->get();
        } elseif (is_array($veXemPhims)) {
            $veXemPhims = collect($veXemPhims);
        }

        $firstVe = $veXemPhims->first();
        if (!$firstVe) {
            Log::warning('Không tìm thấy vé xem phim để gửi email');
            return;
        }

        $suatChieu = SuatChieu::with(['rap', 'phongChieu'])->find($firstVe->ID_SuatChieu);
        $diaChiRap = $suatChieu && $suatChieu->rap ? $suatChieu->rap->DiaChi : '';
        $tenPhim = $firstVe->TenPhim ?? '';
        $ngayXem = $firstVe->NgayXem ?? '';
        $gioChieu = $suatChieu ? $suatChieu->GioChieu : '';
        $phong = $suatChieu && $suatChieu->phongChieu ? $suatChieu->phongChieu->TenPhongChieu : '';
        $email = session('user_email');;
        $tenKhachHang = session('user_fullname');
        Log::info('Email chuẩn bị gửi:', ['email' => $email, 'hoaDonId' => $hoaDon->ID_HoaDon]);

        if (empty($email)) {
            Log::error('Không xác định được email khách hàng để gửi hóa đơn', ['hoaDonId' => $hoaDon->ID_HoaDon]);
            return;
        }

        try {
            $data = [
                'ten_khach_hang' => $tenKhachHang,
                'ma_hoa_don' => $hoaDon->ID_HoaDon,
                'tong_tien'         => $hoaDon->TongTien,
                'hinh_thuc_thanh_toan' => $hoaDon->PTTT,
                'email'             => $email,
                'danh_sach_ghe'     => $veXemPhims->pluck('TenGhe')->toArray(),
                'ten_phim'          => $tenPhim,
                'ngay_chieu'        => $ngayXem,
                'gio_chieu'         => $gioChieu,
                'phong'             => $phong,
                'dia_chi'           => $diaChiRap,
                'thoi_gian_dat'     => $hoaDon->created_at ? $hoaDon->created_at->format('d/m/Y H:i') : '',
                'trang_thai'        => $hoaDon->TrangThaiXacNhanThanhToan == 1 ? 'Đã thanh toán' : 'Chưa thanh toán',
                'ghe'               => implode(',', $veXemPhims->pluck('TenGhe')->toArray()),
                'gia_ve'            => $hoaDon->TongTien,
            ];

            Mail::to($email)->send(new TicketMail((array)$data));
        } catch (\Exception $e) {
            Log::error('Gửi mail vé xem phim thất bại: ' . $e->getMessage());
        }
    }
    public function checkoutStatus(Request $request)
    {
        $status = session('status', $request->input('status', null));
        $maHoaDon = session('maHoaDon', $request->input('maHoaDon', null));
        $hoaDon = null;
        $suatChieu = null;
        $selectedSeats = [];

        if ($maHoaDon) {
            $hoaDon = HoaDon::where('ID_HoaDon', $maHoaDon)->first();
            if ($hoaDon) {
                $veList = VeXemPhim::where('ID_HoaDon', $hoaDon->ID_HoaDon)->get();
                $selectedSeats = $veList->pluck('TenGhe')->toArray();
                $suatChieu = SuatChieu::with(['phim', 'rap', 'phongChieu'])->find($veList->first()->ID_SuatChieu ?? null);
            }
        }

        $viewData = [
            'title' => 'Trạng thái thanh toán',
            'hoaDon' => $hoaDon,
            'suatChieu' => $suatChieu,
            'selectedSeats' => $selectedSeats,
            'error_message' => session('error_message', null),
        ];

        return view('user.pages.kiem-tra-thanh-toan', $viewData)->with('status', $status);
    }

    public function kiemTraKhuyenMai(Request $request)
    {
        $ma = trim($request->input('ma_khuyen_mai'));
        $tongTien = (int) $request->input('tong_tien');
        $now = Carbon::now()->toDateString();

        $km = KhuyenMai::where('MaKhuyenMai', $ma)
            ->where('NgayKetThuc', '>=', $now)
            ->first();

        if (!$km) {
            return response()->json([
                'status' => false,
                'message' => 'Mã khuyến mãi không đúng hoặc đã hết hạn!'
            ]);
        }

        if (KhuyenMaiDaSuDung::where('ID_KhuyenMai', $km->ID_KhuyenMai)
            ->where('ID_TaiKhoan', session('user_id'))
            ->exists()
        ) {
            return response()->json([
                'status' => false,
                'message' => 'Mã khuyến mãi đã được sử dụng trước đó!'
            ]);
        }

        if ($km->SoLuong <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Mã khuyến mãi này đã hết lượt sử dụng!'
            ]);
        }

        if ($km->TrangThai != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Mã khuyến mãi này không còn hoạt động!'
            ]);
        }

        if ($tongTien < $km->DieuKienToiThieu) {
            return response()->json([
                'status' => false,
                'message' => 'Hóa đơn phải có giá trị tối thiểu ' . number_format($km->DieuKienToiThieu, 0, ',', '.') . ' đ để áp dụng mã khuyến mãi!'
            ]);
        }

        // ✅ Tính giảm giá
        $phanTram = $km->PhanTramGiam;
        $soTienGiam = floor($tongTien * $phanTram / 100);

        // Giới hạn giảm tối đa
        if ($km->GiamToiDa > 0 && $soTienGiam > $km->GiamToiDa) {
            $soTienGiam = $km->GiamToiDa;
        }

        $tongTienSauGiam = $tongTien - $soTienGiam;

        // Ghi log
        Log::info('Áp dụng mã khuyến mãi', [
            'tong_tien' => $tongTien,
            'ma_khuyen_mai' => $ma,
            'phan_tram_giam' => $phanTram,
            'so_tien_giam' => $soTienGiam,
            'tong_tien_sau_giam' => $tongTienSauGiam
        ]);

        // Lưu session để dùng sau (ở bước thanh toán)
        session([
            'ma_khuyen_mai' => $km->ID_KhuyenMai,
            'so_tien_giam' => $soTienGiam
        ]);

        return response()->json([
            'status' => true,
            'phan_tram_giam' => $phanTram,
            'so_tien_giam' => $soTienGiam,
            'tong_tien_sau_giam' => $tongTienSauGiam,
            'message' => "Đã áp dụng mã giảm $phanTram% tối đa " . number_format($km->GiamToiDa, 0, ',', '.') . " đ"
        ]);
    }
}
