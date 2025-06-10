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
            $table->string('ma_ghe');
            $table->unsignedBigInteger('suat_chieu_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('hold_until');
            $table->timestamps();

            $table->unique(['ma_ghe', 'suat_chieu_id']); // một ghế/suất chỉ được giữ 1 lần
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