<?php

namespace App\Http\Controllers\Admin;

use App\Models\Phim;
use App\Models\PhongChieu;
use App\Models\SuatChieu;
use App\Http\Controllers\Controller;
use App\Models\Rap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuatChieuController extends Controller
{
    public function index()
    {
        $suatChieus = SuatChieu::with(['phim', 'phongChieu.rap'])->latest()->paginate(10);
        $phims = Phim::all();
        return view('admin.pages.suat_chieu.suat-chieu', compact('suatChieus', 'phims'));
    }

    public function create()
    {
        $raps = Rap::all();
        $phims = Phim::all();
        return view('admin.pages.suat_chieu.create-suat-chieu', compact('raps', 'phims'));
    }


    public function show($id)
    {
        $suatChieu = SuatChieu::with(['phim', 'phongChieu'])->findOrFail($id);
        return view('suat-chieu.show', compact('suatChieu'));
    }

    public function edit($id)
    {
        $suatChieu = SuatChieu::findOrFail($id);
        // Chỉ lấy những phim có TrangThai = 1
        $phims = Phim::where(function ($query) {
            $query->whereDate('NgayKetThuc', '>=', now())
                ->orWhereNull('NgayKetThuc');
        })
            ->get();


        $phongChieus = PhongChieu::join('rap', 'phong_chieu.ID_Rap', '=', 'rap.ID_Rap')
            ->select('phong_chieu.TenPhongChieu', 'phong_chieu.ID_PhongChieu', 'rap.DiaChi', 'rap.TenRap', 'phong_chieu.ID_Rap')
            ->get();
        return view('admin.pages.suat_chieu.detail-suat-chieu', compact('suatChieu', 'phims', 'phongChieus'));
    }

    public function destroy($id)
    {
        $suatChieu = SuatChieu::findOrFail($id);
        $suatChieu->delete();

        return redirect()->route('suat-chieu.index')
            ->with('success', 'Xóa suất chiếu thành công');
    }


    public function filterByDate(Request $request)
    {
        $date = $request->date;
        if (!$date) {
            return redirect()->route('suat-chieu.index');
        }
        $suatChieus = SuatChieu::with(['phim', 'phongChieu'])
            ->whereDate('NgayChieu', $date)
            ->latest()
            ->paginate(10);
        $phims = Phim::all();
        return view('admin.pages.suat_chieu.suat-chieu', compact('suatChieus', 'date', 'phims'));
    }


    public function filterByPhim(Request $request)
    {
        $phimId = $request->phim_id;
        if (!$phimId) {
            return redirect()->route('suat-chieu.index');
        }
        $suatChieus = SuatChieu::with(['phim', 'phongChieu'])
            ->where('ID_Phim', $phimId)
            ->latest()
            ->paginate(10);

        $selectedPhim = Phim::find($phimId);
        $phims = Phim::all();
        return view('admin.pages.suat_chieu.suat-chieu', compact('suatChieus', 'selectedPhim', 'phims'));
    }

    public function filterMovieByDate(Request $request)
    {
        $date = $request->date;
        $Phim = Phim::where('NgayKhoiChieu', '<=', $date)->orWhereNull('NgayKhoiChieu')->get();

        return response()->json($Phim);
    }

    public function filterPhong(Request $request)
    {
        $id = $request->ID_Rap;
        $phongs = PhongChieu::where('ID_Rap', $id)->get();
        return $phongs;
    }

    private function checkLichTrinhXungDot($phongChieuId, $ngayChieu, $gioChieu, $phimId, $excludeId = null)
    {
        $phim = Phim::find($phimId);
        if (!$phim || !$phim->ThoiLuong) {
            return ['has_conflict' => false];
        }

        $startTime = Carbon::parse($ngayChieu . ' ' . $gioChieu);
        $endTime = $startTime->copy()->addMinutes($phim->ThoiLuong + 30);
        $conflictQuery = SuatChieu::with('phim')
            ->where('ID_PhongChieu', $phongChieuId)
            ->where('NgayChieu', $ngayChieu);

        if ($excludeId) {
            $conflictQuery->where('ID_SuatChieu', '!=', $excludeId);
        }

        $existingShows = $conflictQuery->get();

        foreach ($existingShows as $show) {
            if (!$show->phim || !$show->phim->ThoiLuong) continue;

            $existingStart = Carbon::parse($show->NgayChieu . ' ' . $show->GioChieu);
            $existingEnd = $existingStart->copy()->addMinutes($show->phim->ThoiLuong + 30);

            // Kiểm tra xung đột thời gian
            if (($startTime >= $existingStart && $startTime < $existingEnd) ||
                ($endTime > $existingStart && $endTime <= $existingEnd) ||
                ($startTime <= $existingStart && $endTime >= $existingEnd)
            ) {

                return [
                    'has_conflict' => true,
                    'conflict_show' => $show,
                    'conflict_time' => $existingStart->format('H:i') . ' - ' . $existingEnd->format('H:i'),
                    'new_time' => $startTime->format('H:i') . ' - ' . $endTime->format('H:i')
                ];
            }
        }

        return ['has_conflict' => false];
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ID_Phim' => 'required|exists:phim,ID_Phim',
            'ID_PhongChieu' => 'required|exists:phong_chieu,ID_PhongChieu',
            'ID_Rap' => 'required|exists:rap,ID_Rap',
            'GiaVe' => 'required|numeric|min:0',
            'schedule' => 'required|array|min:1',
            'schedule.*' => 'required|string'
        ], [
            'ID_Phim.required' => 'Vui lòng chọn phim',
            'ID_PhongChieu.required' => 'Vui lòng chọn phòng chiếu',
            'GiaVe.required' => 'Vui lòng nhập giá vé',
            'GiaVe.numeric' => 'Giá vé phải là số',
            'GiaVe.min' => 'Giá vé không được âm',
            'schedule.required' => 'Vui lòng thêm ít nhất một lịch chiếu',
            'schedule.min' => 'Vui lòng thêm ít nhất một lịch chiếu'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $errors = [];
        $successCount = 0;
        $conflictCount = 0;
        $invalidCount = 0;

        DB::beginTransaction();

        try {
            $phim = Phim::find($request->ID_Phim);
            $now = Carbon::now();
            if ($phim->NgayKetThuc < $now) {
                return redirect()->back()->with('error', 'Cập nhật suất chiếu thành công');
            }
            foreach ($request->schedule as $date => $timesString) {
                $times = array_filter(explode(',', $timesString));

                foreach ($times as $time) {
                    if (!preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
                        $errors[] = "Giờ chiếu không hợp lệ: {$date} - {$time}";
                        $invalidCount++;
                        continue;
                    }

                    // Kiểm tra ngày chiếu
                    $ngayChieu = Carbon::parse($date);
                    $today = Carbon::today();

                    if ($ngayChieu->lt($today)) {
                        $errors[] = "Không thể tạo suất chiếu trong quá khứ: " . $ngayChieu->format('d/m/Y');
                        $invalidCount++;
                        continue;
                    }

                    if ($ngayChieu->gt($today->copy()->addMonths(3))) {
                        $errors[] = "Không thể tạo suất chiếu quá 3 tháng: " . $ngayChieu->format('d/m/Y');
                        $invalidCount++;
                        continue;
                    }
                    if ($phim->NgayKetThuc && Carbon::parse($phim->NgayKetThuc)->lt($ngayChieu)) {
                        $errors[] = "Phim đã kết thúc chiếu vào ngày " . $ngayChieu->format('d/m/Y');
                        $invalidCount++;
                        continue;
                    }
                    $conflictCheck = $this->checkLichTrinhXungDot(
                        $request->ID_PhongChieu,
                        $date,
                        $time,
                        $request->ID_Phim
                    );

                    if ($conflictCheck['has_conflict']) {
                        $errors[] = "Xung đột lịch chiếu {$ngayChieu->format('d/m/Y')} - {$time}: " .
                            "Phòng đã có suất chiếu \"{$conflictCheck['conflict_show']->phim->TenPhim}\" từ {$conflictCheck['conflict_time']}";
                        $conflictCount++;
                        continue;
                    }
                    SuatChieu::create([
                        'NgayChieu' => $date,
                        'GioChieu' => $time,
                        'GiaVe' => $request->GiaVe,
                        'ID_PhongChieu' => $request->ID_PhongChieu,
                        'ID_Phim' => $request->ID_Phim,
                        'ID_Rap' => $request->ID_Rap
                    ]);

                    $successCount++;
                }
            }

            DB::commit();
            $messages = "";
            $messages .= "✅ Tạo thành công {$successCount} suất chiếu \n";
            $messages .= "⚠️ {$conflictCount} suất chiếu bị xung đột lịch \n";
            $messages .= "❌ {$invalidCount} suất chiếu không hợp lệ \n";

            if ($successCount > 0) {
                return redirect()->route('suat-chieu.index')->with('success', $messages);
            } else {
                return redirect()->back()
                    ->withErrors($errors)
                    ->withInput()
                    ->with('error', 'Không có suất chiếu nào được tạo',  $messages);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])
                ->withInput();
        }
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'GioChieu' => 'required',
            'NgayChieu' => 'required|date',
            'GiaVe' => 'required|numeric|min:0',
            'ID_PhongChieu' => 'required|exists:phong_chieu,ID_PhongChieu',
            'ID_Phim' => 'required|exists:phim,ID_Phim',
            'ID_Rap' => 'required|exists:rap,ID_Rap',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Lấy suất chiếu hiện tại
        $suatChieu = SuatChieu::findOrFail($id);
        $ngayChieu = Carbon::parse($request->NgayChieu);

        // Chỉ cho phép chỉnh sửa suất chiếu trong tương lai
        if ($ngayChieu->lt(Carbon::today())) {
            return redirect()->back()
                ->withErrors(['NgayChieu' => 'Không thể chỉnh sửa suất chiếu trong quá khứ'])
                ->withInput();
        }

        // ✅ Kiểm tra thời gian chiếu phải sau thời điểm hiện tại ít nhất 15 phút
        $thoiGianChieuMoi = Carbon::parse($request->NgayChieu . ' ' . $request->GioChieu);
        $thoiGianToiThieu = Carbon::now()->addMinutes(15);

        if ($thoiGianChieuMoi->lt($thoiGianToiThieu)) {
            return redirect()->back()
                ->withErrors(['GioChieu' => 'Thời gian chiếu phải sau thời điểm hiện tại ít nhất 15 phút'])
                ->withInput();
        }

        // Kiểm tra xung đột lịch chiếu
        $conflictCheck = $this->checkLichTrinhXungDot(
            $request->ID_PhongChieu,
            $request->NgayChieu,
            $request->GioChieu,
            $request->ID_Phim,
            $id
        );

        if ($conflictCheck['has_conflict']) {
            $errorMessage = sprintf(
                'Xung đột lịch chiếu! Phòng đã có suất chiếu "%s" từ %s. Suất chiếu được sửa sẽ từ %s.',
                $conflictCheck['conflict_show']->phim->TenPhim,
                $conflictCheck['conflict_time'],
                $conflictCheck['new_time']
            );

            return redirect()->back()
                ->withErrors(['schedule_conflict' => $errorMessage])
                ->withInput();
        }

        // Kiểm tra phim có đang trong thời gian chiếu không
        $phim = Phim::find($request->ID_Phim);
        if ($phim->NgayKetThuc && Carbon::parse($phim->NgayKetThuc)->lt($ngayChieu)) {
            return redirect()->back()
                ->withErrors(['ID_Phim' => 'Phim đã kết thúc chiếu vào ngày được chọn'])
                ->withInput();
        }

        // Cập nhật suất chiếu
        $suatChieu->update($request->all());

        return redirect()->route('suat-chieu.index')->with('success', 'Cập nhật suất chiếu thành công');
    }

    public function checkLoi(Request $request)
    {
        $conflictCheck = $this->checkLichTrinhXungDot(
            $request->phong_chieu_id,
            $request->ngay_chieu,
            $request->gio_chieu,
            $request->phim_id,
            $request->exclude_id
        );

        return response()->json($conflictCheck);
    }
}
