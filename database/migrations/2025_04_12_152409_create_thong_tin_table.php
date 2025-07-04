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
            $table->id('ID_ThongTin');
            $table->string('HoTen', 100);
            $table->boolean('GioiTinh');
            $table->date('NgaySinh');
            $table->string('Email', 100);
            $table->string('SDT', 11);

            // Cột Luong (nullable)
            $table->decimal('Luong', 15, 2)->nullable();

            // Cột ID_Rap (nullable) và khóa ngoại
            $table->unsignedBigInteger('ID_Rap')->nullable();
            $table->foreign('ID_Rap')->references('ID_Rap')->on('rap')->onDelete('set null');

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
