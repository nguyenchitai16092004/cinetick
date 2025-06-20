<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rap;
use App\Models\Phim;
use App\Models\SuatChieu;
use Carbon\Carbon;

class RapChiTietController extends Controller
{
    public function chiTiet($slug)
    {
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
            $phimsByDay[$date] = Phim::whereIn('ID_Phim', $phimIds)->get();
        }

        return view('frontend.pages.rap', compact('rap', 'days', 'phimsByDay'));
    }
}