<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GheNgoi extends Model
{
    use HasFactory;

    protected $table = 'ghe_ngoi';
    protected $primaryKey = 'ID_Ghe';

    protected $fillable = [
        'TenGhe',
        'LoaiTrangThaiGhe',
        'ID_PhongChieu',
    ];

    // Ghế thuộc về một phòng chiếu
    public function phongChieu()
    {
        return $this->belongsTo(PhongChieu::class, 'ID_PhongChieu', 'ID_PhongChieu');
    }
}
