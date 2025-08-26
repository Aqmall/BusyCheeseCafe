<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationFlowController extends Controller
{
    public function startFlow(Request $request)
    {
        $maxDate = Carbon::now()->addMonth()->toDateString();

        $validated = $request->validate([
            'tanggal'    => 'required|date|after_or_equal:today|before_or_equal:' . $maxDate,
            'waktu'      => 'required',
            'jumlahTamu' => 'required|integer|min:1',
        ]);
        
        $request->session()->put('reservation_data', $validated);
        return redirect()->route('reservasi.flow.meja.show');
    }

    public function showStepMeja(Request $request)
    {
        $reservationData = $request->session()->get('reservation_data');
        if (!$reservationData) {
            return redirect()->route('home');
        }
    
        // 1. Ambil semua meja dari database
        $mejas = Meja::all();
        
        // 2. Cari semua nomor meja yang sudah direservasi pada tanggal dan waktu yang dipilih
        //    dan statusnya bukan 'Dibatalkan' atau 'Selesai'.
        $bookedMejaNumbers = Reservasi::where('tanggal', $reservationData['tanggal'])
            ->where('waktu', $reservationData['waktu'])
            ->whereNotIn('statusReservasi', ['Dibatalkan', 'Selesai'])
            ->pluck('nomorMeja')
            ->toArray();
    
        // 3. Perbarui status meja secara dinamis untuk dikirim ke view
        foreach ($mejas as $meja) {
            if (in_array($meja->nomorMeja, $bookedMejaNumbers)) {
                // Jika meja sudah ada di daftar reservasi aktif, set statusnya menjadi 'Dipesan'
                $meja->statusMeja = 'Dipesan';
            } else {
                // Jika tidak, statusnya adalah 'Tersedia'
                // Logika untuk status 'Terisi' akan ditangani oleh admin secara real-time,
                // dari perspektif pelanggan, meja yang terisi sama dengan sudah dipesan.
                $meja->statusMeja = 'Tersedia';
            }
        }
    
        // Jika tidak ada meja yang tersedia sama sekali, kembalikan dengan pesan error
        $availableMejasCount = $mejas->where('statusMeja', 'Tersedia')
                                     ->where('kapasitas', '>=', $reservationData['jumlahTamu'])
                                     ->count();

        if ($availableMejasCount === 0) {
            return redirect()->route('home')->with('error', 'Maaf, tidak ada meja yang tersedia untuk tanggal, waktu, dan jumlah tamu yang Anda pilih. Silakan coba jadwal lain.');
        }

        return view('reservasi.pilih-meja', compact('reservationData', 'mejas'));
    }

    public function storeStepMeja(Request $request)
    {
        $validated                     = $request->validate(['nomorMeja' => 'required|exists:meja,nomorMeja']);
        $reservationData               = $request->session()->get('reservation_data');

        // Validasi Ulang Tepat Sebelum Menyimpan
        // Ini adalah lapisan pertahanan kedua terhadap race condition
        $isBooked = Reservasi::where('tanggal', $reservationData['tanggal'])
            ->where('waktu', $reservationData['waktu'])
            ->where('nomorMeja', $validated['nomorMeja'])
            ->whereNotIn('statusReservasi', ['Dibatalkan', 'Selesai'])
            ->exists();

        if ($isBooked) {
            return redirect()->back()->withErrors(['nomorMeja' => 'Maaf, meja ini baru saja dipesan oleh orang lain. Silakan pilih meja lain.']);
        }

        $reservationData['nomorMeja']  = $validated['nomorMeja'];
        $request->session()->put('reservation_data', $reservationData);
        return redirect()->route('reservasi.flow.menu.show');
    }

    public function showStepMenu(Request $request)
    {
        $reservationData = $request->session()->get('reservation_data');
        if (!$reservationData || !isset($reservationData['nomorMeja'])) return redirect()->route('home');
        
        $menus = Menu::orderBy('kategori')->get()->groupBy('kategori');
        return view('reservasi.pilih-menu', compact('reservationData', 'menus'));
    }

    public function storeStepMenu(Request $request)
    {
        $validated   = $request->validate(['menu_items' => 'present|array']);
        $menuDipesan = [];
        if (isset($validated['menu_items'])) {
            foreach ($validated['menu_items'] as $menu_id => $item) {
                if (!empty($item['jumlah']) && $item['jumlah'] > 0) {
                    $menuDipesan[$menu_id] = $item;
                }
            }
        }

        if (empty($menuDipesan)) {
            return back()->withInput()->withErrors(['menu_items' => 'Pilih minimal satu menu untuk melanjutkan.']);
        }

        $reservationData                 = $request->session()->get('reservation_data');
        $reservationData['menu_items'] = $menuDipesan;
        $request->session()->put('reservation_data', $reservationData);
        return redirect()->route('reservasi.flow.datadiri.show');
    }

    public function showStepDataDiri(Request $request)
    {
        $reservationData = $request->session()->get('reservation_data');
        if (!$reservationData || !isset($reservationData['menu_items'])) return redirect()->route('home');
        
        $menuIds       = array_keys($reservationData['menu_items']);
        $selectedMenus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');
        return view('reservasi.data-diri', compact('reservationData', 'selectedMenus'));
    }

    public function storeStepDataDiri(Request $request)
    {
        $validated = $request->validate([
            'namaPelanggan' => 'required|string|max:255',
            'noTelepon'     => 'required|string|max:15',
            'email'         => 'required|email',
        ]);
        $reservationData = $request->session()->get('reservation_data');

        DB::beginTransaction();
        try {
            // Validasi akhir untuk mencegah double booking sebelum transaksi
            $isBooked = Reservasi::where('tanggal', $reservationData['tanggal'])
                ->where('waktu', $reservationData['waktu'])
                ->where('nomorMeja', $reservationData['nomorMeja'])
                ->whereNotIn('statusReservasi', ['Dibatalkan', 'Selesai'])
                ->lockForUpdate() // Kunci baris untuk mencegah race condition
                ->exists();

            if ($isBooked) {
                DB::rollBack();
                return redirect()->route('reservasi.flow.meja.show')->withErrors(['nomorMeja' => 'Maaf, meja ini baru saja dipesan saat Anda mengisi form. Silakan pilih meja lain.']);
            }

            $pesanan                   = new Pesanan();
            $pesanan->statusPembayaran = 'Belum Bayar DP';
            $pesanan->save();
            $totalTagihan              = 0;
            $menuIds                   = array_keys($reservationData['menu_items']);
            $selectedMenus             = Menu::whereIn('id', $menuIds)->get()->keyBy('id');

            foreach ($reservationData['menu_items'] as $menu_id => $item) {
                $menu     = $selectedMenus[$menu_id];
                $subTotal = $menu->harga * $item['jumlah'];
                DetailPesanan::create(['pesanan_id' => $pesanan->id, 'menu_id' => $menu->id, 'jumlah' => $item['jumlah'], 'subTotal' => $subTotal]);
                $totalTagihan += $subTotal;
            }

            $totalDP               = $totalTagihan * 0.50;
            $pesanan->totalTagihan = $totalTagihan;
            $pesanan->totalDP      = $totalDP;
            $pesanan->sisaTagihan  = $totalTagihan - $totalDP;
            $pesanan->save();

            $reservasi                = new Reservasi();
            $reservasi->kodeReservasi = 'BC-' . strtoupper(Str::random(8));
            $reservasi->namaPelanggan = $validated['namaPelanggan'];
            $reservasi->noTelepon     = $validated['noTelepon'];
            $reservasi->email         = $validated['email'];
            $reservasi->jumlahTamu    = $reservationData['jumlahTamu'];
            $reservasi->tanggal       = $reservationData['tanggal'];
            $reservasi->waktu         = $reservationData['waktu'];
            $reservasi->nomorMeja     = $reservationData['nomorMeja'];
            $reservasi->pesanan_id    = $pesanan->id;
            $reservasi->statusReservasi = 'Dipesan'; // Eksplisit set status
            $reservasi->save();
            $request->session()->put('final_reservation_code', $reservasi->kodeReservasi);
            
            // Status meja di database tidak diubah di sini, karena status 'Dipesan'
            // sekarang bersifat dinamis berdasarkan jadwal reservasi.
            // Meja::find($reservationData['nomorMeja'])->update(['statusMeja' => 'Dipesan']);

            DB::commit();
            return redirect()->route('reservasi.flow.pembayaran.show');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    public function showStepPembayaran(Request $request)
    {
        $kodeReservasi = $request->session()->get('final_reservation_code');
        if (!$kodeReservasi) return redirect()->route('home');
        
        $reservasi = Reservasi::where('kodeReservasi', $kodeReservasi)->firstOrFail();
        return view('reservasi.pembayaran', compact('reservasi'));
    }

    public function storeStepPembayaran(Request $request)
    {
        $kodeReservasi = $request->session()->get('final_reservation_code');
        $reservasi = Reservasi::where('kodeReservasi', $kodeReservasi)->firstOrFail();
        
        $reservasi->pesanan->statusPembayaran = 'Sudah Bayar DP';
        $reservasi->pesanan->save();

        $request->session()->forget('reservation_data');

        return redirect()->route('reservasi.flow.sukses');
    }

    public function success(Request $request)
    {
        $kodeReservasi = $request->session()->get('final_reservation_code');
        if (!$kodeReservasi) return redirect()->route('home');

        $reservasi = Reservasi::where('kodeReservasi', $kodeReservasi)->firstOrFail();
        $request->session()->forget('final_reservation_code');

        return view('reservasi.sukses', compact('reservasi'));
    }
    
    public function showStruk(Reservasi $reservasi)
    {
        return view('reservasi.struk', compact('reservasi'));
    }
}