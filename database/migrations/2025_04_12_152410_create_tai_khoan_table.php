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
            $table->boolean('TrangThai');
            $table->unsignedBigInteger('ID_CCCD');

            $table->foreign('ID_CCCD')->references('ID_CCCD')->on('thong_tin')->onDelete('cascade');
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
