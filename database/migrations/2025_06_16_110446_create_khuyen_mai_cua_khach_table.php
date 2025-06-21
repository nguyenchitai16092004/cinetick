<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('khuyen_mai_da_su_dung', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_TaiKhoan');
            $table->unsignedBigInteger('ID_KhuyenMai');

            $table->foreign('ID_TaiKhoan')->references('ID_TaiKhoan')->on('tai_khoan')->onDelete('cascade');
            $table->foreign('ID_KhuyenMai')->references('ID_KhuyenMai')->on('khuyen_mai')->onDelete('cascade');
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khuyen_mai_cua_khach');
    }
};
