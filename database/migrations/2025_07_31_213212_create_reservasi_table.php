<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id();
            $table->string('kodeReservasi')->unique();
            $table->string('namaPelanggan');
            $table->string('noTelepon');
            $table->string('email');
            $table->integer('jumlahTamu');
            $table->date('tanggal');
            $table->time('waktu');
            $table->enum('statusReservasi', ['Dipesan', 'Selesai', 'Dibatalkan', 'Check-in'])->default('Dipesan');
            
            $table->string('nomorMeja');
            $table->foreign('nomorMeja')->references('nomorMeja')->on('meja');
            
            $table->foreignId('pesanan_id')->constrained('pesanan');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasi');
    }
};