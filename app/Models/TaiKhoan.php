<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaiKhoan extends Authenticatable
{
    use HasFactory;

    protected $table = 'tai_khoan';
    protected $primaryKey = 'ID_TaiKhoan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'TenDN',
        'MatKhau',
        'TrangThai',
        'ID_CCCD',
        'VaiTro',
        'token_xac_nhan', 
    ];


    protected $hidden = [
        'MatKhau',
    ];

    public function getAuthPassword()
    {
        return $this->MatKhau;
    }

    public function getAuthIdentifierName()
    {
        return 'TenDN';
    }

    public function thongTin()
    {
        return $this->belongsTo(ThongTin::class, 'ID_CCCD', 'ID_CCCD');
    }

    public function getVaiTroTextAttribute()
    {
        switch ($this->VaiTro) {
            case 0:
                return 'Người dùng';
            case 1:
                return 'Quản trị viên';
            default:
                return 'Không xác định';
        }
    }

    public function getTrangThaiTextAttribute()
    {
        return $this->TrangThai ? 'Hoạt động' : 'Vô hiệu';
    }
}
