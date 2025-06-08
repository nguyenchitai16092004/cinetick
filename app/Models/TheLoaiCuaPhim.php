<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TheLoaiCuaPhim extends Model
{
    protected $table = 'the_loai_cua_phim';

    public function phim()
    {
        return $this->belongsToMany(Phim::class, 'the_loai_cua_phim', 'ID_TheLoaiPhim', 'ID_Phim');
    }
}
