<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GheDangGiu extends Model
{
    use HasFactory;

    protected $table = 'ghe_dang_giu';

    protected $fillable = [
        'ID_Ghe',
        'ID_SuatChieu',
        'ID_TaiKhoan',
        'hold_until',
    ];

    protected $casts = [
        'hold_until' => 'datetime',
    ];

    // Quan hệ với User (nếu cần)
    public function user()
    {
        return $this->belongsTo(User::class, 'ID_TaiKhoan');
    }

    // Quan hệ với suất chiếu (nếu có model)
    public function suatChieu()
    {
        return $this->belongsTo(SuatChieu::class, 'ID_SuatChieu');
    }
}