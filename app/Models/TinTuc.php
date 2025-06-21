<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TinTuc extends Model
{
    protected $table = 'tin_tuc';
    protected $primaryKey = "ID_TinTuc";
    protected $fillable = [
        'TieuDe',
        'Slug',
        'NoiDung',
        'LoaiBaiViet',
        'AnhDaiDien',
        'ID_TaiKhoan',
        'LuotThich',
        'LuotXem',
        'TrangThai'
    ];
}