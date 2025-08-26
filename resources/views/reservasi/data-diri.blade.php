@extends('layouts.flow', ['currentStep' => 3])

@section('content')
<div class="text-left mb-8">
    <h2 class="text-3xl font-bold text-cafe-secondary mb-2">Data Diri & Konfirmasi</h2>
    <p class="text-gray-600">Langkah terakhir sebelum pembayaran.</p>
</div>

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

{{-- Alpine.js untuk men-disable tombol submit --}}
<div x-data="{ agreed: false }">
    <form action="{{ route('reservasi.flow.datadiri.store') }}" method="POST">
        @csrf
        <div class="grid lg:grid-cols-2 gap-12">
            {{-- Kolom Kiri: Form Data Diri --}}
            <div class="space-y-6">
                <h3 class="text-xl font-semibold">Data Diri</h3>
                <div>
                    <label for="namaPelanggan" class="block text-sm font-medium mb-2">Nama Lengkap *</label>
                    <input type="text" name="namaPelanggan" id="namaPelanggan" value="{{ old('namaPelanggan') }}" class="w-full h-12 px-4 border rounded-lg" required>
                </div>
                <div>
                    <label for="noTelepon" class="block text-sm font-medium mb-2">Nomor Telepon *</label>
                    <input type="tel" name="noTelepon" id="noTelepon" value="{{ old('noTelepon') }}" class="w-full h-12 px-4 border rounded-lg" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full h-12 px-4 border rounded-lg" required>
                </div>

                {{-- Kotak Kebijakan --}}
                <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Perhatian:</strong> Reservasi yang sudah dikonfirmasi dengan pembayaran DP **tidak dapat dibatalkan (non-refundable)** dan **jadwal tidak dapat diubah**.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Checkbox Persetujuan --}}
                <div class="flex items-start">
                    <div class="flex h-6 items-center">
                        <input id="agree" name="agree" type="checkbox" x-model="agreed" class="h-4 w-4 rounded border-gray-300 text-cafe-accent focus:ring-cafe-accent">
                    </div>
                    <div class="ml-3 text-sm leading-6">
                        <label for="agree" class="font-medium text-gray-900">Saya mengerti dan menyetujui kebijakan di atas.</label>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan: Ringkasan Reservasi --}}
            <div class="space-y-6">
                 {{-- ... (kode ringkasan reservasi tetap sama seperti sebelumnya) ... --}}
            </div>
        </div>

        {{-- Tombol Navigasi --}}
        <div class="mt-8 flex justify-between items-center">
            <a href="{{ route('reservasi.flow.menu.show') }}" class="text-gray-600 font-semibold hover:text-black">&larr; Sebelumnya</a>
            <button type="submit" :disabled="!agreed" class="bg-cafe-primary text-cafe-secondary font-bold py-3 px-8 rounded-lg hover:bg-cafe-primary-dark disabled:bg-gray-300 disabled:cursor-not-allowed">
                Lanjutkan ke Pembayaran
            </button>
        </div>
    </form>
</div>
@endsection