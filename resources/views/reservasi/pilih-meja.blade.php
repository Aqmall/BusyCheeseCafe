@extends('layouts.flow', ['currentStep' => 1])

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="text-left mb-8">
        <h2 class="text-3xl font-bold text-cafe-secondary mb-2">Pilih Meja</h2>
        <p class="text-gray-600">Pilih meja yang tersedia sesuai kapasitas tamu Anda.</p>
    </div>

    <div class="bg-cafe-beige rounded-xl p-4 mb-8 text-center">
        <p>Reservasi untuk <strong class="text-cafe-accent">{{ $reservationData['jumlahTamu'] }} orang</strong> pada tanggal <strong class="text-cafe-accent">{{ \Carbon\Carbon::parse($reservationData['tanggal'])->format('d M Y') }}</strong> pukul <strong class="text-cafe-accent">{{ \Carbon\Carbon::parse($reservationData['waktu'])->format('H:i') }}</strong>.</p>
    </div>

    <form action="{{ route('reservasi.flow.meja.store') }}" method="POST">
        @csrf
        
        {{-- Input tersembunyi ini akan diisi oleh JavaScript saat meja dipilih --}}
        <input type="hidden" name="nomorMeja" id="selectedMejaInput" required>
        
        <div class="relative bg-gray-50 rounded-xl p-4 sm:p-8 border min-h-[500px]">
            <canvas id="mejaCanvas" width="800" height="600"></canvas>
        </div>

        {{-- Legenda --}}
        <div class="mt-4 flex justify-center items-center flex-wrap gap-x-6 gap-y-2 text-sm">
            <div class="flex items-center space-x-2"><div class="w-4 h-4 rounded-full bg-status-available"></div><span>Tersedia</span></div>
            <div class="flex items-center space-x-2"><div class="w-4 h-4 rounded-full bg-status-booked"></div><span>Dipesan</span></div>
            <div class="flex items-center space-x-2"><div class="w-4 h-4 rounded-full bg-status-occupied"></div><span>Terisi</span></div>
            <div class="flex items-center space-x-2"><div class="w-4 h-4 rounded-full bg-gray-400"></div><span>Kapasitas Kurang</span></div>
        </div>

        @error('nomorMeja')
            <p class="text-center text-red-500 mt-4" id="error-meja">{{ $message }}</p>
        @enderror

        <div class="mt-8 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-gray-600 font-semibold hover:text-black">&larr; Kembali</a>
            <button type="submit" class="bg-cafe-primary text-cafe-secondary font-bold py-3 px-8 rounded-lg hover:bg-cafe-primary-dark">
                Selanjutnya &rarr;
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- SETUP ---
        const canvas = document.getElementById('mejaCanvas');
        const ctx = canvas.getContext('2d');
        const hiddenInput = document.getElementById('selectedMejaInput');
        
        // Mengambil data dari Laravel Blade ke JavaScript
        const tables = @json($mejas);
        const reservationData = @json($reservationData);
        
        let selectedMejaId = null;

        const colors = {
            available: '#28A745',
            booked: '#FFC107',
            occupied: '#DC3545',
            unavailable: '#A0AEC0',
            textWhite: '#FFFFFF',
            textBlack: '#333333',
            ring: '#4A90E2'
        };

        // --- FUNGSI MENGGAMBAR ---
        function drawMap() {
            ctx.clearRect(0, 0, canvas.width, canvas.height); // Bersihkan canvas

            // Gambar denah statis
            ctx.fillStyle = '#E5E7EB';
            ctx.fillRect(20, 20, 160, 50); // Bar Counter
            ctx.fillRect(620, 20, 160, 160); // Kitchen
            ctx.fillStyle = '#6B7280';
            ctx.font = '16px "Open Sans"';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('Bar Counter', 100, 45);
            ctx.fillText('Kitchen', 700, 100);

            // Gambar setiap meja
            tables.forEach(table => {
                const isAvailable = table.statusMeja === 'Tersedia' && table.kapasitas >= reservationData.jumlahTamu;
                let fillColor = colors.unavailable;
                let textColor = colors.textWhite;

                if (table.statusMeja === 'Dipesan') fillColor = colors.booked;
                if (table.statusMeja === 'Terisi') fillColor = colors.occupied;
                if (isAvailable) {
                    fillColor = colors.available;
                    canvas.style.cursor = 'pointer';
                }

                // Gambar lingkaran meja
                ctx.beginPath();
                ctx.fillStyle = fillColor;
                ctx.arc(table.pos_x * 2, table.pos_y * 2, 40, 0, 2 * Math.PI);
                ctx.fill();

                // Gambar cincin/ring jika meja dipilih
                if (table.nomorMeja === selectedMejaId) {
                    ctx.strokeStyle = colors.ring;
                    ctx.lineWidth = 5;
                    ctx.stroke();
                }
                
                // Tulis teks (nomor meja dan kapasitas)
                ctx.fillStyle = (fillColor === colors.booked) ? colors.textBlack : colors.textWhite;
                ctx.font = 'bold 20px "Poppins"';
                ctx.fillText(table.nomorMeja, table.pos_x * 2, table.pos_y * 2 - 8);
                ctx.font = '16px "Open Sans"';
                ctx.fillText(table.kapasitas + 'p', table.pos_x * 2, table.pos_y * 2 + 15);
            });
        }

        // --- EVENT LISTENER UNTUK KLIK ---
        canvas.addEventListener('click', function(event) {
            const rect = canvas.getBoundingClientRect();
            const mouseX = (event.clientX - rect.left) * (canvas.width / rect.width);
            const mouseY = (event.clientY - rect.top) * (canvas.height / rect.height);

            tables.forEach(table => {
                const isAvailable = table.statusMeja === 'Tersedia' && table.kapasitas >= reservationData.jumlahTamu;
                const distance = Math.sqrt(
                    Math.pow(mouseX - (table.pos_x * 2), 2) + Math.pow(mouseY - (table.pos_y * 2), 2)
                );

                if (distance < 40 && isAvailable) { // 40 adalah radius lingkaran
                    selectedMejaId = table.nomorMeja;
                    hiddenInput.value = selectedMejaId; // Update input tersembunyi
                    document.getElementById('error-meja')?.remove(); // Hapus pesan error jika ada
                    drawMap(); // Gambar ulang untuk menampilkan seleksi
                }
            });
        });

        // Gambar denah untuk pertama kali
        drawMap();
    });
</script>
@endpush