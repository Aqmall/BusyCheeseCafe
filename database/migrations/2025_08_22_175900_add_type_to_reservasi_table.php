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
        Schema::table('reservasi', function (Blueprint $table) {
            // Tambahkan kolom 'tipe' setelah 'statusReservasi'
            // 'Online' untuk reservasi dari web, 'Walk-in' untuk yang dibuat admin
            $table->enum('tipe', ['Online', 'Walk-in'])->default('Online')->after('statusReservasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};