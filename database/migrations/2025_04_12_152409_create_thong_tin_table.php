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
        Schema::create('thong_tin', function (Blueprint $table) {
            $table->unsignedBigInteger('ID_CCCD')->primary();
            $table->string('HoTen', 100);
            $table->boolean('GioiTinh');
            $table->date('NgaySinh');
            $table->string('Email', 100);
            $table->string('SDT', 15);
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_tin');
    }
};
