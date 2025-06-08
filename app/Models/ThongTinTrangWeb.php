<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongTinTrangWeb extends Model
{
    protected $table = 'thong_tin_trang_web';
    protected $primaryKey = 'Id';
    public $incrementing = false;

    protected $fillable = [
        'Logo',
        'Hotline',
        'Zalo',
        'Facebook',
        'Instagram',
        'Email',
        'DiaChi',
    ];

    public $timestamps = true;
}
