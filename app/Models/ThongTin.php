<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThongTin extends Model
{
    use HasFactory;

    protected $table = 'thong_tin';
    protected $primaryKey = 'ID_ThongTin';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'HoTen',
        'GioiTinh',
        'NgaySinh',
        'Email',
        'SDT',
    ];

    // Quan hệ: Thông tin có nhiều tài khoản
    public function taiKhoans()
    {
        return $this->hasMany(TaiKhoan::class, 'ID_ThongTin', 'ID_ThongTin');
    }

    public function getGioiTinhTextAttribute()
    {
        return $this->GioiTinh ? 'Nam' : 'Nữ';
    }
}