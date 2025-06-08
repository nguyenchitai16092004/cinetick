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
        Schema::create('suat_chieu', function (Blueprint $table) {
            $table->id('ID_SuatChieu');
            $table->time('GioChieu');
            $table->date('NgayChieu');
            $table->decimal('GiaVe', 12, 2);
            $table->unsignedBigInteger('ID_PhongChieu');
            $table->unsignedBigInteger('ID_Phim');
            $table->unsignedBigInteger('ID_Rap');

            $table->foreign('ID_PhongChieu')->references('ID_PhongChieu')->on('phong_chieu');
            $table->foreign('ID_Phim')->references('ID_Phim')->on('phim');
            $table->foreign('ID_Rap')->references('ID_Rap')->on('rap');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suat_chieu');
    }
};
