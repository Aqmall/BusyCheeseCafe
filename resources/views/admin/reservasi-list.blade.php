@extends('layouts.dashboard')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Semua Reservasi</h2>

    <div class="mb-4">
        <form method="GET" action="{{ route('admin.reservasi.list') }}">
            <input type="text" name="search" placeholder="Cari nama atau kode..." class="border p-2 rounded-md w-full md:w-1/3">
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kode</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Pelanggan</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Jadwal</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Status</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($reservasis as $reservasi)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-3 px-4 font-mono">{{ $reservasi->kodeReservasi }}</td>
                            <td class="py-3 px-4">{{ $reservasi->namaPelanggan }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($reservasi->waktu)->format('H:i') }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($reservasi->statusReservasi == 'Dipesan')
                                    <span class="bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-xs">Dipesan</span>
                                @elseif($reservasi->statusReservasi == 'Check-in')
                                    <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">Check-in</span>
                                @elseif($reservasi->statusReservasi == 'Dibatalkan')
                                    <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">Batal</span>
                                @elseif($reservasi->statusReservasi == 'Selesai')
                                    <span class="bg-gray-200 text-gray-800 py-1 px-3 rounded-full text-xs">Selesai</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($reservasi->statusReservasi == 'Selesai')
                                    {{-- Jika sudah selesai, tampilkan tombol Cetak Struk --}}
                                    <a href="{{ route('reservasi.flow.struk', $reservasi->kodeReservasi) }}" target="_blank" class="text-sm font-semibold bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600">Cetak Struk</a>
                                @elseif($reservasi->statusReservasi == 'Dibatalkan')
                                    <span class="text-xs text-gray-500">-</span>
                                @else
                                    {{-- Jika masih aktif, tampilkan tombol Kelola --}}
                                    <a href="{{ route('admin.reservasi.show', $reservasi->id) }}" class="text-sm font-semibold bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">Kelola</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">Tidak ada data reservasi ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $reservasis->links() }}
        </div>
    </div>
@endsection