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
        Schema::table('thong_tin', function (Blueprint $table) {
            $table->unsignedBigInteger('ID_Rap')->nullable()->after('Luong');
            $table->foreign('ID_Rap')->references('ID_Rap')->on('rap')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thong_tin', function (Blueprint $table) {
            $table->dropForeign(['ID_Rap']);
            $table->dropColumn('ID_Rap');
        });
    }
};