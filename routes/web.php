<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AutController;
use App\Http\Controllers\Admin\RapController;
use App\Http\Controllers\Admin\AdminPhimController;
use App\Http\Controllers\Admin\BinhLuanController;
use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\PhongChieuController;
use App\Http\Controllers\Admin\SuatChieuController;
use App\Http\Controllers\Admin\TaiKhoanController;
use App\Http\Controllers\Admin\HoaDonController;
use App\Http\Controllers\Admin\VeXemPhimController;
use App\Http\Controllers\Admin\ThongKeController;
use App\Http\Controllers\Admin\TheLoaiPhimController;
use App\Http\Controllers\Admin\TinTucController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\LienHeController;


use App\Http\Controllers\Apps\PhimController;
use App\Http\Controllers\Apps\AuthController;
use App\Http\Controllers\Apps\DatVeController;
use App\Http\Controllers\Apps\PayOSController;
use App\Http\Controllers\Apps\ThanhToanController;
use App\Http\Controllers\Apps\RapChiTietController;
use App\Http\Controllers\Apps\TinTucChiTietController;
use App\Http\Controllers\Apps\LienHeChiTietController;

//==============================user=====================================//
Route::get('/', [PhimController::class, 'Index'])->name('home');
Route::get('/tim-kiem', [PhimController::class, 'timKiem'])->name('tim-kiem');
// --- Auth ---
Route::prefix('auth')->group(function () {
    Route::get('/dang-ky', [AuthController::class, 'DangKy'])->name('register.form.get');
    Route::post('/dang-ky', [AuthController::class, 'dang_ky'])->name('register.form.post');
    Route::get('/xac-nhan/{token}', [AuthController::class, 'verifyAccount'])->name('verify.account');
    Route::get('/dang-nhap', [AuthController::class, 'DangNhap'])->name('login.form');
    Route::post('/dang-nhap-tai-khoan', [AuthController::class, 'dang_nhap'])->name('login');
    Route::post('/dang-xuat', [AuthController::class, 'dang_xuat'])->name('logout');
    Route::get('/doi-mat-khau', fn() => view('user.pages.thong-tin-tai-khoan.doi-mat-khau'))->name('doi-mat-khau.get');
    Route::post('/doi-mat-khau', [AuthController::class, 'doi_mat_khau'])->name('doi-mat-khau.post');
    Route::get('/quen-mat-khau', fn() => view('user.pages.quen-mat-khau'))->name('quen-mat-khau.get');
    Route::post('/quen-mat-khau', [AuthController::class, 'quen_mat_khau'])->name('quen-mat-khau.post');
});

// --- Thông tin tài khoản ---
Route::prefix('tai-khoan')->group(function () {
    Route::get('/info', [AuthController::class, 'dsHoaDonNguoiDung'])->name('user.info');
    Route::get('/cap-nhat-thong-tin', [AuthController::class, 'showUpdateInfo'])->name('user.updateInfo.get');
    Route::post('/cap-nhat-thong-tin', [AuthController::class, 'updateInfo'])->name('user.updateInfo.post');
    Route::get('/kiem-tra-thanh-toan', [ThanhToanController::class, 'checkoutStatus'])->name('checkout_status');
});

// --- Phim ---
Route::prefix('phim')->group(function () {
    Route::get('/phim-sap-chieu', [PhimController::class, 'phimSapChieu'])->name('phim.sapChieu');
    Route::get('/phim-dang-chieu', [PhimController::class, 'phimDangChieu'])->name('phim.dangChieu');
    Route::get('/chi-tiet-phim/{slug}', [PhimController::class, 'chiTiet'])->name('phim.chiTiet');
});

