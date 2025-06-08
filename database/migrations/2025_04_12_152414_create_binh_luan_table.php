<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('binh_luan', function (Blueprint $table) {
            $table->id('ID_BinhLuan');
            $table->text('NoiDung');    
            $table->tinyInteger('DiemDanhGia');
            $table->unsignedBigInteger('ID_TaiKhoan');
            $table->unsignedBigInteger('ID_Phim');
            $table->foreign('ID_TaiKhoan')->references('ID_TaiKhoan')->on('tai_khoan');
            $table->foreign('ID_Phim')->references('ID_Phim')->on('phim');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binh_luan');
    }
};
