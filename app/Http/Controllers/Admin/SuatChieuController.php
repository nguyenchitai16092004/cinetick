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

class SuatChieuController extends Controller
{
    // ... các method khác giữ nguyên

    /**
     * Kiểm tra xung đột lịch chiếu với thời gian phim
     */
    public function index()
    {
        $suatChieus = SuatChieu::with(['phim', 'phongChieu.rap'])->latest()->paginate(10);
        $phims = Phim::all();
        return view('backend.pages.suat_chieu.suat-chieu', compact('suatChieus', 'phims'));
    }

    /**
     * Hiển thị form tạo mới suất chiếu
     */
    public function create()
    {
        $phims = Phim::where(function ($query) {
            $query->whereDate('NgayKetThuc', '>=', now())->orWhereNull('NgayKetThuc');
        })->get();

        $phongChieus = PhongChieu::join('rap', 'phong_chieu.ID_Rap', '=', 'rap.ID_Rap')->select('phong_chieu.TenPhongChieu', 'phong_chieu.ID_PhongChieu', 'rap.DiaChi', 'rap.TenRap', 'phong_chieu.ID_Rap')->get();
        return view('backend.pages.suat_chieu.create-suat-chieu', compact('phims', 'phongChieus'));
    }


    /**
     * Hiển thị thông tin chi tiết suất chiếu
     */
    public function show($id)
    {
        $suatChieu = SuatChieu::with(['phim', 'phongChieu'])->findOrFail($id);
        return view('suat-chieu.show', compact('suatChieu'));
    }

    /**
     * Hiển thị form chỉnh sửa suất chiếu
     */
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
        return view('backend.pages.suat_chieu.detail-suat-chieu', compact('suatChieu', 'phims', 'phongChieus'));
    }

    /**
     * Cập nhật thông tin suất chiếu
     */

    /**
     * Xóa suất chiếu
     */
    public function destroy($id)
    {
        $suatChieu = SuatChieu::findOrFail($id);
        $suatChieu->delete();

        return redirect()->route('suat-chieu.index')
            ->with('success', 'Xóa suất chiếu thành công');
    }

    /**
     * Lọc suất chiếu theo ngày
     */
    public function filterByDate(Request $request)
    {
        $date = $request->date;
        $suatChieus = SuatChieu::with(['phim', 'phongChieu'])
            ->whereDate('NgayChieu', $date)
            ->latest()
            ->paginate(10);

        return view('backend.pages.suat_chieu.suat-chieu', compact('suatChieus', 'date'));
    }

    /**
     * Lọc suất chiếu theo phim
     */
    public function filterByPhim(Request $request)
    {
        $phimId = $request->phim_id;
        $suatChieus = SuatChieu::with(['phim', 'phongChieu'])
            ->where('ID_Phim', $phimId)
            ->latest()
            ->paginate(10);

        $selectedPhim = Phim::find($phimId);

        return view('backend.pages.suat_chieu.suat-chieu', compact('suatChieus', 'selectedPhim'));
    }

    // Lọc xuất chiếu phim ngày chọn
    public function filterMovieByDate(Request $request)
    {
        $date = $request->date;
        $Phim = Phim::where('NgayKetThuc', '>=', $date)->orWhereNull('NgayKetThuc')->get();

        return response()->json($Phim);
    }
    private function checkScheduleConflict($phongChieuId, $ngayChieu, $gioChieu, $phimId, $excludeId = null)
    {
        // Lấy thông tin phim để biết thời lượng
        $phim = Phim::find($phimId);
        if (!$phim || !$phim->ThoiLuong) {
            return ['has_conflict' => false];
        }

        // Chuyển đổi thời gian bắt đầu và kết thúc
        $startTime = Carbon::parse($ngayChieu . ' ' . $gioChieu);
        $endTime = $startTime->copy()->addMinutes($phim->ThoiLuong + 30); // +30 phút để dọn dẹp phòng

        // Tìm các suất chiếu khác trong cùng phòng và cùng ngày
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

    /**
     * Lưu thông tin suất chiếu mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'GioChieu' => 'required',
            'NgayChieu' => 'required|date|after_or_equal:today',
            'GiaVe' => 'required|numeric|min:0',
            'ID_PhongChieu' => 'required|exists:phong_chieu,ID_PhongChieu',
            'ID_Phim' => 'required|exists:phim,ID_Phim',
            'ID_Rap' => 'required|exists:rap,ID_Rap',
        ], [
            'NgayChieu.after_or_equal' => 'Ngày chiếu không được trong quá khứ',
            'GioChieu.required' => 'Vui lòng chọn giờ chiếu',
            'NgayChieu.required' => 'Vui lòng chọn ngày chiếu',
            'GiaVe.required' => 'Vui lòng nhập giá vé',
            'GiaVe.numeric' => 'Giá vé phải là số',
            'GiaVe.min' => 'Giá vé không được âm',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Kiểm tra ngày chiếu không được trong quá khứ và không quá xa
        $ngayChieu = Carbon::parse($request->NgayChieu);
        $today = Carbon::today();

        if ($ngayChieu->lt($today)) {
            return redirect()->back()
                ->withErrors(['NgayChieu' => 'Không thể tạo suất chiếu trong quá khứ'])
                ->withInput();
        }

        if ($ngayChieu->gt($today->copy()->addMonths(3))) {
            return redirect()->back()
                ->withErrors(['NgayChieu' => 'Không thể tạo suất chiếu quá 3 tháng'])
                ->withInput();
        }

        // Kiểm tra xung đột lịch chiếu với thời lượng phim
        $conflictCheck = $this->checkScheduleConflict(
            $request->ID_PhongChieu,
            $request->NgayChieu,
            $request->GioChieu,
            $request->ID_Phim
        );

        if ($conflictCheck['has_conflict']) {
            $errorMessage = sprintf(
                'Xung đột lịch chiếu! Phòng đã có suất chiếu "%s" từ %s. Suất chiếu mới sẽ từ %s.',
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

        SuatChieu::create($request->all());

        return redirect()->route('suat-chieu.index')
            ->with('success', 'Thêm suất chiếu thành công');
    }

    /**
     * Cập nhật thông tin suất chiếu
     */
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
        $conflictCheck = $this->checkScheduleConflict(
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


    /**
     * API kiểm tra xung đột lịch chiếu (để sử dụng với AJAX)
     */
    public function checkConflict(Request $request)
    {
        $conflictCheck = $this->checkScheduleConflict(
            $request->phong_chieu_id,
            $request->ngay_chieu,
            $request->gio_chieu,
            $request->phim_id,
            $request->exclude_id
        );

        return response()->json($conflictCheck);
    }
}