// --- Đặt vé ---
Route::prefix('dat-ve')->group(function () {
    Route::get('/{phimSlug}/{ngay}/{gio}', [DatVeController::class, 'showBySlug'])->name('dat-ve.show.slug');
    Route::get('/thanh-toan', [DatVeController::class, 'showThanhToan'])->name('dat-ve.thanh-toan');
    Route::post('/thanh-toan', [DatVeController::class, 'thanhToan'])->name('thanh-toan');
    Route::post('/giu-ghe', [DatVeController::class, 'giuGhe']);
    Route::post('/bo-giu-ghe', [DatVeController::class, 'boGiuGhe']);
    Route::post('/bo-giu-ghe-nhieu', [DatVeController::class, 'boGiuGheNhieu']);
});
// -- Bài viết điện ảnh --
Route::prefix('goc-dien-anh')->group(function () {
    Route::get('/', [TinTucChiTietController::class, 'listDienAnh'])->name('ds-bai-viet-dien-anh');
    Route::get('/{slug}', [TinTucChiTietController::class, 'chiTiet'])->name('bai-viet.chiTiet.dien-anh');
    Route::post('/{slug}/like', [TinTucChiTietController::class, 'like'])->name('tin_tuc.like');
    Route::post('/{slug}/unlike', [TinTucChiTietController::class, 'unlike'])->name('tin_tuc.unlike');
    Route::post('/{slug}/view', [TinTucChiTietController::class, 'view'])->name('tin_tuc.view');
});

// -- Bài viết tin khuyến mãi --
Route::prefix('tin-khuyen-mai')->group(function () {
    Route::get('/', [TinTucChiTietController::class, 'listKhuyenMai'])->name('ds-bai-viet-khuyen-mai');
    Route::get('/{slug}', [TinTucChiTietController::class, 'chiTiet'])->name('bai-viet.chiTiet.khuyen-mai');
    Route::post('/{slug}/like', [TinTucChiTietController::class, 'like'])->name('tin_tuc.like');
    Route::post('/{slug}/unlike', [TinTucChiTietController::class, 'unlike'])->name('tin_tuc.unlike');
    Route::post('/{slug}/view', [TinTucChiTietController::class, 'view'])->name('tin_tuc.view');
});

// -- Bài viết thông tin website --
Route::prefix('thong-tin-cinetick')->group(function () {
    Route::get('/{slug}', [TinTucChiTietController::class, 'thongTinCineTickStatic'])->name('thongtincinetick.static');
    Route::post('/{slug}/like', [TinTucChiTietController::class, 'like'])->name('tin_tuc.like');
    Route::post('/{slug}/unlike', [TinTucChiTietController::class, 'unlike'])->name('tin_tuc.unlike');
    Route::post('/{slug}/view', [TinTucChiTietController::class, 'view'])->name('tin_tuc.view');
});

// --- ajax ---
Route::prefix('ajax')->group(function () {
    Route::get('/phim-theo-rap', [PhimController::class, 'ajaxPhimTheoRap']);
    Route::get('/ngay-chieu-theo-rap-phim', [PhimController::class, 'ajaxNgayChieuTheoRapPhim']);
    Route::get('/suat-chieu-theo-rap-phim-ngay', [PhimController::class, 'ajaxSuatChieuTheoRapPhimNgay']);
    //Đánh giá phim
    Route::post('/can-rate', [PhimController::class, 'ajaxCanRatePhim'])->name('ajax.can-rate');
    Route::post('/send-rating', [PhimController::class, 'ajaxSendRating'])->name('ajax.send-rating');
    Route::get('/get-rating', [PhimController::class, 'ajaxGetRating'])->name('ajax.get-rating');
    //Kiểm tra mã khuyến mãi
    Route::post('/kiem-tra-khuyen-mai', [ThanhToanController::class, 'kiemTraKhuyenMai'])->name('ajax.kiem-tra-khuyen-mai');
});

// --- Rạp ---
Route::prefix('rap')->group(function () {
    Route::get('/{slug}', [RapChiTietController::class, 'chiTiet'])->name('rap.chiTiet');
});


