<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rap;
use App\Models\Phim;
use App\Models\SuatChieu;
use Carbon\Carbon;
use App\Models\GheDangGiu;

class RapChiTietController extends Controller
{
    public function chiTiet($slug)
    {
        
        if (session()->has('user_id')) {
            GheDangGiu::where('ID_TaiKhoan', session('user_id'))->delete();
        }
        $rap = Rap::where('TrangThai', 1)->where('Slug', $slug)->firstOrFail();

        $days = [];
        $today = now();
        for ($i = 0; $i < 4; $i++) {
            $days[] = $today->copy()->addDays($i)->toDateString();
        }

        $phimsByDay = [];
        foreach ($days as $date) {
            $phimIds = SuatChieu::where('ID_Rap', $rap->ID_Rap)
                ->where('NgayChieu', $date)
                ->pluck('ID_Phim')
                ->unique();
            $phims = Phim::whereIn('ID_Phim', $phimIds)->get();
            
            // Gán avg_rating cho từng phim
            foreach ($phims as $phim) {
                $avg = $phim->binhLuan()->avg('DiemDanhGia');
                if (is_null($avg)) {
                    $phim->avg_rating = '10';
                } elseif (fmod($avg, 1) == 0.0) {
                    $phim->avg_rating = (string)(int)$avg;
                } else {
                    $phim->avg_rating = sprintf('%.2f', floor($avg * 100) / 100);
                }
            }
            $phimsByDay[$date] = $phims;
        }
        return view('user.pages.rap', compact('rap', 'days', 'phimsByDay'));
    }
}