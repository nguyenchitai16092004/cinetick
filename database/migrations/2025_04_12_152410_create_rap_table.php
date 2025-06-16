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
        Schema::create('rap', function (Blueprint $table) {
            $table->id('ID_Rap');
            $table->string('TenRap', 100);
            $table->string('Slug', 100);
            $table->string('DiaChi', 255);
            $table->boolean('TrangThai');
            $table->string("MoTa",255);
            $table->string('Hotline',20);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rap');
    }
};
