@extends('layouts.dashboard')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto">
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Kelola Reservasi</h2>
            <p class="text-gray-500 font-mono">{{ $reservasi->kodeReservasi }}</p>
        </div>
        {{-- PERBAIKAN ADA DI BARIS INI --}}
        <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:underline">&larr; Kembali ke Dasbor</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
        <div>
            <h3 class="font-bold text-lg mb-2">Detail Pelanggan</h3>
            <p><strong>Nama:</strong> {{ $reservasi->namaPelanggan }}</p>
            <p><strong>Telepon:</strong> {{ $reservasi->noTelepon }}</p>
        </div>
        <div>
            <h3 class="font-bold text-lg mb-2">Detail Jadwal</h3>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d F Y') }} - {{ \Carbon\Carbon::parse($reservasi->waktu)->format('H:i') }} WIB</p>
            <p><strong>Meja:</strong> {{ $reservasi->nomorMeja }} ({{ $reservasi->jumlahTamu }} Orang)</p>
            <p><strong>Status:</strong> <span class="font-bold text-blue-600">{{ $reservasi->statusReservasi }}</span></p>
        </div>
    </div>

    <div class="mb-8">
        <h3 class="font-bold text-lg mb-2">Detail Tagihan</h3>
        <div class="border rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left py-2 px-4 font-semibold text-sm">Menu</th>
                        <th class="text-center py-2 px-4 font-semibold text-sm">Jumlah</th>
                        <th class="text-right py-2 px-4 font-semibold text-sm">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservasi->pesanan->detailPesanan as $detail)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $detail->menu->namaMenu }}</td>
                        <td class="py-2 px-4 text-center">{{ $detail->jumlah }}</td>
                        <td class="py-2 px-4 text-right">Rp {{ number_format($detail->subTotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="font-bold bg-gray-100">
                    <tr>
                        <td colspan="2" class="py-2 px-4 text-right text-gray-600">Total Tagihan:</td>
                        <td class="py-2 px-4 text-right">Rp {{ number_format($reservasi->pesanan->totalTagihan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if($reservasi->statusReservasi == 'Check-in')
    <div class="mt-8 pt-6 border-t">
        <h3 class="font-bold text-lg mb-4">Tambah Pesanan</h3>
        <form action="{{ route('admin.reservasi.addOrder', $reservasi->id) }}" method="POST">
            @csrf
            <div class="space-y-3 mb-4 max-h-60 overflow-y-auto pr-2">
                @foreach($menus as $menu)
                <div class="flex justify-between items-center">
                    <label for="menu_{{ $menu->id }}">{{ $menu->namaMenu }} <span class="text-xs text-gray-500">(Rp {{ number_format($menu->harga, 0, ',', '.') }})</span></label>
                    <input type="number" name="menu_items[{{ $menu->id }}]" id="menu_{{ $menu->id }}" min="0" value="0" class="w-20 px-2 py-1 border rounded-lg text-center">
                </div>
                @endforeach
            </div>
            <button type="submit" class="bg-indigo-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-600">+ Tambahkan ke Tagihan</button>
        </form>
    </div>
    @endif
    
    <div class="mt-8 pt-6 border-t flex items-center space-x-4">
        <h3 class="font-bold text-lg">Aksi Utama:</h3>
        
        @if($reservasi->statusReservasi == 'Dipesan')
            <form action="{{ route('admin.reservasi.checkin', $reservasi->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600">✓ Tandai Check-in</button>
            </form>
            <form action="{{ route('admin.reservasi.cancel', $reservasi->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?');">
                @csrf
                <button type="submit" class="bg-red-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600">Batalkan Reservasi</button>
            </form>
        @endif

        @if($reservasi->statusReservasi == 'Check-in')
            <form action="{{ route('admin.reservasi.complete', $reservasi->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">Selesaikan Reservasi (Checkout)</button>
            </form>
        @endif

        @if($reservasi->statusReservasi == 'Selesai' || $reservasi->statusReservasi == 'Dibatalkan')
            <p class="text-gray-500">Tidak ada aksi lebih lanjut untuk reservasi ini.</p>
        @endif
    </div>
</div>
@endsection