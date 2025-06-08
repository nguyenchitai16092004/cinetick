<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhongChieu extends Model
{
    use HasFactory;

    protected $table = 'phong_chieu';
    protected $primaryKey = 'ID_PhongChieu';

    protected $fillable = [
        'TenPhongChieu',
        'LoaiPhong',
        'TrangThai',
        'SoLuongGhe',
        'ID_Rap',
        'HangLoiDi',
        'CotLoiDi',
    ];
    

    // Một phòng chiếu có nhiều ghế
    public function gheNgois()
    {
        return $this->hasMany(GheNgoi::class, 'ID_PhongChieu', 'ID_PhongChieu');
    }

    // Phòng chiếu thuộc về một rạp
    public function rap()
    {
        return $this->belongsTo(Rap::class, 'ID_Rap', 'ID_Rap');
    }
}