// --- Thanh toán ---
Route::prefix('thanh-toan')->group(function () {
    Route::post('/', [ThanhToanController::class, 'payment'])->name('payment');
    Route::post('/payos-embedded/create', [PayOSController::class, 'createEmbeddedPaymentLink'])->name('payos.embedded.create');
    Route::get('/payos-return', [PayOSController::class, 'handleReturn'])->name('payos.return');
    Route::get('/payos-cancel', [PayOSController::class, 'handleCancel'])->name('payos.cancel');
    Route::get('/status/{orderCode}', [PayOSController::class, 'checkPaymentStatus'])->name('payos.status');
    Route::get('/checkout-status', [ThanhToanController::class, 'checkoutStatus'])->name('checkout_status');
});

// --- Liên hệ ---
Route::prefix('lien-he')->group(function () {
    Route::get('/', [LienHeChiTietController::class, 'index'])->name('lien-he');
    Route::post('/gui-lien-he', [LienHeChiTietController::class, 'send'])->name('lien-he.gui-lien-he');
});

// --- Các trang tĩnh ---
Route::view('/thanh-cong', 'user.pages.thanh-cong')->name('thanh-toan-thanh-cong');
Route::view('/that-bai', 'user.pages.that-bai')->name('thanh-toan-that-bai');


//===============================Routes cho tất cả admin (Vai trò 1 và 2)=====================================//

Route::get('/admin', [AutController::class, 'index']);
Route::get('/admin/login', fn() => view('admin.login'));
Route::post('/dang-nhap-quan-ly', [AutController::class, 'dang_nhap'])->name('login_admin');
Route::post('/admin/dang-xuat', [AutController::class, 'dang_xuat'])->name('logout_admin');
Route::get('/admin/404', fn() => view('backend.pages.404'));

