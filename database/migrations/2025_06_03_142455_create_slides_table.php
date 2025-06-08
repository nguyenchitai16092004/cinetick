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
        Schema::create('slides', function (Blueprint $table) {
            $table->id(); // Id
            $table->string('TieuDe'); // Title
            $table->text('MoTa')->nullable(); // Description
            $table->string('DuongDanAnh'); // Image URL
            $table->string('DuongDanLienKet')->nullable(); // Link URL
            $table->integer('ThuTuHienThi')->default(0); // Display order
            $table->boolean('HienThi')->default(true); // Is active
            $table->timestamps(); // Created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
