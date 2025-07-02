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
        Schema::create('tai_khoan', function (Blueprint $table) {
            $table->id('ID_TaiKhoan');
            $table->string('TenDN', 50);
            $table->string('MatKhau', 100);
            $table->integer('VaiTro');
            $table->string('token_xac_nhan',255);
            $table->boolean('TrangThai')->comment('0: Tạm dừng hoạt động, 1: Hoạt động');
            $table->unsignedBigInteger('ID_ThongTin'); 

            $table->foreign('ID_ThongTin')->references('ID_ThongTin')->on('thong_tin')->onDelete('cascade');
            $table->timestamps();
        });
    }
    


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tai_khoan');
    }
};