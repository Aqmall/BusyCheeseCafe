@extends('layouts.flow', ['currentStep' => 4])

@section('content')
{{-- Gunakan Alpine.js untuk mengelola state timer --}}
<div 
    x-data="paymentTimer('{{ $reservasi->created_at->toIso8601String() }}')" 
    x-init="startTimer()" 
    class="max-w-md mx-auto text-center"
>
    <h2 class="text-3xl font-bold text-cafe-secondary mb-2">Pembayaran</h2>
    <p class="text-gray-600 mb-8">Selesaikan pembayaran DP untuk mengonfirmasi reservasi Anda.</p>

    {{-- BAGIAN BARU: TAMPILAN TIMER --}}
    <div class="mb-8">
        <p class="text-sm text-gray-500">Sisa Waktu Pembayaran:</p>
        <p x-show="!isExpired" class="text-2xl font-bold text-red-600 tracking-widest" x-text="timeLeft"></p>
        <p x-show="isExpired" class="text-2xl font-bold text-gray-500">Waktu Habis</p>
    </div>
    {{-- AKHIR BAGIAN BARU --}}

    <div class="border rounded-lg p-6 space-y-4 bg-gray-50 mb-8">
        <div class="flex justify-between"><span>Total Tagihan:</span><span class="font-semibold">Rp {{ number_format($reservasi->pesanan->totalTagihan, 0, ',', '.') }}</span></div>
        <div class="flex justify-between text-2xl text-cafe-accent">
            <span class="font-semibold">Down Payment (50%):</span>
            <span class="font-bold">Rp {{ number_format($reservasi->pesanan->totalDP, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="font-semibold text-lg">Pilih Metode Pembayaran</h3>
        {{-- Tombol pembayaran akan dinonaktifkan jika waktu habis --}}
        <form action="{{ route('reservasi.flow.pembayaran.store') }}" method="POST">
            @csrf
            <button type="submit" :disabled="isExpired" class="w-full text-left p-4 border rounded-lg hover:bg-gray-100 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-400">Virtual Account</button>
            <button type="submit" :disabled="isExpired" class="w-full text-left p-4 border rounded-lg hover:bg-gray-100 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-400">QRIS</button>
            <button type="submit" :disabled="isExpired" class="w-full text-left p-4 border rounded-lg hover:bg-gray-100 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-400">Kartu Kredit/Debit</button>
        </form>
    </div>

    {{-- Tautan jika waktu habis --}}
    <div x-show="isExpired" class="mt-8">
        <p class="text-gray-600 mb-4">Waktu pembayaran Anda telah habis. Reservasi ini telah dibatalkan secara otomatis.</p>
        <a href="{{ route('home') }}" class="bg-cafe-primary text-cafe-secondary font-bold py-3 px-8 rounded-lg hover:bg-cafe-primary-dark">
            Buat Reservasi Baru
        </a>
    </div>
</div>
@endsection

@push('scripts')
{{-- Tambahkan Alpine.js jika belum ada di layout --}}
<script src="//unpkg.com/alpinejs" defer></script>
<script>
    function paymentTimer(creationTime) {
        return {
            timeLeft: '10:00',
            isExpired: false,
            startTimer() {
                // Tentukan waktu kedaluwarsa (waktu pembuatan + 10 menit)
                const expiryTime = new Date(creationTime).getTime() + 10 * 60 * 1000;

                const interval = setInterval(() => {
                    const now = new Date().getTime();
                    const distance = expiryTime - now;

                    if (distance < 0) {
                        clearInterval(interval);
                        this.timeLeft = '00:00';
                        this.isExpired = true;
                        return;
                    }

                    // Hitung menit dan detik
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Format tampilan menjadi MM:SS
                    this.timeLeft = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                }, 1000);
            }
        }
    }
</script>
@endpush