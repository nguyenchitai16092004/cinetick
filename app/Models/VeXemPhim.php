<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeXemPhim extends Model
{
    protected $table = 've_xem_phim';

    protected $primaryKey = 'ID_Ve';

    public $timestamps = true;

    protected $fillable = [
        'SoLuong',
        'TenGhe',   
        'TenPhim',
        'NgayXem',
        'DiaChi',
        'GiaVe',
        'TrangThai',
        'ID_SuatChieu',
        'ID_HoaDon',
        'ID_Ghe'
    ];

    // Quan hệ với HoaDon - mỗi vé thuộc một hóa đơn
    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'ID_HoaDon', 'ID_HoaDon');
    }

    // Quan hệ với SuatChieu (giả sử có model SuatChieu)
    public function suatChieu()
    {
        return $this->belongsTo(SuatChieu::class, 'ID_SuatChieu', 'ID_SuatChieu');
    }

    // Quan hệ với GheNgoi (giả sử có model GheNgoi)
    public function gheNgoi()
    {
        return $this->belongsTo(GheNgoi::class, 'ID_Ghe', 'ID_Ghe');
    }
}