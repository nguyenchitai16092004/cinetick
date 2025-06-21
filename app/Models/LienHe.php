<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LienHe extends Model
{
    protected $table = 'lien_he';
    protected $primaryKey = "ID_LienHe";
    protected $fillable = [
        'HoTenNguoiLienHe',
        'Email',
        'SDT',
        'TieuDe',
        'NoiDung',
        'AnhMinhHoa',
        'TrangThai',
    ];
}