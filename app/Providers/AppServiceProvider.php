<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Rap;
use App\Models\TinTuc;
use App\Models\ThongTinTrangWeb;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        Carbon::setLocale('vi');
        /*
        if (app()->environment('local') && request()->server('HTTP_HOST') && str_contains(request()->server('HTTP_HOST'), 'ngrok-free.app')) {
            URL::forceScheme('https');
        } else {
        }

        if (app()->environment('local') && request()->server('HTTP_HOST') && str_contains(request()->server('HTTP_HOST'), 'ngrok-free.app')) {
            URL::forceScheme('https');
        } else {
        }
        */
        View::composer('*', function ($view) {
            $raps = Rap::where('TrangThai', 1)->get();
            $view->with('raps', $raps);
            $view->with('footerGioiThieu', TinTuc::where('LoaiBaiViet', 2)->where('TrangThai', 1)->orderBy('created_at')->get());
            $view->with('footerChinhSach', TinTuc::where('LoaiBaiViet', 3)->where('TrangThai', 1)->orderBy('created_at')->get());
            $view->with('thongTinTrangWeb', ThongTinTrangWeb::first());
        });
    }
}