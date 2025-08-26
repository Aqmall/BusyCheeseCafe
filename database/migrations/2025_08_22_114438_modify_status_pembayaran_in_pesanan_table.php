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
        // Ubah kolom enum untuk menambahkan status baru
        Schema::table('pesanan', function (Blueprint $table) {
            $table->enum('statusPembayaran', [
                'Belum Bayar DP', 
                'Sudah Bayar DP', 
                'Lunas', 
                'Dibatalkan',
                'Dibatalkan (DP Masuk)' // <-- Tambahkan nilai baru ini
            ])->default('Belum Bayar DP')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke definisi lama jika migrasi di-rollback
        Schema::table('pesanan', function (Blueprint $table) {
             $table->enum('statusPembayaran', [
                'Belum Bayar DP', 
                'Sudah Bayar DP', 
                'Lunas', 
                'Dibatalkan'
            ])->default('Belum Bayar DP')->change();
        });
    }
};