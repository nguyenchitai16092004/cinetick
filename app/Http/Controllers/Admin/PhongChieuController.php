<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\PhongChieu;
use App\Models\GheNgoi;
use App\Models\Rap;

class PhongChieuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phongChieus = PhongChieu::all();
        return view('backend.pages.phong_chieu.phong-chieu', compact('phongChieus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $raps = Rap::all(); // Lấy danh sách rạp
        return view('backend.pages.phong_chieu.create_phong_chieu', compact('raps'));
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'roomName' => 'required|string|max:100',
            'ID_Rap' => 'required|exists:rap,ID_Rap',
            'LoaiPhong' => 'required|integer|in:0,1', // 0: phòng thường, 1: phòng VIP
            'rowCount' => 'required|integer|min:5|max:10',
            'colCount' => 'required|integer|min:6|max:12',
            'seatLayout' => 'required|json',
            'rowAisles' => 'nullable|array',
            'rowAisles.*' => 'integer|min:1',
            'colAisles' => 'nullable|array',
            'colAisles.*' => 'integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $soPhongChieu = PhongChieu::where('ID_Rap', $request->ID_Rap)->count();
            if ($soPhongChieu >= 5) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Rạp này đã đạt tối đa 5 phòng chiếu.')->withInput();
            }

            // Chuyển đổi seatLayout từ JSON sang mảng
            $seatLayout = json_decode($request->seatLayout, true);
            $soLuongGhe = 0;

            // Đếm số ghế hoạt động (TrangThaiGhe = 1 hoặc 2)
            foreach ($seatLayout as $row) {
                foreach ($row as $seat) {
                    if (isset($seat['TrangThaiGhe']) && $seat['TrangThaiGhe'] > 0) {
                        $soLuongGhe++;
                    }
                }
            }

            // Tạo phòng chiếu
            $phongChieu = PhongChieu::create([
                'TenPhongChieu' => $request->roomName,
                'LoaiPhong' => $request->LoaiPhong,
                'TrangThai' => true,
                'SoLuongGhe' => $soLuongGhe,
                'ID_Rap' => $request->ID_Rap,
                'HangLoiDi' => json_encode($request->rowAisles ?? []),
                'CotLoiDi' => json_encode($request->colAisles ?? []),
            ]);

            // Tạo ghế ngồi
            foreach ($seatLayout as $rowIndex => $row) {
                foreach ($row as $colIndex => $seat) {
                    $trangThaiGhe = 0; // Mặc định không hoạt động

                    if (isset($seat['TrangThaiGhe'])) {
                        $trangThaiGhe = $seat['TrangThaiGhe'];
                    } else if (isset($seat['TrangThai']) && $seat['TrangThai'] == 1) {
                        // Hỗ trợ định dạng cũ
                        $trangThaiGhe = isset($seat['LoaiGhe']) && $seat['LoaiGhe'] == 1 ? 2 : 1;
                    }

                    GheNgoi::create([
                        'TenGhe' => chr(65 + $rowIndex) . ($colIndex + 1),
                        'ID_PhongChieu' => $phongChieu->ID_PhongChieu,
                        'LoaiTrangThaiGhe' => $trangThaiGhe,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('phong-chieu.index')->with('success', 'Thêm phòng chiếu thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi thêm phòng chiếu: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        // Lấy thông tin phòng chiếu và rạp
        $phongChieu = PhongChieu::with('rap')->findOrFail($id);
        $raps = Rap::all();

        // Lấy thông tin ghế
        $ghengoi = GheNgoi::where('ID_PhongChieu', $id)->get();

        // Tính số hàng và cột từ tên ghế
        $rowCount = 0;
        $colCount = 0;

        foreach ($ghengoi as $ghe) {
            if (preg_match('/([A-Z])(\d+)/', $ghe->TenGhe, $matches)) {
                $row = ord($matches[1]) - 64; // Chuyển A->1, B->2, ...
                $col = (int)$matches[2];
                $rowCount = max($rowCount, $row);
                $colCount = max($colCount, $col);
            }
        }

        // Tạo mảng seatLayout với cấu trúc chuẩn cho frontend
        $seatLayout = [];
        for ($i = 0; $i < $rowCount; $i++) {
            $row = [];
            for ($j = 0; $j < $colCount; $j++) {
                $tenGhe = chr(65 + $i) . ($j + 1);
                $ghe = $ghengoi->firstWhere('TenGhe', $tenGhe);

                if ($ghe) {
                    // Tạo đối tượng ghế với trạng thái từ database
                    $row[] = [
                        'TrangThaiGhe' => $ghe->LoaiTrangThaiGhe ?? 0
                    ];
                } else {
                    // Ghế không tồn tại
                    $row[] = [
                        'TrangThaiGhe' => 0
                    ];
                }
            }
            $seatLayout[] = $row;
        }

        // Định dạng lại dữ liệu lối đi
        $rowAisles = json_decode($phongChieu->HangLoiDi ?: '[]');
        $colAisles = json_decode($phongChieu->CotLoiDi ?: '[]');

        return view('backend.pages.phong_chieu.detail_phong_chieu', compact(
            'phongChieu',
            'raps',
            'ghengoi',
            'rowCount',
            'colCount',
            'seatLayout'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'roomName' => 'required|string|max:100',
            'ID_Rap' => 'required|exists:rap,ID_Rap',
            'LoaiPhong' => 'required|integer|in:0,1', // 0: phòng thường, 1: phòng VIP
            'rowCount' => 'required|integer|min:5|max:10',
            'colCount' => 'required|integer|in:6,7,8,9,10,12',
            'rowAisles' => 'array|nullable',
            'colAisles' => 'array|nullable',
            'TrangThai' => 'required|boolean',
            'seatLayout' => 'required|json'
        ]);

        try {
            DB::beginTransaction();

            $phongChieu = PhongChieu::findOrFail($id);
            $seatLayout = json_decode($validated['seatLayout'], true);

            // Đếm số ghế hoạt động
            $soLuongGhe = 0;
            foreach ($seatLayout as $row) {
                foreach ($row as $seat) {
                    // Đếm ghế đang hoạt động (TrangThaiGhe > 0)
                    if (isset($seat['TrangThaiGhe']) && $seat['TrangThaiGhe'] > 0) {
                        $soLuongGhe++;
                    } else if (isset($seat['TrangThai']) && $seat['TrangThai'] == 1) {
                        // Hỗ trợ định dạng cũ
                        $soLuongGhe++;
                    }
                }
            }

            $phongChieu->update([
                'TenPhongChieu' => $validated['roomName'],
                'ID_Rap' => $validated['ID_Rap'],
                'LoaiPhong' => $validated['LoaiPhong'],
                'TrangThai' => $validated['TrangThai'],
                'SoLuongGhe' => $soLuongGhe,
                'HangLoiDi' => json_encode($validated['rowAisles'] ?? []),
                'CotLoiDi' => json_encode($validated['colAisles'] ?? [])
            ]);

            // Lấy tất cả các ghế hiện có của phòng chiếu
            $existingSeats = GheNgoi::where('ID_PhongChieu', $id)
                ->pluck('TenGhe')
                ->toArray();

            // Danh sách ghế đã xử lý
            $processedSeats = [];

            // Cập nhật hoặc tạo mới ghế ngồi
            foreach ($seatLayout as $i => $row) {
                foreach ($row as $j => $seat) {
                    $tenGhe = chr(65 + $i) . ($j + 1);
                    $processedSeats[] = $tenGhe;

                    // Xác định trạng thái ghế từ dữ liệu gửi lên
                    $trangThaiGhe = 0; // Mặc định không hoạt động

                    if (isset($seat['TrangThaiGhe'])) {
                        // Dùng trực tiếp giá trị TrangThaiGhe nếu có
                        $trangThaiGhe = $seat['TrangThaiGhe'];
                    } else if (isset($seat['TrangThai'])) {
                        // Hỗ trợ định dạng cũ
                        if ($seat['TrangThai'] == 1) {
                            // Ghế hoạt động
                            $trangThaiGhe = isset($seat['LoaiGhe']) && $seat['LoaiGhe'] == 1 ? 2 : 1; // 2 = VIP, 1 = thường
                        } else {
                            $trangThaiGhe = 0; // Không hoạt động
                        }
                    }

                    // Tìm ghế hiện có
                    $gheNgoi = GheNgoi::where('ID_PhongChieu', $id)
                        ->where('TenGhe', $tenGhe)
                        ->first();

                    if ($gheNgoi) {
                        // Cập nhật ghế hiện có
                        $gheNgoi->LoaiTrangThaiGhe = $trangThaiGhe;
                        $gheNgoi->save();
                    } else {
                        // Tạo ghế mới nếu chưa tồn tại
                        GheNgoi::create([
                            'TenGhe' => $tenGhe,
                            'ID_PhongChieu' => $id,
                            'LoaiTrangThaiGhe' => $trangThaiGhe
                        ]);
                    }
                }
            }

            // Xóa các ghế không còn trong sơ đồ mới
            $obsoleteSeats = array_diff($existingSeats, $processedSeats);
            if (!empty($obsoleteSeats)) {
                GheNgoi::where('ID_PhongChieu', $id)
                    ->whereIn('TenGhe', $obsoleteSeats)
                    ->delete();
            }

            DB::commit();
            return redirect()->route('phong-chieu.index')->with('success', 'Cập nhật phòng chiếu thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi cập nhật phòng chiếu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $phongChieu = PhongChieu::findOrFail($id);
            GheNgoi::where('ID_PhongChieu', $id)->delete();
            $phongChieu->delete();
            return redirect()->route('phong-chieu.index')->with('success', 'Xóa phòng chiếu thành công!');
        } catch (\Exception $e) {
            return redirect()->route('phong-chieu.index')->with('error', 'Xóa phòng chiếu thất bại: ' . $e->getMessage());
        }
    }

    public function saveGhe(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $phong = PhongChieu::findOrFail($id);
            $data = $request->input('ghe', []);
            $soGhe = 0;

            foreach ($data as $ghe) {
                $tenGhe = $ghe['TenGhe'];
                $trangThaiGhe = (int)($ghe['TrangThaiGhe'] ?? 0);

                // Tìm và cập nhật ghế hiện có
                $gheNgoi = GheNgoi::where('ID_PhongChieu', $id)
                    ->where('TenGhe', $tenGhe)
                    ->first();

                if ($gheNgoi) {
                    $gheNgoi->TrangThaiGhe = $trangThaiGhe;
                    $gheNgoi->save();

                    // Đếm ghế hoạt động
                    if ($trangThaiGhe > 0) {
                        $soGhe++;
                    }
                }
            }

            $phong->SoLuongGhe = $soGhe;
            $phong->save();

            DB::commit();
            return redirect()->route('phong-chieu.index')->with('success', 'Đã lưu sơ đồ ghế thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi lưu sơ đồ ghế: ' . $e->getMessage())->withInput();
        }
    }
}