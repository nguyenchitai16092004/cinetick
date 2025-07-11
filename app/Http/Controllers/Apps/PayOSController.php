<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KhuyenMaiDaSuDung;
use PayOS\PayOS;
use App\Models\HoaDon;
use App\Models\VeXemPhim;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Apps\ThanhToanController;
use App\Models\SuatChieu;
use Illuminate\Support\Facades\DB;


class PayOSController extends Controller
{
    protected $payOS;
    protected $webhookUrl;

    public function __construct()
    {
        $this->payOS = new PayOS(
            env('PAYOS_CLIENT_ID'),
            env('PAYOS_API_KEY'),
            env('PAYOS_CHECKSUM_KEY')
        );

        $this->webhookUrl = env('WEBHOOK_URL');
    }
    /**
     * Tạo link thanh toán cho đơn hàng
     * @param array $orderData
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPaymentLink(array $orderData)
    {
        // Validate đầu vào
        if (
            empty($orderData['tong_tien']) ||
            empty($orderData['ten_khach_hang']) ||
            empty($orderData['email']) ||
            empty($orderData['ID_SuatChieu']) ||
            empty($orderData['selectedSeats']) ||
            !is_array($orderData['selectedSeats'])
        ) {
            return response()->json(['error' => 'Thiếu thông tin đơn hàng!'], 400);
        }
        $total = isset($orderData['tong_tien_sau_giam'])
            ? (int)$orderData['tong_tien_sau_giam']
            : ((isset($orderData['tong_tien']) ? (int)$orderData['tong_tien'] : 0));
        // chỉ tạo orderCode tạm thời (random hoặc time-based) để gửi cho PayOS
        $orderCodeNum = rand(100000000, 999999999);

        $items = array_map(function ($tenGhe) use ($orderData) {
            $seat = collect($orderData['seatDetails'])->firstWhere('TenGhe', $tenGhe);
            $price = isset($seat['GiaVe']) ? (int)$seat['GiaVe'] : 0;
            $formattedPrice = number_format($price, 0, ',', '.') . ' đ';
            return [
                'name'     => 'Vé ' . ($orderData['ten_phim'] ?? '') . ' - Ghế ' . $tenGhe . ' (' . $formattedPrice . ')',
                'quantity' => 1,
                'price'    => $price,
            ];
        }, $orderData['selectedSeats']);

        try {
            $response = $this->payOS->createPaymentLink([
                'orderCode'   => $orderCodeNum,
                'amount'      => $total,
                'description' => substr("Thanh toán vé xem phim", 0, 255),
                'returnUrl'   => route('payos.return') . '?orderCode=' . $orderCodeNum,
                'cancelUrl'   => route('payos.cancel') . '?orderCode=' . $orderCodeNum,
                'buyerName'   => session('user_fullname') ?? '',
                'buyerEmail'  => session('user_email') ?? '',
                'items'       => $items,
                'expiredAt'   => now()->addMinutes(6)->timestamp,
            ]);
            // Lưu orderData vào session theo orderCodeNum để lấy lại khi callback/return
            session(['payos_order_' . $orderCodeNum => $orderData]);

            return response()->json([
                'checkoutUrl' => $response['checkoutUrl'],
                'orderCode' => $orderCodeNum,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Tạo yêu cầu thanh toán thất bại: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Lấy thông tin link thanh toán
     * @param int|string $orderCode
     * @return array|null
     */
    public function getPaymentLinkInformation($orderCode)
    {
        try {
            $info = $this->payOS->getPaymentLinkInformation($orderCode);

            if (!isset($info['status']) || !isset($info['checkoutUrl'])) {
                Log::warning("Thiếu dữ liệu trong getPaymentLinkInformation cho orderCode: $orderCode", $info);
            }

            return $info;
        } catch (\Exception $e) {
            Log::error('PayOS getPaymentLinkInformation Error (orderCode: ' . $orderCode . '): ' . $e->getMessage());
            return null;
        }
    }
    public function checkPaymentStatus($orderCode)
    {
        $paymentInfo = $this->getPaymentLinkInformation($orderCode);

        if (!$paymentInfo) {
            return response()->json([
                'error' => 'Không lấy được thông tin thanh toán'
            ], 404);
        }

        switch ($paymentInfo['status']) {
            case 'PAID':
                $this->updatePaymentSuccess($orderCode);
                // Lưu mã hóa đơn vào session để sử dụng sau này
                return response()->json([
                    'message' => 'Thanh toán thành công',
                    'data' => $paymentInfo
                ]);

            case 'CANCELLED':
                $this->updatePaymentCancel($orderCode);
                return response()->json([
                    'message' => 'Thanh toán đã bị hủy',
                    'data' => $paymentInfo
                ]);

            default:
                return response()->json([
                    'message' => 'Thanh toán chưa hoàn thành',
                    'data' => $paymentInfo
                ]);
        }
    }
    // Các method xử lý callback, trả về từ PayOS
    public function handleReturn(Request $request)
    {
        $orderCode = $request->input('orderCode');

        if ($orderCode) {
            $paymentInfo = $this->getPaymentLinkInformation($orderCode);
            if ($paymentInfo && $paymentInfo['status'] === 'PAID') {
                $this->addMaKhuyenMai();
                $this->updatePaymentSuccess($orderCode);
            }
            return redirect()->route('checkout_status')
                ->with('status', $paymentInfo && $paymentInfo['status'] === 'PAID' ? 'success' : 'fail')
                ->with('maHoaDon', session('maHoaDon', null));
        }

        return redirect()->route('checkout_status')
            ->with('status', 'fail')
            ->with('error_message', 'Không tìm thấy mã hóa đơn thanh toán!');
    }

