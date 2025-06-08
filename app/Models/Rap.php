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
        'TenRap', 'DiaChi', 'TrangThai'
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

