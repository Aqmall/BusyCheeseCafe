<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Reservasi; // Import model Reservasi
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalkinController extends Controller
{
    /**
     * Menampilkan form untuk membuat pesanan walk-in.
     * (Tidak ada perubahan di sini)
     */
    public function create()
    {
        $mejas = Meja::where('statusMeja', 'Tersedia')->get();
        $menus = Menu::all();
        return view('admin.walkin.create', compact('mejas', 'menus'));
    }

    /**
     * Menyimpan pesanan walk-in baru ke database.
     * (Logika dirombak total)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomorMeja' => 'required|exists:meja,nomorMeja',
            'namaPelanggan' => 'required|string|max:255',
            'jumlahTamu' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat entri Pesanan terlebih dahulu
            $pesanan = new Pesanan();
            $pesanan->statusPembayaran = 'Lunas'; // Asumsi walk-in langsung bayar lunas
            $pesanan->totalTagihan = 0; // Total awal 0, pesanan ditambahkan nanti
            $pesanan->totalDP = 0;
            $pesanan->sisaTagihan = 0;
            $pesanan->save();

            // 2. Buat entri Reservasi untuk mencatat data pelanggan walk-in
            $reservasi = new Reservasi();
            $reservasi->kodeReservasi = 'WI-' . strtoupper(Str::random(8)); // 'WI' untuk Walk-In
            $reservasi->namaPelanggan = $request->namaPelanggan;
            $reservasi->noTelepon = '-'; // Bisa dikosongkan atau diisi opsional
            $reservasi->email = 'walkin@busycheese.com'; // Default email untuk walk-in
            $reservasi->jumlahTamu = $request->jumlahTamu;
            $reservasi->tanggal = today(); // Tanggal hari ini
            $reservasi->waktu = now()->format('H:i:s'); // Waktu saat ini
            $reservasi->nomorMeja = $request->nomorMeja;
            $reservasi->pesanan_id = $pesanan->id;
            $reservasi->statusReservasi = 'Check-in'; // Walk-in langsung berstatus 'Check-in'
            $reservasi->tipe = 'Walk-in'; // Tandai sebagai 'Walk-in'
            $reservasi->save();

            // Status meja tidak perlu diubah di sini karena denah meja sudah dinamis
            // dan akan otomatis menampilkan status 'Terisi' berdasarkan reservasi 'Check-in'

            DB::commit();
            
            // Arahkan ke halaman detail reservasi walk-in yang baru dibuat untuk menambah pesanan
            return redirect()->route('admin.reservasi.show', $reservasi->id)->with('success', 'Walk-in untuk meja ' . $request->nomorMeja . ' berhasil dibuat. Silakan tambahkan pesanan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pesanan walk-in: ' . $e->getMessage());
        }
    }
}