<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TinTuc extends Model
{
    protected $table = 'tin_tuc';
   protected $primaryKey = "ID_TinTuc";
    // Các trường được phép gán hàng loạt
    protected $fillable = [
        'TieuDe',
        'NoiDung',
        'LoaiBaiViet',
        'HinhAnh',
        'ID_TaiKhoan',
    ];
}
