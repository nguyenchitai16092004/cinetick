<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('khuyen_mai', function (Blueprint $table) {
            $table->id('ID_KhuyenMai');
            $table->string('MaKhuyenMai', 100);
            $table->date('NgayKetThuc')->nullable();
            $table->decimal('DieuKienToiThieu', 10, 2)->nullable()->comment('Giá trị đơn hàng tối thiểu để áp dụng khuyến mãi');
            $table->decimal('GiamToiDa', 10, 2)->nullable()->comment('Giá trị giảm tối đa');
            $table->integer('PhanTramGiam');
            $table->integer('SoLuong')->default(1000000000);
            $table->integer('TongTienDaGiam')->default(0);
            $table->boolean('TrangThai');
            $table->timestamps();
        });
    }   

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_khuyen_mai');
    }
};
