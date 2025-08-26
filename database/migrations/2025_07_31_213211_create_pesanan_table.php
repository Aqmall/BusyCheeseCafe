<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->decimal('totalTagihan', 10, 2)->default(0);
            $table->decimal('totalDP', 10, 2)->default(0);
            $table->decimal('sisaTagihan', 10, 2)->default(0);
            $table->enum('statusPembayaran', ['Belum Bayar DP', 'Sudah Bayar DP', 'Lunas', 'Dibatalkan'])->default('Belum Bayar DP');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};