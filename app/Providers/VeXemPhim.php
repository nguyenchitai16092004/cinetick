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
        'TenPhim',
        'NgayXem',
        'DiaChi',
        'GiaVe',
        'TrangThai',
        'ID_SuatChieu',
        'ID_HoaDon',
        'ID_Ghe',
    ];
}