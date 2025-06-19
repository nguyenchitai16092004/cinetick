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
        Schema::create('tin_tuc', function (Blueprint $table) {
            $table->id('ID_TinTuc');
            $table->string('TieuDe', 100);
            $table->string('Slug', 255);
            $table->longText('NoiDung');
            $table->boolean('LoaiBaiViet');
            $table->integer('LuotThich')->default(0);
            $table->integer('LuotXem')->default(0);
            $table->string('AnhDaiDien', 255);
            $table->unsignedBigInteger('ID_TaiKhoan');
            $table->boolean('TrangThai')->comment('0: Chờ xuất bản, 1: Xuất bản');;
            $table->foreign('ID_TaiKhoan')->references('ID_TaiKhoan')->on('tai_khoan')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tin_tuc');
    }
};