    protected function updatePaymentSuccess($orderCode)
    {
        // kiểm tra nếu đã tạo hóa đơn rồi thì thôi
        $hoaDon = HoaDon::where('order_code', $orderCode)->first();
        if ($hoaDon) {
            session(['maHoaDon' => $hoaDon->ID_HoaDon]);
            return;
        }

        // Lấy lại orderData từ session
        $orderData = session('payos_order_' . $orderCode, null);
        if (!$orderData) {
            // fallback: có thể lấy từ cache/database nếu cần
            return;
        }

        // Lấy thông tin giao dịch từ PayOS
        $paymentInfo = $this->getPaymentLinkInformation($orderCode);

        // Mặc định null
        $soTaiKhoan = null;
        $tenTaiKhoan = null;
        $tenNganHang = null;
        if (!empty($paymentInfo['transactions'][0])) {
            $tran = $paymentInfo['transactions'][0];
            $soTaiKhoan    = $tran['counterAccountNumber'] ?? null;
            $tenTaiKhoan   = $tran['counterAccountName'] ?? null;
            $tenNganHang   = $tran['counterAccountBankName'] ?? null;
            if (!$tenNganHang && !empty($tran['counterAccountBankId'])) {
                $bankNames = [
                    '970407' => 'Techcombank',
                    '970405' => 'Agribank',
                    '970418' => 'BIDV',
                    '970422' => 'MB Bank (Ngân hàng TMCP Quân đội)',
                    '970416' => 'ACB',
                    '970441' => 'VIB',
                    '970403' => 'Sacombank (STB)',
                    '970432' => 'VPBank',
                    '970423' => 'TPBank',
                    '970436' => 'Vietcombank (VCB)',
                ];
                $tenNganHang = $bankNames[$tran['counterAccountBankId']] ?? null;
            }
        }

        DB::beginTransaction();
        try {
            $maHoaDon = HoaDon::generateMaHoaDon();

            $hoaDon = HoaDon::create([
                'ID_HoaDon'   => $maHoaDon,
                'TongTien'    => isset($orderData['tong_tien']) ? $orderData['tong_tien'] : 0,
                'PTTT'        => 'Chuyển khoản',
                'ID_TaiKhoan' => session('user_id'),
                'order_code'  => $orderCode,
                'SoTaiKhoan'  => $soTaiKhoan,
                'TenTaiKhoan' => $tenTaiKhoan,
                'TenNganHang' => $tenNganHang,
                'TrangThaiXacNhanHoaDon'     => 1,
                'TrangThaiXacNhanThanhToan'  => 1,
                'SoLuongVe'   => count($orderData['selectedSeats']),
                'created_at'  => now(),
                'updated_at'  => now(),
                'SoTienGiam'  => $orderData['so_tien_giam'] ?? 0,
            ]);

            $suatChieu = SuatChieu::with(['rap'])->find($orderData['ID_SuatChieu']);
            $diaChiRap = $suatChieu->rap->DiaChi ?? '';

            foreach ($orderData['seatDetails'] as $seat) {
                VeXemPhim::create([
                    'TenGhe'       => $seat['TenGhe'],
                    'TenPhim'      => $orderData['ten_phim'] ?? '',
                    'NgayXem'      => $orderData['ngay_xem'] ?? '',
                    'DiaChi'       => $diaChiRap,
                    'GiaVe'        => $seat['GiaVe'],
                    'TrangThai'    => 0, // Chưa nhận vé
                    'ID_SuatChieu' => $orderData['ID_SuatChieu'],
                    'ID_HoaDon'    => $maHoaDon,
                    'ID_Ghe'       => $seat['ID_Ghe'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            DB::commit();

            // Xóa orderData khỏi session
            session()->forget('payos_order_' . $orderCode);
            session(['maHoaDon' => $maHoaDon]);

            // Gửi email
            $sendEmail = new ThanhToanController();
            $sendEmail->sendEmailForOrder($hoaDon, VeXemPhim::where('ID_HoaDon', $maHoaDon)->get());
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
    public function handleCancel(Request $request)
    {
        $orderCode = $request->input('orderCode');

        if ($orderCode) {
            $paymentInfo = $this->getPaymentLinkInformation($orderCode);
            if ($paymentInfo && $paymentInfo['status'] === 'CANCELLED') {
                $this->updatePaymentCancel($orderCode);
            }
            // Lấy lại hóa đơn từ DB theo order_code
            $hoaDon = HoaDon::where('order_code', $orderCode)->first();
            $maHoaDon = $hoaDon ? $hoaDon->ID_HoaDon : null;

            return redirect()->route('checkout_status')
                ->with('status', 'cancel')
                ->with('maHoaDon', $maHoaDon)
                ->with('error_message', 'Giao dịch đã bị hủy.');
        }

        return redirect()->route('checkout_status')
            ->with('status', 'cancel')
            ->with('error_message', 'Không tìm thấy mã hóa đơn thanh toán!');
    }
    protected function updatePaymentCancel($orderCode)
    {
        $hoaDon = HoaDon::where('order_code', $orderCode)->first();
        if (!$hoaDon) return;

        // Update trạng thái hóa đơn
        $hoaDon->TrangThaiXacNhanThanhToan = 0; // Chưa thanh toán
        $hoaDon->TrangThaiXacNhanHoaDon = 2;    // Đã hủy 
        $hoaDon->save();

        // Update trạng thái vé (nếu muốn)
        VeXemPhim::where('ID_HoaDon', $hoaDon->ID_HoaDon)->update([
            'TrangThai' => 2 // Đã hủy
        ]);
    }
    public function addMaKhuyenMai()
    {
        $ma = session('ma_khuyen_mai');
        $id = session('user_id');
        if (!$ma || !$id) {
            return;
        }
        KhuyenMaiDaSuDung::create([
            'ID_TaiKhoan' => $id,
            'ID_KhuyenMai' => $ma
        ]);
        // Xóa mã khuyến mãi khỏi session
        session()->forget('ma_khuyen_mai');
        // Xóa mã khuyến mãi khỏi cache nếu có
        if (cache()->has('ma_khuyen_mai_' . $id)) {
            cache()->forget('ma_khuyen_mai_' . $id);
        }
        return;
    }
}