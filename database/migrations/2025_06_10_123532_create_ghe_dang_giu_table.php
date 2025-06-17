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
        Schema::create('ghe_dang_giu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_Ghe');
            $table->unsignedBigInteger('ID_SuatChieu');
            $table->unsignedBigInteger('ID_TaiKhoan');
            $table->timestamp('hold_until');
            $table->timestamps();
            $table->unique(['ID_Ghe', 'ID_SuatChieu']);

            $table->foreign('ID_Ghe')->references('ID_Ghe')->on('ghe_ngoi')->onDelete('cascade');
            $table->foreign('ID_SuatChieu')->references('ID_SuatChieu')->on('suat_chieu')->onDelete('cascade');
            $table->foreign('ID_TaiKhoan')->references('ID_TaiKhoan')->on('tai_khoan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ghe_dang_giu');
    }
};