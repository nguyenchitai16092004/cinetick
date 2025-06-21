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
        Schema::create('lien_he', function (Blueprint $table) {
            $table->id('ID_LienHe');
            $table->string('HoTenNguoiLienHe', 255);
            $table->string('Email',255);
            $table->string('SDT',11)->nullable();
            $table->string('TieuDe',255);
            $table->text('NoiDung');
            $table->string('AnhMinhHoa',255)->nullable();
            $table->boolean('TrangThai')->comment('0: Chờ xử lý, 1: Đã xử lý')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lien_he');
    }
};