Route::prefix('admin')->middleware(['admin'])->group(function () {
    // Home - Tất cả admin đều truy cập được
    Route::get('/home', [HomeController::class, 'index'])->name('cap-nhat-thong-tin.index');

    Route::prefix('hoa-don')->name('hoa-don.')->group(function () {
        Route::get('/', [HoaDonController::class, 'index'])->name('index');
        Route::get('/create', [HoaDonController::class, 'create'])->name('create');
        Route::post('/store', [HoaDonController::class, 'store'])->name('store');
        Route::get('/show/{id}', [HoaDonController::class, 'show'])->name('show');
        Route::post('suat-chieu/loc-phim', [HoaDonController::class, 'filterMovieByDate'])->name('suat-chieu.loc-phim-theo-ngay');
        Route::post('/lay-phong-chieu-theo-id', [HoaDonController::class, 'layTheoId'])->name('suat-chieu.lay-phong');
        Route::post('/kiem-tra-khuyen-mai', [HoaDonController::class, 'kiemTra'])->name('khuyen-mai.kiem-tra');
        Route::post('/dat-ghe-tam', [HoaDonController::class, 'datGheTam'])->name('dat-ghe-tam');
        Route::post('/huy-giu-ghe', [HoaDonController::class, 'huyGiuGhe'])->name('huy-ghe-tam');
        Route::post('/hoa-don/payos/create-payment', [HoaDonController::class, 'payment'])->name('payment');
        Route::post('/hoa-don/payos/check-payment', [HoaDonController::class, 'processCODTicket'])->name('payos.check-payment');
        Route::post('/hoa-don/tao-hoa-don', [HoaDonController::class, 'taoHoaDon'])->name('hoa-don.tao-hoa-don');
        Route::get('/payos-return', [\App\Http\Controllers\Admin\AdminPayOSController::class, 'payosReturn'])->name('payos.return');
        Route::get('/payos-cancel', [\App\Http\Controllers\Admin\AdminPayOSController::class, 'payosCancel'])->name('payos.cancel');
    });

    // Tài khoản
    Route::prefix('tai-khoan')->name('tai-khoan.')->group(function () { 
        Route::get('/edit/{id}', [TaiKhoanController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [TaiKhoanController::class, 'update'])->name('update');
    });
});

//===============================Routes chỉ dành cho Admin cấp cao (Vai trò 2)=====================================//
Route::prefix('admin')->middleware(['admin', \App\Http\Middleware\RoleMiddleware::class . ':2'])->group(function () {
    // Cập nhật thông tin trang web
    Route::post('/cap-nhat-thong-tin-trang', [HomeController::class, 'update'])->name('thong-tin-trang-web.update');

    // Banner
    Route::prefix('banner')->name('banner.')->group(function () {
        Route::get('/create', [BannerController::class, 'create'])->name('create');
        Route::post('/store', [BannerController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BannerController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [BannerController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [BannerController::class, 'destroy'])->name('destroy');
        Route::post('/get-data-by-type', [BannerController::class, 'layDuLieuBangType'])->name('get-data-by-type');
    });
    // Thống kê
    Route::prefix('thong-ke')->name('thong-ke.')->group(function () {
        Route::get('/', [ThongKeController::class, 'index'])->name('index');
        Route::get('/export-excel', [ThongKeController::class, 'exportExcel'])->name('export');
    });

    // Rap - Quản lý đầy đủ
    Route::prefix('rap')->name('rap.')->group(function () {
        Route::get('/', [RapController::class, 'index'])->name('index');
        Route::get('/create', [RapController::class, 'create'])->name('create');
        Route::post('/store', [RapController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [RapController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [RapController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [RapController::class, 'destroy'])->name('destroy');
    });

    // Phòng chiếu - Quản lý đầy đủ
    Route::prefix('phong-chieu')->name('phong-chieu.')->group(function () {
        Route::get('/', [PhongChieuController::class, 'index'])->name('index');
        Route::get('/show/{id}', [PhongChieuController::class, 'show'])->name('show');
        Route::get('/create', [PhongChieuController::class, 'create'])->name('create');
        Route::post('/store', [PhongChieuController::class, 'store'])->name('store');
        Route::put('/update/{id}', [PhongChieuController::class, 'update'])->name('update');
        Route::delete('/{id}', [PhongChieuController::class, 'destroy'])->name('destroy');
    });

    // Phim - Quản lý đầy đủ
    Route::prefix('phim')->name('phim.')->group(function () {
        Route::get('/', [AdminPhimController::class, 'index'])->name('index');
        Route::get('/show/{id}', [AdminPhimController::class, 'show'])->name('show');
        Route::get('/create', [AdminPhimController::class, 'create'])->name('create');
        Route::post('/store', [AdminPhimController::class, 'store'])->name('store');
        Route::put('/update/{id}', [AdminPhimController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminPhimController::class, 'destroy'])->name('destroy');
        Route::get('phim/change-status/{id}', [AdminPhimController::class, 'changeStatus'])->name('change-status');
    });

    // Suất chiếu - Quản lý đầy đủ
    Route::prefix('suat-chieu')->name('suat-chieu.')->group(function () {
        Route::get('/create', [SuatChieuController::class, 'create'])->name('create');
        Route::post('/store', [SuatChieuController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SuatChieuController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SuatChieuController::class, 'update'])->name('update');
        Route::delete('/{id}', [SuatChieuController::class, 'destroy'])->name('destroy');
        Route::post('/check-conflict', [SuatChieuController::class, 'checkLoi'])->name('check-conflict');
        Route::get('/', [SuatChieuController::class, 'index'])->name('index');
        Route::get('/filter/date', [SuatChieuController::class, 'filterByDate'])->name('filter.date');
        Route::get('/filter/phim', [SuatChieuController::class, 'filterByPhim'])->name('filter.phim');
        Route::get('/filter/rap', [SuatChieuController::class, 'filterByRap'])->name('filter.rap');
        Route::post('/loc-phim-theo-ngay', [SuatChieuController::class, 'filterMovieByDate'])->name('loc-phim-theo-ngay');
        Route::post('/loc-phong', [SuatChieuController::class, 'filterPhong'])->name('loc-phong');
    });

    // Thể loại
    Route::prefix('the-loai')->name('the-loai.')->group(function () {
        Route::get('/', [TheLoaiPhimController::class, 'index'])->name('index');
        Route::get('/create', [TheLoaiPhimController::class, 'create'])->name('create');
        Route::post('/store', [TheLoaiPhimController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TheLoaiPhimController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [TheLoaiPhimController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [TheLoaiPhimController::class, 'destroy'])->name('delete');
    });

    // Khuyến mãi
    Route::prefix('khuyen-mai')->name('khuyen-mai.')->group(function () {
        Route::get('/', [KhuyenMaiController::class, 'index'])->name('index');
        Route::get('/create', [KhuyenMaiController::class, 'create'])->name('create');
        Route::post('/store', [KhuyenMaiController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [KhuyenMaiController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [KhuyenMaiController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [KhuyenMaiController::class, 'toggleStatus'])->name('delete');
    });

    // Tài khoản
    Route::prefix('tai-khoan')->name('tai-khoan.')->group(function () {
        Route::get('/', [TaiKhoanController::class, 'index'])->name('index');
        Route::get('/create', [TaiKhoanController::class, 'create'])->name('create');
        Route::post('/store', [TaiKhoanController::class, 'store'])->name('store');
        Route::get('/delete/{id}', [TaiKhoanController::class, 'destroy'])->name('delete');
        Route::get('/change-status/{id}', [TaiKhoanController::class, 'changeStatus'])->name('status');
    });

    // Hóa đơn
    Route::prefix('hoa-don')->name('hoa-don.')->group(function () {
        Route::get('/edit/{id}', [HoaDonController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [HoaDonController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [HoaDonController::class, 'destroy'])->name('destroy');
        Route::get('/export-report', [HoaDonController::class, 'exportReport'])->name('export-report');
    });

    // Vé xem phim
    Route::prefix('ve-xem-phim')->name('ve-xem-phim.')->group(function () {
        Route::get('/{hoaDonId}/create', [VeXemPhimController::class, 'create'])->name('create');
        Route::post('/{hoaDonId}/store', [VeXemPhimController::class, 'store'])->name('store');
        Route::get('/{hoaDonId}/edit/{veId}', [VeXemPhimController::class, 'edit'])->name('edit');
        Route::put('/{hoaDonId}/update/{veId}', [VeXemPhimController::class, 'update'])->name('update');
        Route::delete('/{hoaDonId}/destroy/{veId}', [VeXemPhimController::class, 'destroy'])->name('destroy');
    });

    // Tin tức
    Route::prefix('tin-tuc')->name('tin_tuc.')->group(function () {
        Route::get('/', [TinTucController::class, 'index'])->name('index');
        Route::get('/create', [TinTucController::class, 'create'])->name('create');
        Route::post('/store', [TinTucController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TinTucController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [TinTucController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [TinTucController::class, 'destroy'])->name('destroy');
        Route::post('/upload-image', [TinTucController::class, 'uploadImage'])->name('upload_image');
    });

    // Bình luận
    Route::prefix('binh-luan')->name('binh-luan.')->group(function () {
        Route::get('/', [BinhLuanController::class, 'index'])->name('index');
        Route::get('/show/{id}', [BinhLuanController::class, 'show'])->name('show');
        Route::get('/update-status/{id}', [BinhLuanController::class, 'KiemTraTrangThai'])->name('update-status');
        Route::delete('/destroy/{id}', [BinhLuanController::class, 'destroy'])->name('destroy');
        Route::delete('/destroy-multiple', [BinhLuanController::class, 'destroyMultiple'])->name('destroy-multiple');
        Route::get('/export', [BinhLuanController::class, 'export'])->name('export');
    });

    // Liên hệ
    Route::prefix('lien-he')->name('lien-he.')->group(function () {
        Route::get('/', [LienHeController::class, 'index'])->name('index');
        Route::post('/xuly/{id}', [LienHeController::class, 'xuly'])->name('xuly');
        Route::delete('/destroy/{id}', [LienHeController::class, 'destroy'])->name('destroy');
    });
});
