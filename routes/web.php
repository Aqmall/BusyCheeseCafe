<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReservationFlowController;
use App\Http\Controllers\StaffLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\WalkinController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rute Publik (Untuk Pelanggan) ---

Route::get('/', [PageController::class, 'home'])->name('home');

// Grup Route untuk Alur Reservasi Multi-Langkah
Route::prefix('reservasi')->name('reservasi.flow.')->group(function () {
    Route::post('start', [ReservationFlowController::class, 'startFlow'])->name('start');
    
    Route::get('pilih-meja', [ReservationFlowController::class, 'showStepMeja'])->name('meja.show');
    Route::post('pilih-meja', [ReservationFlowController::class, 'storeStepMeja'])->name('meja.store');

    Route::get('pilih-menu', [ReservationFlowController::class, 'showStepMenu'])->name('menu.show');
    Route::post('pilih-menu', [ReservationFlowController::class, 'storeStepMenu'])->name('menu.store');
    
    Route::get('data-diri', [ReservationFlowController::class, 'showStepDataDiri'])->name('datadiri.show');
    Route::post('data-diri', [ReservationFlowController::class, 'storeStepDataDiri'])->name('datadiri.store');

    Route::get('pembayaran', [ReservationFlowController::class, 'showStepPembayaran'])->name('pembayaran.show');
    Route::post('pembayaran', [ReservationFlowController::class, 'storeStepPembayaran'])->name('pembayaran.store');

    Route::get('sukses', [ReservationFlowController::class, 'success'])->name('sukses');
    
    // Rute untuk menampilkan dan mencetak struk
    Route::get('/{reservasi:kodeReservasi}/struk', [ReservationFlowController::class, 'showStruk'])->name('struk');
});


// --- Rute untuk Staf & Manajemen ---

// Rute Login & Logout
Route::get('staff/login', [StaffLoginController::class, 'showLoginForm'])->name('login');
Route::post('staff/login', [StaffLoginController::class, 'login'])->name('login.perform');
Route::post('staff/logout', [StaffLoginController::class, 'logout'])->name('logout');

// Grup Route untuk Dasbor (DILINDUNGI OLEH OTENTIKASI)
Route::prefix('admin')->middleware('auth')->group(function() {
    // Dasbor Utama
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Daftar Reservasi (Semua)
    Route::get('/reservasi-list', [AdminController::class, 'listReservations'])->name('admin.reservasi.list');

    // --- RUTE BARU UNTUK HALAMAN MANAJEMEN ---
    Route::get('/reservasi-akan-datang', [AdminController::class, 'listUpcoming'])->name('admin.reservasi.upcoming');
    Route::get('/walk-in-list', [AdminController::class, 'listWalkin'])->name('admin.walkin.list');
    Route::get('/reservasi-batal', [AdminController::class, 'listCanceled'])->name('admin.reservasi.canceled');
    // ------------------------------------------

    // Kelola Reservasi Tunggal
    Route::get('/reservasi/{reservasi}', [AdminController::class, 'show'])->name('admin.reservasi.show');
    Route::post('/reservasi/{reservasi}/checkin', [AdminController::class, 'checkin'])->name('admin.reservasi.checkin');
    Route::post('/reservasi/{reservasi}/cancel', [AdminController::class, 'cancel'])->name('admin.reservasi.cancel');
    Route::post('/reservasi/{reservasi}/complete', [AdminController::class, 'complete'])->name('admin.reservasi.complete');
    Route::post('/reservasi/{reservasi}/add-order', [AdminController::class, 'addOrder'])->name('admin.reservasi.addOrder');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');

    // Walk-in (Form Pembuatan)
    Route::get('/walkin', [WalkinController::class, 'create'])->name('admin.walkin.create');
    Route::post('/walkin', [WalkinController::class, 'store'])->name('admin.walkin.store');
});