@extends('layouts.dashboard')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Laporan & Analytics</h2>

    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap items-center gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Awal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="pt-5">
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700">Filter</button>
            </div>
        </form>
    </div>

    {{-- Grid Statistik Diperbarui --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-semibold text-gray-500">Total Pendapatan</h3>
            <p class="text-3xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-semibold text-gray-500">Transaksi Selesai</h3>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $jumlahTransaksi }}</p>
        </div>
        {{-- Card Baru untuk DP Hangus --}}
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-semibold text-gray-500">Pendapatan dari DP Hangus</h3>
            <p class="text-3xl font-bold text-yellow-600 mt-1">Rp {{ number_format($pendapatanDpHangus, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-semibold text-gray-500">Rata-rata per Transaksi</h3>
            <p class="text-3xl font-bold text-gray-900 mt-1">Rp {{ number_format($rataRataOrder, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4 text-gray-700">Grafik Pendapatan Harian (dari Transaksi Selesai)</h3>
            <div>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4 text-gray-700">5 Menu Terlaris</h3>
            <div class="space-y-4">
                @forelse($menuTerlaris as $item)
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ $loop->iteration }}. {{ $item->menu->namaMenu }}</p>
                            <p class="text-sm text-gray-500">{{ $item->menu->kategori }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg">{{ $item->total_terjual }}</p>
                            <p class="text-xs text-gray-500">terjual</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">Tidak ada data penjualan.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Pendapatan',
                data: @json($chartData),
                borderColor: '#4A90E2', // cafe-accent
                backgroundColor: 'rgba(74, 144, 226, 0.2)',
                borderWidth: 2,
                tension: 0.1,
                fill: true,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush