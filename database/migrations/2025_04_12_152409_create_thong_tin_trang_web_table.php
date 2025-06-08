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
        Schema::create('thong_tin_trang_web', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Logo', 255)->nullable();
            $table->string('Zalo', 50)->nullable();
            $table->string('Facebook', 100)->nullable();
            $table->string('Instagram', 100)->nullable();
            $table->string('Email', 100)->nullable();
            $table->string('DiaChi', 255)->nullable();
            $table->string('Hotline', 15)->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_tin_trang_web');
    }
};