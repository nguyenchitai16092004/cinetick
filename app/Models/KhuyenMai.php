<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhuyenMai extends Model
{
    use HasFactory;

    protected $table = 'khuyen_mai';
    protected $primaryKey = 'ID_KhuyenMai';
    public $timestamps = true;

    protected $fillable = [
        'MaKhuyenMai',
        'DieuKienToiThieu',
        'PhanTramGiam',
        'GiamToiDa',
        'NgayKetThuc',
    ];
}