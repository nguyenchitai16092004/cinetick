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
        Schema::create('held_seats', function (Blueprint $table) {
            $table->id();
            $table->integer('ID_SuatChieu');
            $table->string('TenGhe');
            $table->integer('user_id');
            $table->timestamp('held_until');
            $table->timestamps();
            
            $table->index(['ID_SuatChieu', 'TenGhe']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('held_seats');
    }
};
