@extends('layouts.dashboard')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Reservasi Dibatalkan</h2>

    <div class="mb-4">
        <form method="GET" action="{{ route('admin.reservasi.canceled') }}">
            <input type="text" name="search" placeholder="Cari nama atau kode..." value="{{ request('search') }}" class="border p-2 rounded-md w-full md:w-1/3">
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kode</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Pelanggan</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal Dibatalkan</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Status Bayar</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($reservasis as $reservasi)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-3 px-4 font-mono">{{ $reservasi->kodeReservasi }}</td>
                            <td class="py-3 px-4">{{ $reservasi->namaPelanggan }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($reservasi->updated_at)->format('d M Y - H:i') }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($reservasi->pesanan->statusPembayaran == 'Dibatalkan (DP Masuk)')
                                    <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs">DP Hangus</span>
                                @else
                                     <span class="bg-gray-200 text-gray-800 py-1 px-3 rounded-full text-xs">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.reservasi.show', $reservasi->id) }}" class="text-sm font-semibold bg-gray-500 text-white px-3 py-1 rounded-md hover:bg-gray-600">Lihat Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">Tidak ada reservasi yang dibatalkan ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $reservasis->links() }}
        </div>
    </div>
@endsection