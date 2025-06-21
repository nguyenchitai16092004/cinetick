<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TinTuc;
use Illuminate\Support\Facades\DB;


class TinTucChiTietController extends Controller
{


    // Chi tiết bài viết theo slug
    public function chiTiet($slug)
    {
        $tinTuc = TinTuc::where('Slug', $slug)
            ->where('TrangThai', 1)
            ->firstOrFail();
        $userLikedThis = false;
        return view('frontend.pages.chi-tiet-bai-viet', compact('tinTuc', 'userLikedThis'));
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
        $dienAnhs = TinTuc::where('LoaiBaiViet', 0)
            ->where('TrangThai', 1)
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('frontend.pages.dien-anh', compact('dienAnhs'));
    }
    public function listKhuyenMai()
    {
        $khuyenMais = TinTuc::where('LoaiBaiViet', 1)
            ->where('TrangThai', 1)
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('frontend.pages.khuyen-mai', compact('khuyenMais'));
    }
}