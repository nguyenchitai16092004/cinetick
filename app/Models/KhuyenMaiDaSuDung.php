<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhuyenMaiDaSuDung extends Model
{
    protected $table = 'khuyen_mai_da_su_dung';

    // Nếu bạn dùng tên cột id mặc định là `id`, không cần khai báo khóa chính
    protected $primaryKey = 'id';

    public $timestamps = false; // Bảng này không có created_at / updated_at

    protected $fillable = [
        'ID_TaiKhoan',
        'ID_KhuyenMai',
    ];

    // Quan hệ với bảng tai_khoan
    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'ID_TaiKhoan', 'ID_TaiKhoan');
    }

    // Quan hệ với bảng khuyen_mai
    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'ID_KhuyenMai', 'ID_KhuyenMai');
    }
}
