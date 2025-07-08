<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LienHe;
use Carbon\Carbon;

class LienHeController extends Controller
{
    public function index()
    {
        $contacts = LienHe::orderByDesc('created_at')->paginate(20);
        return view('admin.pages.lien_he.lien_he', compact('contacts'));
    }

    public function xuly($id)
    {
        $lienhe = LienHe::findOrFail($id);
        $lienhe->TrangThai = 1;
        $lienhe->save();

        return redirect()->route('lien-he.index')->with('success', 'Đã đánh dấu liên hệ đã xử lý!');
    }

    public function destroy($id)
    {
        $lienhe = LienHe::where('ID_LienHe', $id)->first();

        if (!$lienhe) {
            return redirect()->route('lien-he.index')->with('error', 'Liên hệ không tồn tại.');
        }

        if ($lienhe->TrangThai == 1 && $lienhe->updated_at >= Carbon::now()->subMonth()) {
            return redirect()->route('lien-he.index')->with('error', 'Chỉ được xóa liên hệ đã xác nhận nếu đã cập nhật cách đây hơn 1 tháng.');
        }

        if (in_array($lienhe->TrangThai, [0, 1])) {
            $lienhe->delete();
            return redirect()->route('lien-he.index')->with('success', 'Đã xóa liên hệ!');
        }

        return redirect()->route('lien-he.index')->with('error', 'Không thể xóa liên hệ này.');
    }
}
