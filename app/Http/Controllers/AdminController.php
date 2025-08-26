<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Reservasi;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman utama dasbor.
     */
    public function index()
    {
        // --- LOGIKA DENAH MEJA REAL-TIME ---
        $mejas = Meja::all();
        $reservasiHariIni = Reservasi::where('tanggal', today())
            ->whereNotIn('statusReservasi', ['Dibatalkan', 'Selesai'])
            ->with('pesanan')
            ->get();
        $reservasiMap = $reservasiHariIni->keyBy('nomorMeja');
        foreach ($mejas as $meja) {
            $reservasiPadaMeja = $reservasiMap->get($meja->nomorMeja);
            if ($reservasiPadaMeja) {
                $meja->statusMeja = ($reservasiPadaMeja->statusReservasi == 'Check-in') ? 'Terisi' : 'Dipesan';
            } else {
                $meja->statusMeja = 'Tersedia';
            }
        }

        // --- PENGAMBILAN DATA STATISTIK ---
        $stats = [
            'reservasiHariIni' => $reservasiHariIni->where('statusReservasi', 'Dipesan')->count(),
            'sudahCheckIn' => $reservasiHariIni->where('statusReservasi', 'Check-in')->count(),
            'mejaTerisi' => $mejas->where('statusMeja', 'Terisi')->count(),
            'pendapatanHariIni' => Pesanan::whereHas('reservasi', function ($query) {
                $query->where('tanggal', today())->where('statusReservasi', 'Selesai');
            })->sum('totalTagihan'),
            'reservasiAkanDatang' => Reservasi::where('tanggal', '>', today())->where('statusReservasi', 'Dipesan')->count(),
            'batalReservasi' => Reservasi::where('tanggal', today())->where('statusReservasi', 'Dibatalkan')->count(),
        ];
        
        // --- MENGAMBIL DATA AKTIVITAS TERKINI ---
        $recentActivity = ActivityLog::with('user')->latest()->take(5)->get();

        // --- MENCARI RESERVASI YANG AKAN BERAKHIR ---
        $expiringReservations = Reservasi::where('statusReservasi', 'Check-in')
            ->whereNotNull('check_in_at')
            ->where('check_in_at', '<', Carbon::now()->subMinutes(50))
            ->get();

        return view('dashboard.index', compact('mejas', 'reservasiMap', 'stats', 'recentActivity', 'expiringReservations'));
    }

    /**
     * Menampilkan halaman detail/kelola untuk satu reservasi.
     */
    public function show(Reservasi $reservasi)
    {
        $menus = Menu::all();
        return view('admin.show', compact('reservasi', 'menus'));
    }

    /**
     * Memproses aksi check-in.
     */
    public function checkin(Reservasi $reservasi)
    {
        DB::beginTransaction();
        try {
            $reservasi->statusReservasi = 'Check-in';
            $reservasi->check_in_at = now();
            $reservasi->save();

            $reservasi->pesanan->statusPembayaran = 'Lunas';
            $reservasi->pesanan->sisaTagihan = 0; 
            $reservasi->pesanan->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Melakukan check-in untuk reservasi ' . $reservasi->kodeReservasi . ' (Pelanggan: ' . $reservasi->namaPelanggan . ')'
            ]);

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Reservasi ' . $reservasi->kodeReservasi . ' berhasil Check-in dan ditandai Lunas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal melakukan check-in.');
        }
    }

    /**
     * Memproses aksi pembatalan reservasi.
     */
    public function cancel(Reservasi $reservasi)
    {
        if ($reservasi->statusReservasi != 'Dipesan') {
            return redirect()->route('admin.dashboard')->with('error', 'Hanya reservasi yang berstatus "Dipesan" yang dapat dibatalkan.');
        }
        DB::beginTransaction();
        try {
            $reservasi->statusReservasi = 'Dibatalkan';
            $reservasi->save();

            if ($reservasi->pesanan->statusPembayaran == 'Sudah Bayar DP') {
                $reservasi->pesanan->statusPembayaran = 'Dibatalkan (DP Masuk)';
            } else {
                $reservasi->pesanan->statusPembayaran = 'Dibatalkan';
            }
            $reservasi->pesanan->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Membatalkan reservasi ' . $reservasi->kodeReservasi . ' (Pelanggan: ' . $reservasi->namaPelanggan . ')'
            ]);

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Reservasi ' . $reservasi->kodeReservasi . ' berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan reservasi.');
        }
    }

    /**
     * Memproses aksi selesaikan reservasi (checkout).
     */
    public function complete(Reservasi $reservasi)
    {
        if ($reservasi->statusReservasi != 'Check-in') {
            return redirect()->back()->with('error', 'Hanya reservasi yang sudah "Check-in" yang bisa diselesaikan.');
        }
        DB::beginTransaction();
        try {
            $reservasi->statusReservasi = 'Selesai';
            $reservasi->save();
            $reservasi->pesanan->statusPembayaran = 'Lunas';
            $reservasi->pesanan->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Menyelesaikan (checkout) reservasi ' . $reservasi->kodeReservasi . ' (Pelanggan: ' . $reservasi->namaPelanggan . ')'
            ]);
            
            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Reservasi ' . $reservasi->kodeReservasi . ' telah diselesaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyelesaikan reservasi.');
        }
    }

    /**
     * Menambahkan item menu baru ke pesanan yang ada.
     */
    public function addOrder(Request $request, Reservasi $reservasi)
    {
        $request->validate(['menu_items' => 'required|array', 'menu_items.*' => 'integer|min:0']);
        DB::beginTransaction();
        try {
            $pesanan = $reservasi->pesanan;
            $totalTambahan = 0;
            $itemCount = 0;

            foreach ($request->menu_items as $menu_id => $jumlah) {
                if ($jumlah > 0) {
                    $itemCount += $jumlah;
                    $menu = Menu::find($menu_id);
                    if (!$menu) continue;
                    $subTotal = $menu->harga * $jumlah;
                    DetailPesanan::updateOrCreate(
                        ['pesanan_id' => $pesanan->id, 'menu_id' => $menu->id],
                        ['jumlah' => DB::raw("jumlah + $jumlah"), 'subTotal' => DB::raw("subTotal + $subTotal")]
                    );
                    $totalTambahan += $subTotal;
                }
            }
            
            if ($totalTambahan > 0) {
                $pesanan->totalTagihan += $totalTambahan;
                if ($pesanan->statusPembayaran == 'Lunas') {
                    $pesanan->sisaTagihan += $totalTambahan;
                } else {
                    $pesanan->sisaTagihan += $totalTambahan;
                }
                $pesanan->save();

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'activity' => 'Menambah ' . $itemCount . ' item ke tagihan reservasi ' . $reservasi->kodeReservasi
                ]);
            }
            
            DB::commit();
            return redirect()->back()->with('success', 'Pesanan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambah pesanan: ' . $e->getMessage());
        }
    }

    public function listReservations(Request $request)
    {
        $query = Reservasi::where('tipe', 'Online')->with('meja')->orderBy('tanggal', 'desc')->orderBy('waktu', 'desc');
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('namaPelanggan', 'like', '%' . $request->search . '%')
                  ->orWhere('kodeReservasi', 'like', '%' . $request->search . '%');
            });
        }
        $reservasis = $query->paginate(15);
        return view('admin.reservasi-list', compact('reservasis'));
    }

    public function listUpcoming(Request $request)
    {
        $query = Reservasi::where('tanggal', '>', today())->where('statusReservasi', 'Dipesan')->with('meja')->orderBy('tanggal', 'asc')->orderBy('waktu', 'asc');
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('namaPelanggan', 'like', '%' . $request->search . '%')
                  ->orWhere('kodeReservasi', 'like', '%' . $request->search . '%');
            });
        }
        $reservasis = $query->paginate(15);
        return view('admin.upcoming-list', compact('reservasis'));
    }

    public function listWalkin(Request $request)
    {
        $query = Reservasi::where('tipe', 'Walk-in')->with('meja')->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('namaPelanggan', 'like', '%' . $request->search . '%')
                  ->orWhere('kodeReservasi', 'like', '%' . $request->search . '%');
            });
        }
        $reservasis = $query->paginate(15);
        return view('admin.walkin-list', compact('reservasis'));
    }

    public function listCanceled(Request $request)
    {
        $query = Reservasi::where('statusReservasi', 'Dibatalkan')->with('meja')->orderBy('updated_at', 'desc');
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('namaPelanggan', 'like', '%' . $request->search . '%')
                  ->orWhere('kodeReservasi', 'like', '%' . $request->search . '%');
            });
        }
        $reservasis = $query->paginate(15);
        return view('admin.canceled-list', compact('reservasis'));
    }
}