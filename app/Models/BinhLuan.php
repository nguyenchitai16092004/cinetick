<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    use HasFactory;

    protected $table = 'binh_luan';
    protected $primaryKey = 'ID_BinhLuan';
    public $timestamps = true;
    protected $fillable = [
        'ID_Phim',
        'ID_TaiKhoan',
        'DiemDanhGia',
        'created_at',
        'updated_at'
    ];

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'ID_TaiKhoan', 'ID_TaiKhoan');
    }

    public function phim()
    {
        return $this->belongsTo(Phim::class, 'ID_Phim', 'ID_Phim');
    }

    public function scopeByPhim($query, $phimId)
    {
        return $query->where('ID_Phim', $phimId);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('NoiDung', 'LIKE', '%' . $keyword . '%');
    }

    public function getTrangThaiTextAttribute()
    {
        return $this->TrangThai == 1 ? 'Hiển thị' : 'Ẩn';
    }

    public function getDiemDanhGiaFormattedAttribute()
    {
        return $this->DiemDanhGia ? $this->DiemDanhGia . '/10' : 'Chưa đánh giá';
    }

    public function toggleStatus()
    {
        $this->TrangThai = $this->TrangThai == 1 ? 0 : 1;
        return $this->save();
    }
}