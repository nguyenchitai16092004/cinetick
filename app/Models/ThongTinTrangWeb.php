<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongTinTrangWeb extends Model
{
    protected $table = 'thong_tin_trang_web';
    protected $primaryKey = 'Id';
    public $incrementing = false;

    protected $fillable = [
        'TenDonVi',
        'TenWebsite',
        'Logo',
        'Icon',
        'Hotline',
        'Zalo',
        'Facebook',
        'Instagram',
        'Youtube',
        'Email',
        'DiaChi',
    ];

    public $timestamps = true;
}