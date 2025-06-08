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
            $table->text('NoiDung');
            $table->string('HinhAnh', 255)->nullable();
            $table->unsignedBigInteger('ID_TaiKhoan');
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
