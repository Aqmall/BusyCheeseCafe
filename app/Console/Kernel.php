<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Daftarkan kedua command otomatis di sini
        Commands\CancelPendingReservations::class,
        Commands\CancelNoShowReservations::class, // Command baru ditambahkan
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Jalankan command untuk membatalkan pembayaran tertunda setiap menit
        $schedule->command('reservasi:cancel-pending')->everyMinute();

        // Jalankan command untuk membatalkan no-show setiap hari pukul 01:00 pagi
        $schedule->command('reservasi:cancel-no-show')->dailyAt('01:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}