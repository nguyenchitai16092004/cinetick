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
use App\Events\SeatHeld;

class DatVeController extends Controller
{
    public function showBySlug($phimSlug, $ngay, $gio)
    {
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
            $phim = \App\Models\Phim::where('Slug', $phimSlug)->firstOrFail();

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
                'bookedSeats'
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
            // Validate input
            $request->validate([
                'selectedSeats' => 'required|string',
                'suatChieuId' => 'required|integer|exists:suatchieus,ID_SuatChieu'
            ]);

            // Get selected seats
            $selectedSeats = array_filter(explode(',', $request->input('selectedSeats')));

            if (empty($selectedSeats)) {
                return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một ghế');
            }

            // Get showtime information
            $suatChieu = SuatChieu::with(['phim', 'rap', 'phongChieu'])
                ->findOrFail($request->input('suatChieuId'));

            // Check if seats are still available
            $bookedSeats = VeXemPhim::where('ID_SuatChieu', $suatChieu->ID_SuatChieu)
                ->whereIn('TenGhe', $selectedSeats)
                ->pluck('TenGhe')
                ->toArray();

            if (!empty($bookedSeats)) {
                return redirect()->back()->with('error', 'Một số ghế đã được đặt: ' . implode(', ', $bookedSeats));
            }

            // Get seat information for pricing
            $gheNgoi = GheNgoi::where('ID_PhongChieu', $suatChieu->ID_PhongChieu)
                ->whereIn('TenGhe', $selectedSeats)
                ->get();

            // Calculate total price
            $totalPrice = 0;
            $seatDetails = [];

            foreach ($selectedSeats as $seatName) {
                $seat = $gheNgoi->firstWhere('TenGhe', $seatName);
                if ($seat) {
                    $price = $suatChieu->GiaVe;
                    // Add VIP surcharge if applicable
                    if ($seat->LoaiTrangThaiGhe == 2) {
                        $price += 20000; // VIP surcharge
                    }
                    $totalPrice += $price;
                    $seatDetails[] = [
                        'TenGhe' => $seatName,
                        'LoaiGhe' => $seat->LoaiTrangThaiGhe == 2 ? 'VIP' : 'Thường',
                        'Gia' => $price
                    ];
                }
            }

            return view('frontend.pages.thanh-toan', compact(
                'selectedSeats',
                'suatChieu',
                'seatDetails',
                'totalPrice'
            ));
        } catch (\Exception $e) {
            Log::error('Error in thanhToan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }
    // ...existing code...

    public function showThanhToan(Request $request)
    {
        $suatChieuId = $request->input('ID_SuatChieu');
        $selectedSeats = explode(',', $request->input('selectedSeats', ''));

        $suatChieu = SuatChieu::with(['phim', 'rap', 'phongChieu'])->findOrFail($suatChieuId);

        // Lấy thông tin ghế cho seatDetails
        $gheNgoi = GheNgoi::where('ID_PhongChieu', $suatChieu->ID_PhongChieu)
            ->whereIn('TenGhe', $selectedSeats)
            ->get();

        $seatDetails = [];
        $totalPrice = 0;
        foreach ($selectedSeats as $seatName) {
            $seat = $gheNgoi->firstWhere('TenGhe', $seatName);
            if ($seat) {
                $price = $suatChieu->GiaVe;
                if ($seat->LoaiTrangThaiGhe == 2) {
                    $price = $price * 1.2; 
                }
                $totalPrice += $price;
                $seatDetails[] = [
                    'TenGhe' => $seatName,
                    'LoaiGhe' => $seat->LoaiTrangThaiGhe == 2 ? 'VIP' : 'Thường',
                    'Gia' => $price 
                ];
            }
        }

        return view('frontend.pages.thanh-toan', [
            'suatChieu' => $suatChieu,
            'selectedSeats' => $selectedSeats,
            'seatDetails' => $seatDetails,
            'totalPrice' => $totalPrice,
        ]);
    }
    // ...existing code...
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
    /*
    public function holdSeat(Request $request)
{
    try {
        $request->validate([
            'showtimeId' => 'required|integer',
            'seat' => 'required|string',
        ]);

        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if seat is already booked
        $isBooked = VeXemPhim::where('ID_SuatChieu', $request->showtimeId)
            ->where('TenGhe', $request->seat)
            ->whereHas('hoaDon', function($query) {
                $query->where('TrangThaiXacNhanThanhToan', 1);
            })
            ->exists();

        if ($isBooked) {
            return response()->json(['error' => 'Ghế đã được đặt'], 400);
        }

        // Check if seat is held by someone else
        $heldSeat = HeldSeat::where('ID_SuatChieu', $request->showtimeId)
            ->where('TenGhe', $request->seat)
            ->where('held_until', '>', now())
            ->first();

        if ($heldSeat && $heldSeat->user_id !== $userId) {
            return response()->json(['error' => 'Ghế đang được giữ bởi người khác'], 400);
        }

        // Hold the seat
        $heldUntil = now()->addMinutes(6);
        HeldSeat::updateOrCreate(
            [
                'ID_SuatChieu' => $request->showtimeId,
                'TenGhe' => $request->seat,
            ],
            [
                'user_id' => $userId,
                'held_until' => $heldUntil,
            ]
        );

        // Broadcast the event
        event(new SeatHeld($request->showtimeId, $request->seat, $userId, $heldUntil));

        return response()->json(['success' => true, 'held_until' => $heldUntil]);
    } catch (\Exception $e) {
        Log::error('Error holding seat: ' . $e->getMessage());
        return response()->json(['error' => 'Có lỗi xảy ra'], 500);
    }
}

public function releaseSeat(Request $request)
{
    try {
        $request->validate([
            'showtimeId' => 'required|integer',
            'seat' => 'required|string',
        ]);

        $userId = session('user_id');
        
        HeldSeat::where('ID_SuatChieu', $request->showtimeId)
            ->where('TenGhe', $request->seat)
            ->where('user_id', $userId)
            ->delete();

        event(new SeatReleased($request->showtimeId, $request->seat));

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Error releasing seat: ' . $e->getMessage());
        return response()->json(['error' => 'Có lỗi xảy ra'], 500);
    }
}
    */
}