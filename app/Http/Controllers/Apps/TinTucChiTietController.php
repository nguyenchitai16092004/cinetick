<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TinTuc;
use Illuminate\Support\Facades\DB;
use App\Models\GheDangGiu;


class TinTucChiTietController extends Controller
{


    // Chi tiết bài viết theo slug
    public function chiTiet($slug)
    {
        if (session()->has('user_id')) {
            GheDangGiu::where('ID_TaiKhoan', session('user_id'))->delete();
        }
        
        $tinTuc = TinTuc::where('Slug', $slug)
            ->where('TrangThai', 1)
            ->firstOrFail();
        $userLikedThis = false;
        return view('user.pages.chi-tiet-bai-viet', compact('tinTuc', 'userLikedThis'));
    }
    public function like($slug)
    {
        $tinTuc = TinTuc::where('Slug', $slug)->firstOrFail();
        $tinTuc->increment('LuotThich');
        return response()->json(['LuotThich' => $tinTuc->LuotThich]);
    }

    public function unlike($slug)
    {
        $tinTuc = TinTuc::where('Slug', $slug)->firstOrFail();
        if ($tinTuc->LuotThich > 0) {
            $tinTuc->decrement('LuotThich');
        }
        return response()->json(['LuotThich' => $tinTuc->LuotThich]);
    }

    public function view($slug)
    {
        $tinTuc = TinTuc::where('Slug', $slug)->firstOrFail();
        $tinTuc->increment('LuotXem');
        return response()->json(['LuotXem' => $tinTuc->LuotXem]);
    }
    public function listDienAnh()
    {
        $dienAnhs = TinTuc::where('LoaiBaiViet', 4)
            ->where('TrangThai', 1)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('user.pages.dien-anh', compact('dienAnhs'));
    }
    public function listKhuyenMai()
    {
        if (session()->has('user_id')) {
            GheDangGiu::where('ID_TaiKhoan', session('user_id'))->delete();
        }
        
        $khuyenMais = TinTuc::where('LoaiBaiViet', 1)
            ->where('TrangThai', 1)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('user.pages.khuyen-mai', compact('khuyenMais'));
    }
    public function thongTinCineTickStatic($slug)
    {
        if (session()->has('user_id')) {
            GheDangGiu::where('ID_TaiKhoan', session('user_id'))->delete();
        }
        
        $tinTuc = TinTuc::where('Slug', $slug)
            ->where('TrangThai', 1)
            ->firstOrFail();
        $userLikedThis = false;
        return view('user.pages.chi-tiet-bai-viet', compact('tinTuc', 'userLikedThis'));
    }
}