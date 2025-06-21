<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LienHe;

class LienHeController extends Controller
{
    public function index()
    {
        $contacts = LienHe::orderByDesc('created_at')->paginate(20);
        return view('backend.pages.lien_he.lien_he', compact('contacts'));
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
        $lienhe = LienHe::findOrFail($id);
        $lienhe->delete();

        return redirect()->route('lien-he.index')->with('success', 'Đã xóa liên hệ!');
    }
}