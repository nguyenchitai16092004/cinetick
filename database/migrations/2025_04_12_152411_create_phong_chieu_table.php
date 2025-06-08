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
        Schema::create('phong_chieu', function (Blueprint $table) {
            $table->id('ID_PhongChieu');
            $table->unsignedBigInteger('ID_Rap');
            $table->string('TenPhongChieu', 100);
            $table->boolean('LoaiPhong');
            $table->boolean('TrangThai');
            $table->integer('SoLuongGhe');
            $table->json('HangLoiDi')-> nullable();
            $table->json('CotLoiDi')-> nullable();
            $table->foreign('ID_Rap')->references('ID_Rap')->on('rap');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phong_chieu');
    }
};
