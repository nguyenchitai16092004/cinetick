<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rap extends Model
{
    use HasFactory;

    protected $table = 'rap';

    protected $primaryKey = 'ID_Rap';

    protected $fillable = [
        'TenRap', 'Slug', 'DiaChi', 'TrangThai', 'MoTa', 'Hotline'
    ];


    public function phongChieu()
    {
        return $this->hasMany(PhongChieu::class, 'ID_Rap', 'ID_Rap');
    }
    public function suatChieu()
    {
        return $this->belongsTo(Rap::class, 'ID_Rap', 'ID_Rap');
    }
}

