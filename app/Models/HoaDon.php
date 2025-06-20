<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'hoa_don';
    protected $primaryKey = 'ID_HoaDon';
    public $timestamps = true;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ID_HoaDon',
        'NgayTao',
        'TongTien',
        'SoTienGiam',
        'PTTT',
        'ID_TaiKhoan',
        'Email',
        'TrangThaiXacNhanHoaDon',
        'TrangThaiXacNhanThanhToan',
        'payment_link',
        'order_code',
        'SoLuongVe',
    ];

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'ID_TaiKhoan', 'ID_TaiKhoan');
    }
    public function suatChieu()
    {
        return $this->belongsTo(SuatChieu::class, 'ID_SuatChieu', 'ID_SuatChieu');
    }
    public function veXemPhim()
    {
        return $this->hasMany(VeXemPhim::class, 'ID_HoaDon', 'ID_HoaDon');
    }
    public static function generateMaHoaDon($length = 7)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $ma = '';
            for ($i = 0; $i < $length; $i++) {
                $ma .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (self::where('ID_HoaDon', $ma)->exists());
        return $ma;
    }
}