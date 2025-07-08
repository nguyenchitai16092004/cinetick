<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\PhongChieu;
use App\Models\GheNgoi;
use App\Models\Rap;
use App\Models\SuatChieu;
use Carbon\Carbon;

class PhongChieuController extends Controller
{
    public function index()
    {
        $phongChieus = PhongChieu::orderBy('TrangThai', 'desc')->paginate(5);
        return view('admin.pages.phong_chieu.phong-chieu', compact('phongChieus'));
    }


    public function create()
    {
        $raps = Rap::all();
        return view('admin.pages.phong_chieu.create_phong_chieu', compact('raps'));
    }

    public function store(Request $request)
    {
        $soPhongChieu = PhongChieu::where('ID_Rap', $request->ID_Rap)->count();
        if ($soPhongChieu >= 5) {
            return redirect()->back()->with('error', 'Rạp này đã đạt tối đa 5 phòng chiếu.')->withInput();
        }
        $request->validate([
            'roomName' => [
                'required',
                'string',
                'max:100',
                Rule::unique('phong_chieu', 'TenPhongChieu')->where(function ($query) use ($request) {
                    return $query->where('ID_Rap', $request->ID_Rap);
                }),
            ],
            'ID_Rap' => 'required|exists:rap,ID_Rap',
            'LoaiPhong' => 'required|integer|in:0,1',
            'rowCount' => 'required|integer|min:5|max:10',
            'colCount' => 'required|integer|min:6|max:10',
            'seatLayout' => 'required|json',
            'rowAisles' => 'nullable|array',
            'rowAisles.*' => 'integer|min:1',
            'colAisles' => 'nullable|array',
            'colAisles.*' => 'integer|min:1',
        ], [
            'roomName.required' => 'Vui lòng nhập tên phòng chiếu.',
            'roomName.string' => 'Tên phòng chiếu không hợp lệ.',
            'roomName.max' => 'Tên phòng chiếu không được vượt quá 100 ký tự.',
            'roomName.unique' => 'Tên phòng chiếu đã tồn tại trong rạp này.',

            'ID_Rap.required' => 'Vui lòng chọn rạp.',
            'ID_Rap.exists' => 'Rạp được chọn không tồn tại.',

            'LoaiPhong.required' => 'Vui lòng chọn loại phòng.',
            'LoaiPhong.integer' => 'Loại phòng không hợp lệ.',
            'LoaiPhong.in' => 'Loại phòng không hợp lệ.',

            'rowCount.required' => 'Vui lòng nhập số hàng ghế.',
            'rowCount.integer' => 'Số hàng ghế phải là số nguyên.',
            'rowCount.min' => 'Số hàng ghế tối thiểu là 5.',
            'rowCount.max' => 'Số hàng ghế tối đa là 10.',

            'colCount.required' => 'Vui lòng nhập số cột ghế.',
            'colCount.integer' => 'Số cột ghế phải là số nguyên.',
            'colCount.min' => 'Số cột ghế tối thiểu là 6.',
            'colCount.max' => 'Số cột ghế tối đa là 10.',

            'seatLayout.required' => 'Vui lòng nhập sơ đồ ghế.',
            'seatLayout.json' => 'Sơ đồ ghế phải là chuỗi JSON hợp lệ.',

            'rowAisles.array' => 'Dữ liệu khoảng cách lối đi giữa hàng không hợp lệ.',
            'rowAisles.*.integer' => 'Các vị trí lối đi giữa hàng phải là số nguyên.',
            'rowAisles.*.min' => 'Vị trí lối đi giữa hàng phải từ 1 trở lên.',

            'colAisles.array' => 'Dữ liệu khoảng cách lối đi giữa cột không hợp lệ.',
            'colAisles.*.integer' => 'Các vị trí lối đi giữa cột phải là số nguyên.',
            'colAisles.*.min' => 'Vị trí lối đi giữa cột phải từ 1 trở lên.',
        ]);


        try {
            DB::beginTransaction();
            $seatLayout = json_decode($request->seatLayout, true);
            $soLuongGhe = 0;

            foreach ($seatLayout as $row) {
                foreach ($row as $seat) {
                    if (isset($seat['TrangThaiGhe']) && $seat['TrangThaiGhe'] > 0) {
                        $soLuongGhe++;
                    }
                }
            }

            $phongChieu = PhongChieu::create([
                'TenPhongChieu' => $request->roomName,
                'LoaiPhong' => $request->LoaiPhong,
                'TrangThai' => true,
                'SoLuongGhe' => $soLuongGhe,
                'ID_Rap' => $request->ID_Rap,
                'HangLoiDi' => json_encode($request->rowAisles ?? []),
                'CotLoiDi' => json_encode($request->colAisles ?? []),
            ]);

            foreach ($seatLayout as $rowIndex => $row) {
                foreach ($row as $colIndex => $seat) {
                    $trangThaiGhe = 0;

                    if (isset($seat['TrangThaiGhe'])) {
                        $trangThaiGhe = $seat['TrangThaiGhe'];
                    } else if (isset($seat['TrangThai']) && $seat['TrangThai'] == 1) {
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
        $phongChieu = PhongChieu::with('rap')->findOrFail($id);
        $raps = Rap::all();

        $ghengoi = GheNgoi::where('ID_PhongChieu', $id)->get();

        $rowCount = 0;
        $colCount = 0;

        foreach ($ghengoi as $ghe) {
            if (preg_match('/([A-Z])(\d+)/', $ghe->TenGhe, $matches)) {
                $row = ord($matches[1]) - 64;
                $col = (int)$matches[2];
                $rowCount = max($rowCount, $row);
                $colCount = max($colCount, $col);
            }
        }

        $seatLayout = [];
        for ($i = 0; $i < $rowCount; $i++) {
            $row = [];
            for ($j = 0; $j < $colCount; $j++) {
                $tenGhe = chr(65 + $i) . ($j + 1);
                $ghe = $ghengoi->firstWhere('TenGhe', $tenGhe);

                if ($ghe) {
                    $row[] = [
                        'TrangThaiGhe' => $ghe->LoaiTrangThaiGhe ?? 0
                    ];
                } else {
                    $row[] = [
                        'TrangThaiGhe' => 0
                    ];
                }
            }
            $seatLayout[] = $row;
        }

        $rowAisles = json_decode($phongChieu->HangLoiDi ?: '[]');
        $colAisles = json_decode($phongChieu->CotLoiDi ?: '[]');

        return view('admin.pages.phong_chieu.detail_phong_chieu', compact(
            'phongChieu',
            'raps',
            'ghengoi',
            'rowCount',
            'colCount',
            'seatLayout'
        ));
    }

    public function update(Request $request, $id)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'roomName' => [
                'required',
                'string',
                'max:100',
                Rule::unique('phong_chieu', 'TenPhongChieu')
                    ->where(function ($query) use ($request) {
                        return $query->where('ID_Rap', $request->ID_Rap);
                    })
                    ->ignore($id, 'ID_PhongChieu'),
            ],
            'ID_Rap' => 'required|exists:rap,ID_Rap',
            'LoaiPhong' => 'required|integer|in:0,1',
            'rowCount' => 'required|integer|min:5|max:10',
            'colCount' => 'required|integer|min:6|max:10',
            'seatLayout' => 'required|json',
            'rowAisles' => 'nullable|array',
            'rowAisles.*' => 'integer|min:1',
            'colAisles' => 'nullable|array',
            'colAisles.*' => 'integer|min:1',
            'TrangThai' => 'required|int',
        ], [
            'roomName.required' => 'Vui lòng nhập tên phòng chiếu.',
            'roomName.string' => 'Tên phòng chiếu không hợp lệ.',
            'roomName.max' => 'Tên phòng chiếu không được vượt quá 100 ký tự.',
            'roomName.unique' => 'Tên phòng chiếu đã tồn tại trong rạp này.',

            'ID_Rap.required' => 'Vui lòng chọn rạp.',
            'ID_Rap.exists' => 'Rạp được chọn không tồn tại.',

            'LoaiPhong.required' => 'Vui lòng chọn loại phòng.',
            'LoaiPhong.integer' => 'Loại phòng không hợp lệ.',
            'LoaiPhong.in' => 'Loại phòng chỉ được chọn 0 (thường) hoặc 1 (VIP).',

            'rowCount.required' => 'Vui lòng nhập số hàng ghế.',
            'rowCount.integer' => 'Số hàng ghế phải là số nguyên.',
            'rowCount.min' => 'Số hàng ghế tối thiểu là 5.',
            'rowCount.max' => 'Số hàng ghế tối đa là 10.',

            'colCount.required' => 'Vui lòng nhập số cột ghế.',
            'colCount.integer' => 'Số cột ghế phải là số nguyên.',
            'colCount.min' => 'Số cột ghế tối thiểu là 6.',
            'colCount.max' => 'Số cột ghế tối đa là 10.',

            'seatLayout.required' => 'Vui lòng nhập sơ đồ ghế.',
            'seatLayout.json' => 'Sơ đồ ghế phải là chuỗi JSON hợp lệ.',

            'rowAisles.array' => 'Dữ liệu lối đi giữa các hàng phải là một mảng.',
            'rowAisles.*.integer' => 'Các vị trí lối đi giữa hàng phải là số nguyên.',
            'rowAisles.*.min' => 'Vị trí lối đi giữa hàng phải lớn hơn hoặc bằng 1.',

            'colAisles.array' => 'Dữ liệu lối đi giữa các cột phải là một mảng.',
            'colAisles.*.integer' => 'Các vị trí lối đi giữa cột phải là số nguyên.',
            'colAisles.*.min' => 'Vị trí lối đi giữa cột phải lớn hơn hoặc bằng 1.',

            'TrangThai.required' => 'Vui lòng chọn trạng thái phòng.',
            'TrangThai.int' => 'Trạng thái phòng không hợp lệ.',
        ]);

        try {
            DB::beginTransaction();

            $phongChieu = PhongChieu::findOrFail($id);
            $seatLayout = json_decode($validated['seatLayout'], true);

            $soLuongGhe = 0;
            foreach ($seatLayout as $row) {
                foreach ($row as $seat) {
                    if (isset($seat['TrangThaiGhe']) && $seat['TrangThaiGhe'] > 0) {
                        $soLuongGhe++;
                    } elseif (isset($seat['TrangThai']) && $seat['TrangThai'] == 1) {
                        $soLuongGhe++;
                    }
                }
            }

            // Cập nhật thông tin phòng chiếu
            $phongChieu->update([
                'TenPhongChieu' => $validated['roomName'],
                'ID_Rap' => $validated['ID_Rap'],
                'LoaiPhong' => $validated['LoaiPhong'],
                'TrangThai' => $request->TrangThai,
                'SoLuongGhe' => $soLuongGhe,
                'HangLoiDi' => json_encode($validated['rowAisles'] ?? []),
                'CotLoiDi' => json_encode($validated['colAisles'] ?? []),

            ]);

            // Lấy danh sách ghế cũ
            $existingSeats = GheNgoi::where('ID_PhongChieu', $id)
                ->pluck('TenGhe')
                ->toArray();

            $processedSeats = [];

            foreach ($seatLayout as $i => $row) {
                foreach ($row as $j => $seat) {
                    $tenGhe = chr(65 + $i) . ($j + 1);
                    $processedSeats[] = $tenGhe;

                    $trangThaiGhe = 0;

                    if (isset($seat['TrangThaiGhe'])) {
                        $trangThaiGhe = $seat['TrangThaiGhe'];
                    } elseif (isset($seat['TrangThai']) && $seat['TrangThai'] == 1) {
                        $trangThaiGhe = isset($seat['LoaiGhe']) && $seat['LoaiGhe'] == 1 ? 2 : 1;
                    }

                    $gheNgoi = GheNgoi::where('ID_PhongChieu', $id)
                        ->where('TenGhe', $tenGhe)
                        ->first();

                    if ($gheNgoi) {
                        $gheNgoi->LoaiTrangThaiGhe = $trangThaiGhe;
                        $gheNgoi->save();
                    } else {
                        GheNgoi::create([
                            'TenGhe' => $tenGhe,
                            'ID_PhongChieu' => $id,
                            'LoaiTrangThaiGhe' => $trangThaiGhe,
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

    public function destroy($id)
    {
        try {
            $id = (int)$id;
            $phongChieu = PhongChieu::findOrFail($id);

            $suatChieuChuaChieu = SuatChieu::where('ID_PhongChieu', $id)
                ->whereDate('NgayChieu', '>', Carbon::now()->toDateString())
                ->exists();

            if ($suatChieuChuaChieu) {
                return redirect()->route('phong-chieu.index')->with('error', 'Không thể xóa phòng vì còn suất chiếu chưa chiếu trong phòng này.');
            }
            SuatChieu::where('ID_PhongChieu', $id)->delete();
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
