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
        Schema::create('khuyen_mai', function (Blueprint $table) {
            $table->id('ID_ChiTietKM');
            $table->string('TenKhuyenMai', 100);
            $table->integer('PhanTramGiam');
            $table->decimal('GiaTriToiDa', 10, 2);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_khuyen_mai');
    }
};
