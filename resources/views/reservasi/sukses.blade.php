@extends('layouts.app')

@section('content')
<div class="text-center py-20">
    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto">
        <svg class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    </div>
    
    <h2 class="text-3xl font-bold text-cafe-secondary mt-6">Reservasi Berhasil!</h2>
    <p class="text-xl text-gray-600 mt-2">Terima kasih atas reservasi Anda.</p>

    <div class="bg-cafe-beige rounded-xl p-8 mt-8 max-w-lg mx-auto">
        <p class="text-sm text-gray-600 mb-2">Kode Reservasi Anda:</p>
        <p class="text-3xl font-bold text-cafe-primary tracking-widest">{{ $reservasi->kodeReservasi }}</p>
    </div>

    <div class="mt-8 text-gray-600">
        <p>Email konfirmasi telah dikirim ke **{{ $reservasi->email }}**.</p>
        <p>Harap tiba 10 menit sebelum waktu reservasi dan tunjukkan kode ini kepada staf kami.</p>
    </div>

    <div class="mt-8 flex justify-center items-center gap-4">
        <a href="{{ route('home') }}" class="bg-gray-200 text-gray-800 font-bold py-3 px-8 rounded-lg hover:bg-gray-300">
            Kembali ke Halaman Utama
        </a>
        <a href="{{ route('reservasi.flow.struk', $reservasi->kodeReservasi) }}" target="_blank" class="bg-cafe-primary text-cafe-secondary font-bold py-3 px-8 rounded-lg hover:bg-cafe-primary-dark">
            Cetak Struk
        </a>
    </div>
</div>
@endsection