<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelNoShowReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservasi:cancel-no-show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan reservasi (no-show) dari hari sebelumnya yang belum check-in';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai memeriksa reservasi no-show dari hari kemarin...');

        // Tentukan tanggal target (kemarin)
        $yesterday = Carbon::yesterday()->toDateString();

        // Cari reservasi yang memenuhi kriteria untuk dibatalkan (no-show):
        // 1. Jadwalnya adalah kemarin
        // 2. Statusnya masih 'Dipesan' (belum pernah check-in atau selesai)
        // 3. Status pembayarannya 'Sudah Bayar DP'
        $noShowReservations = Reservasi::where('tanggal', $yesterday)
            ->where('statusReservasi', 'Dipesan')
            ->whereHas('pesanan', function ($query) {
                $query->where('statusPembayaran', 'Sudah Bayar DP');
            })
            ->get();

        if ($noShowReservations->isEmpty()) {
            $this->info('Tidak ada reservasi no-show yang ditemukan.');
            return;
        }

        $this->info($noShowReservations->count() . ' reservasi no-show ditemukan untuk dibatalkan.');

        foreach ($noShowReservations as $reservasi) {
            $reservasi->statusReservasi = 'Dibatalkan';
            $reservasi->pesanan->statusPembayaran = 'Dibatalkan (DP Masuk)'; // Tandai bahwa DP tidak dikembalikan
            $reservasi->save();
            $reservasi->pesanan->save();
            
            Log::info('Reservasi ' . $reservasi->kodeReservasi . ' otomatis dibatalkan karena no-show.');
            $this->info('Reservasi ' . $reservasi->kodeReservasi . ' berhasil dibatalkan (no-show).');
        }

        $this->info('Proses pembatalan no-show selesai.');
    }
}