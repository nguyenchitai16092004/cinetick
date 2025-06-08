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
        Schema::create('the_loai_cua_phim', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_Phim');
            $table->unsignedBigInteger('ID_TheLoaiPhim');

            $table->foreign('ID_Phim')->references('ID_Phim')->on('phim')->onDelete('cascade');
            $table->foreign('ID_TheLoaiPhim')->references('ID_TheLoaiPhim')->on('the_loai_phim')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('the_loai_cua_phim');
    }
};
