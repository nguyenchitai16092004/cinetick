<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    use HasFactory;

    protected $table = 'binh_luan';
    protected $primaryKey = 'ID_BinhLuan';
    
    protected $fillable = [
        'NoiDung',
        'DiemDanhGia',
        'TrangThai',
        'ID_TaiKhoan',
        'ID_Phim'
    ];

    protected $casts = [
        'DiemDanhGia' => 'integer',
        'TrangThai' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Trạng thái mặc định là hiển thị
    protected $attributes = [
        'TrangThai' => 1,
    ];

    // Quan hệ với bảng tài khoản
    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'ID_TaiKhoan', 'ID_TaiKhoan');
    }

    // Quan hệ với bảng phim
    public function phim()
    {
        return $this->belongsTo(Phim::class, 'ID_Phim', 'ID_Phim');
    }

    // Scope để lọc theo phim
    public function scopeByPhim($query, $phimId)
    {
        return $query->where('ID_Phim', $phimId);
    }

    // Scope để tìm kiếm theo nội dung
    public function scopeSearch($query, $keyword)
    {
        return $query->where('NoiDung', 'LIKE', '%' . $keyword . '%');
    }

    // Accessor để lấy trạng thái dạng text
    public function getTrangThaiTextAttribute()
    {
        return $this->TrangThai == 1 ? 'Hiển thị' : 'Ẩn';
    }

    // Accessor để lấy điểm đánh giá với định dạng
    public function getDiemDanhGiaFormattedAttribute()
    {
        return $this->DiemDanhGia ? $this->DiemDanhGia . '/10' : 'Chưa đánh giá';
    }

    // Method để toggle trạng thái
    public function toggleStatus()
    {
        $this->TrangThai = $this->TrangThai == 1 ? 0 : 1;
        return $this->save();
    }
}