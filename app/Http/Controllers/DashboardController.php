<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meja;
use App\Models\Reservasi;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua data meja
        $mejas = Meja::all();

        // Ambil data reservasi yang relevan untuk hari ini
        $reservasis = Reservasi::where('tanggal', today())
                                ->with('pesanan.detailPesanan.menu') // Eager load relasi
                                ->get();

        // Buat pemetaan reservasi berdasarkan nomor meja untuk akses mudah di JavaScript
        $reservasiMap = $reservasis->keyBy('nomorMeja');

        // Pastikan semua key yang dibutuhkan oleh view ada di sini
        $stats = [
            'reservasiHariIni' => $reservasis->count(),
            'sudahCheckIn' => $reservasis->where('statusReservasi', 'Check-in')->count(),
            'mejaTerisi' => $mejas->where('statusMeja', 'Terisi')->count(),
            'pendapatanHariIni' => Pesanan::whereHas('reservasi', function ($query) {
                $query->where('tanggal', today())->where('statusReservasi', 'Selesai');
            })->sum('totalTagihan'),
        ];

        // Mock data untuk aktivitas terkini
        $recentActivity = [
            ['type' => 'reservation', 'message' => 'Reservasi baru dari Aqmal Raditya', 'time' => '2 menit lalu'],
            ['type' => 'checkin', 'message' => 'Meja 3 Check-in', 'time' => '5 menit lalu'],
            ['type' => 'payment', 'message' => 'Pembayaran Lunas Meja 7', 'time' => '8 menit lalu'],
        ];

        return view('dashboard.index', compact('mejas', 'reservasiMap', 'stats', 'recentActivity'));
    }
}