<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelPendingReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservasi:cancel-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan reservasi yang belum dibayar setelah 10 menit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai memeriksa reservasi yang tertunda...');

        // Tentukan batas waktu (10 menit yang lalu)
        $limit = Carbon::now()->subMinutes(10);

        // Cari reservasi yang memenuhi kriteria untuk dibatalkan:
        // 1. Dibuat lebih dari 10 menit yang lalu
        // 2. Tipe 'Online'
        // 3. Status masih 'Dipesan'
        // 4. Pembayaran masih 'Belum Bayar DP'
        $pendingReservations = Reservasi::where('tipe', 'Online')
            ->where('statusReservasi', 'Dipesan')
            ->whereHas('pesanan', function ($query) {
                $query->where('statusPembayaran', 'Belum Bayar DP');
            })
            ->where('created_at', '<=', $limit)
            ->get();

        if ($pendingReservations->isEmpty()) {
            $this->info('Tidak ada reservasi tertunda yang perlu dibatalkan.');
            return;
        }

        $this->info($pendingReservations->count() . ' reservasi ditemukan untuk dibatalkan.');

        foreach ($pendingReservations as $reservasi) {
            $reservasi->statusReservasi = 'Dibatalkan';
            $reservasi->pesanan->statusPembayaran = 'Dibatalkan';
            $reservasi->save();
            $reservasi->pesanan->save();
            
            Log::info('Reservasi ' . $reservasi->kodeReservasi . ' otomatis dibatalkan karena pembayaran tertunda.');
            $this->info('Reservasi ' . $reservasi->kodeReservasi . ' berhasil dibatalkan.');
        }

        $this->info('Proses selesai.');
    }
}