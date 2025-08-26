<div class="bg-white p-8 rounded-2xl shadow-xl">
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Terjadi Kesalahan:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reservasi.flow.start') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid md:grid-cols-3 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-cafe-secondary">Tanggal</label>
                <input type="date" name="tanggal" 
                       min="{{ now()->format('Y-m-d') }}" 
                       max="{{ now()->addMonth()->format('Y-m-d') }}" 
                       class="w-full h-12 text-base rounded-xl border-gray-300 focus:ring-cafe-primary focus:border-cafe-primary" required>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-cafe-secondary">Waktu (Durasi 1 Jam)</label>
                <select name="waktu" class="w-full h-12 text-base rounded-xl border-gray-300 focus:ring-cafe-primary focus:border-cafe-primary" required>
                    <option value="">Pilih waktu</option>
                    {{-- PERUBAHAN ADA DI DALAM LOOPING INI --}}
                    @foreach($timeSlots as $time)
                        @php
                            $endTime = \Carbon\Carbon::parse($time)->addHour()->format('H:i');
                        @endphp
                        <option value="{{ $time }}">{{ $time }} - {{ $endTime }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-cafe-secondary">Jumlah Tamu</label>
                <select name="jumlahTamu" class="w-full h-12 text-base rounded-xl border-gray-300 focus:ring-cafe-primary focus:border-cafe-primary" required>
                    <option value="">Pilih jumlah</option>
                    @for ($i = 1; $i <= 8; $i++)
                        <option value="{{ $i }}">{{ $i }} orang</option>
                    @endfor
                </select>
            </div>
        </div>
        <button type="submit" class="w-full bg-cafe-primary text-cafe-secondary hover:bg-cafe-primary-dark py-4 text-lg font-semibold rounded-xl">
            Lanjutkan Reservasi
        </button>
    </form>
</div>