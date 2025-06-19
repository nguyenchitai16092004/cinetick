<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TinTuc; 

class TinTucController extends Controller
{
    // Lấy 4 bài viết loại 0, trạng thái 1
    public function renderTinTucHome()
    {
        $tinTucs = TinTuc::where('LoaiBaiViet', 0)
            ->where('TrangThai', 1)
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        // Bài main là đầu tiên, 3 bài còn lại là sidebar
        $mainArticle = $tinTucs->first();
        $sidebarArticles = $tinTucs->slice(1, 3);

        return view('frontend.pages.home', compact('mainArticle', 'sidebarArticles'));
    }

    // Chi tiết bài viết theo slug
    public function chiTiet($slug)
    {
        $tinTuc = TinTuc::where('Slug', $slug)
            ->where('TrangThai', 1)
            ->firstOrFail();

        return view('frontend.pages.chi-tiet-bai-viet', compact('tinTuc'));
    }
}